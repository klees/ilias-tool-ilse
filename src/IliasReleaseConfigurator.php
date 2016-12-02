<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS;
/**
 * implementation of an ilias configurator
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */

class IliasReleaseConfigurator implements \CaT\InstILIAS\interfaces\Configurator {

	protected $general;
	protected $absolute_path;
	protected $gDB;
	protected $gTree;
	protected $gUser;
	protected $gRbacadmin;
	protected $gSetting;
	protected $gIlias;
	protected $registration_settings;

	public function __construct($absolute_path, $client_id) {
		define ("CLIENT_ID", $client_id);
		define('IL_PHPUNIT_TEST', true);
		$_COOKIE["ilClientId"] = $client_id;

		$this->absolute_path = $absolute_path;
		$this->initIlias();

		global $ilDB, $tree, $ilUser, $rbacadmin, $ilSetting, $ilias;

		$this->gDB = $ilDB;
		$this->gTree = $tree;
		$this->gUser = $ilUser;
		$this->gRbacadmin = $rbacadmin;
		$this->gSetting = $ilSetting;
		$this->gIlias = $ilias;
	}

	/**
	 * @inheritdoc
	 */
	public function initIlias() {
		chdir($this->absolute_path);
		require_once($this->absolute_path."/Services/Context/classes/class.ilContext.php");
		require_once($this->absolute_path."/Services/Init/classes/class.ilInitialisation.php");
		require_once($this->absolute_path."/Services/AccessControl/classes/class.ilObjRole.php");
		require_once($this->absolute_path."/Modules/OrgUnit/classes/class.ilObjOrgUnit.php");
		require_once($this->absolute_path."/Modules/Category/classes/class.ilObjCategory.php");
		include_once($this->absolute_path."/Services/LDAP/classes/class.ilLDAPServer.php");
		include_once($this->absolute_path."/Services/LDAP/classes/class.ilLDAPServer.php");
		include_once($this->absolute_path."/Services/Component/classes/class.ilPlugin.php");
		require_once($this->absolute_path."/Modules/OrgUnit/classes/Types/class.ilOrgUnitType.php");
		require_once($this->absolute_path."/Services/User/classes/class.ilObjUser.php");
		require_once($this->absolute_path."/Services/PrivacySecurity/classes/class.ilSecuritySettings.php");
		require_once($this->absolute_path."/Services/Object/classes/class.ilObjectFactory.php");
		require_once($this->absolute_path."/Services/COPage/classes/class.ilPageEditorSettings.php");
		require_once($this->absolute_path."/Services/COPage/classes/class.ilPageContentGUI.php");

		//context unittest is not required an ilias authentication
		//we do not need any authentication to configure ILIAS
		\ilContext::init(\ilContext::CONTEXT_UNITTEST);
		\ilInitialisation::initILIAS();

	}

	/**
	 * @inheritdoc
	 */
	public function createRoles(\CaT\InstILIAS\Config\Roles $install_roles) {
		global $rbacadmin;

		foreach ($install_roles->roles() as $role => $value) {
			$newObj = new \ilObjRole();
			$newObj->setTitle($value->title());
			$newObj->setDescription($value->description());
			$newObj->create();

			$rbacadmin->assignRoleToFolder($newObj->getId(), ROLE_FOLDER_ID,'y');
			$rbacadmin->setProtected(ROLE_FOLDER_ID, $newObj->getId(),'n');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function createOrgUnits(\CaT\InstILIAS\Config\OrgUnits $install_orgunits) {
		
		foreach ($install_orgunits->orgunits() as $key => $value) {
			$this->createOrgunit($value, \ilObjOrgUnit::getRootOrgRefId());
		}
	}

	/**
	 * single OrgUnit and her children created
	 * recursiv
	 *
	 * @param $org_unit
	 * @param int $parent_ref_id
	 */
	protected function createOrgUnit($org_unit, $parent_ref_id) {
		$orgu = new \ilObjOrgUnit();
		$orgu->setTitle($org_unit->title());
		$orgu->create();
		$orgu->createReference();
		$orgu->update();

		$orgu->putInTree($parent_ref_id);
		$orgu->initDefaultRoles();

		foreach ($org_unit->children() as $key => $value) {
			$this->createOrgUnit($value, $orgu->getRefId());
		}
	}

	/**
	 * @inheritdoc
	 */
	public function createCategories(\CaT\InstILIAS\Config\Categories $install_categories) {
		foreach ($install_categories->categories() as $key => $value) {
			$this->createCategory($value, $this->gTree->getRootId());
		}
	}

	/**
	 * single Category and her children created
	 * recursiv
	 *
	 * @param $category
	 * @param int $parent_ref_id
	 */
	protected function createCategory($category, $parent_ref_id) {
		$cat = new \ilObjCategory();
		$cat->setTitle($category->title());
		$cat->create();
		$cat->createReference();
		$cat->update();

		$cat->putInTree($parent_ref_id);
		$cat->initDefaultRoles();

		foreach ($category->children() as $key => $value) {
			$this->createCategory($value, $cat->getRefId());
		}
	}

	/**
	 * @inheritdoc
	 */
	public function configureLDAPServer(\CaT\InstILIAS\Config\LDAP $ldap_config) {
		$server = new \ilLDAPServer(0);

		$server->toggleActive(1);
		$server->enableAuthentication(true);
		$server->setName($ldap_config->name());
		$server->setUrl($ldap_config->server());
		$server->setVersion($ldap_config->protocolVersion());
		$server->setBaseDN($ldap_config->basedn());
		$server->setBindingType($ldap_config->conType());
		$server->setBindUser($ldap_config->conUserDn());
		$server->setBindPassword($ldap_config->conUserPw());
		$server->setUserScope($ldap_config->userSearchScope());
		$server->setUserAttribute($ldap_config->attrNameUser());
		$server->enableSyncOnLogin($ldap_config->syncOnLogin());
		$server->enableSyncPerCron($ldap_config->syncPerCron());

		$role_id = $this->getRoleId($ldap_config->registerRoleName());
		$server->setGlobalRole($role_id);

		//group scope is a not null value in database
		//we do not need, but it is necessary to be set
		//1 is the default value
		$server->setGroupScope(1);

		if(!$server->validate()) {
			global $ilErr;
			throw new \Exception("Error creating LDAP Server: ".$ilErr->getMessage());
		}

		$server->create();

		include_once './Services/LDAP/classes/class.ilLDAPAttributeMapping.php';
		$mapping = \ilLDAPAttributeMapping::_getInstanceByServerId($server->getServerId());
		$mapping->setRule('global_role', $role_id, false);
		$mapping->save();
	}

	/**
	* get obj id for role name
	*
	* @param string $role_name
	*
	* @return int
	*/
	protected function getRoleId($role_name) {
		assert('is_string($role_name)');

		return $this->getObjIdByTitle("role", $role_name);
	}

	/**
	* get obj_id for title and type
	*
	* @param string $type
	* @param string $title
	*
	* @return int
	*/
	protected function getObjIdByTitle($type, $title) {
		assert('is_string($type)');
		assert('is_string($title)');

		$query = "SELECT obj_id FROM object_data WHERE type = ".$this->gDB->quote($type, 'text')." AND title = ".$this->gDB->quote($title, 'text')."";
		$res = $this->gDB->query($query);

		assert('$this->gDB->numRows($res) == 1');

		$row = $this->gDB->fetchAssoc($res);

		return (int)$row["obj_id"];
	}

	/**
	 * @inheritdoc
	 */
	public function installPlugins(\CaT\InstILIAS\Config\Plugins $plugins) {
		$plugin_installer = new \CaT\InstILIAS\IliasPluginInstaller($this->absolute_path, $this->gDB);
		$current_plugins = array();
		foreach ($plugins->plugins() as $plugin) {
			if(!$plugin_installer->isInstalled($plugin)) {
				$plugin_installer->install($plugin);
			} else {
				$plugin_installer->updateBranch($plugin);
			}
		}

		$plugin_installer = null;
	}

	/**
	 * @inheritdoc
	 */
	public function activatePlugins(\CaT\InstILIAS\Config\Plugins $plugins) {
		$plugin_installer = new \CaT\InstILIAS\IliasPluginInstaller($this->absolute_path, $this->gDB);
		foreach ($plugins->plugins() as $plugin) {
			$plugin_installer->activate($plugin);
			$plugin_installer->updateLanguage($plugin);
		}
		$plugin_installer = null;
	}

	/**
	 * @inheritdoc
	 */
	public function updatePlugins(\CaT\InstILIAS\Config\Plugins $plugins) {
		$plugin_installer = new \CaT\InstILIAS\IliasPluginInstaller($this->absolute_path, $this->gDB);
		foreach ($plugins->plugins() as $plugin) {
			$plugin_installer->update($plugin);
			$plugin_installer->updateLanguage($plugin);
		}
		$plugin_installer = null;
	}

	/**
	 * @inheritdoc
	 */
	public function createOrgunitTypes(\CaT\InstILIAS\Config\OrgunitTypes $orgunit_types) {
		foreach ($orgunit_types->orgunitTypes() as $orgunit_type) {
			$this->createOrgunitType($orgunit_type);
		}
	}

	/**
	 *
	 * @param \CaT\InstILIAS\Config\OrgunitType $orgunit_type
	 */
	protected function createOrgunitType(\CaT\InstILIAS\Config\OrgunitType $orgunit_type) {
		$type = new \ilOrgUnitType();

		$type->setDefaultLang($orgunit_type->defaultLanguage());
		foreach ($orgunit_type->typeLanguageSettings() as $type_language_setting) {
			$title = $type_language_setting->title();
			$description = $type_language_setting->description();
			$lang_code = $type_language_setting->language();
			$type->setTitle($title, $lang_code);
			$type->setDescription($description, $lang_code);
		}

		$type->save();
	}

	/**
	 * @inheritdocs
	 */
	public function assignOrgunitTypesToOrgunits(\CaT\InstILIAS\Config\OrgunitTypeAssignments $orgunit_type_assignments) {
		foreach ($orgunit_type_assignments->orgunitTypeAssignments() as $orgunit_type_assignment) {
			$this->orgunitTypeAssignment($orgunit_type_assignment);
		}
	}

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\OrgunitTypeAssignment $orgunit_type_assignment
	 */
	protected function orgunitTypeAssignment(\CaT\InstILIAS\Config\OrgunitTypeAssignment $orgunit_type_assignment) {
		$orgunit_id = $this->getOrgunitId($orgunit_type_assignment->orgunitTitle());
		$orgunit_type_id = $this->getOrgunitTypeId($orgunit_type_assignment->orgunitTypeTitle(),$orgunit_type_assignment->orgunitTypeDefaultLanguage());

		if(!$orgunit_id || !$orgunit_type_id) {
			echo "No orgunit or orgunit type found";
			return;
		}

		$orgunit = new \ilObjOrgUnit($orgunit_id, false);
		$orgunit->setOrgUnitTypeId($orgunit_type_id);
		$orgunit->update();
	}

	/**
	 *
	 *
	 * @param string $orgunit_tite
	 *
	 * @return integer;
	 */
	protected function getOrgunitId($orgunit_tite) {
		$select = "SELECT obj_id\n"
				 ." FROM object_data\n"
				 ." WHERE title = ".$this->gDB->quote($orgunit_tite,"text")
				 ."    AND type = 'orgu'";
		$res = $this->gDB->query($select);
		if($this->gDB->numRows($res) == 1) {
			return $this->gDB->fetchAssoc($res)["obj_id"];
		}

		return null;
	}

	/**
	 *
	 *
	 * @param string $orgunit_type_title
	 * @param string $orgunit_default_lang
	 *
	 * @return integer;
	 */
	protected function getOrgunitTypeId($orgunit_type_title, $orgunit_default_lang) {
		$select = "SELECT orgu_types.id\n"
				 ." FROM orgu_types\n"
				 ." JOIN orgu_types_trans ON orgu_types.id = orgu_types_trans.orgu_type_id"
				 ." WHERE member = ".$this->gDB->quote("title","text")
				 ."    AND value = ".$this->gDB->quote($orgunit_type_title,"text")
				 ."    AND lang = ".$this->gDB->quote($orgunit_default_lang,"text");

		$res = $this->gDB->query($select);
		if($this->gDB->numRows($res) == 1) {
			return $this->gDB->fetchAssoc($res)["id"];
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function registration(\CaT\InstILIAS\Config\Users $users) {
		require_once './Services/Registration/classes/class.ilRegistrationSettings.php';
		$this->registration_settings = new \ilRegistrationSettings();

		$this->registration_settings->setRegistrationType((int)$users->registration());
		$this->registration_settings->setRegistrationHashLifetime(max((int)$users->linkLifetime(), \ilRegistrationSettings::REG_HASH_LIFETIME_MIN_VALUE));

		$this->registration_settings->save();
	}

	/**
	 * @inheritdoc
	 */
	public function createUserAccounts(\CaT\InstILIAS\Config\Users $users) {
		foreach ($users->users() as $user) {
			echo "\nCreating user account for :".$user->email()."...";
			$password = $this->createUser($user);
			echo "\tDone. Initialize password: ".$password;
		}
	}

	protected function createUser(\CaT\InstILIAS\Config\User $user) {

		if(!\ilObjUser::_lookupId($user->login())) {
			$new_user = new \ilObjUser();

			$new_user->setTimeLimitUnlimited(true);
			$new_user->setTimeLimitOwner($this->gUser->getId());
			$new_user->setLogin($user->login());
			$new_user->setGender($user->gender());

			$new_user->setFirstname($user->firstname());
			$new_user->setLastname($user->lastname());
			$new_user->setEmail($user->email());
			$new_user->setActive(true);

			$password = $this->generatePasswort();
			$new_user->setPasswd($password, IL_PASSWD_PLAIN);
			$new_user->setTitle($new_user->getFullname());
			$new_user->setDescription($new_user->getEmail());

			$new_user->create();

			$new_user->setLastPasswordChangeTS(time());
			$new_user->saveAsNew();

			$this->gRbacadmin->assignUser($this->getRoleId($user->role()), $new_user->getId(),true);

			$new_user->setProfileIncomplete(true);
			$new_user->update();

			return $password;
		}

		return "User with login ".$user->login()." has been created yet. No new User was created.";
	}

	protected function generatePasswort() {
		return \ilUtil::generatePasswords(1)[0];
	}

	/**
	 * @inheritdoc
	 */
	public function passwordSettings(\CaT\InstILIAS\Config\PasswordSettings $password_settings) {
			$security = \ilSecuritySettings::_getInstance();

			// account security settings
			$security->setPasswordCharsAndNumbersEnabled((bool) $password_settings->numbersAndChars());
			$security->setPasswordSpecialCharsEnabled((bool) $password_settings->useSpecialChars());
			$security->setPasswordMinLength((int) $password_settings->minLength());
			$security->setPasswordMaxLength((int) $password_settings->maxLength());
			$security->setPasswordNumberOfUppercaseChars((int) $password_settings->numUpperChars());
			$security->setPasswordNumberOfLowercaseChars((int) $password_settings->numLowerChars());
			$security->setPasswordMaxAge((int) $password_settings->expireInDays());
			$security->setLoginMaxAttempts((int) $password_settings->maxNumLoginAttempts());
			$security->setPasswordChangeOnFirstLoginEnabled((bool) $password_settings->forgotPasswordAktive());

			$security->save();
	}

	/**
	 * @inheritdoc
	 */
	public function tinyMCE(\CaT\InstILIAS\Config\Editor $editor) {
		$obj_id = $this->getObjIdByType("adve");

		$object = \ilObjectFactory::getInstanceByObjId($obj_id);

		if((bool)$editor->enableTinymce()) {
			$object->_setRichTextEditor("tinymce");
		} else {
			$object->_setRichTextEditor("");
		}

		$object->update();
	}

	/**
	 * @inheritdoc
	 */
	public function repoPageEditor(\CaT\InstILIAS\Config\Editor $editor) {
			$repoPageEdit = $editor->repoPageEditor();

			$buttons = \ilPageContentGUI::_getCommonBBButtons();
			foreach ($buttons as $b => $t)
			{
				\ilPageEditorSettings::writeSetting("rep", "active_".$b,
					$repoPageEdit->valueFor($b));
			}

			$this->gSetting->set("enable_cat_page_edit", (int) $repoPageEdit->enable());
	}

	protected function getObjIdByType($type) {
		$query = "SELECT obj_id FROM object_data WHERE type = ".$this->gDB->quote($type, 'text');
		$res = $this->gDB->query($query);
		$row = $this->gDB->fetchAssoc($res);

		return $row["obj_id"];
	}

	/**
	 * @inheritdoc
	 */
	public function javaServer(\CaT\InstILIAS\Config\JavaServer $java_server) {
		$this->gSetting->set("rpc_server_host", trim($java_server->host()));
		$this->gSetting->set("rpc_server_port", trim($java_server->port()));

		include_once './Services/WebServices/RPC/classes/class.ilRpcIniFileWriter.php';
		$ini = new \ilRpcIniFileWriter();
		$ini->setHost($java_server->host());
		$ini->setPort($java_server->port());
		$ini->setIndexPath($java_server->indexPath());
		$ini->setLogPath($java_server->logFile());
		$ini->setLogLevel($java_server->logLevel());
		$ini->setNumThreads($java_server->numThreads());
		$ini->setMaxFileSize($java_server->maxFileSize());

		$ini->write();

		$fh = fopen($java_server->iniPath()."/ilServer.ini", "w+");
		fwrite($fh, $ini->getIniString());
		fclose($fh);
	}

	/**
	 * @inheritdoc
	 */
	public function certificate(\CaT\InstILIAS\Config\Certificate $certificate) {
		$certificate_settings = new \ilSetting("certificate");
		$certificate_settings->set("active", $certificate->enable());
	}

	/**
	 * @inheritdoc
	 */
	public function soap(\CaT\InstILIAS\Config\Soap $soap) {
		$this->gSetting->set('soap_user_administration', $soap->enable());
		$this->gSetting->set('soap_wsdl_path', trim($soap->wdslPath()));
		$this->gSetting->set('soap_connect_timeout',$soap->timeout());
	}

	/**
	 * @inheritdoc
	 */
	public function learningProgress(\CaT\InstILIAS\Config\LearningProgress $lp) {
		$this->gSetting->set("enable_tracking", $lp->enabled());
		$this->gSetting->set("save_user_related_data", !$lp->anonym());
		$this->gSetting->set("tracking_time_span",$lp->timeSpan());
		$this->gSetting->set("lp_extended_data", $lp->extendedData());
		$this->gSetting->set("object_statistics", $lp->objectStatistics());
		$this->gSetting->set("lp_learner", $lp->ownLp());
		$this->gSetting->set("session_statistics", $lp->sessionStatistics());
		$this->gSetting->set("lp_list_gui", $lp->personalDesktop());
	}

	/**
	 * @inheritdoc
	 */
	public function changeRequirementSettings(\CaT\InstILIAS\Config\Users $user) {
		$required_fields = $user->requiredFields();

		foreach ($user->getBasicFields() as $field) {
			if(is_array($required_fields) && !empty($required_fields) && in_array($field, $required_fields)) {
				$this->gIlias->setSetting("require_".$field, "1");
			} else {
				$this->gIlias->deleteSetting("require_".$field);
			}
		}
	}
}
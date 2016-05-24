<?php
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

	public function __construct($absolute_path, $client_id) {
		define ("CLIENT_ID", $client_id);
		define('IL_PHPUNIT_TEST', true);
		$_COOKIE["ilClientId"] = $client_id;

		$this->absolute_path = $absolute_path;
		$this->initIlias();

		global $ilDB, $tree;

		$this->gDB = $ilDB;
		$this->gTree = $tree;
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

		\ilContext::init(\ilContext::CONTEXT_UNITTEST);
		\ilInitialisation::initILIAS();

	}

	/**
	 * @inheritdoc
	 */
	public function createRoles($install_roles) {
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
	public function createOrgUnits($install_orgunits) {
		
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
	public function createCategories($install_categories) {
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
	public function configurateLDAPServer($ldap_config) {
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

		// things must be set because of not null
		$server->setGroupScope(1);

		if(!$server->validate())
		{
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
}
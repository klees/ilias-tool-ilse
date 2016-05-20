<?php
namespace CaT\InstILIAS;

/**
* implementation of an ilias installator
*
* @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
*/

class IliasReleaseInstallator implements \CaT\InstILIAS\interfaces\Installator {
	//configs
	protected $client;
	protected $db;
	protected $language;
	protected $log;
	protected $server;
	protected $setup;
	protected $tools;

	//tools
	protected $ilias_setup;
	protected $ilias_path;
	protected $general;

	public function __construct($ilias_path, \ilSetup $ilias_setup) {
		$this->ilias_path = $ilias_path;
		$this->ilias_setup = $ilias_setup;
	}

	/**
	 * @inheritdoc
	 */
	public function writeIliasIni() {
		global $ilCtrlStructureReader;
		$this->ilias_setup->saveMasterSetup($this->getIliasIniData());
		$ilCtrlStructureReader->setIniFile($this->ilias_setup->ini);
		$this->ilias_setup->ini_ilias_exists = true;
	}

	public function createLogSystem() {
		if(!is_dir($this->general->log()->path())) {
			mkdir($this->general->log()->path(), 0755, true);
		}

		if(!file_exists($this->general->log()->path()."/".$this->general->log()->fileName())) {
			touch($this->general->log()->path()."/".$this->general->log()->fileName());
		}
	}

	/**
	 * @inheritdoc
	 */
	public function writeClientIni() {
		$ret = $this->getClientIniData();

		if(!is_dir($ret["datadir_path"])) {
			mkdir($ret["datadir_path"], 0755, true);
		}

		$this->ilias_setup->ini_client_exists = $this->ilias_setup->newClient($ret["client_id"]);
		$this->ilias_setup->getClient()->setId($ret["client_id"]);
		$this->ilias_setup->getClient()->setName($ret["client_id"]);
		$this->ilias_setup->getClient()->setDbHost($ret["db_host"]);
		$this->ilias_setup->getClient()->setDbName($ret["db_name"]);
		$this->ilias_setup->getClient()->setDbUser($ret["db_user"]);
		$this->ilias_setup->getClient()->setDbPort($ret["db_port"]);
		$this->ilias_setup->getClient()->setDbPass($ret["db_pass"]);
		$this->ilias_setup->getClient()->setDbType($ret["db_type"]);
		$this->ilias_setup->getClient()->setDSN();

		if(!$this->ilias_setup->saveNewClient()) {
			throw new \Exception($this->ilias_setup->getError());
		}

		$this->setClientIniSetupFinsihed();
	}

	protected function setClientIniSetupFinsihed() {
		$this->ilias_setup->getClient()->status["ini"]["status"] = true;
	}

	/**
	 * @inheritdoc
	 */
	public function installDatabase() {
		$this->ilias_setup->createDatabase($this->general->database()->encoding());
		$this->ilias_setup->installDatabase();
	}

	/**
	 * @inheritdoc
	 */
	public function connectDatabase() {
		$this->ilias_setup->getClient()->connect();
	}

	/**
	 * @inheritdoc
	 */
	public function getDatabaseHandle() {
		global $ilDB;

		return $ilDB;
	}

	public function applyHotfixes(\ilDBUpdate $db_updater) {
		$db_updater->applyHotfix();
		$this->setDBSetupFinished();
	}

	public function applyUpdates(\ilDBUpdate $db_updater) {
		$db_updater->applyUpdate();
	}

	protected function setDBSetupFinished() {
		$this->ilias_setup->getClient()->status["db"]["status"] = true;
	}

	/**
	 * @inheritdoc
	 */
	public function installLanguages(\ilLanguage $lng) {
		$done = $lng->installLanguages($this->general->language()->toInstallLangs(), array());
		
		if($done !== true) {
			throw new \Exception("Error installing languages");
		}

		$this->setDefaultLanguage();
		$this->ilias_setup->getClient()->status["lang"]["status"] = true;
	}

	protected function setDefaultLanguage() {
		$this->ilias_setup->getClient()->setDefaultLanguage($this->general->language()->defaultLang());
	}

	/**
	 * @inheritdoc
	 */
	public function setProxy() {
		$this->setProxySetupFinished();
	}

	protected function setProxySetupFinished() {
		$this->ilias_setup->getClient()->status["proxy"]["status"] = true;
	}

	/**
	 * @inheritdoc
	 */
	public function registerNoNic() {
		$this->ilias_setup->getClient()->setSetting("inst_id","0");
		$this->ilias_setup->getClient()->setSetting("nic_enabled","0");
		$this->setRegisterSetupFinished();
	}

	protected function setRegisterSetupFinished() {
		$this->ilias_setup->getClient()->status["nic"]["status"] = true;
	}

	/**
	 * @inhertidoc
	 */
	public function setPasswordEncoder(\ilUserPasswordEncoderFactory $encoder_factory) {
		$default_encoder = $encoder_factory->getEncoderByName(trim($this->general->client()->passwordEncoder()));
		$default_encoder->onSelection();
		$encoder = array('default_encoder' => $default_encoder->getName());
		$this->ilias_setup->savePasswordSettings($encoder);
	}

	/**
	 * @inhertidoc
	 */
	public function finishSetup() {
		if($this->validatesetup()) {
			$this->ilias_setup->ini->setVariable("clients","default",$this->ilias_setup->getClient()->getId());
			$this->ilias_setup->ini->write();

			$this->ilias_setup->getClient()->ini->setVariable("client","access",1);
			$this->ilias_setup->getClient()->ini->write();

			$this->ilias_setup->getClient()->reconnect();
			$this->ilias_setup->getClient()->setSetting("setup_ok",1);
			$this->ilias_setup->getClient()->status["finish"]["status"] = true;
			
			return true;
		}

		return false;
	}

	/**
	 * validatesetup status again
	 * and set access mode of the first client to online
	 */
	protected function validateSetup()
	{
		foreach ($this->ilias_setup->getClient()->status as $key => $val)
		{
			if ($key != "finish" && $key != "access")
			{
				if ($val["status"] != true)
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function setGeneralConfig(\CaT\InstILIAS\Config\General $general)
	{
		$this->general = $general;
	}

	protected function getIliasIniData() {
		$ret = array();

		$ret["datadir_path"] = $this->general->client()->dataDir();
		$ret["log_path"] = $this->general->log()->path()."/".$this->general->log()->fileName();
		$ret["time_zone"] = $this->general->server()->timezone();
		$ret["convert_path"] = $this->general->tools()->convert();
		$ret["zip_path"] = $this->general->tools()->zip();
		$ret["unzip_path"] = $this->general->tools()->unzip();
		$ret["java_path"] = $this->general->tools()->java();
		$ret["setup_pass"] = $this->general->setup()->passwd();

		return $ret;
	}

	protected function getClientIniData() {
		$ret = array();
		$ret["datadir_path"] = $this->general->client()->dataDir();
		$ret["client_id"] = $this->general->client()->name();
		$ret["db_host"] = $this->general->database()->host();
		$ret["db_name"] = $this->general->database()->database();
		$ret["db_user"] = $this->general->database()->user();
		$ret["db_pass"] = $this->general->database()->password();
		$ret["db_type"] = $this->general->database()->engine();

		return $ret;
	}
}
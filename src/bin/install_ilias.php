<?php
$config_path = $argv[1];

require __DIR__ . '/../../vendor/autoload.php';

//read general configs
$json_string = file_get_contents($config_path);
$parser = new \CaT\InstILIAS\YamlParser();
$general_config = $parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\General");

$http_path = $general_config->server()->httpPath();
$absolute_path = $general_config->server()->absolutePath();
$data_path = $general_config->client()->dataDir();
$client_id = $general_config->client()->name();
$git_url = $general_config->gitBranch()->gitUrl();
$git_branch_name = $general_config->gitBranch()->gitBranchName();
$web_dir = "data";

//define git executer
$git = new \CaT\InstILIAS\GitExecuter;
//clone git
try {
	echo "Clone repository from: ".$git_url;
	echo " (This could take a few minutes)";
	$git->cloneGitTo($git_url, $absolute_path);
	echo "\t\tDone...\n";
} catch(\RuntimeException $e) {
	echo $e->getMessage();
	die(1);
} catch(\LogicException $e) {
	echo $e->getMessage();
	die(1);
}

//switch to branch
try {
	echo "Checkout branch: ".$git_url;
	$git->checkoutBranch($git_branch_name, $absolute_path);
	echo "\t\tDone...\n";
} catch(\RuntimeException $e) {
	echo $e->getMessage();
	die(1);
} catch(\LogicException $e) {
	echo $e->getMessage();
	die(1);
} catch(\InvalidArgumentException $e) {
	echo $e->getMessage();
	die(1);
}

//change dir to ILIAS Folder
chdir($absolute_path);
if(file_exists($absolute_path.'/libs/composer/vendor/autoload.php')) {
	include_once $absolute_path.'/libs/composer/vendor/autoload.php';
}

require_once("my_setup_header.php");

//define setup object
$setup = new \ilSetup(true,"admin");

//define installator
echo "Initialize installer";
$iinst = new \CaT\InstILIAS\IliasReleaseInstallator($path, $setup);
echo "\t\tDone...\n";

//set configs to installator
echo "\nStart install ILIAS\n";
$iinst->setGeneralConfig($general_config);

//create ilias.ini.php
echo "Create ILIAS ini";
$iinst->writeIliasIni();
echo "\t\tDone...\n";

//create client.ini.php + folder structure
echo "Create Client ini";
$iinst->writeClientIni();
echo "\t\tDone...\n";

$iinst->createLogSystem();

$iinst->connectDatabase();

//install ILIAS Database
echo "Create Database";
$iinst->installDatabase();
//conncet to Database
$db = $iinst->getDatabaseHandle();
$db_updater = new \ilDBUpdate($db);
$iinst->applyHotfixes($db_updater);
$iinst->applyUpdates($db_updater);
echo "\t\tDone...\n";

//install languages
echo "Install Languages";
$lng->setDbHandler($ilDB);
$iinst->installLanguages($lng);
echo "\t\tDone...\n";

//set proxy use
$iinst->setProxy();

//set register for nic
$iinst->registerNoNic();

//save encoding for passwort
$encoder_factory = new \ilUserPasswordEncoderFactory(array());
$iinst->setPasswordEncoder($encoder_factory);

//finish ILIAS setup
if(!$iinst->finishSetup()) {
    echo "Something went wrong at finish setup";
    die(1);
}
echo "\n\nIlias successfull installed.";
<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

$config_path = $argv[1];

function getUserInput() {
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	return trim($line);
}

function clearDirectory($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? clearDirectory("$dir/$file") : unlink("$dir/$file");
	}

	rmdir($dir);
}

require __DIR__ . '/../../vendor/autoload.php';

$yaml_string = file_get_contents($config_path);
$parser = new \CaT\InstILIAS\YamlParser();
$general_config = $parser->read_config($yaml_string, "\\CaT\\InstILIAS\\Config\\General");

$http_path = $general_config->server()->httpPath();
$absolute_path = $general_config->server()->absolutePath();
$data_path = $general_config->client()->dataDir();
$client_id = $general_config->client()->name();
$git_url = $general_config->gitBranch()->gitUrl();
$git_branch_name = $general_config->gitBranch()->gitBranchName();
$web_dir = "data";

echo "\n";
$requirement_checker = new \CaT\InstILIAS\IliasRequirementChecker;
if(!$requirement_checker->dataDirectoryExists($data_path)) {
	echo "Data directory does not exist. Create the directory (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Data directory is missing.";
		die(1);
	}

	echo "Creating data directory...";
	mkdir($data_path, 0755, true);
	echo "\t\t\t\t\t\t\t\tDone!\n";
}

if(!$requirement_checker->dataDirectoryPermissions($data_path)) {
	echo "Not enough permissions on data directory. Set permissions (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Not enough permissions on data directory.";
		die(1);
	}

	echo "Setting permission to required...";
	chmod($data_path, 0755);
	echo "\t\t\t\t\t\tDone!\n";
}

if(!$requirement_checker->dataDirectoryEmpty($data_path, $client_id, $web_dir)) {
	echo "Data directory is not empty. Clean the directory (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Data directory is not empty.";
		die(1);
	}

	echo "Cleaning the directory ".$data_path."/".$client_id."...";
	clearDirectory($data_path."/".$client_id);
	echo "\t\t\t\t\t\tDone!\n";
}

if(!$requirement_checker->logDirectoryExists($general_config->log()->path())) {
	echo "Log directory does not exist. ";
	echo "Creating log directory...";
	mkdir($general_config->log()->path(), 0755, true);
	echo "\t\t\t\t\t\t\t\t\tDone!\n";
}

if(!$requirement_checker->logFileExists($general_config->log()->path(), $general_config->log()->fileName())) {
	touch($general_config->log()->path()."/".$general_config->log()->fileName());
	chmod($general_config->log()->path()."/".$general_config->log()->fileName(), 0777);
}

if(!$requirement_checker->validPHPVersion(phpversion(), "5.4")) {
	echo "Your PHP Version is too old. Please update to 5.4 or higher.\n";
	die(1);
}

if(!$requirement_checker->mysqliExist() && !$requirement_checker->oracleExist()) {
	echo "Neither an option to connect via mysqli or oracle is installed. Please intall at least one of these.\n";
	die(1);
}


if(!$requirement_checker->databaseConnectable($general_config->database()->host(), $general_config->database()->user(), $general_config->database()->password())) {
	echo "It's not possible to connect a MySQL database.\n";
	echo "Please ensure you have one of these and the needed extensions installed.\n";
	die(1);
}

if(!$requirement_checker->phpVersionILIASBranchCompatible(phpversion(), $git_branch_name)) {
	echo "Your PHP Version (".phpversion().") is not compatible to the selected branch (".$git_branch_name.").";
	die(1);
}

$git = new \CaT\InstILIAS\GitExecuter;
try {
	echo "Clone repository from ".$git_url;
	echo " (This could take a few minutes)...";
	$git->cloneGitTo($git_url, $git_branch_name, $absolute_path);
	echo "\t\t\tDone!\n";
} catch(\RuntimeException $e) {
	echo $e->getMessage();
	die(1);
} catch(\LogicException $e) {
	echo $e->getMessage();
	die(1);
}

chdir($absolute_path);
if(file_exists($absolute_path.'/libs/composer/vendor/autoload.php')) {
	include_once $absolute_path.'/libs/composer/vendor/autoload.php';
}

// my_setup_header has a high error risk.
// It defines a lot of constant or objects
// and requires ILIAS files.
// Unfortunately it is required to do these steps,
// or the installation will not run.
echo "Initializing ILIAS...";
require_once("my_setup_header.php");
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

$setup = new \ilSetup(true,"admin");
echo "Initializing installer...";
$iinst = new \CaT\InstILIAS\IliasReleaseInstaller($setup, $general_config);
echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "\nStart installing ILIAS\n";
echo "Creating ilias.ini...";
$iinst->writeIliasIni();
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "Creating client.ini...";
$iinst->writeClientIni();
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

$iinst->connectDatabase();

echo "Creating database...";
$iinst->installDatabase();
$db = $iinst->getDatabaseHandle();
$db_updater = new \ilDBUpdate($db);
$iinst->applyHotfixes($db_updater);
$iinst->applyUpdates($db_updater);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "Installing languages...";
$lng->setDbHandler($ilDB);
$iinst->installLanguages($lng);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";


$iinst->setProxy();
$iinst->registerNoNic();

$encoder_factory = new \ilUserPasswordEncoderFactory(array());
$iinst->setPasswordEncoder($encoder_factory);

if(!$iinst->finishSetup()) {
	echo "\nSomething went wrong.";
	die(1);
}

echo "\nILIAS successfull installed.";
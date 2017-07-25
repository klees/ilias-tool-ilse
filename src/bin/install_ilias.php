<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

$config_path = $argv[1];
$non_interactiv = ($argv[2] !== null) ? $argv[2] : null;
$skip = false;

if($non_interactiv !== null) {
	$skip = $non_interactiv == "non_interactiv";
}

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
$parser = new \CaT\ilse\YamlParser();
$general_config = $parser->read_config($yaml_string, "\\CaT\\ilse\\Config\\General");

$http_path = $general_config->server()->httpPath();
$absolute_path = $general_config->server()->absolutePath();
$data_path = $general_config->client()->dataDir();
$client_id = $general_config->client()->name();
$git_url = $general_config->gitBranch()->url();
$git_branch_name = $general_config->gitBranch()->branch();
$web_dir = "data";

echo "\n";

$requirement_checker = new \CaT\ilse\IliasRequirementChecker;
$check = $requirement_checker->dataDirectoryExists($data_path);
if(!$skip && !$check) {
	echo "Data directory does not exist. Create the directory (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Aborted by user.";
		die(1);
	}

	echo "Creating data directory...";
	mkdir($data_path, 0777, true);
	echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
} else if($skip && !$check) {
	echo "Creating data directory...";
	mkdir($data_path, 0777, true);
	echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

$check = $requirement_checker->dataDirectoryPermissions($data_path);
if(!$skip && !$check) {
	echo "Not enough permissions on data directory. Set permissions (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Aborted by user.";
		die(1);
	}

	echo "Setting permission to required...";
	chmod($data_path, 0777);
	echo "\t\t\t\t\t\tDone!\n";
} else if($skip && !$check) {
	echo "Setting permission to required...";
	chmod($data_path, 0777);
	echo "\t\t\t\t\t\tDone!\n";
}

$check = $requirement_checker->dataDirectoryEmpty($data_path, $client_id, $web_dir);
if(!$skip && !$check) {
	echo "Data directory is not empty. Clean the directory (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Aborted by user.";
		die(1);
	}

	echo "Cleaning the directory ".$data_path."/".$client_id."...";
	clearDirectory($data_path."/".$client_id);
	echo "\t\t\t\t\t\tDone!\n";
}

$check = $requirement_checker->logDirectoryExists($general_config->log()->path());
if(!$skip && !$check) {
	echo "Log directory does not exist. Create the directory (yes|no)? ";
	$line = getUserInput();
	if(strtolower($line) != "yes") {
		echo "Aborted by user.";
		die(1);
	}

	echo "Creating log directory...";
	mkdir($general_config->log()->path(), 0777, true);
	echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
} else if($skip && !$check) {
	echo "Creating log directory...";
	mkdir($general_config->log()->path(), 0777, true);
	echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if(!$requirement_checker->logFileExists($general_config->log()->path(), $general_config->log()->fileName())) {
	touch($general_config->log()->path()."/".$general_config->log()->fileName());
	chmod($general_config->log()->path()."/".$general_config->log()->fileName(), 0777);
}

if(!$requirement_checker->validPHPVersion(phpversion(), "5.4")) {
	echo "Your PHP Version is too old. Please update to 5.4 or higher.\n";
	die(1);
}

if(!$requirement_checker->pdoExist()) {
	echo "PDO is not installed.\n";
	die(1);
}

if(!$requirement_checker->databaseConnectable($general_config->database()->host(), $general_config->database()->user(), $general_config->database()->password())) {
	echo "It's not possible to connect a MySQL database.\n";
	echo "Please ensure you have a MySQL database installed or started.\n";
	die(1);
}

if(!$requirement_checker->phpVersionILIASBranchCompatible(phpversion(), $git_branch_name)) {
	echo "Your PHP Version (".phpversion().") is not compatible to the selected branch (".$git_branch_name.").";
	die(1);
}

$git = new \CaT\ilse\GitExecuter;
try {
	echo "Clone repository from ".$git_url;
	echo " (This could take a few minutes)...";
	$git->cloneGitTo($git_url, $git_branch_name, $absolute_path);
	echo "\t\t\tDone!\n";
} catch(\RuntimeException $e) {
	echo $e->getMessage();
	die(1);
}

chmod($absolute_path, 0777);

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
$iinst = new \CaT\ilse\IliasReleaseInstaller($setup, $general_config);
echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "\nStart installing ILIAS\n";
echo "Creating ilias.ini...";
$iinst->writeIliasIni();
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "Creating client.ini...";
$iinst->writeClientIni();
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "Checken session lifetime...";
if(!$iinst->checkSessionLifeTime()) {
	echo "\n\t\tYour session max lifetime in php.ini is smaller then ILIAS lifetime. Please change it or ILIAS lifetime will never be used.\n";
}
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

$iinst->connectDatabase();

echo "Creating database...";
$iinst->installDatabase();
$db = $iinst->getDatabaseHandle();
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
$db_updater = new \ilDBUpdate($db);

echo "Applying updates...";
$iinst->applyUpdates($db_updater);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
echo "Applying hotfixes...";
$iinst->applyHotfixes($db_updater);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";


echo "Installing languages...";
$lng->setDbHandler($ilDB);
$iinst->installLanguages($lng);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";


$iinst->setProxy();
$iinst->registerNoNic();

if(!$iinst->finishSetup()) {
	echo "\nSomething went wrong.";
	die(1);
}

echo "\nILIAS successfull installed.";
<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */
$config_path = $argv[1];

require __DIR__ . '/../../vendor/autoload.php';

$yaml_string = file_get_contents($config_path);
$parser = new \CaT\Ilse\YamlParser();
$general_config = $parser->read_config($yaml_string, "\\CaT\\Ilse\\Config\\General");
$http_path = $general_config->server()->httpPath();
$absolute_path = $general_config->server()->absolutePath();
$data_path = $general_config->client()->dataDir();
$client_id = $general_config->client()->name();
$git_url = $general_config->gitBranch()->url();
$git_branch_name = $general_config->gitBranch()->branch();
$web_dir = "data";

chdir($absolute_path);
if(file_exists($absolute_path.'/libs/composer/vendor/autoload.php')) {
	include_once $absolute_path.'/libs/composer/vendor/autoload.php';
}
echo "Initializing ILIAS...";
require_once("my_setup_header.php");
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

$setup = new \ilSetup(true,"admin");
$iinst = new \CaT\Ilse\IliasReleaseInstaller($setup, $general_config);

$iinst->newClient($client_id);
$iinst->connectDatabase();

$git = new \CaT\Ilse\GitExecuter;
try {
	echo "Updating ILIAS Code from ".$git_url;
	echo " (This could take a few minutes)...";
	$git->cloneGitTo($git_url, $git_branch_name, $absolute_path);
	echo "\t\t\tDone!\n";
} catch(\RuntimeException $e) {
	echo $e->getMessage();
	die(1);
}

echo "\nUpdating database...";
$db = $iinst->getDatabaseHandle();
$db_updater = new \ilDBUpdate($db);

echo "Applying updates...";
$iinst->applyUpdates($db_updater);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
echo "Applying hotfixes...";
$iinst->applyHotfixes($db_updater);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

echo "Updating languages...";
$lng->setDbHandler($ilDB);
$iinst->installLanguages($lng);
echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
<?php

$config_path = $argv[1];

require __DIR__ . '/../../vendor/autoload.php';

//set error_reporting auf ein böses level
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

$json_string = file_get_contents($config_path);
$parser = new \CaT\InstILIAS\YamlParser();
$general_config = $parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\General");

$absolute_path = $general_config->server()->absolutePath();
$client_id = $general_config->client()->name();

echo "\n\nConfigurate ILIAS.";
$ilias_configurator = new \CaT\InstILIAS\IliasReleaseConfigurator($absolute_path, $client_id);

if($general_config->category() !== null) {
	echo "\nCreate Categories.";
	$ilias_configurator->createCategories($general_config->category());
	echo "\t\tDone...\n";
}

if($general_config->orgunit() !== null) {
	echo "\nCreate OrgUnits.";
	$ilias_configurator->createOrgUnits($general_config->orgunit());
	echo "\t\tDone...\n";
}

if($general_config->role() !== null) {
	echo "\nCreate global Roles.";
	$ilias_configurator->createRoles($general_config->role());
	echo "\t\tDone...\n";
}

if($general_config->ldap() !== null) {
	echo "\nConfigure LDAP Server.";
	$ilias_configurator->configureLDAPServer($general_config->ldap());
	echo "\t\tDone...\n";
}

if($general_config->table() !== null) {
	echo "\nConfigure Tables Server.";
	$ilias_configurator->configureTables($general_config->table());
	echo "\t\tDone...\n";
}

echo "\n\nIlias successfull configured.";
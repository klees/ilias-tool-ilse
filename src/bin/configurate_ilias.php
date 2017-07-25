<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

$config_path = $argv[1];

require __DIR__ . '/../../vendor/autoload.php';

error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

$yaml_string = file_get_contents($config_path);
$parser = new \CaT\ilse\YamlParser();
$general_config = $parser->read_config($yaml_string, "\\CaT\\ilse\\Config\\General");

$absolute_path = $general_config->server()->absolutePath();
$client_id = $general_config->client()->name();

$ilias_configurator = new \CaT\ilse\IliasReleaseConfigurator($absolute_path, $client_id);
echo "\n\nConfigure ILIAS.";

if($general_config->category() !== null) {
	echo "\nCreating categories...";
	$ilias_configurator->getCategoriesConfigurator()->createCategories($general_config->category());
	echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->orgunit() !== null) {
	echo "\nCreating orgunits...";
	$ilias_configurator->getOrgUnitsConfigurator()->createOrgUnits($general_config->orgunit());
	echo "\t\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->role() !== null) {
	echo "\nCreating global roles...";
	$ilias_configurator->getRolesConfigurator()->createRoles($general_config->role());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->ldap() !== null) {
	echo "\nConfiguring LDAP server settings...";
	$ilias_configurator->getLDAPConfigurator()->configureLDAPServer($general_config->ldap());
	$ilias_configurator->getLDAPConfigurator()->mapLDAPValues($general_config->ldap());
	echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->plugin() !== null) {
	echo "\nInstalling plugins...";
	$ilias_configurator->getPluginsConfigurator()->installPlugins($general_config->plugin());
	$ilias_configurator->getPluginsConfigurator()->activatePlugins($general_config->plugin());
	echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->orgunitType() !== null) {
	echo "\nCreating orgunit types...";
	$ilias_configurator->getOrgUnitsConfigurator()->createOrgunitTypes($general_config->orgunitType());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->orgunitTypeAssignment() !== null) {
	echo "\nAssigning orgunit types to orgunit...";
	$ilias_configurator->getOrgUnitsConfigurator()->assignOrgunitTypesToOrgunits($general_config->orgunitTypeAssignment());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->passwordSettings() !== null) {
	echo "\nConfiguring password settings...";
	$ilias_configurator->getUserConfigurator()->passwordSettings($general_config->passwordSettings());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->user() !== null) {
	$user_configurator = $ilias_configurator->getUserConfigurator();
	echo "\nConfiguring self registration mode...";
	$user_configurator->registration($general_config->user());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

	echo "\nChanging requirement settings for basic fields...";
	$user_configurator->changeRequirementSettings($general_config->user());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

	if($general_config->user()->users()) {
		echo "\nCreating user accounts...";
		$user_configurator->createUserAccounts($general_config->user());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}
}

if($general_config->editor() !== null) {
	echo "\nSetting usage of TinyMCE...";
	$ilias_configurator->getEditorConfigurator()->tinyMCE($general_config->editor());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";

	echo "\nSetting usage of repo page editor...";
	$ilias_configurator->getEditorConfigurator()->repoPageEditor($general_config->editor());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->javaServer() !== null) {
	echo "\nConfiguring java server...";
	$ilias_configurator->getJavaServerConfigurator()->javaServer($general_config->javaServer());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->certificate() !== null) {
	echo "\nConfiguring certificate...";
	$ilias_configurator->getCertificatesConfigurator()->certificate($general_config->certificate());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->soap() !== null) {
	echo "\nConfiguring soap...";
	$ilias_configurator->getSoapConfigurator()->soap($general_config->soap());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

if($general_config->learningProgress() !== null) {
	echo "\nConfiguring LP...";
	$ilias_configurator->getLearningProgressConfigurator()->learningProgress($general_config->learningProgress());
	echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
}

echo "\n\nIlias successfull configured.";
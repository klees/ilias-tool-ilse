<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\interfaces;

/**
 * Interface for ILIAS Configurator.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
interface Configurator {
	/**
	 * initialize ilias for further configuration
	 */
	public function initIlias();

	/**
	 * creates global roles
	 *
	 *@param \CaT\InstILIAS\Config\Roles $install_roles
	 */
	public function createRoles(\CaT\InstILIAS\Config\Roles $install_roles);

	/**
	 * creates organisational units according to defined structur
	 * recursive
	 *
	 * @param \CaT\InstILIAS\Config\OrgUnits $install_orgunits
	 */
	public function createOrgUnits(\CaT\InstILIAS\Config\OrgUnits $install_orgunits);

	/**
	 * creates categories units according to defined structur
	 * recursive
	 *
	 * @param \CaT\InstILIAS\Config\OrgUnits $install_categories
	 */
	public function createCategories(\CaT\InstILIAS\Config\Categories $install_categories);

	/**
	 * configurates the LDAP server settings for login
	 *
	 * @param \CaT\InstILIAS\Config\LDAP $ldap_config
	 */
	public function configureLDAPServer(\CaT\InstILIAS\Config\LDAP $ldap_config);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\Plugins $plugins
	 */
	public function installPlugins(\CaT\InstILIAS\Config\Plugins $plugins);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\Plugins $plugins
	 */
	public function activatePlugins(\CaT\InstILIAS\Config\Plugins $plugins);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\OrgunitTypes $orgunit_types
	 */
	public function createOrgunitTypes(\CaT\InstILIAS\Config\OrgunitTypes $orgunit_types);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\OrgunitTypeAssignment $orgunit_type_assignment
	 */
	public function assignOrgunitTypesToOrgunits(\CaT\InstILIAS\Config\OrgunitTypeAssignments $orgunit_type_assignment);

	/**
	 * Configurate the type of self registration
	 *
	 * @param \CaT\InstILIAS\Config\Users $users
	 */
	public function registration(\CaT\InstILIAS\Config\Users $users);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\Users $users
	 */
	public function createUserAccounts(\CaT\InstILIAS\Config\Users $users);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\PasswordSettings $password_settings
	 */
	public function passwordSettings(\CaT\InstILIAS\Config\PasswordSettings $password_settings);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\TinyMCE $tiny_mce
	 */
	public function tinyMCE(\CaT\InstILIAS\Config\Editor $editor);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\JavaServer $java_server
	 */
	public function javaServer(\CaT\InstILIAS\Config\JavaServer $java_server);

	/**
	 *
	 *
	 * @param \CaT\InstILIAS\Config\Editor $editor
	 */
	public function repoPageEditor(\CaT\InstILIAS\Config\Editor $editor);

	/**
	 * Enable or disable certifcates
	 *
	 * @param \CaT\InstILIAS\Config\Certificate $certificate
	 */
	public function certificate(\CaT\InstILIAS\Config\Certificate $certificate);

	/**
	 * Configure SOAP
	 *
	 * @param \CaT\InstILIAS\Config\Soap $soap
	 */
	public function soap(\CaT\InstILIAS\Config\Soap $soap);
}
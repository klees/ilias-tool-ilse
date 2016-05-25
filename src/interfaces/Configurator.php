<?php
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
	 *@param mixed $install_roles
	 */
	public function createRoles($install_roles);

	/**
	 * creates organisational units according to defined structur
	 * recursive
	 *
	 * @param mixed $install_orgunits
	 */
	public function createOrgUnits($install_orgunits);

	/**
	 * creates categories units according to defined structur
	 * recursive
	 *
	 * @param mixed $install_categories
	 */
	public function createCategories($install_categories);

	/**
	 * configurates the LDAP server settings for login
	 *
	 * @param mixed $ldap_config
	 */
	public function configurateLDAPServer($ldap_config);
}
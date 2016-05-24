<?php
namespace CaT\InstILIAS\interfaces;

/**
 * Interface for ILIAS Configurator.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
interface Configurator {
	/**
	 * ilias is initialisiert
	 */
	public function initIlias();

	/**
	 * roles in ilias created
	 */
	public function createRoles($install_roles);

	/**
	 * orgunits in ilias created
	 * recursive
	 *
	 * @param mixed $install_orgunits
	 */
	public function createOrgUnits($install_orgunits);

	/**
	 * categories in ilias created
	 * recursive
	 *
	 * @param mixed $install_categories
	 */
	public function createCategories($install_categories);

	/**
	 * ldap server is configured
	 *
	 * @param mixed $ldap_config
	 */
	public function configureLDAPServer($ldap_config);
}
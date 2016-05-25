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
	public function configurateLDAPServer(\CaT\InstILIAS\Config\LDAP $ldap_config);
}
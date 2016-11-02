<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\interfaces;

/**
 * Inteface for installing, updating, activate or deactivate an ILIAS Plugin
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
interface Plugin {
	/**
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 * @param string 					   $absolute_path
	 *
	 * @throws RuntimeException
	 *
	 * @return boolean
	 */
	public function install(\CaT\InstILIAS\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 *
	 * @return booelan
	 */
	public function isInstalled(\CaT\InstILIAS\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function update(\CaT\InstILIAS\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function activate(\CaT\InstILIAS\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function deactivate(\CaT\InstILIAS\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 */
	public function updateLanguage(\CaT\InstILIAS\Config\Plugin $plugin);

	/**
	 * get an instance of installed plugin
	 *
	 * @return object
	 */
	public function getPluginObject($plugin_name);

	/**
	 * Uninstall plugin
	 *
	 * @param string $plugin_name
	 */
	public function uninstall($plugin_name);
}
<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\ilse\interfaces;

/**
 * Inteface for installing, updating, activate or deactivate an ILIAS Plugin
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
interface Plugin {
	/**
	 *
	 * @param \CaT\ilse\Config\Plugin $plugin
	 *
	 * @throws RuntimeException
	 *
	 * @return boolean
	 */
	public function install(\CaT\ilse\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\ilse\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function isInstalled(\CaT\ilse\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\ilse\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function update(\CaT\ilse\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\ilse\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function activate(\CaT\ilse\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\ilse\Config\Plugin $plugin
	 *
	 * @return boolean
	 */
	public function deactivate(\CaT\ilse\Config\Plugin $plugin);

	/**
	 *
	 * @param \CaT\ilse\Config\Plugin $plugin
	 */
	public function updateLanguage(\CaT\ilse\Config\Plugin $plugin);

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

	/**
	 * Check wether the plugin needs an update
	 *
	 * @param \ilPlugin $plugin
	 */
	public function needsUpdate(\ilPlugin $plugin);
}
<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\mocks;
/**
 * implementation of plugin interface to install plugins
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class IliasPluginInstallerMock implements \CaT\InstILIAS\interfaces\Plugin {
	/**
	 * @inheritdoc
	 */
	public function install(\CaT\InstILIAS\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function isInstalled(\CaT\InstILIAS\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function update(\CaT\InstILIAS\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function activate(\CaT\InstILIAS\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function deactivate(\CaT\InstILIAS\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function updateLanguage(\CaT\InstILIAS\Config\Plugin $plugin) {
		return;
	}

	/**
	 * @inheritdoc
	 */
	public function getPluginObject($plugin_name) {
		return null;
	}
}
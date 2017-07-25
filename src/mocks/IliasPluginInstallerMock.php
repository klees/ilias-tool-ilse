<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\ilse\mocks;
/**
 * implementation of plugin interface to install plugins
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class IliasPluginInstallerMock implements \CaT\ilse\interfaces\Plugin {
	/**
	 * @inheritdoc
	 */
	public function install(\CaT\ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function isInstalled(\CaT\ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function update(\CaT\ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function activate(\CaT\ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function deactivate(\CaT\ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function updateLanguage(\CaT\ilse\Config\Plugin $plugin) {
		return;
	}

	/**
	 * @inheritdoc
	 */
	public function getPluginObject($plugin_name) {
		return null;
	}

	public function uninstall($plugin_name) {
		return null;
	}
}
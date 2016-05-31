<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS;
/**
 * implementation of plugin interface to install plugins
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class IliasPluginInstaller implements \CaT\InstILIAS\interfaces\Plugin {
	/**
	 * @inheritdoc
	 */
	public function install(\CaT\InstILIAS\Config\Plugin $plugin, $absolute_path) {
		assert('is_string($absolute_path)');

		$git = new \CaT\InstILIAS\GitExecuter;
		$plugin_path = $absolute_path."/".$plugin->componentType()."/".$plugin->componentName()."/".$plugin->pluginSlot()."/".$plugin->name();

		try {
			$git->cloneGitTo($plugin->git()->gitUrl(), $plugin->git()->gitBranchName(), $plugin_path);
		} catch(\RuntimeException $e) {
			echo $e->getMessage();
			die(1);
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function update(\CaT\InstILIAS\Config\Plugin $plugin) {
		$pl = getPluginObject($plugin);
		$pl->update();

		return !$pl->needsUpdate();
	}

	/**
	 * @inheritdoc
	 */
	public function activate(\CaT\InstILIAS\Config\Plugin $plugin) {
		$pl = getPluginObject($plugin);
		$pl->activate();

		return $pl->isActive();
	}

	/**
	 * @inheritdoc
	 */
	public function deactivate(\CaT\InstILIAS\Config\Plugin $plugin) {
		$pl = getPluginObject($plugin);
		$pl->deactivate();

		return !$pl->isActive();
	}

	/**
	 * @inheritdoc
	 */
	public function updateLanguage(\CaT\InstILIAS\Config\Plugin $plugin) {
		$pl = getPluginObject($plugin);
		$pl->updateLanguages();
	}

	/**
	 * get the ILIAS plugin object for defined plugin
	 *
	 * @param \CaT\InstILIAS\Config\Plugin $plugin
	 *
	 * @return ilPlugin $pl
	 */
	protected function getPluginObject(\CaT\InstILIAS\Config\Plugin $plugin) {
		if($plugin>componentType() == "Services") {
			$cType = IL_COMP_SERVICE;
		} else if($plugin>componentType() == "Modules") {
			$c_type = IL_COMP_MODULE;
		}

		include_once("./Services/Component/classes/class.ilPlugin.php");
		$pl = ilPlugin::getPluginObject($c_type, $plugin->componentName(), $plugin->pluginSlot(), $plugin->name());

		return $pl;
	}
}
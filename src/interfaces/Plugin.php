<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\interfaces;

/**
 * Inteface for installing, updating, activate or deactivate an ILIAS Plugin
 *
 */
interface Plugin {
	/**
	 *
	 * @param string $absolute_path			Path ILIAS is installed to
	 * @param string $component_category	Name of the category (Services or Modules)
	 * @param string $component_name		Name of the component (Cron)
	 * @param string $plugin_slot			Used plugin slot
	 */
	public function createPath($absolute_path, $component_category, $component_name, $plugin_slot);

	/**
	 *
	 * @param \CaT\InstILIAS\Congig\GitBranch $git_branch 	gitBranch config to find the needed plugin
	 * @param string $absolute_path							Path ILIAS is installed to
	 * @param string $component_category					Name of the category (Services or Modules)
	 * @param string $component_name						Name of the component (Cron)
	 * @param string $plugin_slot							Used plugin slot
	 */
	public function checkout(\CaT\InstILIAS\Congig\GitBranch $git_branch, $absolute_path, $component_category, $component_name, $plugin_slot);

	/**
	 *
	 */
	public function update();

	/**
	 *
	 */
	public function activate();

	/**
	 *
	 */
	public function deactivate();

	/**
	 *
	 */
	public function updateLanguage();
}
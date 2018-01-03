<?php
namespace CaT\Ilse\Setup;

use CaT\Ilse\Aux\ILIAS\PluginInfo;

/**
 * Interface Plugin
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
interface PluginAdministration
{
	/**
	 * Creates a record in db for the specified plugin
	 *
	 * @param 	PluginInfo 		$pi
	 * @return 	void
	 */
	public function install(PluginInfo $pi);

	/**
	 * Update plugins
	 *
	 * @param 	PluginInfo 		$pi
	 * @return 	void
	 */
	public function update(PluginInfo $pi);

	/**
	 * Activate plugin
	 *
	 * @param 	$name
	 * @return 	void
	 */
	public function activate(PluginInfo $pi);

	/**
	 * Update language for a plugin
	 *
	 * @param 	PluginInfo 		$pi
	 * @return 	void
	 */
	public function updateLanguage(PluginInfo $pi);

	/**
	 * Uninstall plugin
	 *
	 * @param 	PluginInfo 		$pi
	 * @return 	void
	 */
	public function uninstall(PluginInfo $pi);

	/**
	 * Checks whether a plugin needs an update
	 *
	 * @param 	PluginInfo 		$pi
	 * @return 	bool
	 */
	public function needsUpdate(PluginInfo $pi);

	/**
	 * Checks whether a plugin is active
	 *
	 * @param 	PluginInfo 		$pi
	 * @return 	bool
	 */
	public function isActive(PluginInfo $pi);
}
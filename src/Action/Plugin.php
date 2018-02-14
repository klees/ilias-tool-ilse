<?php
namespace CaT\Ilse\Action;

use CaT\Ilse\Aux\ILIAS\PluginInfo;
/**
 * Trait Plugin
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
trait Plugin
{
	/**
	 * Get an array with the ilias path for a plugin and its name
	 *
	 * @param 	PluginInfo 	$info
	 * @return 	string[]
	 */
	protected function createPluginMetaData(PluginInfo $info)
	{
		$link = array();
		$absolute_path = $this->server->absolute_path();
		$plugin_default_path = "Customizing/global/plugins";

		$link['path'] =
			$absolute_path."/".
			$plugin_default_path."/".
			$info->getComponentType()."/".
			$info->getComponentName()."/".
			$info->getSlot();
		$link['name'] = $info->getPluginName();

		return $link;
	}
}

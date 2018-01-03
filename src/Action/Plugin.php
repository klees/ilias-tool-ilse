<?php
namespace CaT\Ilse\Action;

/**
 * Trait Plugin
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
trait Plugin
{
	/**
	 * Get repo urls from config file
	 *
	 * @return string[]
	 */
	public function getRepoUrls()
	{
		$ret = array_map(function($plugin)
			{
				return $plugin->git()->url();
			}
			, $this->plugins->plugins());
		return $ret;
	}

	/**
	 * Get a PluginInfo object.
	 *
	 * @param 	string 	$dir
	 * @return 	PluginInfo
	 */
	protected function getPluginInfo($dir)
	{
		assert('is_string($dir)');

		if(!$this->filesystem->isDirectory($dir)) {
			throw new \Exception("Plugin Directory ".$dir." doesn't exist!");
		}

		return $this->getPluginInfoReader()->readInfo($dir);
	}

	/**
	 * Get an array of installed plugins with their repo-names.
	 *
	 * @return string[] | []
	 */
	public function getInstalledPlugins()
	{
		if($this->filesystem->exists($this->plugins->dir()) && !$this->filesystem->isEmpty($this->plugins->dir()))
		{
			return $this->filesystem->getSubdirectories($this->plugins->dir());
		}
		return array();
	}

	/**
	 * Get an instance of PluginInfoReader for ILIAS 5.2
	 *
	 * @return 	ILIAS\PluginInfoReader
	 */
	protected function getPluginInfoReader()
	{
		if($this->plugin_info_reader == null) {
			$this->plugin_info_reader = $this->reader_factory->getPluginInfoReader("5.2", $this->server, $this->filesystem);
		}
		return $this->plugin_info_reader;
	}

	/**
	 * Get an array with the ilias path for a plugin and its name
	 *
	 * @param 	PluginInfo 	$info
	 * @return 	string[]
	 */
	public function createPluginMetaData(PluginInfo $info)
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
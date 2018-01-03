<?php
namespace CaT\Ilse\Action;

use CaT\Ilse\Config\Server;
use CaT\Ilse\Config\Plugins;
use CaT\Ilse\Setup\PluginAdministrationFactory;
use CaT\Ilse\Config\General;
use CaT\Ilse\Aux\ILIAS;
use CaT\Ilse\Aux\TaskLogger;
use CaT\Ilse\Aux\Filesystem;
use CaT\Ilse\Aux\ILIAS\PluginInfo;

/**
 * Install or update plugins from a list.
 * It also deletes plugins that are installed and now absent from the list.
 * Then it activates the plugins and updates the language.
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
class UpdatePlugins implements Action
{
	use Plugin;

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var Plugins
	 */
	protected $plugins;

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @var PluginAdministration | null
	 */
	protected $plugin_admin;

	/**
	 * @var PluginInfoReader | null
	 */
	protected $plugin_info_reader;

	/**
	 * @var General
	 */
	protected $config;

	/**
	 * @var PluginAdminFactory
	 */
	protected $plugin_admin_factory;

	/**
	 * @var PluginInfoReaderFactory
	 */
	protected $reader_factory;

	/**
	 * @var TaskLogger
	 */
	protected $logger;

	/**
	 * Constructor of the class UpdatePlugins
	 */
	public function __construct(
		Server $server,
		Plugins $plugins,
		Filesystem $filesystem,
		General $config,
		PluginAdministrationFactory $plugin_admin_factory,
		TaskLogger $logger,
		ILIAS\PluginInfoReaderFactory $reader_factory
	) {
		$this->server = $server;
		$this->plugins = $plugins;
		$this->filesystem = $filesystem;
		$this->config = $config;
		$this->plugin_admin_factory = $plugin_admin_factory;
		$this->logger = $logger;
		$this->reader_factory = $reader_factory;
	}

	/**
	 * @inheritdoc
	 */
	public function perform()
	{
		$pis = $this->getPluginInfoObjects();
		$this->install($pis);
		$this->update($pis);
		$this->activate($pis);
		$this->updateLanguage($pis);
	}

	/**
	 * Get an instance of PluginAdministration
	 *
	 * @return PluginAdministration
	 */
	protected function getPluginAdmin()
	{
		if(!$this->plugin_admin)
		{
			$this->plugin_admin = $this->plugin_admin_factory->getPluginAdministrationForRelease(
				"5.2",
				$this->config
				);
		}
		return $this->plugin_admin;
	}

	/**
	 * Install plugins
	 *
	 * @param 	PluginInfo[]	$pis
	 * @return 	void
	 */
	protected function install(array $pis)
	{
		$this->logger->eventually("Install plugins", function() use($pis) {
			foreach ($pis as $pi) {
				$link = $this->createPluginMetaData($pi);
				if(!$this->filesystem->isLink($link["path"].'/'.$link['name'])) {
					$this->logger->always("install plugin ".$pi->getPluginName(), function() use($pi) {
						$this->getPluginAdmin()->install($pi);
					});
				}
			}
		});
	}

	/**
	 * Update plugins
	 *
	 * @param 	PluginInfo[]	$pis
	 * @return 	void
	 */
	protected function update(array $pis)
	{
		$this->logger->eventually("Update plugin", function() use($pis) {
			foreach ($pis as $pi) {
				if($this->getPluginAdmin()->needsUpdate($pi)) {
					$this->logger->always("update plugin ".$pi->getPluginName(), function() use($pi) {
						$this->getPluginAdmin()->update($pi);
					});
				}
			}
		});
	}

	/**
	 * Activate plugins
	 *
	 * @param 	PluginInfo[]	$pis
	 * @return 	void
	 */
	protected function activate(array $pis)
	{
		$this->logger->eventually("Activate plugin", function() use($pis) {
			foreach ($pis as $pi) {
				if(!$this->getPluginAdmin()->isActive($pi)) {
					$this->logger->always("activate plugin ".$pi->getPluginName(), function() use($pi) {
						$this->getPluginAdmin()->activate($pi);
					});
				}
			}
		});
	}

	/**
	 * Update language
	 *
	 * @param 	PluginInfo[]	$pis
	 * @return 	void
	 */
	protected function updateLanguage(array $pis)
	{
		$this->logger->eventually("Update plugin language", function() use($pis) {
			foreach ($pis as $pi) {
				$this->logger->always("update language for plugin ".$pi->getPluginName(), function() use($pi) {
					$this->getPluginAdmin()->updateLanguage($pi);
				});
			}
		});
	}

	/**
	 * Delete plugin
	 *
	 * @param 	string 		$name
	 * @return 	void
	 */
	public function uninstall($name)
	{
		assert('is_string($name)');

		$this->getPluginAdmin()->uninstall($name);
	}

	/**
	 * Get a PluginInfo object foreach installed plugin.
	 *
	 * @return PluginInfo[]
	 */
	protected function getPluginInfoObjects()
	{
		$pis = array();
		$plugins = $this->getInstalledPlugins();

		foreach ($plugins as $plugin) {
			$pis[] = $this->getPluginInfo($this->plugins->dir().'/'.$plugin);
		}
		return $pis;
	}
}
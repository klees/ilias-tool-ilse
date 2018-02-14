<?php
namespace CaT\Ilse\Action;

use CaT\Ilse\Config\Server;
use CaT\Ilse\Config\Plugins;
use CaT\Ilse\Aux\Filesystem;
use CaT\Ilse\Aux\Git\GitFactory;
use CaT\Ilse\Aux\TaskLogger;
use CaT\Ilse\Aux\ILIAS\PluginInfoReaderFactory;
use CaT\Ilse\Aux\ILIAS\PluginInfo;

/**
 * Class UpdatePluginsDirectory
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
class UpdatePluginsDirectory implements Action
{
	const BRANCH = "master";

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
	 * @var GitFactory
	 */
	protected $git_factory;

	/**
	 * @var TaskLogger
	 */
	protected $task_logger;

	/**
	 * @var PluginInfoReaderFactory
	 */
	protected $reader_factory;

	/**
	 * @var ILIAS\PluginInfoReader
	 */
	protected $plugin_info_reader;

	/**
	 * @var UpdatePlugins
	 */
	protected $update_plugins;

	/**
	 * @var Yaml
	 */
	protected $parser;


	/**
	 * Constructor of the class UpdatePluginsDirectory
	 */
	public function __construct(
		Server $server,
		Plugins $plugins,
		Filesystem $filesystem,
		GitFactory $git_factory,
		TaskLogger $task_logger,
		PluginInfoReaderFactory $reader_factory,
		UpdatePlugins $update_plugins
	) {
		$this->server = $server;
		$this->plugins = $plugins;
		$this->filesystem = $filesystem;
		$this->git_factory = $git_factory;
		$this->task_logger = $task_logger;
		$this->reader_factory = $reader_factory;
		$this->plugin_info_reader = null;
		$this->update_plugins = $update_plugins;
	}

	/**
	 * @inheritdoc
	 */
	public function perform()
	{
		$this->initPluginDir();
		$this->clonePlugins();
		$this->updatePlugins();
		$this->deleteUnlistedPlugins();
		$this->linkPluginsToIlias();
	}

	/**
	 * Initialize the plugin directory.
	 *
	 * @throws \Exception
	 * @return void
	 */
	protected function initPluginDir()
	{
		if(!$this->filesystem->exists($this->plugins->dir())) {
			$this->task_logger->always("Make plugin directory", function () {
				$this->filesystem->makeDirectory($this->plugins->dir());
			});
		}
		$this->task_logger->always("Check write permissions", function() {
			if(!$this->filesystem->isWriteable($this->plugins->dir())) {
					throw new \Exception("No write permissions");
			}
		});
	}

	/**
	 * Clone plugins
	 *
	 * @return void
	 */
	protected function clonePlugins()
	{
		$installed_plugins = $this->filesystem->getSubdirectories($this->plugins->dir());
		$repos = $this->getRepoInfo();
		$this->task_logger->eventually("Clone new plugins", function () use ($repos, $installed_plugins) {
			foreach ($repos as list($url, $branch)) {
				$name = $this->getRepoNameFromUrl($url);
				if(in_array($name, $installed_plugins)) {
					continue;
				}
				$this->filesystem->makeDirectory($this->plugins->dir()."/".$name);
				$git = $this->git_factory->getRepo($this->plugins->dir()."/".$name, $url);
				$this->task_logger->always("clone plugin $name", function() use ($git, $branch) {
					$git->gitClone();
					$git->gitCheckOut($branch);
				});
			}
		});
	}

	/**
	 * Update plugins
	 *
	 * @return void
	 */
	protected function updatePlugins()
	{
		$repos = $this->getRepoInfo();
		$this->task_logger->eventually("Pull plugins", function () use ($repos) {
			foreach ($repos as list($url, $branch)) {
				$name = $this->getRepoNameFromUrl($url);
				$git = $this->git_factory->getRepo($this->plugins->dir().'/'.$name, $url);
				$this->task_logger->always("pull plugin $name", function() use ($git, $branch) {
					$git->gitPull($branch);
				});
			}
		});
	}

	/**
	 * Delete plugins, that not listed in the config file
	 *
	 * @return void
	 */
	protected function deleteUnlistedPlugins()
	{
		$urls = array_map(function($v) { return $v[0]; }, $this->getRepoInfo());
		$installed_plugins = $this->filesystem->getSubdirectories($this->plugins->dir());
		$marked_plugins = $this->getUnlistedPlugins($installed_plugins, $urls);
		$reader = $this->getPluginInfoReader();

		$this->task_logger->eventually("Delete plugins", function () use($marked_plugins, $reader) {
			foreach($marked_plugins as $marked_plugin) {
				$this->task_logger->always("delete plugin $marked_plugin", function() use($marked_plugin) {
					$pi = $reader->readInfo($this->plugins->dir().'/'.$marked_plugin);
					$name = $pi->getPluginName();
					$path = $this->server->absolute_path()."/".$pi->getRelativePluginPath();
					$this->update_plugins->uninstall($pi);
					$this->filesystem->remove($path.'/'.$name);
					$this->filesystem->remove($this->plugins->dir()."/".$marked_plugin);
				});
			}
		});
	}

	/**
	 * Link plugins to ilias
	 *
	 * @throws \Exception
	 * @return void
	 */
	protected function linkPluginsToIlias()
	{
		$reader = $this->getPluginInfoReader();
		$this->task_logger->eventually("Link plugins", function () use ($reader) {
			$installed_plugins = $this->filesystem->getSubdirectories($this->plugins->dir());
			foreach($installed_plugins as $plugin) {
				$pi = $reader->readInfo($this->plugins->dir().'/'.$plugin);
				$name = $pi->getPluginName();
				$path = $this->server->absolute_path()."/".$pi->getRelativePluginPath();
				if(!$this->filesystem->exists($path)) {
					$this->filesystem->makeDirectory($path);
				}
				if(!$this->filesystem->isWriteable($path)) {
					throw new \Exception("No write acces to '$path'");
				}
				if(!$this->filesystem->isLink("$path/$name")) {
					$this->task_logger->always("link plugin ".$pi->getPluginName(), function() use($plugin, $path, $name) {
						$this->filesystem->symlink($this->plugins->dir()."/".$plugin, "$path/$name");
					});
				}
			}
		});
	}

	/**
	 * Get the repo name
	 *
	 * @param 	string 	$url
	 * @return 	string
	 */
	public function getRepoNameFromUrl($url)
	{
		assert('is_string($url)');

		$lastslash = strrpos($url, '/');
		$result = substr($url, $lastslash+1);
		$git = strpos($result, ".git");
		if($git) {
			$result = substr($result, 0, $git);
		}
		return $result;
	}

	/**
	 * Get unlisted plugins
	 *
	 * @param 	array 	$installed
	 * @param 	array 	$listed
	 * @return 	string[]
	 */
	public function getUnlistedPlugins(array $installed, array $listed)
	{
		$listed = array_map(function($url) { return $this->getRepoNameFromUrl($url); }, $listed);
		return array_diff($installed, $listed);
	}

	/**
	 * Get an instance of PluginInfoReader for ILIAS 5.2
	 *
	 * @return 	ILIAS\PluginInfoReader
	 */
	protected function getPluginInfoReader()
	{
		if($this->plugin_info_reader === null) {
			$this->plugin_info_reader = $this->reader_factory->getPluginInfoReader("5.2", $this->server, $this->filesystem);
		}
		return $this->plugin_info_reader;
	}


	/**
	 * Get urls and branches of plugin-repos.
	 *
	 * Each entry is an $url,$branch-tuple.
	 *
	 * @return array[]
	 */
	protected function getRepoInfo() {
		return array_map(function($plugin) {
			return [$plugin->git()->url(), $plugin->git()->branch()];
		}, $this->plugins->plugins());
	}
}

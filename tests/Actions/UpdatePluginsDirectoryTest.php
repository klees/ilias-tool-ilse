<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use \CaT\Ilse\Action\UpdatePluginsDirectory;
use \CaT\Ilse\Aux\UpdatePluginsHelper;
use \CaT\Ilse\Action\UpdatePlugins;
use \CaT\Ilse\Config;
use \CaT\Ilse\IliasReleaseConfigurator;
use \CaT\Ilse\Aux\TaskLogger;
use CaT\Ilse\Aux\Git;
use CaT\Ilse\Aux\ILIAS;
use CaT\Ilse\Aux;
use \CaT\Ilse\Aux\ILIAS\PluginInfo;
use \CaT\Ilse\Aux\ILIAS\PluginInfoReader;

use CaT\Ilse\Action\UpdatePlugin;

class UpdatePluginsDirectoryTest extends PHPUnit_Framework_TestCase
{
	protected $path;
	protected $url;
	protected $url2;
	protected $expected;
	protected $git_factory;
	protected $git_wrapper;
	protected $filesystem;
	protected $task_logger;
	protected $update_plugins;
	protected $plugin_info_reader_factory;
	protected $git;
	protected $server;
	protected $plugin;
	protected $plugins;

	public function setUp()
	{
		$this->path = "/var/www/html/ilias/Customizing/global/plugins/Service/Repository/RepositoryObject";
		$this->url = "https://github.com/conceptsandtraining/ilias-tool-ilse.git";
		$this->url2 = "https://github.com/conceptsandtraining/ilias-tool-ilse";
		$this->expected = "ilias-tool-ilse";

		$this->git_factory = $this->createMock(Git\GitFactory::class);
		$this->git_wrapper = $this->createMock(Git\GitWrapper::class);
		$this->filesystem = $this->createMock("CaT\Ilse\Aux\Filesystem");
		$this->task_logger = $this->createMock(TaskLogger::class);
		$this->update_plugins = $this->createMock(UpdatePlugins::class);
		$this->plugin_info_reader_factory = $this->createMock(ILIAS\PluginInfoReaderFactory::class);

		$this->git = new Config\Git($this->url, "master", "5355");
		$this->server = new Config\Server("http://ilias.de", "/var/www/html/ilias", "Europe/Berlin");
		$this->plugin = new Config\Plugin($this->path, $this->git);
		$this->plugins = new Config\Plugins($this->path, array($this->plugin));

		$this->object = new UpdatePluginsDirectory(
			$this->server,
			$this->plugins,
			$this->filesystem,
			$this->git_factory,
			$this->task_logger,
			$this->plugin_info_reader_factory,
			$this->update_plugins
		);
	}
	public function test_perform()
	{
		$name = "ilias-tool-ilse";

		$this->filesystem
			->expects($this->any())
			->method("makeDirectory")
			->will($this->onConsecutiveCalls(
				array(
					$this->path,
					$this->path.'/'.$name,
					$this->path
				)));
		$this->filesystem
			->expects($this->any())
			->method("exists")
			->willReturn(true);
		$this->filesystem
			->expects($this->any())
			->method("isEmpty")
			->willReturn(false);
		$this->filesystem
			->expects($this->any())
			->method("isDirectory")
			->will($this->onConsecutiveCalls(
				array(
					$this->path.$name
				)))
			->willReturn(true);
		$this->filesystem
			->expects($this->atLeast(1))
			->method("isWriteable")
			->with($this->path)
			->willReturn(true);
		$this->filesystem
			->expects($this->exactly(3))
			->method("getSubdirectories")
			->with($this->path)
			->willReturn(array($name));

		$this->git_factory
			->expects($this->any())
			->method("getRepo")
			->with($this->path."/".$name, $this->url, false)
			->willReturn($this->git_wrapper);

		$this->task_logger
			->expects($this->any())
			->method("eventually")
			->will($this->returnCallback(function($s, $c) {
				$c();
			}));
		$this->task_logger
			->expects($this->any())
			->method("always")
			->will($this->returnCallback(function($s, $c) {
				$c();
			}));

		$plugin_info = new PluginInfo
			( "Service"
			, "Repository"
			, "RepositoryObject"
			, "robj"
			, "test"
			);
		$plugin_info_reader = $this->createMock(PluginInfoReader::class);
		$plugin_info_reader
			->expects($this->once())
			->method("readInfo")
			->with($this->path."/".$name)
			->willReturn($plugin_info);

		$this->plugin_info_reader_factory
			->expects($this->once())
			->method("getPluginInfoReader")
			->with("5.2", $this->server, $this->filesystem)
			->willReturn($plugin_info_reader);

		$this->object->perform();
	}

	public function test_getRepoNameFromUrl()
	{
		$result = $this->object->getRepoNameFromUrl($this->url);
		$this->assertEquals($result, $this->expected);

		$result = $this->object->getRepoNameFromUrl($this->url2);
		$this->assertEquals($result, $this->expected);
	}

	public function test_getUnlistedPlugins()
	{
		$installed = [
			"Apfel",
			"Birne",
			"Banane"
			];

		$listed = [
			"/Apfel",
			"/Birne",
			"/Erdbeere"
			];

		$result = $this->object->getUnlistedPlugins($installed, $listed);

		$this->assertContains("Banane", $result);
		$this->assertNotContains("Erdbeere", $result);
	}
}

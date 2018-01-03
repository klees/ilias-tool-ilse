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

use CaT\Ilse\Action\UpdatePlugin;

// If database had it own interface like filesystem, we could
// drop this and write a proper test instead.
class UpdatePluginsDirectoryForTest extends UpdatePluginsDirectory{
}

class UpdatePluginsDirectoryTest extends PHPUnit_Framework_TestCase
{
	public function test_perform()
	{
		$url = "https://my_plugin";
		$path = "/home/vagrant/dummy";
		$name = "my_plugin";
		$yaml = "---
ComponentType: Services
ComponentName: EventHandling
Slot: EventHook
SlotId: evhk";

		$git_factory = $this->createMock(Git\GitFactory::class);
		$git_wrapper = $this->createMock(Git\GitWrapper::class);
		$filesystem = $this->createMock("CaT\Ilse\Aux\Filesystem");
		$task_logger = $this->createMock(TaskLogger::class);
		$update_plugins = $this->createMock(UpdatePlugins::class);
		$plugin_info_reader_factory = $this->createMock(ILIAS\PluginInfoReaderFactory::class);

		$git = new Config\Git($url, "master", "5355");
		$server = new Config\Server("http://ilias.de", "/var/www/html/ilias", "Europe/Berlin");
		$plugin = new Config\Plugin($path, $git);
		$plugins = new Config\Plugins($path, array($plugin));

		$action = new UpdatePluginsDirectoryForTest(
			$server,
			$plugins,
			$filesystem,
			$git_factory,
			$task_logger,
			$plugin_info_reader_factory,
			$update_plugins
			);

		$filesystem
			->expects($this->any())
			->method("makeDirectory")
			->will($this->onConsecutiveCalls(
				array(
					$path,
					$path.'/'.$name,
					$path
				)));

		// $filesystem
		// 	->expects($this->any())
		// 	->method("exists")
		// 	->willReturn(true);
		$filesystem
			->expects($this->any())
			->method("isWriteable")
			->willReturn(true);
		// $filesystem
		// 	->expects($this->any())
		// 	->method("getSubdirectories")
		// 	->willReturn(array("test", "test2"));
		// $filesystem
		// 	->expects($this->any())
		// 	->method("read")
		// 	->willReturn($yaml);

		$git_factory
			->expects($this->any())
			->method("getRepo")
			->with($path."/".$name, $url, false)
			->willReturn($git_wrapper);

		$task_logger
			->expects($this->any())
			->method("eventually")
			->will($this->returnCallback(function($s, $c) {
				$c();
			}));
		$task_logger
			->expects($this->any())
			->method("always")
			->will($this->returnCallback(function($s, $c) {
				$c();
			}));

		// $git_wrapper
		// 	->expects($this->any())
		// 	->method("gitClone");

		// $update_plugins
		// 	->expects($this->any())
		// 	->method("uninstall");

		$action->perform();
	}
}
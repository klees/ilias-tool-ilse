<?php

use \CaT\Ilse\Config\Plugin;
use \CaT\Ilse\Config\Plugins;
use \CaT\Ilse\Config\Git;

class PluginsConfigTest extends PHPUnit_Framework_TestCase {
	public function test_getRepoUrls() {
		$pl1 = $this->getMockBuilder(Plugin::class)
			->setMethods(["git"])
			->disableOriginalConstructor()
			->getMock();
		$git1 = $this->getMockBuilder(Git::class)
			->setMethods(["url"])
			->disableOriginalConstructor()
			->getMock();
		$pl2 = $this->getMockBuilder(Plugin::class)
			->setMethods(["git"])
			->disableOriginalConstructor()
			->getMock();
		$git2 = $this->getMockBuilder(Git::class)
			->setMethods(["url"])
			->disableOriginalConstructor()
			->getMock();
		$plugins = new Plugins("/", [$pl1, $pl2]);

		$pl1
			->expects($this->once())
			->method("git")
			->willReturn($git1);
		$git1
			->expects($this->once())
			->method("url")
			->willReturn("repo1");
		$pl2
			->expects($this->once())
			->method("git")
			->willReturn($git2);
		$git2
			->expects($this->once())
			->method("url")
			->willReturn("repo2");

		$repos = $plugins->getRepoUrls();

		$this->assertEquals(["repo1", "repo2"], $repos);
	}
}

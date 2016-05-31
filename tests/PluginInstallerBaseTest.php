<?php

use \CaT\InstILIAS\Config\Plugins;
use \CaT\InstILIAS\YamlParser;
use \CaT\InstILIAS\mocks\IliasPluginInstallerMock;

class PluginInstallerBaseTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new YamlParser();
		$this->yaml_string = "--- 
plugins:
    0:
        component_type: Services
        component_name: Cron
        plugin_slot: CronHook
        name: Pluginname
        git:
            git_url: Bernd
            git_branch_name: master";

        $this->config = $this->parser->read_config($this->yaml_string, "\\CaT\\InstILIAS\\Config\\Plugins");
        $this->plugin_installer = new IliasPluginInstallerMock();
	}

	public function test_installPlugin() {
		$result = $this->plugin_installer->install($this->config->plugins()[0], "pfad_zu_ilias");
		$this->assertTrue($result);
	}

	public function test_updatePlugin() {
		$result = $this->plugin_installer->update($this->config->plugins()[0]);
		$this->assertTrue($result);
	}

	public function test_activatePlugin() {
		$result = $this->plugin_installer->activate($this->config->plugins()[0]);
		$this->assertTrue($result);
	}

	public function test_deactivatePlugin() {
		$result = $this->plugin_installer->deactivate($this->config->plugins()[0]);
		$this->assertTrue($result);
	}
}
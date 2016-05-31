<?php

use \CaT\InstILIAS\Config\Plugin;

class PluginConfigTest extends PHPUnit_Framework_TestCase {
	public function test_not_enough_params() {
		try {
			$config = new Plugin();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	/**
	 * @dataProvider	PluginConfigValueProvider
	 */
	public function test_PluginConfig($comp_type, $comp_name, $plugin_slot, $name, $git, $valid) {
		if ($valid) {
			$this->_test_valid_PluginConfig($comp_type, $comp_name, $plugin_slot, $name, $git);
		}
		else {
			$this->_test_invalid_PluginConfig($comp_type, $comp_name, $plugin_slot, $name, $git);
		}
	}

	public function _test_valid_PluginConfig($comp_type, $comp_name, $plugin_slot, $name, $git) {
		$config = new Plugin($comp_type, $comp_name, $plugin_slot, $name, $git);
		$this->assertEquals($comp_type, $config->componentCategory());
		$this->assertEquals($comp_name, $config->componentName());
		$this->assertEquals($plugin_slot, $config->pluginSlot());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\GitBranch", $config->git());
	}

	public function _test_invalid_PluginConfig($comp_type, $comp_name, $plugin_slot, $name, $git) {
		try {
			$config = new Plugin($comp_type, $comp_name, $plugin_slot, $name, $git);
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function PluginConfigValueProvider() {
		$ret = array();
		foreach ($this->componentTypeProvider() as $comp_type) {
			foreach ($this->componentNameProvider() as $comp_name) {
				foreach ($this->pluginSlotProvider() as $plugin_slot) {
					foreach ($this->nameProvider() as $name) {
						foreach ($this->gitBranchProvider() as $git) {
							$ret[] = array
								( $comp_type[0], $comp_name[0], $plugin_slot[0], $name[0], $git[0]
								, $comp_type[1] && $comp_name[1] && $plugin_slot[1] && $name[1] && $git[1]);
						}
					}
				}
			}
		}
		return $ret;
	}

	public function componentTypeProvider() {
		return array(array("Services", true)
					, array(4, false)
					, array(true, false)
					, array(array(), false)
				);
	}

	public function componentNameProvider() {
		return array(array("Cron", true)
					, array(4, false)
					, array(true, false)
					, array(array(), false)
				);
	}

	public function pluginSlotProvider() {
		return array(array("CronHook", true)
					, array(4, false)
					, array(true, false)
					, array(array(), false)
				);
	}

	public function gitBranchProvider() {
		return array(array(new \CaT\InstILIAS\Config\GitBranch("url", "branch"), true)
					, array(4, false)
					, array(true, false)
					, array(array(), false)
				);
	}

	public function nameProvider() {
		return array(array("pluginname", true)
					, array(4, false)
					, array(true, false)
					, array(array(), false)
				);
	}
}

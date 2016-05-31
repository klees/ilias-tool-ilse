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
	public function test_PluginConfig($comp_category, $comp_name, $plugin_slot, $git, $valid) {
		if ($valid) {
			$this->_test_valid_PluginConfig($comp_category, $comp_name, $plugin_slot, $git);
		}
		else {
			$this->_test_invalid_PluginConfig($comp_category, $comp_name, $plugin_slot, $git);
		}
	}

	public function _test_valid_PluginConfig($comp_category, $comp_name, $plugin_slot, $git) {
		$config = new Plugin($comp_category, $comp_name, $plugin_slot, $git);
		$this->assertEquals($comp_category, $config->componentCategory());
		$this->assertEquals($comp_name, $config->componentName());
		$this->assertEquals($plugin_slot, $config->pluginSlot());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\GitBranch", $config->git());
	}

	public function _test_invalid_PluginConfig($comp_category, $comp_name, $plugin_slot, $git) {
		try {
			$config = new Plugin($comp_category, $comp_name, $plugin_slot, $git);
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function PluginConfigValueProvider() {
		$ret = array();
		foreach ($this->componentCategoryProvider() as $comp_category) {
			foreach ($this->componentNameProvider() as $comp_name) {
				foreach ($this->plugSlotProvider() as $plugin_slot) {
					foreach ($this->gitBranchProvider() as $git) {
						$ret[] = array
							( $comp_category[0], $comp_name[0], $plugin_slot[0], $git[0]
							, $comp_category[1] && $comp_name[1] && $plugin_slot[1] && $git[1]);
					}
				}
			}
		}
		return $ret;
	}

	public function componentCategoryProvider() {
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

	public function plugSlotProvider() {
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
}

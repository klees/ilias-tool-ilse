<?php

use \CaT\InstILIAS\Config\Categories;
use \CaT\InstILIAS\YamlParser;

class CategoriesConfigTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new YamlParser();
		$this->yaml_string = "--- 
categories:
    0:
        title: Eins
    1:
        title: Zwei
        children:
            10:
                title: ZweiEins
                children: []
            11:
                title: ZweiZwei
                children: []
    2:
        title: Drei
        children: []";
	}

	public function test_not_enough_params() {
		try {
			$config = new Categories();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function test_createCategoriesConfig() {
		$config = $this->parser->read_config($this->yaml_string, "\\CaT\\InstILIAS\\Config\\Categories");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Categories", $config);
	}
}
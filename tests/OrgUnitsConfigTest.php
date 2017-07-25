<?php

use \CaT\ilse\Config\OrgUnits;
use \CaT\ilse\YamlParser;

class OrgUnitsConfigTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new YamlParser();
		$this->yaml_string = "--- 
orgunits:
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
			$config = new OrgUnits();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function test_createOrgUnitsConfig() {
		$config = $this->parser->read_config($this->yaml_string, "\\CaT\\ilse\\Config\\OrgUnits");

		$this->assertInstanceOf("\\CaT\\ilse\\Config\\OrgUnits", $config);
	}
}
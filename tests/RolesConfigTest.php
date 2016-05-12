<?php

use \CaT\InstILIAS\Config\Roles;
use \CaT\InstILIAS\YamlParser;

class RolesConfigTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new YamlParser();
		$this->yaml_string = "--- 
roles:
    0:
        title: Titel1
        description: Der darf alles sehen sonst nicht.
    1:
        title: Titel2
        description: Gruppe für alle
    2:
        title: Titel3
        description: Neue Menschen";
	}

	public function test_not_enough_params() {
		try {
			$config = new Roles();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function test_createIliasConfig() {
		$config = $this->parser->read_config($this->yaml_string, "\\CaT\\InstILIAS\\Config\\Roles");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Roles", $config);
		$this->assertInternalType("array", $config->roles());

		foreach ($config->roles() as $key => $value) {
			$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\role", $value);
		}
	}
}
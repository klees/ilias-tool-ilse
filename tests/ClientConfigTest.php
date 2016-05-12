<?php

use \CaT\InstILIAS\Config\Client;

class ClientConfigTest extends PHPUnit_Framework_TestCase {
	public function test_not_enough_params() {
		try {
			$config = new Client();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	/**
	 * @dataProvider	ClientConfigValueProvider
	 */
	public function test_ClientConfig($data_dir, $name, $password_encoder, $valid) {
		if ($valid) {
			$this->_test_valid_ClientConfig($data_dir, $name, $password_encoder, $valid);
		}
		else {
			$this->_test_invalid_ClientConfig($data_dir, $name, $password_encoder, $valid);
		}
	}

	public function _test_valid_ClientConfig($data_dir, $name, $password_encoder) {
		$config = new Client($data_dir, $name, $password_encoder);
		$this->assertEquals($data_dir, $config->dataDir());
		$this->assertEquals($name, $config->name());
		$this->assertEquals($password_encoder, $config->passwordEncoder());
	}

	public function _test_invalid_ClientConfig($data_dir, $name, $password_encoder) {
		try {
			$config = new Client($data_dir, $name, $password_encoder);
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function ClientConfigValueProvider() {
		$ret = array();
		foreach ($this->dataDirProvider() as $data_dir) {
			foreach ($this->nameProvider() as $name) {
				foreach ($this->passwordEncoderProvider() as $password_encoder) {
					$ret[] = array
						( $data_dir[0], $name[0], $password_encoder[0]
						, $data_dir[1] && $name[1] && $password_encoder[1]);
				}
			}
		}
		return $ret;
	}

	public function dataDirProvider() {
		// Second parameter encodes whether the value is a valid config.
		return array
			( array("/data_dir", true)
			, array(1, false)
			);
	}

	public function nameProvider() {
		return array
			( array("ILIAS", true)
			, array(2, false)
			);
	}

	public function passwordEncoderProvider() {
		return array
			( array("md5", true)
			, array("FOO", false)
			, array("bcrypt", true)
			, array(1, false)
			);
	}
}

<?php

use \CaT\InstILIAS\YamlParser;

class YamlParserTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new YamlParser();
	}

	/**
	* @dataProvider readConfigWithValuesProvider
	*/
	public function test_readConfigWithValues($string, $class) {
		$obj = $this->parser->read_config($string, $class);
		$this->assertInstanceOf($class, $obj);
	}

	public function test_createClientConfig() {
		$json_string = "--- 
data_dir : sdasdads
name : hugo
password_encoder : md5
session_expire: 120";
		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\Client");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Client", $obj);
		$this->assertEquals($obj->dataDir(), "sdasdads");
		$this->assertInternalType("string", $obj->dataDir());
		$this->assertEquals($obj->name(), "hugo");
		$this->assertInternalType("string", $obj->name());
		$this->assertEquals($obj->passwordEncoder(), "md5");
		$this->assertInternalType("string", $obj->passwordEncoder());
	}

	public function test_createDbConfig() {
		$json_string = '---
host: 127.0.0.1
database: ilias
user: user
password: passwd
engine: innodb
encoding: utf8_general_ci';
		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\DB");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\DB", $obj);
		
		$this->assertEquals($obj->host(), "127.0.0.1");
		$this->assertInternalType("string", $obj->host());

		$this->assertEquals($obj->database(), "ilias");
		$this->assertInternalType("string", $obj->database());

		$this->assertEquals($obj->user(), "user");
		$this->assertInternalType("string", $obj->user());

		$this->assertEquals($obj->password(), "passwd");
		$this->assertInternalType("string", $obj->password());

		$this->assertEquals($obj->engine(), "innodb");
		$this->assertInternalType("string", $obj->engine());

		$this->assertEquals($obj->encoding(), "utf8_general_ci");
		$this->assertInternalType("string", $obj->encoding());
	}

	public function test_createGitConfig() {
		$json_string = '---
git_url: https://github.com/
git_branch_name: ilias';

		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\GitBranch");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\GitBranch", $obj);
		
		$this->assertEquals($obj->gitUrl(), "https://github.com/");
		$this->assertInternalType("string", $obj->gitUrl());

		$this->assertEquals($obj->gitBranchName(), "ilias");
		$this->assertInternalType("string", $obj->gitBranchName());
	}

	public function test_createLanguageConfig() {
		$json_string = '---
default: de
available:
    - en
    - de';
		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\Language");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Language", $obj);
		
		$this->assertEquals($obj->default(), "de");
		$this->assertInternalType("string", $obj->default());

		$this->assertEquals($obj->available(), array("en","de"));
		$this->assertInternalType("array", $obj->available());
	}

	public function test_createServerConfig() {
		$json_string = '---
http_path: http://localhost/
absolute_path: /path
timezone: Europe/Berlin';
		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\Server");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Server", $obj);
		
		$this->assertEquals($obj->httpPath(), "http://localhost/");
		$this->assertInternalType("string", $obj->httpPath());

		$this->assertEquals($obj->absolutePath(), "/path");
		$this->assertInternalType("string", $obj->absolutePath());

		$this->assertEquals($obj->timezone(), "Europe/Berlin");
		$this->assertInternalType("string", $obj->timezone());
	}

	public function test_createSetupConfig() {
		$json_string = '---
master_password: KarlHeinz';
		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\Setup");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Setup", $obj);

		$this->assertEquals($obj->masterPassword(), "KarlHeinz");
		$this->assertInternalType("string", $obj->masterPassword());
	}

	public function test_createToolsConfig() {
		$json_string = '---
convert: /ImageMagick
zip: /zip
unzip: /unzip
java: /java';
		$obj = $this->parser->read_config($json_string, "\\CaT\\InstILIAS\\Config\\Tools");

		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Tools", $obj);

		$this->assertEquals($obj->convert(), "/ImageMagick");
		$this->assertInternalType("string", $obj->convert());

		$this->assertEquals($obj->zip(), "/zip");
		$this->assertInternalType("string", $obj->zip());

		$this->assertEquals($obj->unzip(), "/unzip");
		$this->assertInternalType("string", $obj->unzip());

		$this->assertEquals($obj->java(), "/java");
		$this->assertInternalType("string", $obj->java());
	}

	public function readConfigWithValuesProvider() {
		$json_string = '---
data_dir : /data_dir
name : ILIAS
password_encoder : bcrypt
session_expire: 120
host: 127.0.0.1
database: ilias
user: user
password: passwd
engine: innodb
encoding: utf8_general_ci
default: de
available:
    - en 
    - de
http_path: http://localhost
absolute_path: /path
timezone: Europe/Berlin
master_password: KarlHeinz
convert: /ImageMagick
zip: //zip
unzip: /n/unzip
java: /java
path: /path
file_name: ilias.log
git_url: https://github.com/
git_branch_name: ilias';

		return array
			( array($json_string, "\\CaT\\InstILIAS\\Config\\Client")
			, array($json_string, "\\CaT\\InstILIAS\\Config\\DB")
			, array($json_string, "\\CaT\\InstILIAS\\Config\\GitBranch")
			, array($json_string, "\\CaT\\InstILIAS\\Config\\Language")
			, array($json_string, "\\CaT\\InstILIAS\\Config\\Server")
			, array($json_string, "\\CaT\\InstILIAS\\Config\\Setup")
			, array($json_string, "\\CaT\\InstILIAS\\Config\\Tools")
			);
	}

}

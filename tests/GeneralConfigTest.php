<?php

use \CaT\InstILIAS\Config\General;
use \CaT\InstILIAS\YamlParser;

class GeneralConfigTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new YamlParser();
		$this->yaml_string = "---
client:
    data_dir : /data_dir
    name : Ilias5
    password_encoder : bcrypt 
database:
    host: 127.0.0.1
    database: ilias
    user: user
    password: passwd
    engine: innodb
    encoding: utf8_general_ci 
language:
    default_lang: de
    to_install_langs:
        - en
        - de
server:
    http_path: http://localhost/
    absolute_path: /yourpath
    timezone: Europe/Berlin
setup:
    passwd: passwd
tools:
    convert: /convert
    zip: /zip
    unzip: /unzip
    java: /java
log:
    path: /path
    file_name: ilias.log
git_branch:
    git_url: https://github.com/ILIAS-eLearning/ILIAS.git
    git_branch_name: release_5-1
category:
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
            children: []
orgunit:
    orgunits:
        0:
            title: OrgEins
        1:
            title: OrgZwei
            children:
                10:
                    title: OrgZweiEins
                    children: []
                11:
                    title: OrgZweiZwei
                    children: []
        2:
            title: OrgDrei
            children: []
role:
    roles:
        0:
            title: Titel1
            description: Der darf alles sehen sonst nicht.
        1:
            title: Titel2
            description: Gruppe für alle
        2:
            title: Titel3
            description: Neue Menschen
ldap:
    name: ldap
    server: ldap://127.0.0.1:389
    basedn: cn=user,dc=dcdom,dc=local
    con_type: 1
    con_user_dn: cn=ldap,cn=user,dc=dcdom,dc=local
    con_user_pw: abcd
    sync_on_login: 1
    sync_per_cron: 0
    attr_name_user: sAMAccountName
    protocol_version: 3
    user_search_scope: 0
    register_role_name: User
table:
    tables:
        0:
            name: test
            mode: create
            columns:
                0:
                    name: spalte 1
                    type: text
                    db_null: 1
                    length: 150";
	}

	public function test_not_enough_params() {
		try {
			$config = new General();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function test_createIliasConfig() {
		$config = $this->parser->read_config($this->yaml_string, "\\CaT\\InstILIAS\\Config\\General");
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\General", $config);
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Client", $config->client());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\DB", $config->database());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Language", $config->language());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Server", $config->server());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Setup", $config->setup());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Tools", $config->tools());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Log", $config->log());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\GitBranch", $config->git_branch());
		$this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Categories", $config->category());
        $this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\OrgUnits", $config->orgunit());
        $this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Roles", $config->role());
        $this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\LDAP", $config->ldap());
        $this->assertInstanceOf("\\CaT\\InstILIAS\\Config\\Tables", $config->table());
	}
}
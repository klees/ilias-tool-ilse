<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for one client of ILIAS.
 *
 * @method string name()
 * @method string server()
 * @method string basedn()
 * @method string conType()
 * @method string conUserDn()
 * @method string conUserPw()
 * @method string synchType()
 * @method string userGroup()
 * @method string attrNameUser()
 * @method string protocolVersion()
 * @method string userSearchScope()
 * @method string registerRoleName()
 */
class LDAP extends Base {
	const SERVER_REGEX = "/^(ldap:\/\/)/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "name"	=> array("string", true)
			, "server"	=> array("string", true)
			, "basedn"	=> array("string", true)
			, "con_type"	=> array("int", true)
			, "con_user_dn"	=> array("string", true)
			, "con_user_pw"	=> array("string", true)
			, "sync_on_login"	=> array("int", true)
			, "sync_per_cron"	=> array("int", true)
			, "user_group"	=> array("string", true)
			, "attr_name_user"	=> array("string", true)
			, "protocol_version"	=> array("int", true)
			, "user_search_scope"	=> array("int", true)
			, "register_role_name"	=> array("string", true)
			);
	}

	protected static $con_types = array
			( 0
			, 1
			);

	protected static $sync_types = array
			( 0
			, 1
			);

	protected static $protocol_versions = array
			( 2
			, 3
			);

	protected static $user_search_scopes = array
			( 0
			, 1
			);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "con_type":
				return $this->checkContentValueInArray($value, self::$con_types);
			case "sync_on_login":
			case "sync_per_cron":
				return $this->checkContentValueInArray($value, self::$sync_types);
			case "protocol_version":
				return $this->checkContentValueInArray($value, self::$protocol_versions);
			case "server":
				return $this->checkContentPregmatch($value, self::SERVER_REGEX);
			case "user_search_scope":
				return $this->checkContentValueInArray($value, self::$user_search_scopes);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}

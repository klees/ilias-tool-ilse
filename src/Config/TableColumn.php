<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for a table column
 *
 * @method string name()
 * @method string type()
 * @method boolean dbNull()
 * @method string default()
 */
class TableColumn extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "name"		=> array("string", false)
			, "type"		=> array("string", false)
			, "db_null"		=> array("int", false)
			, "length"		=> array("int", true)
			, "default"		=> array("string", true)
			);
	}

	protected static $valid_types = array(
		"text",
		"integer",
		"float",
		"date",
		"time",
		"timestamp",
		"clob",
		"blob"
	);

	protected static $valid_db_null = array(
		0,
		1
	);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "type":
				return $this->checkContentValueInArray($value, self::$valid_types);
			case "db_null":
				return $this->checkContentValueInArray($value, self::$valid_db_null);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}

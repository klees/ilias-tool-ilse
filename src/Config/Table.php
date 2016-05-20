<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for a table column
 *
 * @method string name()
 * @method string mode()
 * @method array columns()
 * @method array primaryKeys()
 */
class Table extends Base {
	const NAME_REGEX = "/^[a-z][_a-z0-9]*/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "name"			=> array("string", false)
			, "mode"			=> array("string", false)
			, "columns"			=> array(array("\\CaT\\InstILIAS\\Config\\TableColumn"), false)
			, "primary_keys"	=> array(array("string"), true)
			);
	}

	protected static $valid_modes = array(
		"create",
		"addColumn",
		"dropColumn",
		"clear",
		"drop"
	);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "name":
				return $this->checkContentPregmatch($value, self::NAME_REGEX);
			case "mode":
				return $this->checkContentValueInArray($value, self::$valid_modes);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}

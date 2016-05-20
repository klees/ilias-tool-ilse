<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for a table column
 *
 * @method string name()
 * @method string mode()
 * @method array columns()
 */
class Table extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "name"	=> array("string", false)
			, "mode"	=> array("string", false)
			, "columns"	=> array(array("\\CaT\\InstILIAS\\Config\\TableColumn"), false)
			);
	}

	protected static $valid_modes = array(
		"create",
		"update",
		"clear",
		"drop"
	);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "mode":
				return $this->checkContentValueInArray($value, self::$valid_modes);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}

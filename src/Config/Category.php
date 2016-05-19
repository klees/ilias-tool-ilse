<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS database.
 *
 * @method string title()
 * @method array childs()
 */
class Category extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "title"	=> array("string", true)
			, "childs"	=> array(array("\\CaT\\InstILIAS\\Config\\Category"), true)
			);
	}
}
<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS database.
 *
 * @method string title()
 * @method string description()
 */
class Role extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "title"			=> array("string", false)
			, "description" 	=> array("string", false)
			);
	}
}
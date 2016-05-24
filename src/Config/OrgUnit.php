<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS database.
 *
 * @method string title()
 * @method array children()
 */
class OrgUnit extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "title"	=> array("string", false)
			, "children"	=> array(array("\\CaT\\InstILIAS\\Config\\OrgUnit"), true)
			);
	}
}
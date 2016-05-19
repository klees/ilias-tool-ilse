<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for Roles.
 *
 * @method array roles()
 */
class Roles extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "roles"			=> array(array("\\CaT\\InstILIAS\\Config\\Role"), true)
			);
	}
}
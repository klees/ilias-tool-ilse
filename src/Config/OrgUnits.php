<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for OrgUnits.
 *
 * @method array orgunits()
 */
class OrgUnits extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "orgunits" => array(array("\\CaT\\InstILIAS\\Config\\OrgUnit"), true)
			);
	}
}
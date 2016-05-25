<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for OrgUnits.
 *
 * @method array categories()
 */
class Categories extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "categories" => array(array("\\CaT\\InstILIAS\\Config\\Category"), false)
			);
	}
}
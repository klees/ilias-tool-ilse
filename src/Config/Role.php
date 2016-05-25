<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

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
			, "description" 	=> array("string", true)
			);
	}
}
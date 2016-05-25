<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS database.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
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
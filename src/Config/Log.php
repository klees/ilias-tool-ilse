<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for the log of ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string path()
 * @method string fileName()
 */
class Log extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "path"			=> array("string", false)
			, "file_name"		=> array("string", false)
			);
	}
}
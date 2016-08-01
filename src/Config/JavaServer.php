<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for JavaServer.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array active()
 */
class JavaServer extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "host" => array("string", false)
			, "port" => array("int", false)
			);
	}
}
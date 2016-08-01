<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for TinyMCE.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array active()
 */
class TinyMCE extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "active" => array("int", false)
			);
	}
}
<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for server values
 *
 * TODO: This name seems odd. It's about the master password, right?
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string passwd()
 */
class Setup extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("passwd"		=> array("string", false));
	}
}
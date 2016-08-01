<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for User Accounts.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array users()
 */
class Users extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "users" => array(array("\\CaT\\InstILIAS\\Config\\User"), false)
			);
	}
}
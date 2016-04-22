<?php
namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS database.
 *
 * @method \\CaT\\InstILIAS\\Config\\GitBranch mainRepo()
 * @method array logs()
 * @method string text()
 * @method array plugins()
 */
class Ilias extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "main_repo"	=> array("\\CaT\\InstILIAS\\Config\\GitBranch", false)
			, "logs"		=> array(array("string"), false)
			, "text"		=> array("string", false)
			, "plugins"		=> array(array("\\CaT\\InstILIAS\\Config\\GitBranch"), false)
			);
	}
}
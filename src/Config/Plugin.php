<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string componentType()
 * @method string componentName()
 * @method string pluginSlot()
 * @method \CaT\InstILIAS\Config\GitBranch git()
 */
class Plugin extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "component_type" => array("string", false)
			, "component_name" => array("string", false)
			, "plugin_slot" => array("string", false)
			, "name" => array("string", false)
			, "git" => array("\\CaT\\InstILIAS\\Config\\GitBranch", false)
			);
	}
}
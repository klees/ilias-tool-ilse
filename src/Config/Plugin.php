<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method \CaT\ilse\Config\GitBranch name()
 * @method \CaT\ilse\Config\GitBranch git()
 */
class Plugin extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("name" => array("string", false)
			, "git" => array("\\CaT\\ilse\\Config\\GitBranch", false)
			);
	}
}
<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array plugins()
 */
class Plugins extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("dir"		=> array("string", false)
			,"plugins" 	=> array(array("\\CaT\\Ilse\\Config\\Plugin"), false)
			);
	}

	/**
	 * Get urls of plugin-repos.
	 *
	 * @return string[]
	 */
	public function getRepoUrls() {
		return array_map(function($plugin) {
			return $plugin->git()->url();
		}, $this->plugins);
	}
}

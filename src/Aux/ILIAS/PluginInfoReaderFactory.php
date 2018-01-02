<?php
/* Copyright (c) 2016, 2017 Daniel Weise <daniel.weise@concepts-and-training.de> Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux\ILIAS;

use CaT\Ilse\Config\Server;
use CaT\Ilse\Aux\Filesystem;

/**
 * Provides a PluginInfoReader object depending on a given version number.
 */
class PluginInfoReaderFactory {
	/**
	 * @param	string	$version
	 * @return	PluginInfoReader
	 */
	public function getPluginInfoReader(
		$version,
		Server $server,
		Filesystem $filesystem
	) {
		assert('is_string($version)');

		if (substr($version, 0, 3) == "5.2") {
			return new PluginInfoReader52($server, $filesystem);
		}
		throw new \InvalidArgumentException("There is no plugin info reader for version '$version'");
	}
}
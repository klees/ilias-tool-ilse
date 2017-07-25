<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS;

use CaT\InstILIAS\interfaces;
/**
 * Serves common pathes
 */
class CommonPathes implements interfaces\CommonPathes
{
	/**
	 * @inheritdoc
	 */
	public function getCWD()
	{
		return getcwd();
	}

	/**
	 * @inheritdoc
	 */
	public function getHomeDir()
	{
		return getenv("HOME");
	}
}
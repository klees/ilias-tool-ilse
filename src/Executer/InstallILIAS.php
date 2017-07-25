<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Executer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use CaT\Ilse\App;

/**
 * Run the ILIAS installation process
 */
class InstallILIAS
{
	/**
	 * Constructor of the InstallILIAS class
	 *
	 */
	public function _construct()
	{

	}

	/**
	 * Start the installation process
	 * 
	 * @param string 		$config
	 * @param bool 			$interactive
	 */
	public function run($config, $interactive)
	{
		assert('is_string($config)');
		assert('is_bool($interactive)');

		
	}
}
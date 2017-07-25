<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use CaT\InstILIAS\App;

/**
 * Base class for all commands
 */
abstract class BaseCommand extends Command
{
	/**
	 * @var Symfony\Component\Process\Process
	 */
	protected $process;

	/**
	 * @var CaT\InstILIAS\interfaces\Path
	 */
	protected $path;

	public function __construct(\CaT\InstILIAS\interfaces\CommonPathes $path)
	{
		parent::__construct();
		$this->process = new Process("");
		$this->path = $path;
	}

	/**
	 * Get the configfile path for a given name
	 * 
	 * @param string 	$name
	 * 
	 */
	protected function getConfigPathByName($name)
	{
		assert('is_string($name)');
		return $this->path->getHomeDir() . "/" . App::II_P_GLOBAL_CONFIG . "/" . $name . "/" . App::II_F_CONFIG;
	}

}
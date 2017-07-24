<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Command;

use Symfony\Component\Console\Command\Command;

/**
 * Base class for all commands
 */
class BaseCommand extends Command
{

	public function __construct()
	{
		parent::__construct();
	}

}
<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS;

use Symfony\Component\Console\Application;
use CaT\InstILIAS\GitExecuter;


/**
 * Do the main initializing
 */
class App extends Application
{
	const II_P_GLOBAL_CONFIG 	= ".ilias-installer/config";
	const II_F_CONFIG			= "ii_config.yaml";
	const II_R_CONFIG			= "https://github.com/conceptsandtraining/ilias-configs.git";
	const II_R_BRANCH			= "master";

	/**
	 * @var string
	 */
	protected $home;

	public function __construct()
	{
		parent::__construct();

		$this->home = getenv("HOME");
		$this->initConfigRepo();
		$this->initCommands();
	}

	/**
	 * Initialize all commands, and add them to the app
	 */
	protected function initCommands()
	{
		// $this->add(new Command\StatusCommand($this->getGlobalConfig()));
		// $this->add(new Command\InfoCommand($this->getGlobalConfig()));
		// $this->add(new Command\CreateCommand($this->getGlobalConfig()));
		// $this->add(new Command\StopCommand($this->getGlobalConfig()));
		// $this->add(new Command\UpCommand($this->getGlobalConfig()));
		// $this->add(new Command\RemoveCommand($this->getGlobalConfig()));
		$this->add(new Command\InstallCommand());
	}

	/**
	 * Initialize the config repo in ~/.ilias-installer/config
	 */
	protected function initConfigRepo()
	{
		$ge = new GitExecuter();

		if(!is_dir($this->home . "/" . self::II_P_GLOBAL_CONFIG))
		{
			$ge->cloneGitTo(self::II_R_CONFIG,
							self::II_R_BRANCH,
							$this->home . "/" . self::II_P_GLOBAL_CONFIG
							);
		}
	}
}
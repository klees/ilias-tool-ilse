<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Implementation of the install command
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
class ReinstallCommand extends BaseCommand
{
	/**
	 * Configure the command with description and help text
	 */
	protected function configure()
	{
		$this
			->setName("reinstall")
			->setDescription("Reinstall the Ilias-Environment.")
			->addArgument("config_name", InputArgument::REQUIRED, "Name of the Ilias Config File.")
			->addOption("interactiv", "i", InputOption::VALUE_NONE, "Set i to start the setup in interatciv mode.");
			;
	}

	/**
	 * Exexutes the command
	 *
	 * @param InputInterface 	$in
	 * @param OutputInterface 	$out
	 */
	protected function execute(InputInterface $in, OutputInterface $out)
	{
		$args = ["config_name" => $in->getArgument("config_name"),
				 "interactiv" => $in->getOption("interactiv")
				];

		$this->delete($args);
		$this->start($args);
		$out->writeln("\t\t\t\tDone!");
	}

	/**
	 * Start the installation process
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function start(array $args)
	{
		$this->process->setWorkingDirectory($this->path->getCWD() . "/" . "src/bin");
		$this->process->setCommandLine("php install_ilias.php "
									 . $this->getConfigPathByName($args['config_name']) . " "
									 . "non_interactiv");//$args['interactiv']);
		$this->process->setTty(true);
		$this->process->run();
	}

	/**
	 * Delete an ILIAS-Environment
	 */
	protected function delete(array $args)
	{
		$ri = new deleteIlias($this->getConfigPathByName($args['config_name']));
		$ri->run();
	}
}
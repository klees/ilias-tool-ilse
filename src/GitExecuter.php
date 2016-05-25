<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS;
use Gitonomy\Git\Admin as Git;

/**
 * Implementation of the git interface.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class GitExecuter implements \CaT\InstILIAS\interfaces\Git {

	const URL_REG_EX = "/^(https:\/\/github\.com)/";

	/**
	 * @inhertidoc
	 */
	public function cloneGitTo($git_url, $git_branch, $installation_path) {
		assert('is_string($git_url)');
		assert('is_string($git_branch)');
		assert('is_string($installation_path)');

		if(!preg_match(self::URL_REG_EX, strtolower($git_url))) {
			throw new \LogicException("GitExecuter::cloneGitTo: No valid gitHub URL ".$git_url);
		}

		if(is_dir($installation_path)) {
			throw new \LogicException("GitExecuter::cloneGitTo: No valid destination ".$installation_path);
		}

		$args = array("--depth", "1", "--branch", $git_branch);
		Git::cloneRepository($installation_path, $git_url, $args);
	}
}
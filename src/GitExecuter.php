<?php
namespace CaT\InstILIAS;
use Gitonomy\Git\Admin;

class GitExecuter implements \CaT\InstILIAS\interfaces\Git {

	const URL_REG_EX = "/^(https:\/\/github\.com)/";

	public function cloneGitTo($git_url, $git_branch, $installation_path) {
		if(!preg_match(self::URL_REG_EX, strtolower($git_url))) {
			throw new \LogicException("GitExecuter::cloneGitTo: No valid gitHub URL ".$git_url);
		}

		if(is_dir($installation_path)) {
			throw new \LogicException("GitExecuter::cloneGitTo: No valid destination ".$installation_path);
		}

		$args = array("--depth", "1", "--branch", $git_branch);
		Admin::cloneRepository($installation_path, $git_url, $args);
	}
}
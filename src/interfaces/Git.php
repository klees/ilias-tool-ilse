<?php


namespace CaT\InstILIAS\interfaces;

/**
 * clones the repository an checkout the needed branch
 *
 * @param string $git_url 				Url to ILIAS repository
 * @param string $git_branch			Branch should be checked out
 * @param string $installation_path		Path clone repository to
 */
interface Git {
	public function cloneGitTo($git_url, $git_branch, $installation_path);
}

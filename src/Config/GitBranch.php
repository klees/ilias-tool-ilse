<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for the git repo and branch name to get ILIAS from.
 *
 * TODO: This most probably is not specific to ILIAS, so it could be named
 * GitBranch or something.
 *
 * @method string gitUrl()
 * @method string gitBranchName()
 */
class GitBranch extends Base {
	const URL_REG_EX = "/^(https:\/\/github\.com)/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "git_url"			=> array("string", false)
			, "git_branch_name"	=> array("string", false)
			);
	}

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "git_url":
				return $this->checkContentPregmatch($value, self::URL_REG_EX);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}
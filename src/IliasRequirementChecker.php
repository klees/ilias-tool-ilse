<?php
namespace CaT\InstILIAS;
/**
 * checks requirements for ILIAS installation
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class IliasRequirementChecker implements \CaT\InstILIAS\interfaces\RequirementChecker {
	/**
	 * @inheritdocs
	 */
	public function dataDirectoryExists($path) {
		assert('is_string($path)');

		return is_dir($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryPermissions($path) {
		assert('is_string($path)');

		return is_writable($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryEmpty($path, $client) {
		assert('is_string($path)');
		assert('is_string($client)');

		if($d = dir($path."/".$client)) {
			while($n = $d->read() ) {
				if($n == '.' OR $n == '..') {
					continue;
				}

				$d->close();
				return false;
			}
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function validPHPVersion($phpversion, $required) {
		assert('is_string($phpversion)');
		assert('is_string($required)');

		return $phpversion >= $required;
	}

	public function phpVersionILIASBranchCompatible($phpversion, $branch_name) {
		assert('is_string($phpversion)');
		assert('is_string($branch_name)');

		if($phpversion >= "7.0" && $branch_name != "trunk") {
			return false;
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function mysqliExist() {
		return class_exists("mysqli");
	}

	/**
	 * @inheritdocs
	 */
	public function databaseConnectable($host, $user, $passwd) {
		assert('is_string($host)');
		assert('is_string($user)');
		assert('is_string($passwd)');

		$mysqli = new \mysqli($host, $user, $passwd);

		if ($mysqli->connect_error) {
			return false;
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function logDirectoryExists($path) {
		assert('is_string($path)');

		return is_dir($path);
	}

	/**
	 * @inheritdocs
	 */
	public function logFileExists($path, $file_name) {
		assert('is_string($path)');
		assert('is_string($file_name)');

		return file_exists($path."/".$file_name);
	}
}
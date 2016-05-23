<?php
namespace CaT\InstILIAS\interfaces;

/**
 * Interface for requirement checker.
 *
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
interface RequirementChecker {
	/**
	 * 
	 *
	 * @return bool
	 */
	public function dataDirectoryExists($path);

	/**
	 * 
	 *
	 * @return bool
	 */
	public function dataDirectoryPermissions($path);

	/**
	 * 
	 * @return bool
	 */
	public function validPHPVersion($required);

	/**
	 * 
	 *
	 * @return bool
	 */
	public function validDatabaseType($host, $user, $passwd);

	/**
	 * 
	 *
	 * @return bool
	 */
	public function logDirectoryExists($path);

	/**
	 * 
	 *
	 * @return bool
	 */
	public function logFileExists($path, $file_name);
}

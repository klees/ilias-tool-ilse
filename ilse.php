#!/usr/bin/env php
<?php
/**
 * Main entry point for ilias-installer
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
require_once(__DIR__."/vendor/autoload.php");

// TODO: remove after development
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE);

$path = new \CaT\InstILIAS\CommonPathes();
$app = new \CaT\InstILIAS\App($path);
$app->run();
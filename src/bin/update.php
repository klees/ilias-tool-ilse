<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

$config_path = $argv[1];
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

$php = PHP_BINARY;

$cmds = array($php." ".__DIR__."/update_ilias.php $config_path"
			 ,$php." ".__DIR__."/update_configuration_ilias.php $config_path");

$die = false;
foreach ($cmds as $cmd) {
	while (@ob_end_flush());
	$proc = popen($cmd, 'r');

	while (!feof($proc)) {
		$output = fread($proc, 4096);
		echo $output;
		try{
			flush();
		} catch (Exception $e) {
			//empty because of no exception output is necessary
		}
	}
}
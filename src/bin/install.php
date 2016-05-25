<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

$config_path = $argv[1];

error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

$cmds = array("php ".__DIR__."/install_ilias.php $config_path"
			, "php ".__DIR__."/configurate_ilias.php $config_path");

foreach ($cmds as $cmd) {
	while (@ob_end_flush());
	$proc = popen($cmd, 'r');

	while (!feof($proc)) {
		echo fread($proc, 4096);
		try{
			flush();
		} catch (Exception $e) {
			//empty because of no exception output is necessary
		}
	}
}
die(0);
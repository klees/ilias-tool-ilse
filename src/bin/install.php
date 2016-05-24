<?php
$config_path = $argv[1];
//set error_reporting auf ein böses level
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

$cmds = array("php ".__DIR__."/install_ilias.php $config_path"
			, "php ".__DIR__."/configurate_ilias.php $config_path");

foreach ($cmds as $cmd) {
	while (@ob_end_flush()); // end all output buffers if any
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
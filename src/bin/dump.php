<?php

require __DIR__ . '/../../vendor/autoload.php';
use CaT\InstILIAS\MergeConfigs;

$i = 1;
$count = 0;
for($i = 1; $i < count($argv); $i++) {
	$ext = pathinfo($argv[$i], PATHINFO_EXTENSION);
	$ext = strtolower($ext);

	if($ext == "yml" || $ext == "yaml") {
		$config[] = $argv[$i];
		$count++;
	}
}

$merge = new MergeConfigs();
$ret = $merge->mergeConfigs($config);

echo $ret;
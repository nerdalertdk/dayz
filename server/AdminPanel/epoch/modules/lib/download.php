<?php

include_once('../../config.php');

$path_parts = pathinfo($_GET['file']);
$file = $path_parts['basename'];
$filepath = $patharma."\\@dayzcc_config\\".$serverinstance."\\".(isset($_GET['battleye']) ? "BattlEye\\" : "");

if (substr(strtolower($file), -strlen(".log")) === ".log" || substr(strtolower($file), -strlen(".log")) === ".rpt") {
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="'.$file.'"');
	readfile($filepath."\\".$file);
}

?>
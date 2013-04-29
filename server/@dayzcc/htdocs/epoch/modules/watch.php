<?php

error_reporting(0);

$ini = parse_ini_file($patharma."\\@dayzcc_config\\".$serverinstance."\\HiveExt.ini", true);
$timeoffset = 0;

if ($ini['Time']['Type'] == "Static") {
	$timeoffset = date('H') - $ini['Time']['Hour'];
} else {
	$timeoffset = $ini['Time']['Offset'];
}

echo '<embed src="flash/clock.swf" width="150px" height="150px" flashVars="timeOffset='.$timeoffset.'" quality="high" wmode="transparent" menu="false"></embed>';

?>
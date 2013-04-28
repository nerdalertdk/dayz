<?php

$res = mysql_query("
			SELECT object_data.* FROM `object_data` 
			WHERE object_data.Instance = '".$serverinstance."' 
			AND (Classname = 'dummy' 
			OR Classname = 'TentStorage' 
			OR Classname = 'Hedgehog_DZ' 
			OR Classname = 'Wire_cat1' 
			OR Classname = 'WoodGate_DZ' 
			OR Classname = 'Sandbag1_DZ'
			OR Classname = 'Fort_RazorWire'
			OR Classname = 'TrapBear'
			OR Classname = 'VaultStorageLocked')
			") or die(mysql_error());
$markers = markers_deployable($res, $serverworld);

?>
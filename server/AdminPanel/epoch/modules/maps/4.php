<?php

$res = mysql_query("
SELECT object_data.* FROM `object_data` 
WHERE object_data.Instance = '".$serverinstance."' 
AND object_data.Damage < '0.95' 
AND Classname != 'dummy' 
AND Classname != 'TentStorage' 
AND Classname != 'Hedgehog_DZ' 
AND Classname != 'Wire_cat1' 
AND Classname != 'WoodGate_DZ' 
AND Classname != 'Sandbag1_DZ'
AND Classname != 'Fort_RazorWire'
AND Classname != 'VaultStorageLocked'
AND Classname != 'TrapBear'") or die(mysql_error());
$markers = markers_vehicle($res, $serverworld);

?>
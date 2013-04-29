<?php

//$res1 = mysql_query("SELECT profile.name, survivor.* FROM `profile`, `survivor` AS `survivor` WHERE profile.unique_id = survivor.unique_id") or die(mysql_error());
$res1 = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND character_data.Alive = '1'") or die(mysql_error());
$res2 = mysql_query("SELECT object_data.* FROM `object_data` WHERE object_data.Instance = '".$serverinstance."' AND object_data.Damage < '0.95' AND Classname != 'dummy' AND Classname != 'TentStorage' AND Classname != 'Hedgehog_DZ' AND Classname != 'Wire_cat1' AND Classname != 'WoodGate_DZ' AND Classname != 'Sandbag1_DZ' AND Classname != 'Fort_RazorWire' AND Classname != 'VaultStorageLocked' AND Classname != 'TrapBear'") or die(mysql_error());
$res3 = mysql_query("SELECT object_data.* FROM `object_data` WHERE object_data.Instance = '".$serverinstance."' AND (Classname = 'dummy' OR Classname = 'TentStorage' OR Classname = 'Hedgehog_DZ' OR Classname = 'Wire_cat1' OR Classname = 'WoodGate_DZ' OR Classname = 'Sandbag1_DZ' OR Classname = 'Fort_RazorWire' OR Classname = 'TrapBear' OR Classname = 'VaultStorageLocked')") or die(mysql_error());

$markers = array();
$markers = array_merge($markers, markers_player($res1, $serverworld));
$markers = array_merge($markers, markers_vehicle($res2, $serverworld));
$markers = array_merge($markers, markers_deployable($res3, $serverworld));

?>
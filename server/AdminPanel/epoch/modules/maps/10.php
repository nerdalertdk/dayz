<?php

$res1 = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND character_data.Alive = '1' AND character_data.lastactiv > DATE_SUB(now(), INTERVAL 5 MINUTE)") or die(mysql_error());
$res2 = mysql_query("SELECT object_data.* FROM `object_data` WHERE object_data.Instance = '".$serverinstance."' AND object_data.Damage < '0.95' AND Classname != 'dummy' AND Classname != 'TentStorage' AND Classname != 'Hedgehog_DZ' AND Classname != 'Wire_cat1' AND Classname != 'WoodGate_DZ' AND Classname != 'Sandbag1_DZ' AND Classname != 'Fort_RazorWire' AND Classname != 'VaultStorageLocked' AND Classname != 'TrapBear' AND lastactive > DATE_SUB(now(), INTERVAL 5 MINUTE)") or die(mysql_error());

$markers = array();
$markers = array_merge($markers, markers_player($res1, $serverworld));
$markers = array_merge($markers, markers_vehicle($res2, $serverworld));

?>
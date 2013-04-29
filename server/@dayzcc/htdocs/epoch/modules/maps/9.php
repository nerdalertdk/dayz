<?php

$res = mysql_query("SELECT player_data.PlayerName, character_data.* 
			FROM `player_data`, `character_data`
			WHERE character_data.InstanceID = '".$serverinstance."'
			AND player_data.PlayerUID = character_data.PlayerUID
			AND character_data.Alive = '1'
			AND character_data.lastactiv > DATE_SUB(now(), INTERVAL 5 MINUTE)") or die(mysql_error());
$markers = markers_player($res, $serverworld);

?>
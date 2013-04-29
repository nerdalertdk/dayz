<?php

$markers = array();
$cmd = "players";	
$answer = rcon($serverip, $serverport, $rconpassword, $cmd);

if ($answer != "") {
	$k = strrpos($answer, "---");
	$l = strrpos($answer, "(");
	$out = substr($answer, $k + 4, $l - $k - 5);
	$parray = preg_split ('/$\R?^/m', $out);

	$players = array();
	for ($j = 0; $j < count($parray); $j++) { $players[] = ""; }
	for ($i = 0; $i < count($parray); $i++)
	{
		$m = 0;
		$players[$i][] = "";
		$pout = preg_replace('/\s+/', ' ', $parray[$i]);
		for ($j = 0; $j < strlen($pout); $j++) {
			$char = substr($pout, $j, 1);
			if ($m < 4) {
				if ($char != " ") { $players[$i][$m] .= $char; } else {$m++; }
			} else {
				$players[$i][$m] .= $char;
			}
		}
	}

	for ($i = 0; $i < count($players); $i++) {
		if (strlen($players[$i][4]) > 1) {
			$playername = trim(str_replace(" (Lobby)", "", $players[$i][4]));
			$ip = $players[$i][1];
			$ping = $players[$i][2];
			
			//$res = mysql_query("SELECT profile.name, survivor.* FROM `profile`, `survivor` AS `survivor` WHERE profile.unique_id = survivor.unique_id AND profile.name LIKE '%".(mysql_real_escape_string($playername))."%' ORDER BY survivor.last_updated DESC LIMIT 1") or die(mysql_error());
			$res = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND player_data.PlayerName LIKE '%".(mysql_real_escape_string($playername))."%' ORDER BY character_data.lastactiv DESC LIMIT 1") or die(mysql_error());
			$markers = array_merge($markers, markers_player($res, $serverworld));
		}
	}
}
else
{
	$markers["error"] = '<div id="page-error" style="margin: 0 0 15px 20px;"><h2>BattlEye did not respond.</h2></div>';
}

?>
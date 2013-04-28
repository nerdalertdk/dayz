<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "feed") !== false))
{
	// Kill and Hits Feed written by SilverShot and Crosire
	$pagetitle = "Kill feed";
	
	$tablerows = '';
	$tableheader = '<tr>
		<th class="product-table-header" width="25%"><a>Player</a></th>
		<th class="product-table-header" width="75%"><a>Amount</a></th>
		</tr>';
		
	$res = mysql_query("
	SELECT feed.*
	FROM dayz_misc.killfeed AS feed
	WHERE feed.type = 'pkill'
	AND feed.instance = 11
	ORDER BY feed.killtime DESC
	LIMIT 50
	")or die (mysql_error());
	
	$query = "
		SELECT player_data.PlayerName, character_data.*
			FROM `player_data`, `character_data`
		WHERE character_data.InstanceID = '".$serverinstance."' 
			AND player_data.PlayerUID = character_data.PlayerUID";
			
	$res = mysql_query($query) or die(mysql_error("fejl"));	
	//KillsZ
	if (mysql_num_rows($res) > 0) {
						//KillsZ
						$KillsZ = array (); // initialize 
						//KillsB
						$KillsB = array (); // initialize
						//KillsH
						$KillsH = array (); // initialize
						//Alive
						$Alive = array (); // initialize
						//HeadshotsZ
						$HeadshotsZ = array (); // initialize
						while ($row=mysql_fetch_array($res))
						{
							//KillsZ
							$KillsZ[] = $row['KillsZ']; // sum 
							$totalKillsz = $row['KillsZ'];
							//KillsB
							$KillsB[] = $row['KillsB']; // sum 
							$totalKillsB = $row['KillsB'];
							//KillsH
							$KillsH[] = $row['KillsH']; // sum 
							$totalKillsH = $row['KillsH'];
							//HeadshotsZ
							$HeadshotsZ[] = $row['HeadshotsZ']; // sum 
							$totalHeadshotsZ = $row['HeadshotsZ'];

						}
						//Alive
						$Alive = mysql_query("SELECT count(*) FROM character_data WHERE Alive=1");
						$totalAlive = mysql_fetch_array($Alive);
						//MOST kills
						$mostkills = mysql_query("SELECT count(KillsB) FROM character_data WHERE Alive=1 GROUP BY PlayerUID ORDER BY PlayerUID DESC");
						$kills = mysql_fetch_array($mostkills);
						//A
						$Alive = mysql_query("SELECT count(*) FROM character_data WHERE Alive=1");
						$totalAlive = mysql_fetch_array($Alive);
						//KillsZ
						$KillsZ = array_sum($KillsZ);
						//KillsB
						$KillsB = array_sum($KillsB);
						//KillsH
						$KillsH = array_sum($KillsH);
						//Alive
						//$Alive = array_sum($Alive);
						//HeadshotsZ
						$HeadshotsZ = array_sum($HeadshotsZ);
						
						$totalplayers= mysql_query("SELECT count(*) FROM character_data");
						$num_totalplayers = mysql_fetch_array($totalplayers);
						
						$playerdeaths = mysql_query("SELECT count(*) FROM character_data WHERE Alive=0");
						//$num_deaths = mysql_num_rows($playerdeaths);
						$num_deaths = mysql_fetch_array($playerdeaths);
						
						$alivebandits = mysql_query("SELECT count(*) FROM character_data WHERE character_data.Humanity <= 0 AND character_data.Alive = 1");
						$num_alivebandits = mysql_fetch_array($alivebandits);
						
						$totalVehicles = mysql_query("SELECT count(*)  FROM `object_data` WHERE object_data.Instance = '".$serverinstance."' AND object_data.Damage < '0.95' AND Classname != 'dummy' AND Classname != 'TentStorage' AND Classname != 'Hedgehog_DZ' AND Classname != 'Wire_cat1' AND Classname != 'WoodGate_DZ' AND Classname != 'Sandbag1_DZ'AND Classname != 'Fort_RazorWire'AND Classname != 'TrapBear'AND Classname != 'VaultStorageLocked'");
						$num_totalVehicles = mysql_fetch_array($totalVehicles);
						
						//$Played24h = mysql_query("SELECT count(*) from survivor WHERE last_updated > now() - INTERVAL 1 DAY");
						$Played24h = mysql_query("select count(*) from (SELECT count(*) from character_data WHERE lastactiv > now() - INTERVAL 1 DAY group by PlayerUID) uniqueplayers");
						$num_Played24h = mysql_fetch_array($Played24h);

	}

	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Total Players</a></td>
			<td align="center" class="gear_preview">'.$num_totalplayers[0].'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Players in Last 24h</a></td>
			<td align="center" class="gear_preview">'.$num_Played24h[0].'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Alive Characters</a></td>
			<td align="center" class="gear_preview">'.$totalAlive[0].'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Player Deaths</a></td>
			<td align="center" class="gear_preview">'.$num_deaths[0].'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Zombies Killed</a></td>
			<td align="center" class="gear_preview">'.$KillsZ.'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Zombies Headshots</a></td>
			<td align="center" class="gear_preview">'.$HeadshotsZ.'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Murders</a></td>
			<td align="center" class="gear_preview">'.$KillsH.'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Bandits Alive</a></td>
			<td align="center" class="gear_preview">'.$num_alivebandits[0].'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Bandits Killed</a></td>
			<td align="center" class="gear_preview">'.$KillsB.'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Vehicles</a></td>
			<td align="center" class="gear_preview">'.$num_totalVehicles[0].'</td>
			</tr>';
	$tablerows .= '<tr>
			<td align="center" class="gear_preview"><a>Moste Killed</a></td>
			<td align="center" class="gear_preview">'.print_r($kills).'</td>
			</tr>';
			
	echo '<div id="page-heading"><title>'.$pagetitle.' - '.$sitename.'</title><h1>'.$pagetitle.'</h1></div>';
?>

	<table id="content-table" border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th rowspan="3"><img src="images/forms/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
			<th class="corner-topleft"></th>
			<td class="border-top">&nbsp;</td>
			<th class="corner-topright"></th>
			<th rowspan="3"><img src="images/forms/side_shadowright.jpg" width="20" height="300" alt="" /></th>
		</tr>
		<tr>
			<td class="border-left"></td>
			<td>
				<div id="content-table-inner">		
					<div id="table-content">
						<br />
						<table id="product-table" border="0" width="100%" cellpadding="0" cellspacing="0">
							<?php echo $tableheader; ?>
							<?php echo $tablerows; ?>			
						</table>
					</div>
					<div class="clear"></div>
				</div>
			</td>
			<td class="border-right"></td>
		</tr>
		<tr>
			<th class="corner-bottomleft"></th>
			<td class="border-bottom">&nbsp;</td>
			<th class="corner-bottomright"></th>
		</tr>
	</table>
	<div class="clear">&nbsp;</div>

<?php
}
else
{
	header('Location: index.php');
}

?>
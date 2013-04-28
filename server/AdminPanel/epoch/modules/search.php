<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "table") !== false))
{
	if (isset($_POST['search'])) {
		$pagetitle = "Search for ".$_POST['search'];
	} else if (isset($_GET['search'])) {
		$pagetitle = "Search for ".$_GET['search'];
	} else {
		$pagetitle = "New search";
	}
	
	?>

	<div id="page-heading">
		<title><?php echo $pagetitle." - ".$sitename; ?></title>
		<h1><?php echo $pagetitle; ?></h1>
	</div>

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
					<?php
						include('modules/searchbar.php');
					
						if (isset($_POST['type'])) {
							$type = $_POST['type'];
						} else if (isset($_GET['type'])) {
							$type = $_GET['type'];
						} else {
							$type = 'player';
						}
						
						if (isset($_POST['search'])) {
							$search = $_POST['search'];
						} else if (isset($_GET['search'])) {
							$search = $_GET['search'];
						} else {
							$search = '';
						}
						
						if ($search != '')
						{
							require_once('modules/tables/rows.php');

							$search = substr($search, 0, 64);
							$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
							$search = trim(preg_replace("/\s(\S{1,2})\s/", " ", preg_replace("[ +]", "  "," $search ")));
							$search = preg_replace("[ +]", " ", $search);
							$logic = "OR";

							echo '<br /><table id="product-table" border="0" width="100%" cellpadding="0" cellspacing="0">';

							switch ($type) {
								case 'player':
									$tableheader = header_player(1, 0);
									echo $tableheader;
									$res = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND player_data.PlayerName LIKE '%". str_replace(" ", "%' OR player_data.PlayerName LIKE '%", $search). "%' ORDER BY character_data.lastactiv DESC") or die(mysql_error());
									$tablerows = "";
									while ($row = mysql_fetch_array($res)) { $tablerows .= row_player($row, $serverworld); }
									echo $tablerows;
									break;
								case 'playerinv':
									$tableheader = header_player(1, 0);
									echo $tableheader;
									$res = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND character_data.Inventory LIKE '%". str_replace(" ", "%' OR `Backpack` LIKE '%", $search). "%'") or die(mysql_error());
									$tablerows = "";
									while ($row = mysql_fetch_array($res)) { $tablerows .= row_player($row, $serverworld); }
									echo $tablerows;
									break;
								case 'vehicle':
									$tableheader = header_vehicle(4, "", 0);
									echo $tableheader;
									$res = mysql_query("SELECT world_vehicle.vehicle_id, vehicle.class_name, instance_vehicle.* FROM `world_vehicle`, `vehicle`, `instance_vehicle` WHERE vehicle.id = world_vehicle.vehicle_id AND instance_vehicle.world_vehicle_id = world_vehicle.id AND vehicle.class_name LIKE '%". str_replace(" ", "%' OR vehicle.class_name LIKE '%", $search). "%'") or die(mysql_error());
									$tablerows = "";
									while ($row = mysql_fetch_array($res)) { $tablerows .= row_vehicle($row, "", $serverworld); }
									echo $tablerows;
									break;
								case 'vehicleinv':
									$tableheader = header_vehicle(4, "", 0);
									echo $tableheader;
									$res = mysql_query("SELECT world_vehicle.vehicle_id, vehicle.class_name, instance_vehicle.* FROM `world_vehicle`, `vehicle`, `instance_vehicle` WHERE vehicle.id = world_vehicle.vehicle_id AND instance_vehicle.world_vehicle_id = world_vehicle.id AND instance_vehicle.inventory LIKE '%". str_replace(" ", "%' OR instance_vehicle.inventory LIKE '%", $search). "%'") or die(mysql_error());
									$tablerows = "";
									while ($row = mysql_fetch_array($res)) { $tablerows .= row_vehicle($row, "", $serverworld); }
									echo $tablerows;
									break;
								case 'tent':
									$tableheader = header_deployable(5, "", 0);
									echo $tableheader;
									$res = mysql_query("SELECT deployable.class_name, instance_deployable.* FROM `deployable`, `instance_deployable` WHERE deployable.id = instance_deployable.deployable_id AND deployable.class_name = 'TentStorage' AND instance_deployable.inventory LIKE '%". str_replace(" ", "%' OR instance_deployable.inventory LIKE '%", $search). "%'") or die(mysql_error());
									$tablerows = "";
									while ($row = mysql_fetch_array($res)) { $tablerows .= row_deployable($row, "", $serverworld); }
									echo $tablerows;
									break;
								default:
									$tableheader = header_player(1, 0);
									echo $tableheader;
									$res = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND player_data.PlayerName LIKE '%". str_replace(" ", "%' OR player_data.PlayerName LIKE '%", $search). "%' ORDER BY character_data.lastactiv DESC") or die(mysql_error());
									$tablerows = "";
									while ($row = mysql_fetch_array($res)) { $tablerows .= row_player($row, $serverworld); }
									echo $tablerows;
							};
								
							echo '</table>';
						}
					?>
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

<?php
}
else
{
	header('Location: index.php');
}

?>
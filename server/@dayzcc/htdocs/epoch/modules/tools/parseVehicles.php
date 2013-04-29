<?php

if (isset($_SESSION['user_id']))
{

	/** 3D Editor Mission File Parser
	  *
	  * This will take your mission file and add any new vehicles to your vehicle table and all spawn points
	  * to your world_vehicle table
	  * Written by: Planek and Crosire
	  *
	  **/

	if (file_exists("vehicles.sqf")) {
		$missionfile = file_get_contents("vehicles.sqf");
		$rows = explode("\n", $missionfile);
		array_shift($rows);
		$vehiclecount = 0;

		?>

		<div id="page-heading">
			<h1>Create vehicles</h1>
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
				<div id="content-table-inner" style="height: 300px; overflow-y: scroll">
					<table border="1" width="100%" cellpadding="0" cellspacing="0" id="vehicle-table">
					<tr>
						<th width="50%">Class Name</th>
						<th width="45%">Position</th>
					</tr>

					<?php

					$resultIDQuery = mysql_query("SELECT `id` FROM `world_vehicle`");
					while ($row = mysql_fetch_array($resultIDQuery, MYSQL_NUM)) { $userDataIDs[] = $row[0]; }
					$id = max($userDataIDs) + 1;
						
					for ($i = 0; $i < count($rows); $i++)
					{
						$direction = 0;
						$exists = false;
						
						if (strpos($rows[$i], '_this = createVehicle [') !== false)
						{
							// Get vehicle values
							
							$strings = explode("\"", $rows[$i]);
							$firstOpenBracket = strpos($rows[$i], "[");
							$secondOpenBracket = strpos($rows[$i], "[", $firstOpenBracket + strlen("]"));
							$firstCloseBracket = strpos($rows[$i], "]");
						
							if (strpos($rows[$i + 2], '_this setDir') !== false)
							{
								$firstSpace = strpos($rows[$i + 2], " ");
								$secondSpace = strpos($rows[$i + 2], " ", $firstSpace + strlen(" "));
								$thirdSpace = strpos($rows[$i + 2], " ", $secondSpace + strlen(" "));
								$forthSpace = strpos($rows[$i + 2], " ", $thirdSpace + strlen(" "));
								$period = strpos($rows[$i + 2], ".");
								$direction = substr($rows[$i + 2], $forthSpace + 1, $period - $forthSpace - 1);
							}
						
							$pos = "[$direction,".substr($rows[$i], $secondOpenBracket, $firstCloseBracket - $secondOpenBracket + 1)."]";
							$pos = str_replace(array(' '), '', $pos);
							$newPos = explode(",", $pos);
							
							if (count($newPos) == 3)
							{
								$pos = "[$direction,".substr($rows[$i], $secondOpenBracket, $firstCloseBracket - $secondOpenBracket).",0]]";
								$pos = str_replace(array(' '), '', $pos);
							}

							// Insert to database

							$resultCheckQuery = mysql_query("SELECT * FROM `instance_vehicle`");
							while ($row = mysql_fetch_array($resultCheckQuery)) { if ($row['worldspace'] == $pos) { $exists = true; } }

							if (!$exists)
							{
								$error = false;
								$matchFound = false;
								$resultClassNameQuery = mysql_query("SELECT * FROM `vehicle`;");
								while ($row = mysql_fetch_assoc($resultClassNameQuery)) { if ($strings[1] == $row['class_name']) { $matchFound = true; } }

								if (!$matchFound)
								{
									//echo "Inserting new Class Name";
									if (!mysql_query("INSERT INTO `vehicle` (`class_name`, `damage_min`, `damage_max`, `fuel_min`, `fuel_max`, `limit_min`, `limit_max`, `parts`) VALUES ('$strings[1]', '0.100', '0.700', '0.200', '0.800', '0', '100', 'motor')")) { echo mysql_error(); }
								}

								$time = date("y-m-d H:i:s", time());
								
								$resultIDQuery = mysql_query("SELECT * FROM `vehicle` WHERE `class_name` = '$strings[1]'");
								$userDataIDQuery = mysql_fetch_assoc($resultIDQuery);
								$vehicle_id = $userDataIDQuery['id'];
								$resultWorldQuery = mysql_query("SELECT `world_id` FROM `instance` WHERE `id` = '$serverinstance'");
								$userDataWorldQuery = mysql_fetch_assoc($resultWorldQuery);
								$world_id = $userDataWorldQuery['world_id'];
								
								if (!mysql_query("INSERT INTO `world_vehicle` (`id`, `vehicle_id`, `world_id`, `worldspace`, `chance`) VALUES ('$id', '$vehicle_id', '$world_id', '$pos', '0')")) { echo mysql_error()."<br />"; $error = true; }
								if (!mysql_query("INSERT INTO `instance_vehicle` (`world_vehicle_id`, `instance_id`, `worldspace`, `inventory`, `parts`, `fuel`, `damage`, `last_updated`, `created`) VALUES ('$id', '$serverinstance', '$pos', '[]', '[]', '1', '0', '$time', '$time')")) { echo mysql_error()."<br />"; $error = true; }

								$resultVIDQuery = mysql_query("SELECT `id` FROM `instance_vehicle` WHERE `worldspace` = '$pos' AND `world_vehicle_id` = '$id' AND `instance_id` = '$serverinstance'");
								$userDataVIDQuery = mysql_fetch_assoc($resultVIDQuery);
								$vid = $userDataVIDQuery['id'];
								
								$vehiclecount++;
								$id++;

								if (!$error) { ?>
								
								<tr>
									<td align="center" style="vertical-align:middle;"><a href="index.php?view=info&show=4&id=<?php echo $vid; ?>"><?php echo $strings[1] ?></a></td>
									<td align="center" style="vertical-align:middle;"><a href="index.php?view=info&show=4&id=<?php echo $vid; ?>"><?php echo $pos ?></a></td>
								</tr>

							<?php }
							}
						}
					}
					?>

					</table>

					<br />
					<br />

					<strong><?php echo $vehiclecount; ?></strong> new vehicles added!
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
		echo "<div id='page-heading'><h2>Mission file not found.</h2></div>";
	}
}
else
{
	header('Location: index.php');
}

?>
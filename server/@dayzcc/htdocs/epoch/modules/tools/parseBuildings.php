<?php

if (isset($_SESSION['user_id']))
{
	/** 3D Editor Mission File Parser
	  *
	  * This will take your mission file and add any new buildings to your building table
	  * Written by: Planek and Crosire
	  *
	  **/

	if (file_exists("buildings.sqf")) {
		$missionfile = file_get_contents("buildings.sqf");
		$rows = explode("\n", $missionfile); array_shift($rows);
		$buildingcount = 0;
		
		?>

		<div id="page-heading">
			<h1>Create buildings</h1>
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
					<table border="1" width="100%" cellpadding="0" cellspacing="0" id="building-table">
					<tr>
						<th width="50%">Class Name</th>
						<th width="45%">Position</th>
					</tr>

					<?php
						
					for ($i = 0; $i < count($rows); $i++)
					{
						$direction = 0;
						$exists = false;
						
						if (strpos($rows[$i], '_this = createVehicle [') !== false)
						{
							// Get building values
							
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
							$newPos = explode(",",$pos);
							
							if (count($newPos) == 3)
							{
								$pos = "[$direction,".substr($rows[$i], $secondOpenBracket, $firstCloseBracket - $secondOpenBracket).",0]]";
								$pos = str_replace(array(' '), '', $pos);
							}

							// Insert to database
							
							$resultCheckQuery = mysql_query("SELECT * FROM `instance_building`");
							while ($row = mysql_fetch_array($resultCheckQuery)) { if ($row['worldspace'] == $pos) { $exists = true; } }
							
							if (!$exists)
							{
								$error = false;
								
								$matchFound = false;
								$resultClassNameQuery = mysql_query("SELECT * FROM `building`");
								while ($row = mysql_fetch_array($resultClassNameQuery, MYSQL_ASSOC)) { if ($strings[1] == $row['class_name']) { $matchFound = true; } }

								if (!$matchFound)
								{
									//echo "Inserting new Class Name";
									if (!mysql_query("INSERT INTO `building` (`class_name`) VALUES ('$strings[1]')")) { echo mysql_error(); }
								}

								$time = date("y-m-d H:i:s", time());

								$resultIDQuery = mysql_query("SELECT * FROM `building` WHERE `class_name` = '$strings[1]';");
								$userDataIDQuery = mysql_fetch_array($resultIDQuery, MYSQL_ASSOC);
								$building_id = $userDataIDQuery['id'];
								
								if (!mysql_query("INSERT INTO `instance_building` (`building_id`, `instance_id`, `worldspace`, `created`) VALUES ('$building_id', '$serverinstance', '$pos', '$time')")) { echo mysql_error()."<br />"; $error = true; }
								
								$buildingcount++;
								
							if (!$error) { ?>
							
							<tr>
								<td align="center" style="vertical-align:middle;"><?php echo $strings[1] ?></td>
								<td align="center" style="vertical-align:middle;"><?php echo $pos ?></td>
							</tr>
							
							<?php }
							}
						}
					}
					?>

					</table>

					<br />
					<br />

					<strong><?php echo $buildingcount; ?></strong> new buildings added!
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
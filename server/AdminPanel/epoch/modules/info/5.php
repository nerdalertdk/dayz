<?php

if (isset($_GET["delete"])) {
	mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELETE DEPLOYABLE {$_GET["id"]}', '{$_SESSION['login']}', NOW())");
	mysql_query("DELETE FROM `object_data` WHERE `ObjectUID` = '{$_GET["id"]}'") or die(mysql_error());
	echo '<script type="text/javascript">window.location = "index.php?view=table&show=5"</script>';
}

//$res = mysql_query("SELECT deployable.class_name, instance_deployable.* FROM `deployable`, `instance_deployable` WHERE deployable.id = instance_deployable.deployable_id AND instance_deployable.id = '".$_GET["id"]."' LIMIT 1") or die(mysql_error());
$res = mysql_query("
SELECT object_data.* FROM `object_data` 
WHERE object_data.Instance = '".$serverinstance."' 
AND ObjectID = '".$_GET["id"]."' LIMIT 1") or die(mysql_error());
$row = mysql_fetch_assoc($res);

$Worldspace = str_replace("[", "", $row['Worldspace']);
$Worldspace = str_replace("]", "", $Worldspace);
$Worldspace = explode(",", $Worldspace);
$Deployable = $row['Inventory'];
$Deployable = json_decode($Deployable);

$xml = file_get_contents('items.xml', true);
require_once('modules/lib/class.xml2array.php');
$items_xml = XML2Array::createArray($xml);
$xml = file_get_contents('vehicles.xml', true);
require_once('modules/lib/class.xml2array.php');
$vehicles_xml = XML2Array::createArray($xml);

?>

<div id="page-heading">
	<title><?php echo $row['Classname']." - ".$sitename; ?></title>
	<h1><?php echo $row['Classname']." - ".$row['ObjectUID']." - Last save: ".$row['Datestamp']." - Position: ".$row['Worldspace']; ?></h1>
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
				<div id="table-content">
					<div id="gear_vehicle">
						<div class="gear_info">
							<div class="playermodel"><img src="images/vehicles/<?php echo strtolower($row['Classname']); ?>.png" style="width: 100%;" /></div>
							<div id="gps" style="margin-left: 46px; margin-top: 54px">
								<div class="gpstext" style="font-size: 22px; width: 60px; text-align: left; margin-left: 47px; margin-top: 13px">
									<?php echo round($Worldspace[0] / 100); ?>
								</div>
								<div class="gpstext" style="font-size: 22px; width: 60px; text-align: left; margin-left: 47px; margin-top: 34px">
									<?php echo round($Worldspace[3] / 100); ?>
								</div>
								<div class="gpstext" style="width: 120px; margin-left: 13px; margin-top: 61px">
									<?php
										require_once("modules/calc.php");
										echo sprintf("%03d", round(world_x($Worldspace[1], $serverworld))).sprintf("%03d", round(world_y($Worldspace[2], $serverworld)));
									?>
								</div>							
							</div>
							<?php
								if ($row['CharacterID'] != "0") {
									$resowner = mysql_query("
										SELECT player_data.PlayerName, character_data.* 
										FROM `player_data`, `character_data` 
										WHERE player_data.PlayerUID = character_data.PlayerUID
										AND character_data.PlayerUID = '".$row['CharacterID']."' LIMIT 1");
									$owner = '';
									$ownerid = '';
									$owneruid = '';
									
									while ($rowowner = mysql_fetch_array($resowner)) {
										//print_r($rowowner);
										$owner = $rowowner['PlayerName'];
										$ownerid = $rowowner['CharacterID'];
										$owneruid = $rowowner['PlayerUID'];
									}
									
									?>
									<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -95px">
										<?php echo 'Owner:&nbsp;<a href="index.php?view=info&show=1&uid='.$owneruid.'&id='.$ownerid.'" style="color: blue">'.$owner.'</a>'; ?>
									</div>
									<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -75px">
										<?php echo 'Owner ID:&nbsp;'.$row['CharacterID']; ?>
									</div>
									<?php
								}
							?>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -55px">
								<a href="javascript:if (confirm('Are you sure you want to delete this deployable?')) { window.location = 'index.php?view=info&show=5&id=<?php echo $_GET["id"]; ?>&delete'; }" style="color: blue">Delete</a>
							</div>
						</div>
						<!-- Deployable -->
						<div class="vehicle_gear">	
							<div id="vehicle_inventory">	
							<?php
								$maxmagazines = 24;
								$maxweaps = 3;
								$maxbacks = 0;
								$freeslots = 0;
								$freeweaps = 0;
								$freebacks = 0;
								$DeployableName = $row['Classname'];
								
								$class = strtolower($row['Classname']);
								if (array_key_exists('s'.$class, $vehicles_xml['vehicles'])) {
									$maxmagazines = $vehicles_xml['vehicles']['s'.$class]['transportmaxmagazines'];
									$maxweaps = $vehicles_xml['vehicles']['s'.$class]['transportmaxweapons'];
									$maxbacks = $vehicles_xml['vehicles']['s'.$class]['transportmaxbackpacks'];
									$DeployableName = $vehicles_xml['vehicles']['s'.$class]['Name'];
								}
								
								if (count($Deployable) > 0) {
									$bpweaponscount = count($Deployable[0][0]);
									$bpweapons = array();
									for ($m = 0; $m < $bpweaponscount; $m++) {
										for ($mi = 0; $mi < $Deployable[0][1][$m]; $mi++) { $bpweapons[] = $Deployable[0][0][$m]; }
									}							

									
									$bpitemscount = count($Deployable[1][0]);
									$bpitems = array();
									for ($m = 0; $m < $bpitemscount; $m++) {
										for ($mi = 0; $mi < $Deployable[1][1][$m]; $mi++) { $bpitems[] = $Deployable[1][0][$m]; }
									}
									
									$bpackscount = count($Deployable[2][0]);
									$bpacks = array();
									for ($m = 0; $m < $bpackscount; $m++) {
										for ($mi = 0; $mi < $Deployable[2][1][$m]; $mi++) { $bpacks[] = $Deployable[2][0][$m]; }
									}
									
									$Deployable = (array_merge($bpweapons, $bpacks, $bpitems));
									$freebacks = $maxbacks;
									$Deployableslots = 0;
									$Deployableitem = array();
									$bpweapons = array();
									
									for ($i = 0; $i < count($Deployable); $i++) {
										if(array_key_exists('s'.$Deployable[$i], $items_xml['items'])) {
											switch($items_xml['items']['s'.$Deployable[$i]]['Type']) {
												case 'binocular':
													$Deployableitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													break;
												case 'rifle':
													$bpweapons[] = array('image' => '<img style="max-width: 84px; max-height: 84px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													break;
												case 'pistol':
													$bpweapons[] = array('image' => '<img style="max-width: 84px; max-height: 84px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													break;
												case 'backpack':
													$bpweapons[] = array('image' => '<img style="max-width: 84px; max-height: 84px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													$freebacks = $freebacks - 1;
													break;
												case 'heavyammo':
													$Deployableitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													break;
												case 'smallammo':
													$Deployableitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													break;
												case 'item':
													$Deployableitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Deployable[$i].'.png" title="'.$Deployable[$i].'" alt="'.$Deployable[$i].'"/>', 'slots' => $items_xml['items']['s'.$Deployable[$i]]['Slots']);
													break;
												default:
													$s = '';
											}
										}
									}	
									
									$weapons = count($bpweapons);
									$magazines = $maxmagazines;
									$freeslots = $magazines;
									$freeweaps = $maxweaps;
									$jx = 1; $jy = 0; $jk = 0; $jl = 0;
									$numlines = 0;
									for ($j = 0; $j < $weapons; $j++) {
										if ($jk > 3) { $jk = 0; $jl++; }
										echo '<div class="gear_slot" style="margin-left: '.($jx+(86*$jk)).'px; margin-top: '.($jy+(86*$jl)).'px; width: 84px; height: 84px;">'.$bpweapons[$j]['image'].'</div>';
										$freeweaps = $freeweaps - 1;
										$jk++;
									}
									
									if ($jl > 0) {
										$numlines = $jl + 1;
									} elseif ($jl == 0) {
										if ($weapons > 0) { $numlines++; }
									}

									$jx = 1; $jy = (86 * $numlines); $jk = 0; $jl = 0;
									for ($j = 0; $j < $magazines; $j++) {
										if ($jk > 6){ $jk = 0; $jl++; }
										if ($j < count($Deployableitem)) {
											echo '<div class="gear_slot" style="margin-left: '.($jx+(49*$jk)).'px; margin-top: '.($jy+(49*$jl)).'px; width: 47px; height: 47px;">'.$Deployableitem[$j]['image'].'</div>';
											$freeslots = $freeslots - 1;
										} else {
											echo '<div class="gear_slot" style="margin-left: '.($jx+(49*$jk)).'px; margin-top: '.($jy+(49*$jl)).'px; width: 47px; height: 47px;"></div>';
										}								
										$jk++;
									}
								}						
							?>
							</div>
							<div class="backpackname">
								<?php echo 'Magazines:&nbsp;'.$freeslots.'&nbsp;/&nbsp;'.$maxmagazines.'&nbsp;Weapons:&nbsp;'.$freeweaps.'&nbsp;/&nbsp;'.$maxweaps.'&nbsp;Backs:&nbsp;'.$freebacks.'&nbsp;/&nbsp;'.$maxbacks.'&nbsp;'; ?>
							</div>
						</div>
						<!-- Deployable -->
					</div>
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
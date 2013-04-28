<?php

// Thanks to SilverShot and ChemicalBliss for their initial idea about the repair, refuel and delete features
if (isset($_GET["delete"])) {
	mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELETE VEHICLE {$_GET["id"]}', '{$_SESSION['login']}', NOW())");
	mysql_query("DELETE FROM `object_data` WHERE `ObjectID` = '{$_GET["id"]}'") or die(mysql_error());
	echo '<script type="text/javascript">window.location = "index.php?view=table&show=4"</script>';
} else {
	if (isset($_GET["repair"])) {
		mysql_query("UPDATE `object_data` SET Hitpoints='[]', damage='0' WHERE ObjectID = '{$_GET["id"]}'");
	}
	if (isset($_GET["refuel"])) {
		mysql_query("UPDATE `object_data` SET Fuel='1' WHERE ObjectID = '{$_GET["id"]}'");
	}
}

$res = mysql_query("SELECT object_data.* FROM `object_data` WHERE object_data.ObjectID = '{$_GET["id"]}' LIMIT 1") or die(mysql_error());
$row = mysql_fetch_assoc($res);

$Worldspace = str_replace("[", "", $row['Worldspace']);
$Worldspace = str_replace("]", "", $Worldspace);
$Worldspace = explode(",", $Worldspace);
$Vehicle = $row['Inventory'];
$Vehicle = json_decode($Vehicle);

$Hitpoints = $row['Hitpoints'];
//$Hitpoints ='[["wheel_1_1_steering",0.2],["wheel_2_1_steering",0],["wheel_1_4_steering",1],["wheel_2_4_steering",1],["wheel_1_3_steering",1],["wheel_2_3_steering",1],["wheel_1_2_steering",0],["wheel_2_2_steering",1],["motor",0.1],["karoserie",0.4]]';
$Hitpoints = json_decode($Hitpoints);

$xml = file_get_contents('items.xml', true);
require_once('modules/lib/class.xml2array.php');
$items_xml = XML2Array::createArray($xml);
$xml = file_get_contents('vehicles.xml', true);
require_once('modules/lib/class.xml2array.php');
$vehicles_xml = XML2Array::createArray($xml);

?>

<div id="page-heading">
	<title><?php echo $row['Classname']." - ".$sitename; ?></title>
	<h1><?php echo $row['Classname']." - ".$row['ObjectID']." - Last save: ".$row['Datestamp']." - Position: ".$row['Worldspace']; ?></h1>
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
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -95px">
								<?php echo 'Damage:&nbsp;<a href="index.php?view=info&show=4&id='.$_GET["id"].'&repair" style="color: blue">'.(substr($row['Damage'], 0, 10)).'</a>'; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -75px">
								<?php echo 'Fuel:&nbsp;<a href="index.php?view=info&show=4&id='.$_GET["id"].'&refuel" style="color: blue">'.(substr($row['Fuel'], 0, 10)).'</a>'; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -55px">
								<a href="javascript:if (confirm('Are you sure you want to delete this vehicle?')) { window.location = 'index.php?view=info&show=4&id=<?php echo $_GET["id"]; ?>&delete'; }" style="color: blue">Delete</a>
							</div>
						</div>
						<!-- Vehicle -->
						<div class="vehicle_gear">	
							<div id="vehicle_inventory">	
								<?php
									$maxmagazines = 24;
									$maxweaps = 3;
									$maxbacks = 0;
									$freeslots = 0;
									$freeweaps = 0;
									$freebacks = 0;
									$VehicleName = $row['Classname'];
									
									$class = strtolower($row['Classname']);
									if (array_key_exists('s'.$class, $vehicles_xml['vehicles'])){
										$maxmagazines = $vehicles_xml['vehicles']['s'.$class]['transportmaxmagazines'];
										$maxweaps = $vehicles_xml['vehicles']['s'.$class]['transportmaxweapons'];
										$maxbacks = $vehicles_xml['vehicles']['s'.$class]['transportmaxbackpacks'];
										$VehicleName = $vehicles_xml['vehicles']['s'.$class]['Name'];
									}
									
									if (count($Vehicle) > 0) {
										$bpweaponscount = count($Vehicle[0][0]);
										$bpweapons = array();
										for ($m = 0; $m < $bpweaponscount; $m++) {
											for ($mi=0; $mi < $Vehicle[0][1][$m]; $mi++) { $bpweapons[] = $Vehicle[0][0][$m]; }
										}							

									$bpitemscount = count($Vehicle[1][0]);
									$bpitems = array();
									for ($m = 0; $m < $bpitemscount; $m++){
										for ($mi = 0; $mi < $Vehicle[1][1][$m]; $mi++) { $bpitems[] = $Vehicle[1][0][$m]; }
									}
									
									$bpackscount = count($Vehicle[2][0]);
									$bpacks = array();
									for ($m = 0; $m < $bpackscount; $m++){
										for ($mi = 0; $mi < $Vehicle[2][1][$m]; $mi++) { $bpacks[] = $Vehicle[2][0][$m]; }
									}
									
									$Vehicle = (array_merge($bpweapons, $bpacks, $bpitems));
									$freebacks = $maxbacks;
									$Vehicleslots = 0;
									$Vehicleitem = array();
									$bpweapons = array();
									for ($i = 0; $i < count($Vehicle); $i++) {
										if(array_key_exists('s'.$Vehicle[$i],$items_xml['items'])){
											switch ($items_xml['items']['s'.$Vehicle[$i]]['Type']){
												case 'binocular':
													$Vehicleitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
													break;
												case 'rifle':
													$bpweapons[] = array('image' => '<img style="max-width: 84px; max-height: 84px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
													break;
												case 'pistol':
													$bpweapons[] = array('image' => '<img style="max-width: 84px; max-height: 84px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
													break;
												case 'backpack':
													$bpweapons[] = array('image' => '<img style="max-width: 84px; max-height: 84px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
													$freebacks = $freebacks - 1;
													break;
												case 'heavyammo':
													$Vehicleitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
													break;
												case 'smallammo':
													$Vehicleitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
													break;
												case 'item':
													$Vehicleitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Vehicle[$i].'.png" title="'.$Vehicle[$i].'" alt="'.$Vehicle[$i].'"/>', 'slots' => $items_xml['items']['s'.$Vehicle[$i]]['Slots']);
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
									$jx = 1;
									$jy = 0;
									$jk = 0;
									$jl = 0;
									$numlines = 0;
									for ($j = 0; $j < $weapons; $j++) {
										if ($jk > 3) { $jk = 0; $jl++; }
										echo '<div class="gear_slot" style="margin-left: '.($jx + (86 * $jk)).'px; margin-top: '.($jy + (86 * $jl)).'px; width: 84px; height: 84px;">'.$bpweapons[$j]['image'].'</div>';
										$freeweaps = $freeweaps - 1;
										$jk++;
									}

									if ($jl > 0){
										$numlines = $jl+1;
									}

									if ($jl == 0){
										if ($weapons > 0) { $numlines++; }
									}

									$jx = 1;
									$jy = (86*$numlines);
									$jk = 0;
									$jl = 0;
									for ($j = 0; $j < $magazines; $j++) {
										if ($jk > 6){$jk = 0; $jl++; }
										if ($j < count($Vehicleitem)) {
											echo '<div class="gear_slot" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;">'.$Vehicleitem[$j]['image'].'</div>';
											$freeslots = $freeslots - 1;
										} else {
											echo '<div class="gear_slot" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;"></div>';
										}								
										$jk++;
									}	
									}					
								?>
							</div>
							<div class="backpackname">
								<?php echo 'Magazines:&nbsp;'.$freeslots.'&nbsp;/&nbsp;'.$maxmagazines.'&nbsp;Weapons:&nbsp;'.$freeweaps.'&nbsp;/&nbsp;'.$maxweaps.'&nbsp;Backs:&nbsp;'.$freebacks.'&nbsp;/&nbsp;'.$maxbacks.'&nbsp;';?>
							</div>
						</div>
						<!-- Vehicle -->
						
						<!-- Hitpoints -->
						<div class="vehicle_hitpoints">	
							<?php
								$jx = 1;
								$jy = 48;
								$jk = 0;
								$jl = 0;
								for ($i = 0; $i < count($Hitpoints); $i++) {
									if ($jk > 3) { $jk = 0; $jl++; }
									$hit = '<img style="max-width: 90px; max-height: 90px;" src="images/hits/'.$Hitpoints[$i][0].'.png" title="'.$Hitpoints[$i][0].' - '.round(100 - ($Hitpoints[$i][1] * 100)).'%" alt="'.$Hitpoints[$i][0].' - '.round(100 - ($Hitpoints[$i][1] * 100)).'%"/>';
									echo '<div class="hit_slot" style="margin-left: '.($jx + (93 * $jk)).'px; margin-top: '.($jy + (93 * $jl)).'px; width: 91px; height: 91px; background-color: rgba(100,'.round((255 / 100) * (100 - ($Hitpoints[$i][1] * 100))).', 0, 0.8);">'.$hit.'</div>';
									$jk++;
								}							
							?>
							<div class="backpackname">
								<?php echo 'Hitpoints'; ?>
							</div>
						</div>
						<!-- Hitpoints -->
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
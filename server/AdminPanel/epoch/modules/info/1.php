<?php

// Inventory save and player delete code by Crosire

if (isset($_GET['delete'])) {
	mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELET character_data {$_GET["id"]}', '{$_SESSION['login']}', NOW())");
	mysql_query("DELETE FROM `character_data` WHERE `PlayerUID` = '{$_GET['uid']}' AND `CharacterID` = '{$_GET["id"]}'") or die(mysql_error());
	echo '<script type="text/javascript">window.location = "index.php?view=table&show=1"</script>';
} else if (isset($_GET['clear'])) {
	mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('CLEAR character_data {$_GET["id"]}', '{$_SESSION['login']}', NOW())");
	mysql_query("UPDATE `character_data` SET `inventory` = '[[],[]]', `Backpack` = '[\"\",[[],[]],[[],[]]]' WHERE `PlayerUID` = '{$_GET['uid']}' AND `CharacterID` = '{$_GET["id"]}'") or die(mysql_error());
	echo '<script type="text/javascript">window.location = "index.php?view=check"</script>';
} else {
	if (isset($_POST['inventory'])) {
		mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('Edit character_data {$_GET["uid"]}', '{$_SESSION['login']}', NOW())");
		mysql_query("UPDATE `character_data` SET `Inventory` = '".(mysql_real_escape_string($_POST["inventory"]))."' WHERE `PlayerUID` = '{$_GET['uid']}' AND `CharacterID` = '{$_GET["id"]}'");
	}

	if (isset($_POST['backpack'])) {
		//mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('Backpack edit character_data {$_GET["id"]}', '{$_SESSION['login']}', NOW())");
		mysql_query("UPDATE `character_data` SET `Backpack` = '".(mysql_real_escape_string($_POST["backpack"]))."' WHERE `PlayerUID` = '{$_GET['uid']}' AND `CharacterID` = '{$_GET["id"]}'");
	}

	if (isset($_POST['model'])) {
		//mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('Model edit character_data {$_GET["id"]}', '{$_SESSION['login']}', NOW())");
		if ($_POST['model'] != '' && $_POST['model'] != '""') { mysql_query("UPDATE `character_data` SET `Model` = '".(str_replace('"', '', $_POST["model"]))."' WHERE `PlayerUID` = '{$_GET['uid']}' AND `id` = '{$_GET["id"]}'"); }
	}
}

$res = mysql_query("SELECT player_data.*, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND character_data.PlayerUID = '".$_GET["uid"]."' AND character_data.CharacterID = '".$_GET["id"]."' LIMIT 1") or die(mysql_error());
$row = mysql_fetch_assoc($res);

$Worldspace = str_replace("[", "", $row['Worldspace']);
$Worldspace = str_replace("]", "", $Worldspace);
$Worldspace = explode(",", $Worldspace);
$RawInventory = $row['Inventory'];
$Inventory = json_decode($RawInventory);
$RawBackpack = $row['Backpack'];
$Backpack = json_decode($RawBackpack);
$model = str_replace('"', '', $row['Model']);
$name = $row['PlayerName'];

if (is_array($Inventory)) { if (array_key_exists(1, $Inventory)) { $Inventory = (array_merge($Inventory[0], $Inventory[1])); } } else {$Inventory = array(); }

$xml = file_get_contents('/items.xml', true);
require_once('modules/lib/class.xml2array.php');
$items_xml = XML2Array::createArray($xml);

if (file_exists('banned.txt')) {
	$txt = file_get_contents('banned.txt', true);
	$txt = str_replace("\"", "", str_replace("\r", "", $txt));
	$items_banned = explode("\n", $txt);
} else {
	$items_banned = array();
}

$binocular = array();
$rifle = '<img style="max-width: 220px; max-height: 92px;" src="images/gear/rifle.png" title="" alt="" />';
$pistol = '<img style="max-width: 92px; max-height: 92px;" src="images/gear/pistol.png" title="" alt="" />';
$second = '<img style="max-width: 220px; max-height: 92px;" src="images/gear/second.png" title="" alt="" />';
$heavyammo = array();
$heavyammoslots = 0;
$smallammo = array();
$usableitems = array();

for ($i = 0; $i < count($Inventory); $i++){
	if (array_key_exists($i, $Inventory)){
		$curitem = $Inventory[$i];
		$icount = "";
		
		if (is_array($curitem)) { $curitem = $Inventory[$i][0]; $icount = ' - '.$Inventory[$i][1].' rounds'; }
		if (array_key_exists('s'.$curitem, $items_xml['items'])) {
			if (isset($items_xml['items']['s'.$curitem]['Type'])) {
				switch($items_xml['items']['s'.$curitem]['Type']) {
					case 'binocular':
						$binocular[] = '<img style="max-width: 78px; max-height: 78px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.'" alt="'.$curitem.'" />';
						break;
					case 'rifle':
						$rifle = '<img style="max-width: 220px; max-height: 92px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.'" alt="'.$curitem.'" />';
						break;
					case 'pistol':
						$pistol = '<img style="max-width: 92px; max-height: 92px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.'" alt="'.$curitem.'" />';
						break;
					case 'backpack':
						break;
					case 'heavyammo':
						$heavyammo[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'" />', 'slots' => $items_xml['items']['s'.$curitem]['Slots']);
						break;
					case 'smallammo':
						$smallammo[] = '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'" />';
						break;
					case 'item':
						$usableitems[] = '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.'" alt="'.$curitem.'" />';
						break;
					default:
						$s = '';
				}
			} else {
				$debug .= 'Error finding:&nbsp;'.$curitem.';<br />';
			}
		} else {
			$debug .= 'Unknown item:&nbsp;'.$curitem.';<br />';
		}
		
		if (in_array($curitem, $items_banned)) {
			$debug .= 'Banned Item:&nbsp;'.$curitem.';<br />';
		}
	}
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		InventoryEditor.Init();
		InventoryEditor.InventoryData = <? echo $RawInventory ?>;
		InventoryEditor.BackpackData = <? echo $RawBackpack ?>;
		InventoryEditor.ModelData = <? echo '"'.$model.'"'; ?>;
		
		$("#invedit-showinventory").on("click", function() { $("#invedit-inventory").fadeIn(1000); $("#invedit-showinventory").fadeOut(1000); });
		$("#invedit-showbackpack").on("click", function() { $("#invedit-backpack").fadeIn(1000); $("#invedit-showbackpack").fadeOut(1000); });
	});
	function post() {
		document.invedit.submit();
	}
</script>

<div id="page-heading">
	<title><?php echo $name." - ".$sitename; ?></title>
	<h1><?php echo $name." - ".$row['PlayerUID']." - Last save: ".$row['lastactiv']." - Position: ".$row['Worldspace']; ?></h1>
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
				<div id="invedit-toolbar">
					<table border="0" width="100%" id="invedit-table">
						<form name="invedit" action="index.php?view=info&show=1&uid=<?php echo $_GET["uid"]; ?>&id=<?php echo $_GET["id"]; ?>" method="post">
							<tr>
								<td><center><textarea name="inventory" id="invedit-inventory" rows="5" style="width: 100%; resize: none; display: none"><? echo $RawInventory; ?></textarea></center></td>
								<td><center><textarea name="backpack" id="invedit-backpack" rows="5" style="width: 100%; resize: none; display: none"><? echo $RawBackpack; ?></textarea></center></td>
							</tr>
							<tr>
								<td><textarea name="model" id="invedit-model" style="width: 0px; resize: none;" hidden><? echo $model; ?></textarea></td>
							</tr>
						</form>
						<tr>
							<td><center><button id="invedit-showinventory" style="width: 100%">Show Inventory</button></center></td>
							<td><center><button id="invedit-showbackpack" style="width: 100%">Show Backpack</button></center></td>
						</tr>
					</table>
					<br />
					<table border="0" id="invedit-table">
						<tr><td><div id="invedit-area"></div></td></tr>
					</table>
				</div>
				<br />
				<div id="table-content">
					<div id="gear_player">
						<!-- General Info -->
						<div class="gear_info">
							<div class="playermodel EditableItem" data-type="model">
								<img src="images/models/<?php echo $model; ?>.png" alt="<?php echo $model; ?>" style="width: 100%;" />
							</div>
							<div id="gps" style="margin-left: 46px; margin-top: 54px">
								<div class="gpstext" style="font-size: 22px; width: 60px; text-align: left; margin-left: 47px; margin-top: 13px">
									<?php echo round($Worldspace[0] / 100); ?>
								</div>
								<div class="gpstext" style="font-size: 22px; width: 60px; text-align: left; margin-left: 47px; margin-top: 34px">
									<?php if (array_key_exists(3, $Worldspace)) { echo round($Worldspace[3] / 100); } else { echo "0"; } ?>
								</div>
								<div class="gpstext" style="width: 120px; margin-left: 13px; margin-top: 61px">
									<?
										require_once('modules/calc.php');
										if (array_key_exists(1, $Worldspace) && array_key_exists(2, $Worldspace)) { echo sprintf("%03d", round(world_x($Worldspace[1], $serverworld))).sprintf("%03d", round(world_y($Worldspace[2], $serverworld))); } else { echo "000000"; }
									?>
								</div>							
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -135px">
								<?php echo 'Humanity:&nbsp;'.$row['Humanity']; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -115px">
								<?php echo 'Zombie kills:&nbsp;'.$row['KillsZ']; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -95px">
								<?php echo 'Zombie headshots:&nbsp;'.$row['HeadshotsZ']; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -75px">
								<?php echo 'Survivers killed:&nbsp;'.$row['KillsH']; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -55px">
								<?php echo 'Bandits killed:&nbsp;'.$row['KillsB']; ?>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -35px">
								<a href="javascript:if (confirm('Are you sure you want to delete this player?')) { window.location='index.php?view=info&show=1&uid=<? echo $row['PlayerUID']; ?>&id=<? echo $row['CharacterID']; ?>&delete'; }">Delete</a>
							</div>
							<div class="statstext" style="width: 180px; margin-left: 205px; margin-top: -15px">
								<a href="javascript:post();">Save</a>
							</div>
						</div>
						<!-- Inventory -->
						<div class="gear_inventory">
							<div class="gear_slot EditableItem" data-type="binocular" style="margin-left: 1px; margin-top: 48px; width: 80px; height: 80px;">
								<?php if (array_key_exists(0, $binocular)) { echo $binocular[0]; } else { echo '<img style="max-width: 78px; max-height: 78px;" src="images/gear/binocular.png" title="" alt="" />'; } ?>
							</div>
							<div class="gear_slot EditableItem" data-type="binocular" style="margin-left: 292px; margin-top: 48px; width: 80px; height: 80px;">
								<?php if (array_key_exists(1, $binocular)) { echo $binocular[1]; } else { echo '<img style="max-width: 78px; max-height: 78px;" src="images/gear/binocular.png" title="" alt="" />'; } ?>
							</div>
							<div class="gear_slot EditableItem" data-type="primary" style="margin-left: 0px; margin-top: 130px; width: 224px; height: 96px;">
								<?php echo $rifle; ?>
							</div>
							<div class="gear_slot EditableItem" data-type="backpackitem" style="margin-left: 0px; margin-top: 228px; width: 224px; height: 96px;">
								<?php
									if (array_key_exists(0, $Backpack)) {
										if ($Backpack[0] != "") { echo '<img style="max-width: 220px; max-height: 92px;" src="images/thumbs/'.$Backpack[0].'.png" title="'.$Backpack[0].'" alt="'.$Backpack[0].'" />'; } else { echo $second; }
									} else {
										echo $second;
									}
								?>
							</div>
							<div class="gear_slot EditableItem" data-type="pistol" style="margin-left: 30px; margin-top: 326px; width: 96px; height: 96px;">
								<?php echo $pistol; ?>
							</div>
							<?php
								// Big Ammo
								$jx = 226; $jy = 130; $jk = 0; $jl = 0;
								$maxslots = 12;
								for ($j = 0; $j < $maxslots; $j++) {
									if ($jk > 2) { $jk = $jk - 3; $jl++; }
									$hammo = '<img style="max-width: 43px; max-height: 43px;" src="images/gear/heavyammo.png" title="" alt="" />';
									if ($j > 5){ $hammo = '<img style="max-width: 43px; max-height: 43px;" src="images/gear/grenade.png" title="" alt="" />'; }
									if (array_key_exists($j, $heavyammo)) {
										$hammo = $heavyammo[$j]['image'];									
										echo '<div class="gear_slot EditableItem" data-type="inventory" data-slot="'.$j.'" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;">'.$hammo.'</div>';
										$jk = $jk - 1 + $heavyammo[$j]['slots'];
										$heavyammoslots = $heavyammoslots + $heavyammo[$j]['slots'];
									} else {
										if ($heavyammoslots == $maxslots) { break; }
										$heavyammoslots++;
										echo '<div class="gear_slot EditableItem" data-type="inventory" data-slot="'.$j.'" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;">'.$hammo.'</div>';
									}
									$jk++;
								}
								
								// Small Ammo
								$jx = 128; $jy = 326; $jk = 0; $jl = 0;
								for ($j = 0; $j < 8; $j++) {
									if ($jk > 3) { $jk = 0; $jl++; }
									$sammo = '<img style="max-width: 43px; max-height: 43px;" src="images/gear/smallammo.png" title="" alt="" />';
									if (array_key_exists($j, $smallammo)) { $sammo = $smallammo[$j]; }
									echo '<div class="gear_slot EditableItem" data-type="secondary" data-slot="'.$j.'" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;">'.$sammo.'</div>';								
									$jk++;
								}
								
								// Items
								$jx = 30; $jy = 424; $jk = 0; $jl = 0;
								for ($j = 0; $j < 12; $j++) {
									if ($jk > 5){$jk = 0; $jl++; }
									$uitem = '<img style="max-width: 43px; max-height: 43px;" src="" title="" alt="" />';
									if (array_key_exists($j, $usableitems)) { $uitem = $usableitems[$j]; }
									echo '<div class="gear_slot EditableItem" data-type="toolbelt" data-slot="'.$j.'" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;">'.$uitem.'</div>';								
									$jk++;
								}
							?>
							<div class="inventoryheader">
								<?php echo "Start Time: ".$row['Datestamp']; ?>
							</div>
						</div>
						<!-- Backpack -->
						<div class="gear_backpack">
							<?php
								$maxmagazines = 0;
								$BackpackName = "";

								if (array_key_exists(0, $Backpack)) {
									if ($Backpack[0] != "") {
										$BackpackName = $Backpack[0];
										if (array_key_exists('s'.$Backpack[0], $items_xml['items'])) { $maxmagazines = $items_xml['items']['s'.$Backpack[0]]['maxmagazines']; }
									}
								}

								$bpweapons = array();
								if (array_key_exists(0, $Backpack[1])) {
									$bpweaponscount = count($Backpack[1][0]);
									for ($m = 0; $m < $bpweaponscount; $m++) {
										for ($mi = 0; $mi < $Backpack[1][1][$m]; $mi++) { $bpweapons[] = $Backpack[1][0][$m]; }
									}
								}

								$bpitems = array();
								if (array_key_exists(0, $Backpack[2])) {
									$bpitemscount = count($Backpack[2][0]);							
									for ($m = 0; $m < $bpitemscount; $m++) {
										for ($mi = 0; $mi < $Backpack[2][1][$m]; $mi++) { $bpitems[] = $Backpack[2][0][$m]; }
									}
								}
								
								$Backpack = (array_merge($bpweapons, $bpitems));
								$backpackslots = 0;
								$backpackitem = array();
								$bpweapons = array();
								for ($i = 0; $i < count($Backpack); $i++) {
									if (array_key_exists('s'.$Backpack[$i], $items_xml['items'])) {
										switch($items_xml['items']['s'.$Backpack[$i]]['Type']) {
											case 'binocular':
												$backpackitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'" />', 'slots' => $items_xml['items']['s'.$Backpack[$i]]['Slots']);
												break;
											case 'rifle':
												$bpweapons[] = array('image' => '<img style="max-width: 124px; max-height: 92px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'" />', 'slots' => $items_xml['items']['s'.$Backpack[$i]]['Slots']);
												break;
											case 'pistol':
												$bpweapons[] = array('image' => '<img style="max-width: 92px; max-height: 92px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'" />', 'slots' => $items_xml['items']['s'.$Backpack[$i]]['Slots']);
												break;
											case 'backpack':
												break;
											case 'heavyammo':
												$backpackitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'" />', 'slots' => $items_xml['items']['s'.$Backpack[$i]]['Slots']);
												break;
											case 'smallammo':
												$backpackitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'" />', 'slots' => $items_xml['items']['s'.$Backpack[$i]]['Slots']);
												break;
											case 'item':
												$backpackitem[] = array('image' => '<img style="max-width: 43px; max-height: 43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'" />', 'slots' => $items_xml['items']['s'.$Backpack[$i]]['Slots']);
												break;
											default:
												$s = '';
										}
									}
								}	
								
								$weapons = count($bpweapons);
								$magazines = $maxmagazines;
								$freeslots = $magazines;
								
								$jx = 1; $jy = 48; $jk = 0; $jl = 0;
								for ($j = 0; $j < $weapons; $j++) {
									if ($jk > 1) { $jk = 0; $jl++; }
									echo '<div class="gear_slot" data-type="backpack" data-slot="'.$j.'" style="margin-left: '.($jx + (130 * $jk)).'px; margin-top: '.($jy + (98 * $jl)).'px; width: 128px; height: 96px;">'.$bpweapons[$j]['image'].'</div>';
									$magazines = $magazines - $bpweapons[$j]['slots'];	
									$freeslots = $freeslots - $magazines;
									$jk++;
								}
								
								$jx = 1; $jy = 48 + (98 * round($weapons / 2)); $jk = 0; $jl = 0;
								for ($j = 0; $j < $magazines; $j++) {
									if ($jk > 6) { $jk = 0; $jl++; }
									if ($j < count($backpackitem)) {
										echo '<div class="gear_slot" data-type="backpack" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;">'.$backpackitem[$j]['image'].'</div>';
										$jk = $jk - 1 + $backpackitem[$j]['slots'];
										$backpackslots = $backpackslots + $backpackitem[$j]['slots'];
										$freeslots = $freeslots - $backpackitem[$j]['slots'];
									} else {
										if ($backpackslots == $maxmagazines) { break; }
										$backpackslots++;
										echo '<div class="gear_slot" data-type="backpack" style="margin-left: '.($jx + (49 * $jk)).'px; margin-top: '.($jy + (49 * $jl)).'px; width: 47px; height: 47px;"></div>';
									}								
									$jk++;
								}	 			
							?>
							<div class="backpackname">
								<?php echo $BackpackName.'&nbsp;&nbsp;(&nbsp;'.($maxmagazines - $freeslots).'&nbsp;/&nbsp;'.$maxmagazines.'&nbsp;)'; ?>
							</div>
						</div>
						<!-- Backpack -->
					</div>
					<?php echo $debug; ?>
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
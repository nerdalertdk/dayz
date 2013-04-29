<?php
ini_set('display_errors', 0);
if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "table") !== false))
{
	$pagetitle = "Items check";
	//mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('ITEMS CHECK', '{$_SESSION['login']}', NOW())") or die(mysql_error());	

	$xml = file_get_contents('items.xml', true);
	require_once('modules/lib/class.xml2array.php');
	$items_xml = XML2Array::createArray($xml);

	if (file_exists('banned.txt')) {
		$txt = file_get_contents('banned.txt', true);
		$txt = str_replace("\"", "", str_replace("\r", "", $txt));
		$items_banned = explode("\n", $txt);
	} else {
		$items_banned = array();
	}
	
	$rows = '';
	$count = 0;

	$res = mysql_query("SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE player_data.PlayerUID = character_data.PlayerUID AND character_data.Alive = '1'") or die(mysql_error());
	if (mysql_num_rows($res) > 0) {
		while ($row = mysql_fetch_array($res)) {
			$Inventory = $row['Inventory'];	
			$Inventory = json_decode($Inventory);
			$Backpack = $row['Backpack'];
			$Backpack = json_decode($Backpack);
			$Unknown = array();
			
			if (is_array($Inventory)) { // SilverShot Patch
				if (array_key_exists(0, $Inventory)) { 
					if (array_key_exists(1, $Inventory)) { $Inventory = (array_merge($Inventory[0], $Inventory[1])); }
				} else { 
					if (array_key_exists(1, $Inventory)) { $Inventory = $Inventory[1]; }			
				}
			} else {
				$Inventory = array();
			}
			
			$bpweaponscount = count($Backpack[1][0]);
			$bpweapons = array();
			for ($m = 0; $m < $bpweaponscount; $m++) { for ($mi = 0; $mi < $Backpack[1][1][$m]; $mi++) {$bpweapons[] = $Backpack[1][0][$m]; } }		
			$bpitemscount = count($Backpack[2][0]);
			$bpitems = array();
			for ($m = 0; $m < $bpitemscount; $m++) { for ($mi = 0; $mi < $Backpack[2][1][$m]; $mi++) {$bpitems[] = $Backpack[2][0][$m]; } }

			$Backpack = array_merge($bpweapons, $bpitems);
			$Inventory = array_merge($Inventory, $Backpack);

			for ($i = 0; $i < count($Inventory); $i++) { 
				if (array_key_exists($i, $Inventory)) { 
					$curitem = $Inventory[$i];
					if (is_array($curitem)) { $curitem = $Inventory[$i][0]; }
					if (in_array($curitem, $items_banned)) { $Unknown[] = $curitem; }
					if (!array_key_exists('s'.$curitem, $items_xml['items'])) { $Unknown[] = $curitem; }
				}
			}

			if (count($Unknown) > 0) { 
				$rows .= '<tr>
					<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$row['PlayerUID'].'&id='.$row['CharacterID'].'&clear"><img src="images/icons/player_clear.png" title="Delete inventory" alt="Delete inventory"/></a></td>
					<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$row['PlayerUID'].'&id='.$row['CharacterID'].'">'.$row['PlayerName'].'</a></td>
					<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$row['PlayerUID'].'&id='.$row['CharacterID'].'">'.$row['PlayerUID'].'</a></td>
					<td align="center" class="gear_preview">';
					
				foreach ($Unknown as $item) { 
					$rows .= $item."; ";
					$count++;
				}
				
				$rows .= '</td></tr>';
			}
		}
	}
	
	$res = mysql_query("SELECT object_data.* FROM `object_data` 
			WHERE object_data.Instance = '".$serverinstance."' 
			AND (Classname = 'dummy' 
			OR Classname = 'TentStorage' 
			#OR Classname = 'Hedgehog_DZ' 
			#OR Classname = 'Wire_cat1' 
			#OR Classname = 'WoodGate_DZ' 
			#OR Classname = 'Sandbag1_DZ'
			#OR Classname = 'Fort_RazorWire'
			#OR Classname = 'TrapBear'
			OR Classname = 'VaultStorageLocked')") or die(mysql_error());
	if (mysql_num_rows($res) > 0) {
		while ($row = mysql_fetch_array($res)) {
			$Inventory = $row['Inventory'];	
			$Inventory = json_decode($Inventory);
			$Unknown = array();
			
			$bpweaponscount = count($Inventory[1][0]);
			$bpweapons = array();
			for ($m = 0; $m < $bpweaponscount; $m++) { for ($mi = 0; $mi < $Inventory[1][1][$m]; $mi++) {$bpweapons[] = $Inventory[1][0][$m]; } }		
			$bpitemscount = count($Inventory[2][0]);
			$bpitems = array();
			for ($m = 0; $m < $bpitemscount; $m++) { for ($mi = 0; $mi < $Inventory[2][1][$m]; $mi++) {$bpitems[] = $Inventory[2][0][$m]; } }

			$Inventory = array_merge($bpweapons, $bpitems);

			for ($i = 0; $i < count($Inventory); $i++) { 
				if (array_key_exists($i, $Inventory)) { 
					$curitem = $Inventory[$i];
					if (is_array($curitem)) { $curitem = $Inventory[$i][0]; }
					if (in_array($curitem, $items_banned)) { $Unknown[] = $curitem; }
					if (!array_key_exists('s'.$curitem, $items_xml['items'])) { $Unknown[] = $curitem; }
				}
			}

			if (count($Unknown) > 0) { 
				$rows .= '<tr>
					<td align="center" class="gear_preview"><a href="index.php?view=info&show=5&id='.$row['ObjectID'].'&delete"><img src="images/icons/player_clear.png" title="Delete inventory" alt="Delete inventory"/></a></td>
					<td align="center" class="gear_preview"><a href="index.php?view=info&show=5&id='.$row['ObjectID'].'">'.$row['Classname'].' (Deployable)</a></td>
					<td align="center" class="gear_preview">'.$row['CharacterID'].'</td>
					<!--<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$row['CharacterID'].'&id='.$row['CharacterID'].'">'.$row['CharacterID'].'</a></td>-->
					<td align="center" class="gear_preview">';
					
				foreach ($Unknown as $item) { 
					$rows .= $item."; ";
					$count++;
				}
				
				$rows .= '</td></tr>';
			}
		}
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
				<div id="table-content">
				<?php if ($count > 0) { ?>
					<div id="message-red">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="red-left">Warning! <?php echo $count; ?> banned items found!</td>
								<td class="red-right"><a class="close-red"><img src="images/forms/icon_close_red.gif" alt="" /></a></td>
							</tr>
						</table>
					</div>
					<table id="product-table" border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<th class="product-table-header" style="width: 5%"><a>Clear</a></th>
							<th class="product-table-header" style="width: 20%"><a>Player Name</a></th>
							<th class="product-table-header" style="width: 20%"><a>Player UID</a></th>
							<th class="product-table-header" style="width: 55%"><a>Banned items</a></th>
						</tr>
						<?php echo $rows; ?>				
					</table>
				<?php } else { ?>
					<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">No banned items found!</td>
								<td class="green-right"><a class="close-red"><img src="images/forms/icon_close_green.gif" alt="" /></a></td>
							</tr>
						</table>
					</div>
				<?php } ?>
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

<?php
}
else
{ 
	header('Location: index.php');
}

?>
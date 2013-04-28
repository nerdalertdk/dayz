<?php
ini_set('display_errors', 0);
if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "table") !== false))
{
	$pnumber = 0;
	$tableheader = '';
	$tablerows = '';
	$pageNum = 1;
	$maxPage = 1;
	$rowsPerPage = 50;
	$nav = '';
	$self = 'index.php?view=table&show='.$show;
	$paging = '';

	$formhead = "";
	$formfoot = "";
	
	if (isset($_GET["show"])) {
		$show = $_GET["show"];
	} else {
		$show = 0;
	}

	if (isset($_GET["sort"])) {
		$sort = $_GET["sort"];
	} else {
		$sort = 0;
	}
	
	if (isset($_GET['order'])) {
		$order = $_GET['order'];
	} else {
		$order = "ASC";
	}
	
	switch ($show) {
		case 0:
			$pagetitle = "Online players (RCON)";
			break;
		case 1:
			$query = "SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data`
			WHERE character_data.InstanceID = '".$serverinstance."' 
			AND player_data.PlayerUID = character_data.PlayerUID 
			AND character_data.Alive = '1'";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY player_data.PlayerName $order";
					break;
				case 2:
					$query .= " ORDER BY character_data.PlayerUID` $order";
					break;
				case 3:
					$query .= " ORDER BY character_data.Worldspace $order";
					break;
				case 4:
					$query .= " ORDER BY character_data.Medical $order";
					break;
				case 5:
					$query .= " ORDER BY character_data.Inventory $order";
					break;
				case 6:
					$query .= " ORDER BY character_data.Backpack $order";
					break;
				case 7:
					$query .= " ORDER BY character_data.Humanity $order";
					break;
				default:
					$query .= " ORDER BY character_data.Datestamp";
			};
			
			$pagetitle = "Alive players";		
			break;
		case 11:
			$query = "SELECT player_data.PlayerName, character_data.* 
			FROM `player_data`, `character_data`
			WHERE character_data.InstanceID = '".$serverinstance."'
			AND player_data.PlayerUID = character_data.PlayerUID
			AND character_data.Alive = '1'
			AND character_data.lastactiv > DATE_SUB(now(), INTERVAL 7 MINUTE)";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY player_data.PlayerName $order";
					break;
				case 2:
					$query .= " ORDER BY character_data.PlayerUID` $order";
					break;
				case 3:
					$query .= " ORDER BY character_data.Worldspace $order";
					break;
				case 4:
					$query .= " ORDER BY character_data.Medical $order";
					break;
				case 5:
					$query .= " ORDER BY character_data.Inventory $order";
					break;
				case 6:
					$query .= " ORDER BY character_data.Backpack $order";
					break;
				case 7:
					$query .= " ORDER BY character_data.Humanity $order";
					break;
				default:
					$query .= " ORDER BY character_data.Datestamp";
			};
			
			$pagetitle = "Online players";		
			break;
		case 12:
			$query = "
			SELECT player_data.PlayerName, character_data.* 
			FROM `player_data`, `character_data` 
			WHERE character_data.InstanceID = '".$serverinstance."' 
			AND player_data.PlayerUID = character_data.PlayerUID 
			AND character_data.Alive = '1'
			AND character_data.Model LIKE 'pz_%'
			";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY player_data.PlayerName $order";
					break;
				case 2:
					$query .= " ORDER BY character_data.PlayerUID` $order";
					break;
				case 3:
					$query .= " ORDER BY character_data.Worldspace $order";
					break;
				case 4:
					$query .= " ORDER BY character_data.Medical $order";
					break;
				case 5:
					$query .= " ORDER BY character_data.Inventory $order";
					break;
				case 6:
					$query .= " ORDER BY character_data.Backpack $order";
					break;
				default:
					$query .= " ORDER BY character_data.Datestamp $order";
			};
			
			$pagetitle = "Alive Zombies";		
			break;
		
		case 2:
			$query = "SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE character_data.InstanceID = '".$serverinstance."' AND player_data.PlayerUID = character_data.PlayerUID AND character_data.Alive = '0' AND character_data.inventory NOT LIKE '[[],[]]'";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY survivor.name $order";
					break;
				case 2:
					$query .= " ORDER BY survivor.unique_id $order";
					break;
				case 3:
					$query .= " ORDER BY survivor.worldspace $order";
					break;
				case 4:
					$query .= " ORDER BY survivor.medical $order";
					break;
				case 5:
					$query .= " ORDER BY survivor.inventory $order";
					break;
				case 6:
					$query .= " ORDER BY survivor.backpack $order";
					break;
			};
			
			$pagetitle = "Dead players";	
			break;
		case 3:
			$query = "SELECT player_data.PlayerName, character_data.* FROM `player_data`, `character_data` WHERE character_data.InstanceID = '".$serverinstance."' AND player_data.PlayerUID = character_data.PlayerUID";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY survivor.name $order";
					break;
				case 2:
					$query .= " ORDER BY survivor.unique_id $order";
					break;
				case 3:
					$query .= " ORDER BY survivor.worldspace $order";
					break;
				case 4:
					$query .= " ORDER BY survivor.medical $order";
					break;
				case 5:
					$query .= " ORDER BY survivor.inventory $order";
					break;
				case 6:
					$query .= " ORDER BY survivor.backpack $order";
					break;
			};
			
			$pagetitle = "All players";	
			break;
		case 4:
			$query = "SELECT object_data.* FROM `object_data` 
			WHERE object_data.Instance = '".$serverinstance."' 
			AND object_data.Damage < '0.95'
						AND Classname != 'dummy' 
AND Classname != 'TentStorage' 
AND Classname != 'Hedgehog_DZ' 
AND Classname != 'Wire_cat1' 
AND Classname != 'WoodGate_DZ' 
AND Classname != 'Sandbag1_DZ'
AND Classname != 'Fort_RazorWire'
AND Classname != 'TrapBear'
AND Classname != 'VaultStorageLocked'
			";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY `id` $order";
					break;
				case 2:
					$query .= " ORDER BY `class_name` $order";
					break;
				case 3:
					$query .= " ORDER BY `damage` $order";
					break;
				case 4:
					$query .= " ORDER BY `worldspace` $order";
					break;
				case 5:
					$query .= " ORDER BY `inventory` $order";
					break;
				case 6:
					$query .= " ORDER BY `parts` $order";
					break;
			};
			
			$pagetitle = "Ingame vehicles";	
			break;
		case 5:
			$query = "
			SELECT object_data.* FROM `object_data` 
			WHERE object_data.Instance = '".$serverinstance."' 
			AND (Classname = 'TentStorage' OR Classname = 'VaultStorageLocked')
			";
			
			switch ($sort) {
				case 1:
					$query .= " ORDER BY  OjectID $order";
					break;
				case 2:
					$query .= " ORDER BY `ObjectUID` $order";
					break;
				case 3:
					$query .= " ORDER BY `Classname` $order";
					break;
				case 4:
					$query .= " ORDER BY `Worldspace` $order";
					break;
				case 5:
					$query .= " ORDER BY `Inventory` $order";
					break;
			};
			
			$pagetitle = "Ingame deployables";
			break;
		default:
			$pagetitle = "Online players";
	};
	
	echo '<div id="page-heading"><title>'.$pagetitle.' - '.$sitename.'</title><h1>'.$pagetitle.'</h1></div>';
	
	require_once('modules/tables/rows.php');
	include('modules/tables/'.$show.'.php');
	
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
						<div id="message-blue">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="blue-left"><?php echo (isset($delresult) ? $delresult : $pagetitle.": ".$pnumber."."); ?></td>
									<td class="blue-right"><a class="close-blue"><img src="images/forms/icon_blue.gif" alt="" /></a></td>
								</tr>
							</table>
						</div>
						<?php echo $paging.'<br \><br \><br \>'.$formhead.'<table id="product-table" border="0" width="100%" cellpadding="0" cellspacing="0">'.$tableheader.$tablerows.'</table>'.$formfoot; ?>	
					</div>
					<?php echo $paging; ?>
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
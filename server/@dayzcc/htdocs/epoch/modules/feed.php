<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "feed") !== false))
{
	// Kill and Hits Feed written by SilverShot and Crosire
	$pagetitle = "Killfeed";
	$pathlog = str_replace('.exe', '.rpt', $pathserver);
	$order = 0;
	$hide = 0;
	
	$tablerows = '';
	$tableheader = '<tr>
		<th class="product-table-header" width="10%"><a href="index.php?view=feed&order='.$order.'">Time</a></th>
		<th class="product-table-header" width="10%"><a>Type</a></th>
		<th class="product-table-header" width="25%"><a>Attacker</a></th>
		<th class="product-table-header" width="25%"><a>Victim</a></th>
		<th class="product-table-header" width="10%"><a>Range</a></th>
		<th class="product-table-header" width="20%"><a>Weapon</a></th>
		</tr>';
		
	if (isset($_GET['order'])) {
		$order = $_GET['order'];
		if ($order == "1") { $order = 0; } else { $order = 1; }
	}
	
	if (isset($_GET['hide'])) {
		$hide = $_GET['hide'];
	}

	echo '<div id="page-heading"><title>'.$pagetitle.' - '.$sitename.'</title><h1>'.$pagetitle.'</h1></div>';
	
	if (file_exists($pathlog))
	{
		foreach (file($pathlog) as $line) {
			$sectionpattern = "#\b([0-9\:]+)\s\"PLAYER:\s([".($hide == "1" ? "" : "HIT|")."KILL]+):\s([\S ]+)\swas\s[".($hide == "1" ? "" : "hit|")."killed by]+\s([\S ]+)\s[with]+\s([weapon\s]+)?([\S ]*)\s[from ]+([\d.]*)m#"; // Kill and Hits regex by SilverShot, slightly modified by Crosire.

			if (preg_match($sectionpattern, $line, $matches)) {
				$time = $matches[1];
				$type = $matches[2];
				$range = $matches[7];
				$weapon = $matches[6];
				$color = ($type == "KILL" ? "FF0000" : "FFFFFF");
				$victim = preg_replace("#\b[A-Z] [0-9]\-[0-9]\-[A-Z]\:[0-9] \(#", "", $matches[3]);
				$victim = preg_replace("#\) REMOTE\b#", "", $victim);
				$attacker = preg_replace("#\b[A-Z] [0-9]\-[0-9]\-[A-Z]\:[0-9] \(#", "", $matches[4]);
				$attacker = preg_replace("#\) REMOTE\b#", "", $attacker);
				
				$attacker_res = mysql_query("SELECT profile.name, survivor.* FROM `profile`, `survivor` AS `survivor` WHERE profile.unique_id = survivor.unique_id AND profile.name LIKE '".(mysql_real_escape_string($attacker))."' ORDER BY survivor.last_updated DESC LIMIT 1");
				$victim_res = mysql_query("SELECT profile.name, survivor.* FROM `profile`, `survivor` AS `survivor` WHERE profile.unique_id = survivor.unique_id AND profile.name LIKE '".(mysql_real_escape_string($victim))."' ORDER BY survivor.last_updated DESC LIMIT 1");
				$attacker_row = mysql_fetch_array($attacker_res);
				$victim_row = mysql_fetch_array($victim_res);
				
				if ($order == "1") {
					$tablerows .= '<tr>
						<td align="center" class="gear_preview">'.$time.'</td>
						<td align="center" class="gear_preview"><strong><font color="#'.$color.'">'.$type.'</font></strong></td>
						<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$attacker_row['unique_id'].'&id='.$attacker_row['id'].'">'.$attacker.'</a></td>
						<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$victim_row['unique_id'].'&id='.$victim_row['id'].'">'.$victim.'</a></td>
						<td align="center" class="gear_preview">'.$range.'</td>
						<td align="center" class="gear_preview"><a href="index.php?view=search&type=playerinv&search='.$weapon.'">'.$weapon.'</a></td>
						</tr>';
				} else {
					$tablerows = '<tr>
						<td align="center" class="gear_preview">'.$time.'</td>
						<td align="center" class="gear_preview"><strong><font color="#'.$color.'">'.$type.'</font></strong></td>
						<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$attacker_row['unique_id'].'&id='.$attacker_row['id'].'">'.$attacker.'</a></td>
						<td align="center" class="gear_preview"><a href="index.php?view=info&show=1&uid='.$victim_row['unique_id'].'&id='.$victim_row['id'].'">'.$victim.'</a></td>
						<td align="center" class="gear_preview">'.$range.'</td>
						<td align="center" class="gear_preview"><a href="index.php?view=search&type=playerinv&search='.$weapon.'">'.$weapon.'</a></td>
						</tr>'.$tablerows;
				}
			}
		}
	}
	else
	{
		echo "<div id='page-heading'><h2>Log file not found.</h2></div>";
	}

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
						<div onclick="var loc = window.location.href; loc = loc.replace('&hide=0', ''); loc = loc.replace('&hide=1', ''); if (!document.getElementById('Hide').checked) { window.location.href = loc + '&hide=0'; } else { window.location.href = loc + '&hide=1'; }">
							<input id="Hide" name="Hide" type="checkbox" value="1" <?php echo $hide == "1" ? "checked" : "";?>>&nbsp;&nbsp;Hide hit messages</input>
						</div>
						<br />
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
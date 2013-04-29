<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "log") !== false))
{
	$pagetitle = "Edit Bans";
	$filter = "bans.txt";
	
	if (isset($_GET['filter'])) {
		$pagetitle = "Edit Filters";
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
				<div id="content-text-inner">
					<?php
						require_once('modules/file.php');

						if (isset($_GET['filter'])) {
							$filter = $_GET['filter'];
							if ($filter == "") {
								echo '<table width="600px">';

								foreach (glob($patharma."\\@dayzcc_config\\".$serverinstance."\\BattlEye\\*.txt") as $filepath) {
									$filename = substr($filepath, strrpos($filepath, "\\") + 1);
									
									if (file_exists($filepath) && !(substr(strtolower($filename), -strlen("bans.txt")) == "bans.txt")) {
										echo '<tr><td width="50%"><a href="index.php?view=battleye&filter='.$filename.'">'.$filename.'</a></td>
											<td width="10%"><strong><a href="index.php?view=battleye&filter='.$filename.'">Edit</a></strong></td>
											<td width="15%">'.(formatBytes(filesize($filepath))).'</td>
											<td width="25%">'.(date ("Y-M-d H:i:s", filemtime($filepath))).'</td></tr>';
									}
								}
								
								echo "</table>";
							}
						}
						
						if ($filter != "") {
							if (isset($_POST['save'])) {
								$filepath = $patharma . "\\@dayzcc_config\\".$serverinstance."\\BattlEye\\".$filter;
								if (file_exists($filepath)) {
									$file = fopen($filepath, "w") or die('<div id="message-red">
										<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>
										<td class="red-left">Unable to open file: "'.$filepath.'"</td>
										<td class="red-right"><a class="red-green"><img src="images/forms/icon_close_red.gif" alt="" /></a></td>
										</tr></table></div>');
									fwrite($file, $_POST['save']) or die('<div id="message-red">
										<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>
										<td class="red-left">Unable to write to file: "'.$filepath.'"</td>
										<td class="red-right"><a class="red-green"><img src="images/forms/icon_close_red.gif" alt="" /></a></td>
										</tr></table></div>');
									fclose($file);

									echo '<div id="message-green">
										<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>
										<td class="green-left">Successfully saved changes to "'.$filter.'"</td>
										<td class="green-right"><a class="close-green"><img src="images/forms/icon_close_green.gif" alt="" /></a></td>
										</tr></table></div>';
								} else {
									echo '<div id="page-heading"><h2>File not found: '.$filepath.'<h2></div>';
								}
							}
							
							echo '<form method="post"><textarea name="save" rows="30" wrap="off" style="width: 100%; white-space: nowrap;">';

							$filepath = $patharma."\\@dayzcc_config\\".$serverinstance."\\BattlEye\\".$filter;
							
							if (file_exists($filepath) ) {
								echo file_get_contents($filepath);
							}

							echo '</textarea><br /><br /><div align="right"><input type="submit" class="submit" /></div></form>';
						}
					?>
				</div>
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

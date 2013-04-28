<?php

// This script was written by SilverShot and overhauled by Crosire.

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "control") !== false))
{
	if (isset($_GET['step'])) {
		$step = $_GET['step'];
	} else {
		$step = 1;
	}

	if ($step == 4) {
		$pagetitle = "Restore Database :: Complete";
	} else {
		$pagetitle = "Restore Database :: Step ".$step;
	}
	
	function splitSQL($file, $delimiter = ';') {
		global $dbname;
		set_time_limit(0);
		mysql_select_db($dbname) or die (mysql_error());

		if (is_file($file) === true) {
			$file = fopen($file, 'r');
			if (is_resource($file) === true) {
				$query = array();
				while (feof($file) === false) {
					$query[] = fgets($file);
					if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
						$query = trim(implode('', $query));
						if (mysql_query($query) === false) {
							echo '<strong>Error:</strong> '.(mysql_error()).'<br />Query: '.(substr($query, 0, 150)).'<br />';
						}
						while (ob_get_level() > 0) {
							ob_end_flush();
						}
						flush();
					}
					if (is_string($query) === true) {
						$query = array();
					}
				}
				return fclose($file);
			}
		}
		return false;
	}

	?>
	
	<div id="page-heading">
		<title><?php echo "Restore Database - ".$sitename; ?></title>
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
						<?php
							require_once('modules/file.php');

							$settingspath = $patharma."/@dayzcc_config/".$serverinstance."/settings.xml";
							
							if (file_exists($settingspath)) {
								// Thanks to ChemicalBliss for the code to find the Database backup folder path.
								$xml = file_get_contents($settingspath, true);
								$xml = mb_convert_encoding($xml, 'UTF-8', 'UCS-2LE');
								preg_match_all("#\<section id\=\"Backup\"\>(.+)\<setting id\=\"Path\"\>(.+?)\<\/setting\>#is", $xml, $matches);
								$backupfolder = $matches[2][0];

								switch ($step)
								{
									case 1:
										$backups = glob($backupfolder."\\*.sql", GLOB_NOSORT);
										array_multisort(array_map('filemtime', $backups), SORT_NUMERIC, SORT_DESC, $backups);
										
										if (count($backups) > 0) {
											echo 'Select the database dump to overwrite the current database with:<br /><br />
												<table width="600px">';

											foreach ($backups as $file) {
												$filepath = $file;
												$file = substr($file, strrpos($file, "\\") + 1);
												echo '<tr><td width="50%">
													<a href="index.php?view=dbrestore&step=2&file='.$file.'">'.$file.'</a>
													</td><td width="15%">'.(formatBytes(filesize($filepath))).'</td><td width="35%">'.(date ("Y-M-d H:i:s", filemtime($filepath))).'</td></tr>';
											}
											
											echo "</table>";
										} else {
											echo '<div id="page-heading"><h2>No database dumps found in "'.$backupfolder.'".</h2></div>';
										}
										break;
									case 2:
										if (isset($_GET['file'])) {
											$file = $backupfolder."\\".$_GET['file'];
											
											echo '<script type="text/javascript">if (confirm(\'Are you sure you want to wipe the complete database? This cannot be undone! If the restore fails you may be unable to access the dashboard!\nDatabase gets restored from: '.(str_replace("\\", "\\\\", $file)).'\')) { window.location = \'index.php?view=dbrestore&step=3&file='.$_GET['file'].'\'; } else { window.location = \'index.php?view=manage\'; }</script>';
										} else {
											echo '<div id="page-heading"><h2>No database backup dump specified.</h2></div>';
										}
										break;
									case 3:
										if (isset($_GET['file'])) {
											$file = $backupfolder."\\".$_GET['file'];
											
											if (file_exists($file)) {
												/*echo 'Dropping database "'.$dbname.'" ...<br />';
												mysql_query("DROP DATABASE `{$dbname}`") or die(mysql_error());
												echo 'Database dropped!<br /><br />Creating new database "'.$dbname.'" ...<br />';
												mysql_query("CREATE DATABASE `{$dbname}`") or die(mysql_error());
												echo 'Database created!<br /><br />';*/
												echo 'Restoring database from file ...<br /><br />';
												splitSQL($file);
												echo '<br />Process completed!<br /><br />
													<script type="text/javascript">setTimeout(function() { window.location = "index.php?logout"; }, 5000);</script>';
											} else {
												echo '<div id="page-heading"><h2>File not found: '.$file.'</h2></div>';
											}
										} else {
											echo '<div id="page-heading"><h2>No database backup dump specified.</h2></div>';
										}
										break;
								}
							} else {
								echo '<div id="page-heading"><h2>File not found: '.$settingspath.'</h2></div>';
							}
						?>
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
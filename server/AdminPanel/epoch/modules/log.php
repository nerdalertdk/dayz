<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "log") !== false))
{
	$pagetitle = "Server Logs";
	$type = "server";

	if (isset($_GET['type']))
	{
		$type = $_GET['type'];
		
		switch($_GET['type'])
		{
			case "battleye":
				$pagetitle = "BattlEye Logs";
				break;
			case "server":
				$pagetitle = "Server Logs";
				break;
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
				<div id="content-text-inner">
					<?php
						require_once('modules/file.php');

						if (!isset($_GET['display']) && !isset($_GET['download']) && !isset($_GET['delete'])) {
							switch ($type)
							{
								case "battleye":
									$files = glob($patharma."\\@dayzcc_config\\".$serverinstance."\\BattlEye\\*.log");
									if (count($files) > 0) {
										echo '<table width="600px">';
										foreach ($files as $filepath) {
											$filename = substr($filepath, strrpos($filepath, "\\") + 1);
											
											if (file_exists($filepath)) {
												echo '<tr><td width="30%"><a href="index.php?view=log&type=battleye&display='.$filename.'">'.$filename.'</a></td>
													<td width="10%"><center><strong><a href="index.php?view=log&type=battleye&display='.$filename.'">View</a></strong></center></td>
													<td width="10%"><center><strong><a href="index.php?view=log&type=battleye&download='.$filename.'">Download</a></strong></center></td>
													<td width="10%"><center><strong><a href="index.php?view=log&type=battleye&delete='.$filename.'">Delete</a></strong></center></td>
													<td width="15%">'.(formatBytes(filesize($filepath))).'</td>
													<td width="25%">'.date("Y-M-d H:i:s", filemtime($filepath)).'</td></tr>';
											}
										}
										echo "</table>";
									} else {
										echo '<div id="page-heading"><h2>No BattlEye log files found.</h2></div>';
									}
									break;
								case "server":
									$files = glob($patharma."\\@dayzcc_config\\".$serverinstance."\\*.{LOG,log,RPT,rpt}", GLOB_BRACE);
									if (count($files) > 0) {
										echo '<table width="600px">';
										foreach ($files as $filepath) {
											$filename = substr($filepath, strrpos($filepath, "\\") + 1);
											
											if (file_exists($filepath)) {
												echo '<tr><td width="30%"><a href="index.php?view=log&type=server&display='.$filename.'">'.$filename.'</a></td>
													<td width="10%"><center><strong><a href="index.php?view=log&type=server&display='.$filename.'">View</a></strong></center></td>
													<td width="10%"><center><strong><a href="index.php?view=log&type=server&download='.$filename.'">Download</a></strong></center></td>
													<td width="10%"><center><strong><a href="index.php?view=log&type=server&delete='.$filename.'">Delete</a></strong></center></td>
													<td width="15%">'.(formatBytes(filesize($filepath))).'</td>
													<td width="25%">'.date("Y-M-d H:i:s", filemtime($filepath)).'</td></tr>';
											}
										}
										echo "</table>";
									} else {
										echo '<div id="page-heading"><h2>No server log files found.</h2></div>';
									}
									break;
							}
						} else {
							if (isset($_GET['display'])) {
								$file = $_GET['display'];
								if ($file != "") {
									$path_parts = pathinfo($file);
									$file = $path_parts['basename'];
									
									if (substr(strtolower($file), -strlen(".log")) === ".log" || substr(strtolower($file), -strlen(".log")) === ".rpt") {
										$filepath = $patharma."\\@dayzcc_config\\".$serverinstance."\\".($_GET['type'] == "battleye" ? "BattlEye\\" : "").$file;
										
										if (file_exists($filepath)) {
											echo '<textarea name="say" rows="30" wrap="off" style="width: 99%; white-space: nowrap;" readonly>'.(last_lines($filepath, 100)).'</textarea>';
										} else {
											echo '<div id="page-heading"><h2>File not found: '.$filepath.'<h2></div>';
										}
									} else {
										echo '<div id="page-heading"><h2>Invalid file specified.<h2></div>';
									}
								} else {
									echo '<div id="page-heading"><h2>Invalid file specified.<h2></div>';
								}
							} else if (isset($_GET['download'])) {
								$file = $_GET['download'];
								if ($file != "") {
									echo '<script type="text/javascript">window.open("modules/lib/download.php?file='.$file.($_GET['type'] == "battleye" ? "&battleye" : "").'"); window.location = "index.php?view=log&type='.$_GET['type'].'";</script>';
								} else {
									echo '<div id="page-heading"><h2>Invalid file specified.<h2></div>';
								}
							} else if (isset($_GET['delete'])) {
								$file = $_GET['delete'];
								if ($file != "") {
									$path_parts = pathinfo($file);
									$file = $path_parts['basename'];
									$filepath = $patharma."\\@dayzcc_config\\".$serverinstance."\\".($_GET['type'] == "battleye" ? "BattlEye\\" : "").$file;

									if (file_exists($filepath) && is_file($filepath)) {
										if (isset($_GET['confirm'])) {
											unlink($filepath);
											echo '<script type="text/javascript">window.location = "index.php?view=log&type='.$_GET['type'].'";</script>';
										} else {
											echo '<script type="text/javascript">if (confirm("Do you really want to delete \"'.$_GET['delete'].'\"?")) { window.location = "index.php?view=log&type='.$_GET['type'].'&delete='.$_GET['delete'].'&confirm"; } else { window.location = "index.php?view=log&type='.$_GET['type'].'"; }</script>';
										}
									} else {
										echo '<div id="page-heading"><h2>File not found: '.$filepath.'<h2></div>';
									}
								} else {
									echo '<div id="page-heading"><h2>Invalid file specified.<h2></div>';
								}
							}
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

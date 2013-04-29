<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "control") !== false))
{
	$pagetitle = "Server Control";

	if (isset($_GET['action'])) {
		switch($_GET['action']) {
			case 0:
				exec($patharma."\\@dayzcc_config\\".$serverinstance."\\scripts\\restart.bat");
				mysql_query("INSERT INTO `log_tool`(`action`, `user`, `timestamp`) VALUES ('START SERVER','{$_SESSION['login']}', NOW())");
				sleep(6);
				break;
			case 1:
				exec('taskkill /IM "'.$exeserver.'"');
				mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('STOP SERVER', '{$_SESSION['login']}', NOW())");
				sleep(3);
				break;
			case 2:
				exec('taskkill /IM "'.$exeserver.'" /F 2>&1', $output);
				mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('KILLED SERVER', '{$_SESSION['login']}', NOW())");
				sleep(3);
				$outmessage = implode('&nbsp;', $output);
				break;
			case 3:
				rcon($serverip, $serverport, $rconpassword, "#restart");
				mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('RESTART SERVER', '{$_SESSION['login']}', NOW())");
				sleep(1);
				break;
			default:
				sleep(1);
		}
		
		echo '<script type="text/javascript">$.unblockUI</script>';
	}

	$tasklist = exec('tasklist /FI "IMAGENAME eq '.$exeserver.'" /FO CSV');
	$tasklist = explode(",", strtolower($tasklist));
	$tasklist = str_replace('"', "", $tasklist[0]);
	if ($tasklist == strtolower($exeserver)) {
		$serverrunning = true;
	} else {
		$serverrunning = false;
	}
	
	?>
	
	<script type="text/javascript">
		function cblockUI() { $.blockUI({ message: $('#blockMessage') }); }
	</script>
	
	<div id="blockMessage" style="display: none;">
		<h1><img src="images/forms/spinner.gif" alt="" /> Waiting ...</h1>
	</div>

	<div id="page-heading">
		<?php
			echo "<title>".$pagetitle." - ".$sitename."</title>";
			echo "<h1>".$pagetitle."</h1>";
		?>
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
					<?php if ($serverrunning) { ?>
						<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="green-left">Server is running.</td>
							<td class="green-right"><a class="close-green"><img src="images/forms/icon_close_green.gif" alt="" /></a></td>
						</tr>
						</table>
						</div>
						<div id="step-holder">	
							<div class="step-no"><a href="index.php?view=control&action=3"><img src="images/icons/start.png" alt="Restart" /></a></div>
							<div class="step-dark-left"><a href="index.php?view=control&action=3">Restart</a></div>
							<div class="step-dark-right">&nbsp;</div>
							<div class="step-no"><a href="index.php?view=control&action=1" onclick="cblockUI();"><img src="images/icons/stop.png" alt="Stop" /></a></div>
							<div class="step-dark-left"><a href="index.php?view=control&action=1" onclick="cblockUI();">Stop</a></div>
							<div class="step-dark-right">&nbsp;</div>
							<div class="step-no"><a href="index.php?view=control&action=2" onclick="cblockUI();"><img src="images/icons/stop.png" alt="Kill" /></a></div>
							<div class="step-dark-left"><a href="index.php?view=control&action=2" onclick="cblockUI();">Kill Process</a></div>
							<div class="step-dark-round">&nbsp;</div>
							<div class="clear"></div>
						</div>
					<?php } else { ?>
						<div id="message-red">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="red-left">Server is stopped.<?php if (isset($outmessage)) { echo " (".$outmessage.")"; } ?></td>
								<td class="red-right"><a class="close-red"><img src="images/forms/icon_close_red.gif" alt="" /></a></td>
							</tr>
							</table>
						</div>
						<div id="step-holder">	
							<div class="step-no"><a href="index.php?view=control&action=0" onclick="cblockUI();"><img src="images/icons/start.png" alt="Start" /></a></div>
							<div class="step-dark-left"><a href="index.php?view=control&action=0" onclick="cblockUI();">Start</a></div>
							<div class="step-dark-right">&nbsp;</div>
							<div class="step-no-off"><img src="images/icons/stop.png" alt="Stop" /></div>
							<div class="step-light-left">Stop</div>
							<div class="step-light-round">&nbsp;</div>
							<div class="clear"></div>
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
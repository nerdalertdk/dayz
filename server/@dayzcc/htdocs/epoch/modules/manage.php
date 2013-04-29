<?php

// This page was written by SilverShot and completly overhauled by Crosire

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "manage") !== false))
{
	$pagetitle = "Manage Server";
	
	$status = exec('tasklist /FI "IMAGENAME eq '.$exeserver.'" /FO CSV');
	$status = str_replace('"', "", explode(",", strtolower($status))[0]);
	$serverrunning = false; if ($status == strtolower($exeserver)) { $serverrunning = true; }
	
	?>
	
	<div id="page-heading">
		<title><?php echo $pagetitle." - ".$sitename; ?></title>
		<h1><?php echo $pagetitle; ?></h1>
	</div>

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
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
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top" width="0%">
								<!--<?php if (!$serverrunning) { ?>
									<div align="center" id="message-red">
										<table border="0" width="300px" cellpadding="0" cellspacing="0">
											<tr>
												<td class="red-left">Manage Vehicles</td>
												<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
											</tr>
										</table>
									</div>
									<br />
									<div align="center" id="message-red">
										<table border="0" width="300px" cellpadding="0" cellspacing="0">
											<tr>
												<td class="red-left"><a href="javascript:if (confirm('Do you really want to spawn new vehicles?')) { window.location = 'index.php?view=actions&manage=vehicles&action=0'; }">Spawn vehicles</a></td>
												<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
											</tr>
											<tr>
												<td class="red-left"><a href="javascript:if (confirm('Do you really want to fix all vehicles?')) { window.location = 'index.php?view=actions&manage=vehicles&action=3'; }">Fix and refuel all vehicles</a></td>
												<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
											</tr>
											<tr>
												<td class="red-left"><a href="javascript:if (confirm('Do you really want to respawn all vehicles?')) { window.location = 'index.php?view=actions&manage=vehicles&action=2'; }">Respawn all vehicles</a></td>
												<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
											</tr>
											<tr>
												<td class="red-left"><a href="javascript:if (confirm('Do you really want to delete all vehicles?')) { window.location = 'index.php?view=actions&manage=vehicles&action=1'; }">Delete all vehicles</a></td>
												<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
											</tr>
										</table>
									</div>
									<br />
									<br />
								<?php } ?>
								<div align="center" id="message-red">
									<table border="0" width="300px"cellpadding="0" cellspacing="0">
										<tr>
											<td class="red-left">Manage Players</td>
											<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
										</tr>
									</table>
								</div>
								<br />
								<div align="center" id="message-red">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="red-left"><a href="javascript:if (confirm('Do you really want to delete all dead players?')) { window.location = 'index.php?view=actions&manage=players&action=0'; }">Delete dead players</a></td>
											<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="red-left"><a href="javascript:if (confirm('Do you really want to delete all players?')) { window.location = 'index.php?view=actions&manage=players&action=1'; }">Delete all players</a></td>
											<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="red-left"><a href="index.php?view=table&show=<?php echo ($serverrunning ? "0" : "1"); ?>">Show <?php echo ($serverrunning ? "online" : "alive"); ?> players</a></td>
											<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="red-left"><a href="index.php?view=check">Check items</a></td>
											<td class="red-right"><img src="images/forms/icon_red.gif" alt="" /></td>
										</tr>
									</table>
								</div>
							</td>-->
							<td width="1%">&nbsp;</td>
							<td valign="top" width="34%">
							<!--	<?php if (!$serverrunning) { ?>
									<div align="center" id="message-green">
										<table border="0" width="300px"cellpadding="0" cellspacing="0">
											<tr>
												<td class="green-left">Manage Database</td>
												<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
											</tr>
										</table>
									</div>
									<br />
									<div align="center" id="message-green">
										<table border="0" width="300px" cellpadding="0" cellspacing="0">
											<tr>
												<td class="green-left"><a href="javascript:if (confirm('This will delete all dead and invalid players and clears the database log. Do you want to continue?')) { window.location = 'index.php?view=actions&manage=database&action=0'; }">Cleanup Database</a></td>
												<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
											</tr>
											<tr>
												<td class="green-left"><a href="index.php?view=actions&manage=database&action=1">Restore Database</a></td>
												<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
											</tr>
										</table>
									</div>
									<br />
									<br />
								<?php } ?>
								<div align="center" id="message-green">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="green-left">Manage Server</td>
											<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
										</tr>
									</table>
								</div>
								<br />
								<div align="center" id="message-green">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="green-left"><a href="index.php?view=control"><?php echo ($serverrunning ? "Stop" : "Start"); ?> Server</a></td>
											<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
										</tr>
										<?php if (strpos($_SESSION['user_permissions'], "whitelist") !== false) { ?>
											<tr>
												<td class="green-left"><a href="index.php?view=whitelist">Edit Whitelist</a></td>
												<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
											</tr>
										<?php } ?>
									</table>
								</div>
								<br />
								<br />-->
								<div align="center" id="message-green">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="green-left">Manage BattlEye</td>
											<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
										</tr>
									</table>
								</div>
								<br />
								<div align="center" id="message-green">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="green-left"><a href="index.php?view=battleye">Edit Bans</a></td>
											<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="green-left"><a href="index.php?view=battleye&filter">Edit Filters</a></td>
											<td class="green-right"><img src="images/forms/icon_green.gif" alt="" /></td>
										</tr>
									</table>
								</div>
							</td>
							<td width="1%">&nbsp;</td>
							<td valign="top" width="34%">
								<div align="center" id="message-blue">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="blue-left">Server Logs</td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
									</table>
								</div>
								<br />
								<div align="center" id="message-blue">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=server">View Logs</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=server&display=arma2oaserver_<?php echo $serverinstance; ?>.rpt">RPT Server Log</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=server&display=hiveext.log">Hive Extension Log</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
									</table>
								</div>
								<br/>
								<br/>
								<div align="center" id="message-blue">
									<table border="0" width="300px" cellpadding="0" cellspacing="0">
										<tr>
											<td class="blue-left">BattlEye Logs</td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
									</table>
								</div>
								<br />
								<div align="center" id="message-blue">
									<table border="0" width="300px" cellpadding="0" cellspacing="10">
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=battleye">View Logs</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=battleye&display=createvehicle.log">createvehicle.log</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=battleye&display=mpeventhandler.log">mpeventhandler.log</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=battleye&display=remoteexec.log">remoteexec.log</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
										<tr>
											<td class="blue-left"><a href="index.php?view=log&type=battleye&display=scripts.log">scripts.log</a></td>
											<td class="blue-right"><img src="images/forms/icon_blue.gif" alt="" /></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
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
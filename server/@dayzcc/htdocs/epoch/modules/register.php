<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "user") !== false))
{
	$login = "Username";
	$password = "Password";
	$checked = array('manage' => true, 'log' => true, 'control' => true, 'table' => true, 'map' => true, 'tools' => true, 'feed' => true, 'user' => true, 'whitelist' => true);
	
	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$login = mysql_fetch_assoc(mysql_query("SELECT `login` FROM `users` WHERE `id` = '{$id}' LIMIT 1"))['login'];
		$checked = array('manage' => false, 'log' => false, 'control' => false, 'table' => false, 'map' => false, 'tools' => false, 'feed' => false, 'user' => false, 'whitelist' => false);
		$res = mysql_fetch_assoc(mysql_query("SELECT `permissions` FROM `users` WHERE `id` = '{$id}' LIMIT 1"));
		$permissions = $res['permissions'];
		
		foreach (explode(',', str_replace(" ", "", $permissions)) as $check) {
			$checked[$check] = true;
		}
	}

	?>
		
	<div id="page-heading">
		<h1>Registration</h1>
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
						<form action="index.php?view=users<?php echo (isset($_GET['edit']) ? '&edit='.$id : ''); ?>" method="post">
							<h2>Enter login name and password for the user:</h2>
							<input type="text" name="login" value="<?php echo $login; ?>" <?php echo (isset($_GET['edit']) ? 'readonly' : ''); ?> maxlength="50" onblur="if (this.value == '') { this.value = 'Username'; }" onfocus="if (this.value == 'Username') { this.value = ''; }" style="display: inline; padding-top: 6px; padding-bottom: 6px; padding-left: 6px; width: 200px;" />
							<input type="text" name="password" value="<?php echo $password; ?>" maxlength="32" onblur="if (this.value == '') { this.value = 'Password'; }" onfocus="if (this.value == 'Password') { this.value = ''; }" style="display: inline; padding-top: 6px; padding-bottom: 6px; padding-left: 6px; width: 200px;" />
							<br /><br />
							<h2>Select the pages the user should be allowed to view:</h2>
							<div style="border: 2px solid #ccc; width: 403px; height: 100px; padding-top: 6px; padding-left: 6px; overflow-y: scroll;">
								<?php
									$permissions = array( array('manage', '"Manage Overview"'), array('log', '"Server and BattlEye Logs"'), array('control', '"Server Control", "Logs", "BattlEye", "Bans"'), array('table', '"Playerlist", "Vehiclelist", "Deployablelist", "Check items", "Search"'), array('map', '"Playermap", "Vehiclemap", "Deployablemap", "Wreckmap"'), array('tools', '"Vehicle import tools"'), array('feed', '"Killfeed"'), array('whitelist', '"Whitelist"'), array('user', '"Accounts"') );
									for ($i = 0; $i < count($permissions); $i++) {
										echo '<input type="checkbox" name="'.$permissions[$i][0].'" '.($checked[$permissions[$i][0]] ? 'checked' : '').' />&nbsp;&nbsp;&nbsp;'.$permissions[$i][1].'<br />';
									}
								?>
							</div>
							<br />
							<input type="submit" class="submit" />
						</form>
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
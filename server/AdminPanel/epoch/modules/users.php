<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "user") !== false))
{
	$pagetitle = "Manage users";
	$delresult = '';
	
	function GenerateSalt($n = 3) {
		$key = '';
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
		$counter = strlen($pattern)-1;
		for ($i = 0; $i < $n; $i++) { $key .= $pattern{rand(0, $counter)}; }
		return $key;
	}
	
	if (isset($_POST['login']) && isset($_POST['password']))
	{
		if (isset($_GET['edit'])) {
			$type = "edit";
			$id = $_GET['edit'];
		} else {
			$type = "register";
		}
		
		$login = mysql_real_escape_string($_POST['login']);
		$password = mysql_real_escape_string($_POST['password']);
		$permissions = '';
		$error = false;
		$errortext = '';
		
		if (strlen($login) < 2) {
			$error = true;
			$errortext .= 'Login must be at least 2 characters. ';
		}
		if (strlen($password) < 6) {
			$error = true;
			$errortext .= 'Password must be at least 6 characters. ';
		}
		
		if (mysql_num_rows(mysql_query("SELECT `id` FROM `users` WHERE `login` = '{$login}' LIMIT 1")) == 1 && $type != "edit") {
			$error = true;
			$errortext .= 'Login already used. ';
		}

		foreach (array('manage', 'log', 'control', 'table', 'map', 'tools', 'feed', 'user', 'whitelist') as $permission) {
			if (isset($_POST[$permission])) {
				if ($_POST[$permission] == "on") { $permissions .= $permission.", "; }
			}
		}
		$permissions = mysql_real_escape_string(substr($permissions, 0, -2));

		if (!$error) {
			$salt = GenerateSalt();
			$hash = md5(md5($password).$salt);
			
			if ($type == "edit") {
				mysql_query("UPDATE `users` SET `login` = '{$login}', ".($password != "Password" ? "`password` = '{$hash}', `salt` = '{$salt}', " : "")."`permissions` = '{$permissions}' WHERE `id` = '{$id}'") or die(mysql_error());
				mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('EDITED USER {$login}', '{$_SESSION['login']}', NOW())");
			} else {
				mysql_query("INSERT INTO `users` SET `login` = '{$login}', `password` = '{$hash}', `salt` = '{$salt}', `permissions` = '{$permissions}'") or die(mysql_error());
				mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('REGISTERED USER {$login}', '{$_SESSION['login']}', NOW())");
			}

			$delresult = '<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>
				<td class="green-left">User "'.$login.'" was '.($type == "edit" ? 'edited' : 'added').'!</td>
				<td class="green-right"><a class="close-green"><img src="images/forms/icon_close_green.gif" alt="" /></a></td>
				</tr></table></div>';
		} else {
			$delresult = '<div id="message-red">
				<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>
				<td class="red-left">Error: '.$errortext.'</td>
				<td class="red-right"><a class="close-red"><img src="images/forms/icon_close_red.gif" alt="" /></a></td>
				</tr></table></div>';
		}
	}
	else if (isset($_POST["user"]))
	{
		$deluser = $_POST["user"];
		$delresult = '<div id="message-green"><table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td class="green-left">User ';

		for ($i = 0; $i < count($deluser); $i++) {
			mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELETED USER: ".$deluser[$i]."', '{$_SESSION['login']}', NOW())");
			mysql_query("DELETE FROM `users` WHERE `id` = '".$deluser[$i]."'") or die(mysql_error());
			
			$delresult .= $deluser[$i].", ";
		}
		
		$delresult = substr($delresult, 0, -2).' removed!</td><td class="green-right"><a class="close-green"><img src="images/forms/icon_close_green.gif" alt="" /></a></td></tr></table></div>';
	}
	else if (isset($_GET['register']))
	{
		?>
			<div id="Popup" class="modalPopup" style="display: none;">
				<a id="closebutton" style="float: right;" href="#" onclick="HideModalPopup('Popup'); return false;"><img src="images/forms/action_delete.gif" title="Close" alt="Close" /></a><br />
				<?php include_once('modules/register.php'); ?>
			</div>
			<script type="text/javascript">ShowModalPopup('Popup');</script>
		<?php
	}
	else if (isset($_GET['edit']))
	{
		?>
			<div id="Popup" class="modalPopup" style="display: none;">
				<a id="closebutton" style="float: right;" href="#" onclick="HideModalPopup('Popup'); return false;"><img src="images/forms/action_delete.gif" title="Close" alt="Close" /></a><br />
				<?php include_once('modules/register.php'); ?>
			</div>
			<script type="text/javascript">ShowModalPopup('Popup');</script>
		<?php
	}
	else
	{
		mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('MANAGED USERS', '{$_SESSION['login']}', NOW())");
	}

	$res = mysql_query("SELECT * FROM `users` ORDER BY `id` ASC") or die(mysql_error());
	$number = mysql_num_rows($res);
	$users = '';

	while ($row = mysql_fetch_array($res)) {
		$users .= '<tr>
			<td align="center" style="height: 47px;"><input name="user[]" value="'.$row['id'].'" type="checkbox"/></td>
			<td align="center" style="height: 47px;"><a href="index.php?view=users&edit='.$row['id'].'"><img src="images/forms/action_edit.gif" title="Edit" alt="Edit" /></a></td>
			<td align="center" style="height: 47px;">'.$row['id'].'</td>
			<td align="center" style="height: 47px;">'.$row['login'].'</td>
			<td align="center" style="height: 47px;">'.$row['permissions'].'</td>
			<td align="center" style="height: 47px;">'.$row['lastlogin'].'</td></tr>';
	}

	?>

	<script type="text/javascript">
		function post() {
			document.del.submit();
		}
	</script>
	
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
					<div>
						<img src="images/forms/icon_plus.gif" width="21" height="21" title="Add new user" alt="" style="vertical-align: middle" />
						<span style="vertical-align: middle"><a href="index.php?view=users&register"><b>Add new user</b></a></span>
					</div>
					<br />
					<br />
					<?php echo $delresult; ?>
					<div id="table-content">
						<form action="index.php?view=users" method="post">
							<table id="product-table" border="1" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<th class="product-table-header" style="width: 5%"><a>Delete</a></th>
									<th class="product-table-header" style="width: 5%"><a>Edit</a></th>
									<th class="product-table-header" style="width: 5%"><a>ID</a></th>
									<th class="product-table-header" style="width: 25%"><a>Username</a></th>
									<th class="product-table-header" style="width: 40%"><a>Permissions</a></th>
									<th class="product-table-header" style="width: 20%"><a>Last access</a></th>
								</tr>
								<?php echo $users; ?>
							</table>
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
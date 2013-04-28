<?php

mysql_connect($dbhost.':'.$dbport, $dbuser, $dbpass) or die (mysql_error());
mysql_select_db($dbname) or die (mysql_error());

$coluser = "000000";
$colpass = "000000";
$message = "";

if (isset($_SESSION['user_id']))
{
	header('Location: index.php');
	exit;
}

if (!empty($_POST))
{
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	$sql = mysql_query("SELECT `salt` FROM `users` WHERE `login` = '{$login}' LIMIT 1") or die(mysql_error());

	if (mysql_num_rows($sql) == 1)
	{
		$row = mysql_fetch_assoc($sql);
		$salt = $row['salt'];
		$password = md5(md5($_POST['password']).$salt);
		$sql = mysql_query("SELECT `id` FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}' LIMIT 1") or die(mysql_error());

		if (mysql_num_rows($sql) == 1)
		{
			$row = mysql_fetch_assoc($sql);
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['login'] = $login;
			$time = 86400;
			
			$sql = mysql_query("SELECT `permissions` FROM `users` WHERE `login` = '{$login}' LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			$_SESSION['user_permissions'] = $row['permissions'];
			
			if (isset($_POST['remember']))
			{
				setcookie('login', $login, time() + $time, "/");
				setcookie('password', $password, time() + $time, "/");
			}

			mysql_query("UPDATE `users` SET `lastlogin` = NOW() WHERE `login` = '{$login}' LIMIT 1");
			mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('LOGIN', '{$login}', NOW())");
			
			header('Location: index.php');
			exit;
		}
		else
		{
			$colpass = "900122";
			$message = "Incorrect password!";
		}
	}
	else
	{
		$coluser = "900122";
		$message = "Incorrect username!";
	}
}

$instances = array();
foreach (glob("../../../@dayzcc_config/*") as $info) {
	if (is_dir($info)) { $instances[] = basename($info); }
}

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;" />
		<title>Login</title>
		<link rel="stylesheet" href="css/login.css" type="text/css" media="screen" title="default" />
	</head>
	<body id="login-bg"> 
		<div id="login-logo"></div>
		<form action="index.php" method="post">
			<div id="login-box">	
				<div id="login-inner">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th><font color="#<?php echo $coluser; ?>">Username</font></th>
							<td><input type="text" name="login" class="login-inp" /></td>
						</tr>
						<tr>
							<th><font color="#<?php echo $colpass; ?>">Password</font></th>
							<td><input type="password" name="password" value="" class="login-inp" /></td>
						</tr>
						<tr>
							<th><font color="#000000">Instance</font></th>
							<td><select name="instance" class="login-select">
								<?php
									foreach ($instances as $value) {
										echo '<option value="'.$value.'"'.(intval($value) == (isset($_GET["instance"]) ? $_GET["instance"] : 1) ? " selected" : "").'>'.$value.'</option>';
									}
								?>
							</select></td>
						</tr>
						<tr>
							<th></th>
							<td><input type="checkbox" name="remember" id="login-check" /><label for="login-check">Remember me</label></td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="login-submit" /></td>
						</tr>
						<tr>
							<th></th>
							<td><font color="#900122"><b><?php echo $message; ?><b></font></td>
						</tr>
					</table>
				</div>
			</div>
		</form>
	</body>
</html>
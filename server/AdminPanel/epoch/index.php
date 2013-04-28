<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include('config.php');

if (isset($_GET['logout']))
{
	/*if (mysql_connect($dbhost.':'.$dbport, $dbuser, $dbpass)) {
		mysql_select_db($dbname) or die (mysql_error());
		mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('LOGOUT', '{$_SESSION['login']}', NOW())");
	}*/

	if (isset($_SESSION['user_id'])) {
		unset($_SESSION['user_id']);
	}
	if (isset($_SESSION['user_permissions'])) {
		unset($_SESSION['user_permissions']);
	}
	if (isset($_SESSION['login'])) {
		unset($_SESSION['login']);
	}
		
	setcookie('login', '', 0, "/");
	setcookie('password', '', 0, "/");

	header('Location: index.php');
	exit;
}
else
{
	if (isset($_SESSION['user_id']))
	{
		mysql_connect($dbhost.':'.$dbport, $dbuser, $dbpass) or die (mysql_error());
		mysql_select_db($dbname) or die (mysql_error());

		include('modules/rcon.php');
		include('modules/header.php');

		if (isset($_GET["show"])) {
			$show = $_GET["show"];
		} else {
			$show = 0;
		}

		if (isset($_GET['view'])) {
			include('modules/'.$_GET["view"].'.php');
		} else {
			include('modules/dashboard.php');
		}

		include('modules/footer.php');
	}
	else
	{
		include('modules/login.php');
	}
}

?>
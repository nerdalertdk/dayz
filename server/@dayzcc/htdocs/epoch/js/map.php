<?php

session_start();
error_reporting (0);
chdir("..");
require_once("config.php");
require_once("modules/rcon.php");
require_once("modules/maps/markers.php");

mysql_connect($dbhost.':'.$dbport, $dbuser, $dbpass) or die;
mysql_select_db($dbname) or die;

$markers = array();
$callback = "";
$id = 0;

if (isset($_GET['id'])) {
	$tmp = intval($_GET['id']);
	if ($tmp >= 0 && $tmp <= 10) { $id = $tmp; }
}

if (isset($_GET['callback'])) {
	$callback = $_GET['callback'];
}

include('modules/maps/'.$id.'.php');

echo $callback.'('.(json_encode($markers)).')';

?>
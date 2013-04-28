<?php

// Multi instance support was written by Krunch
date_default_timezone_set("Europe/Copenhagen");
if (!isset($_SESSION['user_id']) || isset($_GET["instance"]))
{
	if (isset($_POST["instance"])) {
		$serverinstance = intval($_POST["instance"]);
	} else if (isset($_GET["instance"])) {
		if ($_GET["instance"] > 0) {
			$serverinstance = intval($_GET["instance"]);
		} else {
			$serverinstance = 1;
		}
	} else {
		$serverinstance = 1;
	}

	$_SESSION["serverinstance"] = $serverinstance;
	$_SESSION["patharma"] 		= $patharma = realpath(dirname(__FILE__) . "/../../..");
	
	$settings = simplexml_load_file($patharma."\\@dayzcc_config\\$serverinstance\\settings.xml");
	$hive = parse_ini_file($patharma."\\@dayzcc_config\\$serverinstance\\HiveExt.ini", true);
	
	$_SESSION["servermodlist"] 	= $servermodlist = (string)$settings->section[0]->setting[0];
	$_SESSION["serverworld"] 	= $serverworld = (string)$settings->section[0]->setting[2];
	$_SESSION["serverip"] 		= $serverip = (string)$settings->section[2]->setting[0];
	$_SESSION["serverport"] 	= $serverport = (string)$settings->section[2]->setting[1];
	$_SESSION["rconpassword"] 	= $rconpassword = (string)$settings->section[2]->setting[2];
	$_SESSION["dbhost"] 		= $dbhost = $hive["Database"]["Host"];
	$_SESSION["dbport"] 		= $dbport = $hive["Database"]["Port"];
	$_SESSION["dbuser"] 		= $dbuser = $hive["Database"]["Username"];
	$_SESSION["dbpass"] 		= $dbpass = $hive["Database"]["Password"];
	$_SESSION["dbname"] 		= $dbname = $hive["Database"]["Database"];

}
else
{
	$serverinstance = $_SESSION["serverinstance"];
	$patharma 		= $_SESSION["patharma"];
	$servermodlist 	= $_SESSION["servermodlist"];
	$serverip 		= $_SESSION["serverip"];
	$serverport 	= $_SESSION["serverport"];
	$rconpassword 	= $_SESSION["rconpassword"];
	$dbhost 		= $_SESSION["dbhost"];
	$dbport 		= $_SESSION["dbport"];
	$dbuser 		= $_SESSION["dbuser"];
	$dbpass 		= $_SESSION["dbpass"];
	$dbname 		= $_SESSION["dbname"];
	$serverworld 	= $_SESSION["serverworld"];
}

$sitename	= "DayZ Controlcenter (Instance ".$serverinstance.")";
$exeserver	= "arma2oaserver_".$serverinstance.".exe";
$pathserver	= $patharma."\\@dayzcc_config\\".$serverinstance."\\".$exeserver;

?>
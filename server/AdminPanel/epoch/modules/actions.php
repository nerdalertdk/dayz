<?php

if (isset($_SESSION['user_id']))
{
	if (isset($_GET['manage']))
	{
		switch ($_GET['manage']) {
			case "vehicles":
				$action = "0";
				if (isset($_GET['action'])) { $action = $_GET['action']; }
				switch ($action) {
					case "0":
						mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('SPAWN VEHICLES', '{$_SESSION['login']}', NOW())");
						$worldid = 0;
						$res = mysql_query("SELECT `id` FROM `world` WHERE `name` = '$serverworld'");
						$worldid = intval(mysql_fetch_assoc($res)['id']);

						require_once('modules/lib/class.vehicles.php');
						$generation = new vehicle_generator(new mysqli($dbhost.':'.$dbport, $dbuser, $dbpass, $dbname));
						$generation->setDatabaseName($dbname);
						$generation->setInstanceID($serverinstance);
						$generation->setWorldID($worldid);
						$generation->execute();
						
						exit();
						break;
					case "1":
						mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELETE ALL VEHICLES', '{$_SESSION['login']}', NOW())");
						mysql_query("DELETE FROM `instance_vehicle`") or die(mysql_error());
						break;
					case "2":
						mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('RESPAWN ALL VEHICLES', '{$_SESSION['login']}', NOW())");
						$counter = 0;
						$query = mysql_query("SELECT * FROM `instance_vehicle`");
						
						while ($row = mysql_fetch_array($query)) {
							$counter++;
							
							$res = mysql_query("SELECT * FROM `world_vehicle` WHERE `id` = '".$row['world_vehicle_id']."'");
							$vehicle = mysql_fetch_array($res);

							mysql_query("UPDATE `instance_vehicle` SET `worldspace` = '".$vehicle['worldspace']."', `parts` = '[]', `fuel` = '100', `damage` = '0' WHERE `world_vehicle_id` = '".$row['world_vehicle_id']."'");
						}
						break;
					case "3":
						mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('FIX ALL VEHICLES', '{$_SESSION['login']}', NOW())");
						$res = mysql_query("SELECT * FROM `instance_vehicle`");

						while ($row = mysql_fetch_array($res)) {
							mysql_query("UPDATE `instance_vehicle` SET `parts` = '[]', `fuel` = '100', `damage` = '0' WHERE `id` = '".$row['id']."'");
						}
						break;
				}
				break;
			case "players":
				$action = "0";
				if (isset($_GET['action'])) { $action = $_GET['action']; }
				switch ($action) {
					case "0":
						mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELETE DEAD PLAYERS', '{$_SESSION['login']}', NOW())");
						mysql_query("DELETE FROM `survivor` WHERE `is_dead` = 1 AND `id` NOT IN (SELECT DISTINCT `owner_id` FROM `instance_deployable`)") or die(mysql_error());
						break;
					case "1":
						mysql_query("INSERT INTO `log_tool` (`action`, `user`, `timestamp`) VALUES ('DELETE ALL PLAYERS', '{$_SESSION['login']}', NOW())");
						mysql_query("DELETE FROM `survivor` WHERE `id` NOT IN (SELECT DISTINCT `owner_id` FROM `instance_deployable`)") or die(mysql_error());
						break;
				}
				break;
			case "database":
				$action = "0";
				if (isset($_GET['action'])) { $action = $_GET['action']; }
				switch ($action) {
					case "0":
						mysql_query("TRUNCATE TABLE `log_entry`") or die(mysql_error());
						mysql_query("DELETE FROM `survivor` WHERE `inventory` = '[[],[]]' AND `backpack` = '[\"\",[[],[]],[[],[]]]'  AND `id` NOT IN (SELECT DISTINCT `owner_id` FROM `instance_deployable`)") or die(mysql_error());
						mysql_query("DELETE FROM `survivor` WHERE `worldspace` = '[]' AND `id` NOT IN (SELECT DISTINCT `owner_id` FROM `instance_deployable`)") or die(mysql_error());
						mysql_query("DELETE FROM `survivor` WHERE `is_dead` = '1' AND `id` NOT IN (SELECT DISTINCT `owner_id` FROM `instance_deployable`)") or die(mysql_error());
						break;
					case "1":
						echo '<script type="text/javascript">window.location = "index.php?view=dbrestore";</script>';
						break;
				}
				break;
		}
		
		echo '<script type="text/javascript">window.location = "index.php?view=manage";</script>';
	}
	
	if (isset($_GET["kick"])) {
		$cmd = "kick ".$_GET["kick"];
		$answer = rcon($serverip, $serverport, $rconpassword, $cmd);
		
		echo '<script type="text/javascript">window.location = "index.php?view=table&show=0";</script>';
	}
	
	if (isset($_GET["ban"])) {
		$cmd = "ban ".$_GET["ban"]." -1 Ban from WebAdmin by ".$_SESSION['login'];
		$answer = rcon($serverip, $serverport, $rconpassword, $cmd);
		
		echo '<script type="text/javascript">window.location = "index.php?view=table&show=0";</script>';
	}
	
	if (isset($_POST["say"])){
		$id = "-1"; if (isset($_GET["id"])) { $id = $_GET["id"]; }
		$cmd = "Say ".$id." ".$_POST["say"];
		$answer = rcon($serverip, $serverport, $rconpassword, $cmd);
		
		echo '<script type="text/javascript">window.location = "index.php";</script>';
	}

	echo '<script type="text/javascript">window.location = "index.php";</script>';
}
else
{
	header('Location: index.php');
}

?>
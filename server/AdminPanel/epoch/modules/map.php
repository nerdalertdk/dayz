<?php

if (isset($_SESSION['user_id']) && (strpos($_SESSION['user_permissions'], "map") !== false))
{
	switch ($show) {
		case 0:
			$pagetitle = "Online player locations";
			break;
		case 1:
			$pagetitle = "Alive player locations";		
			break;
		case 2:
			$pagetitle = "Dead player locations";	
			break;
		case 3:
			$pagetitle = "All player locations";	
			break;
		case 4:
			$pagetitle = "Ingame vehicle locations";
			break;
		case 5:
			$pagetitle = "Deployable locations";
			break;
		case 6:
			$pagetitle = "Wreck and care package locations";	
			break;
		case 7:
			$pagetitle = "All locations";
			break;
		case 8:
			$pagetitle = "Zombie player locations";	
			break;
		case 10:
			$pagetitle = "Active players and Vehicles";	
			break;	
		default:
			$pagetitle = "Online player locations";
	};
	
	echo '<div id="page-heading"><title>'.$pagetitle.' - '.$sitename.'</title><h1>'.$pagetitle.'</h1></div>';

	include('modules/leaf.php');
}
else
{
	header('Location: index.php');
}

?>
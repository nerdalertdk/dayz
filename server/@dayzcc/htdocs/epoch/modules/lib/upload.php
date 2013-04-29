<?php

if (isset($_FILES['vehicles']['name'])) {
	$filename = basename($_FILES['vehicles']['name']);
	$fileext = substr($filename, strpos($filename, '.') + 1, strlen($filename) - 1);
	$filepath = "./../../vehicles.sqf";
	
	if ($fileext == "sqf") {
		if (move_uploaded_file($_FILES['vehicles']['tmp_name'], $filepath)) {
			echo '<script type="text/javascript">window.location = "../../index.php?view=tools&vehicle";</script>';
		}
	} else {
		echo '<script type="text/javascript">alert("The file extension \"'.$fileext.'\" is not supported."); window.location = "../../index.php?view=tools";</script>';
	}
} else if (isset($_FILES['buildings']['name'])) {
	$filename = basename($_FILES['buildings']['name']);
	$fileext = substr($filename, strpos($filename, '.') + 1, strlen($filename) - 1);
	$filepath = "./../../buildings.sqf"; 
	
	if ($fileext == "sqf") {
		if (move_uploaded_file($_FILES['buildings']['tmp_name'], $filepath)) {
			echo '<script type="text/javascript">window.location = "../../index.php?view=tools&building";</script>';
		}
	} else {
		echo '<script type="text/javascript">alert("The file extension \"'.$fileext.'\" is not supported."); window.location = "../../index.php?view=tools";</script>';
	}
}

?>
<?php

if (isset($_SESSION['user_id']))
{
	$debug = '';
	
	echo "<header><script src='js/invedit.js' type='text/javascript'></script><link rel='stylesheet' href='css/invedit.css' /></header>";
	include('modules/info/'.$show.'.php');
}
else
{
	header('Location: index.php');
}

?>
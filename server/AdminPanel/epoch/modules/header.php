<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;" />
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="default" />
		<link rel="stylesheet" href="css/nav.css" type="text/css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js" language="javascript" type="text/javascript"></script>
		<script src="js/popup.js" type="text/javascript"></script>
		<script src="js/blockui.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="page-top-outer">    
				<div id="time">
				<?php
					$ini = parse_ini_file($patharma."\\@dayzcc_config\\".$serverinstance."\\HiveExt.ini", true);
					$timeoffset = 0;

					if ($ini['Time']['Type'] == "Static") {
						$timeoffset = date('H') - $ini['Time']['Hour'];
					} else {
						$timeoffset = $ini['Time']['Offset'];
					}
					echo "Time ingame: ".date('H:i', time() + 3600 * $timeoffset);
				?>
				
				</div>
		<div id="page-top">
			<div id="logo"><a href="index.php"><img src="images/forms/dayz_epoch_logo.png" width="135px" height="100px" title="Instance <?php echo $serverinstance; ?>" alt="DayZ Controlcenter" /></a></div>
				<div id="search"><?php include('modules/searchbar.php'); ?></div>
			</div>
		</div>
		<?php include('modules/navbar.php'); ?>
		<div class="clear"></div>

		<div id="content-outer">
			<div id="content">
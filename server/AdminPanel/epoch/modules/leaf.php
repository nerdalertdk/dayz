<?php

if (isset($_SESSION['user_id']) && (strpos($_SESSION['user_permissions'], "map") !== false))
{
	?>

	<head>
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" type="text/css" />
		<link rel="stylesheet" href="css/map.css" type="text/css" />
		<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js" type="text/javascript"></script>
		<script src="js/map.js" type="text/javascript"></script>
		<script src="js/map/<?php echo $serverworld; ?>.js"></script>
	</head>
	
	<div id="map"></div>
	
	<script>
	InitMap();
	
	var Icon = L.Icon.extend({ options: { iconSize: [32, 37], iconAnchor: [16, 35] } });
	var Car = new Icon({ iconUrl: 'images/icons/Car.png' }),
		Bus = new Icon({ iconUrl: 'images/icons/Bus.png' }),
		ATV = new Icon({ iconUrl: 'images/icons/ATV.png' }),
		Armored = new Icon({ iconUrl: 'images/icons/armored.png' }),
		Bike = new Icon({ iconUrl: 'images/icons/Bike.png' }),
		Wreck = new Icon({ iconUrl: 'images/icons/Wreck.png' }),
		Crashsite = new Icon({ iconUrl: 'images/icons/Crashsite.png' }),
		Care = new Icon({ iconUrl: 'images/icons/Care.png' }),
		Farmvehicle = new Icon({ iconUrl: 'images/icons/Farmvehicle.png' }),
		Helicopter = new Icon({ iconUrl: 'images/icons/Helicopter.png' }),
		lBoat = new Icon({ iconUrl: 'images/icons/lBoat.png' }),
		mBoat = new Icon({ iconUrl: 'images/icons/mBoat.png' }),
		sBoat = new Icon({ iconUrl: 'images/icons/sBoat.png' }),
		Motorcycle = new Icon({ iconUrl: 'images/icons/Motorcycle.png' }),
		PBX = new Icon({ iconUrl: 'images/icons/PBX.png' }),
		Truck = new Icon({ iconUrl: 'images/icons/Truck.png' }),
		Plane = new Icon({ iconUrl: 'images/icons/Plane.png' }),
		C130 = new Icon({ iconUrl: 'images/icons/C130.png' }),
		MV22 = new Icon({ iconUrl: 'images/icons/MV22.png' }),
		Support = new Icon({ iconUrl: 'images/icons/Truck.png' }),
		Trap = new Icon({ iconUrl: 'images/icons/Trap.png' }),
		Wire = new Icon({ iconUrl: 'images/icons/Wire.png' }),
		Vault = new Icon({ iconUrl: 'images/icons/Vault.png' }),
		Tent = new Icon({ iconUrl: 'images/icons/Tent.png' }),
		Hedgehog = new Icon({ iconUrl: 'images/icons/Hedgehog.png' }),
		Sandbag = new Icon({ iconUrl: 'images/icons/Sandbag.png' }),
		Object = new Icon({ iconUrl: 'images/icons/Object.png' }),
		Player = new Icon({ iconUrl: 'images/icons/player.png' }),
		PlayerDead = new Icon({ iconUrl: 'images/icons/player_dead.png' });
	var trackPolyline = L.Polyline.extend({ options: { uid: -1 }, });
	var trackCircleMarker = L.CircleMarker.extend({ options: { uid: -1 }, });
	var mapMarker = L.Marker.extend({ options: { uid: -1 }, });

	map.on("mousemove", function (a) {
		$("#mapCoords").html(fromLatLngToGps(a.latlng));
	});
	
	var intervalId;
	var plotlayers = [];
	var tracklines = [];
	var tracklayers = [];
	var trackstartlayers = [];
	var trackendlayers = [];
	var autorefresh = false;
	
	if (<?php echo $show; ?> == 0 || <?php echo $show; ?> == 9) {
		autorefresh = true;
	}
	
	function autorefreshToggle() {
		if (autorefresh) {
			$('#mapRefresh').css('background-color', "#404040");
			$('#mapRefresh').css('background-color', "rgba(0, 0, 0, 0.5)");
			intervalId = setInterval(function() { getData(<?php echo $show; ?>); }, 5000);
		} else {
			$('#mapRefresh').css('background-color', "#ff0000");
			$('#mapRefresh').css('background-color', "rgba(255, 0, 0, 0.5)");
			clearInterval(intervalId);
		}
	}
	
	$('#map').append('<div id="mapCoords"><label>000 000</label></div>');
	$('#map').append('<div id="mapRefresh"><label>Auto refresh</label></div>');
	$('#mapRefresh').click(function() {
		autorefresh = !autorefresh;
		autorefreshToggle();
	});
	
	autorefreshToggle();	
	getData(<?php echo $show; ?>);
	</script>

<?php
}
else
{
	header('Location: index.php');
}

?>
<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "tools") !== false))
{
	$pagetitle = "Database Tools";
	
	if (isset($_GET['vehicle'])) { ?>

		<div id="vPopup" class="modalPopup" style="display: none;">
			<a id="closebutton" style="float: right;" href="#" onclick="HideModalPopup('vPopup'); return false;"><img src="images/forms/action_delete.gif" alt="" /></a><br />
			<?php include_once('modules/tools/parseVehicles.php'); ?>
		</div>
		<script type="text/javascript">ShowModalPopup('vPopup');</script>
		
	<?php } if (isset($_GET['building'])) { ?>

		<div id="bPopup" class="modalPopup" style="display: none;">
			<a id="closebutton" style="float: right;" href="#" onclick="HideModalPopup('bPopup'); return false;"><img src="images/forms/action_delete.gif" alt="" /></a><br />
			<?php include_once('modules/tools/parseBuildings.php'); ?>
		</div>
		<script type="text/javascript">ShowModalPopup('bPopup');</script>
		
	<?php } ?>

	<div id="page-heading">
		<title><?php echo $pagetitle." - ".$sitename; ?></title>
		<h1><?php echo $pagetitle; ?></h1>
	</div>
		
	<table id="content-table" border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th rowspan="3"><img src="images/forms/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
			<th class="corner-topleft"></th>
			<td class="border-top">&nbsp;</td>
			<th class="corner-topright"></th>
			<th rowspan="3"><img src="images/forms/side_shadowright.jpg" width="20" height="300" alt="" /></th>
		</tr>
		<tr>
			<td class="border-left"></td>
			<td>
				<div id="content-table-inner">
					<h3>This set of tools parses saved mission files created in the 3D editor. You can easily import vehicles and buildings into your database this way.</h3>

					<br />
					
					<h2>INSTRUCTIONS:</h2>

					<p>1. At the main menu of ArmA press &lt;ALT + E&gt;. Select your world [World].</p><br />
					<p>2. Once you are in the 3D editor do the following:</p><br />
					<p>Place "Center (F10)" anywhere on map and hit OK.</p>
					<p>Place "Group (F2)" anywhere on map and hit OK.</p>
					<p>Place "Unit (F1)" anywhere on map and hit OK.</p>
					<p>Your mission can be saved now!</p><br />
					<p>3. Find anywhere you like on the map, &lt;RIGHT CLICK&gt; and select "default camera" (takes you to 3d view).</p><br />
					<p>4. Upper-Right Menu select "Vehicle (F5), double click the ground, select the vehicle/building to place, hit OK.</p><br />
					<p>5. &lt;LEFT CLICK&gt; and hold yellow circle for object and drag to place. Hold &lt;SHIFT&gt; while holding &lt;LEFT CLICK&gt; to rotate. Hold &lt;ALT&gt; while holding &lt;LEFT CLICK&gt; to raise or lower object.</p><br />
					<p>6. Hover over the object and hit &lt;DELETE&gt; to remove.</p><br />
					<p>7. Save your progress as "User mission" under name [Your Mission Name]</p><br />
					<p>8. Press the "Import" buttons below to upload the file from the following source and add the vehicles and/or buildings:</p><br />
					<p>"%userprofile%\Documents\ArmA 2 Other Profiles\[Profile Name]\missions\[Your Mission Name].[World]\mission.sqf"</p>

					<br />
					<br />

					<h2>IMPORT VEHICLES:</h2>
					<p>Adds all vehicles to 'instance_vehicle' and 'vehicle' tables from 'vehicles.sqf' file to be spawned in on next restart.</p>
					<br />
					<form action="modules/lib/upload.php" method="post" enctype="multipart/form-data">
						<input type="file" name="vehicles" /> <input type="submit" />
					</form>

					<br />
					<br />

					<h2>IMPORT BUILDINGS:</h2>
					<p>Adds all buildings to 'instance_building' and 'building' tables from 'buildings.sqf' file to be spawned in on next restart.</p>
					<br />
					<form action="modules/lib/upload.php" method="post" enctype="multipart/form-data">
						<input type="file" name="buildings" /> <input type="submit" />
					</form>
				</div>
			</td>
			<td class="border-right"></td>
		</tr>
		<tr>
			<th class="corner-bottomleft"></th>
			<td class="border-bottom">&nbsp;</td>
			<th class="corner-bottomright"></th>
		</tr>
	</table>

<?php
}
else
{
	header('Location: index.php');
}

?>
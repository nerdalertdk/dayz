<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbhost   		= "localhost"; 
$dbname			= "dayz_epoch";
$dbuser			= "USER";
$dbpass			= "PASS";
$dbport			= 3306;
$serverinstance		= 11;

mysql_connect($dbhost.':'.$dbport, $dbuser, $dbpass) or die (mysql_error());
mysql_select_db($dbname) or die (mysql_error());

if (isset($_POST['trader'])) {
	
	    $totalFields = 0;
	    $pattern = "#\d+#";
		
	    if (isset($_POST['item'])) {$item = $_POST['item']; $totalFields++; }
	    if (isset($_POST['sell']) && preg_match($pattern,$_POST['sell'])) {$sell = $_POST['sell']; $totalFields++; }
	    if (isset($_POST['buy']) && preg_match($pattern,$_POST['buy']))	{$buy = $_POST['buy']; $totalFields++; }
	    if (isset($_POST['metal']))	{$metal = $_POST['metal']; $totalFields++; }
	    if (isset($_POST['stock']) && preg_match($pattern,$_POST['stock'])){$stock = $_POST['stock']; $totalFields++; }
	    if (isset($_POST['type'])) {$type = $_POST['type']; $totalFields++; }
	    if (isset($_POST['trader'])) {$trader = $_POST['trader']; $totalFields++; }
	    if (isset($_POST['afile'])) {$afile = $_POST['afile']; $totalFields++; }

	    $item = mysql_real_escape_string(trim($item, " \t"));
	    $sell = mysql_real_escape_string($sell);
	    $buy = mysql_real_escape_string($buy);
	    $metal = mysql_real_escape_string($metal);
	    $stock = mysql_real_escape_string($stock);
	    $type = mysql_real_escape_string($type);
	    $trader = mysql_real_escape_string($trader);
	    $afile = mysql_real_escape_string($afile);
		
		if($totalFields == 8 && $trader != "--- Select Trader ---" && $metal != "--- Select Metal ---" && $type != "--- Select Type ---" && $afile != "--- Select afile ---"){
			$queryset = "
				INSERT INTO `traders_data` (`id`, `item`, `qty`, `buy`, `sell`, `order`, `tid`, `afile`) 
				VALUES (0, '[\"".$item."\",".$type."]', ".(int)$stock.", '[".(int)$buy.",\"".$metal."\",1]', '[".(int)$sell.",\"".$metal."\",1]', 0, ".(int)$trader.", '".$afile."');";
			$res = mysql_query($queryset) or die(mysql_error());
		echo $item." Inserted";
		}else{
			echo "Error inserting item ".$item;
		}
}
?>

<style>
label{
	display:none;
}
</style>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" name="shop" method="post">
	<label for="item">Item</label>
		<input type="text" name="item" size="37" value="" placeholder="Item" /><br />
	<label for="buy">Price Buy</label>
		<input type="text" name="buy" size="37" value="" placeholder="Price Buy"/><br />
	<label for="sell">Price Sell</label>
		<input type="text" name="sell" size="37" value="" placeholder="Price Sell"/><br />
	<label for="stock">Stock</label>
		<input type="text" name="stock" size="37" value="" placeholder="Stock"/><br />
	<select name="metal">
		<option>--- Select Metal  ---</option>
		<option value="ItemSilverBar">ItemSilverBar</option>
		<option value="ItemSilverBar10oz" >ItemSilverBar10oz</option>
		<option value="ItemGoldBar" >ItemGoldBar</option>
		<option value="ItemGoldBar10oz" >ItemGoldBar10oz</option>
	</select><br />
	<select name="type">
		<option>--- Select Type ---</option>
		<option value="3" >Weapon</option>
		<option value="2" >Vehicle and backpack</option>
		<option value="1" >Magazine</option>
	</select><br />
	<select name="afile">
		<option>--- Select afile  ---</option>
		<option value="trade_weapons">trade_weapons</option>
		<option value="trade_items" >trade_items</option>
		<option value="trade_any_vehicle" >trade_any_vehicle</option>
		<option value="trade_any_boat" >trade_any_boat</option>
		<option value="trade_backpacks" >trade_backpacks</option>
	</select><br />
	<select name="trader">
		<option>--- Select Trader ---</option>
		<option>--- BASH ---</option>
		<option value="1" >Weapons - Sidearm</option>
		<option value="2" >Weapons - Rifles</option>
		<option value="3" >Weapons - Shotguns and Crossbows</option>
		<option value="25" >Ammo - Sidearm Ammo</option>
		<option value="26" >Ammo - Rifle Ammo</option>
		<option value="27" >Ammo - Shotgun and Crossbow Ammo</option>
		<option>--- KLEN ---</option>
		<option value="4" >Weapons - Sidearm</option>
		<option value="5" >Weapons - Rifles</option>
		<option value="6" >Weapons - Shotguns and Crossbows</option>
		<option value="28" >Ammo - Sidearm Ammo</option>
		<option value="29" >Ammo - Rifle Ammo</option>
		<option value="30" >Ammo - Shotgun and Crossbow Ammo</option>
		<option value="41" >Auto - Trucks Armed</option>
		<option value="42" >Auto - UAZ</option>
		<option value="43" >Auto - Helicopter Armed</option>
		<option value="44" >Auto - Military Armed</option>
		<option value="45" >Auto - Fuel Trucks</option>
		<option value="46" >Auto - Heavy Armor Unarmed</option>
		<option>--- STARY (High end) ---</option>
		<option value="57" >Weapons - Assault Rifle</option>
		<option value="58" >Weapons - Machine Gun</option>
		<option value="59" >Weapons - Sniper Rifle</option>
		<option value="60" >Weapons - Explosives</option>
		<option value="31" >Ammo - Assault Rifle Ammo</option>
		<option value="32" >Ammo - Machine Gun Ammo</option>
		<option value="33" >Ammo - Sniper Rifle Ammo</option>
		<option>--- HERO SHOP ---</option>
		<option value="53" >Clothes</option>
		<option value="186" >Weapons</option>
		<option value="187" >Weapons Vehicles</option>
	</select><br />
	<input type="button" name="add" value="Add" onclick='this.form.submit()' />
</form>

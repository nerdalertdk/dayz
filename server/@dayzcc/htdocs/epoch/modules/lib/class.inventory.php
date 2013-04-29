<?php

// This script was written by ChemicalBliss and slightly altered by Crosire

Class inventory_generator {
	public function execute($vehicle_type){
		$class = $this->template($vehicle_type);
		if ($class === false) { return "[]"; } // Exit when unknown
		$itemArray = array("weapons_tools" => array(), "items" => array(), "packs" => array());
		foreach (str_split($class, 2) as $item) {
			switch($item[0]) {
				case "w":
					$key = "weapons_tools";
					break;
				case "t":
					$key = "weapons_tools";
					break;
				case "i":
					$key = "items";
					break;
				case "p":
					$key = "packs";
					break;
			}
			$newItem = $this->template_code($item);
			if ($newItem === false) {
				continue;
			} else {
				$itemArray[$key][] = $newItem;
				if ($item[0] == "w") {
					$clips = rand(0, 3);
					for ($i = 0; $i < $clips; $i++) { $itemArray["items"][] = $this->weapon_ammo($newItem); }
				}
			}
		}
		
		$weapons_tools = (count($itemArray['weapons_tools']) <= 0) ? '[[],[]]' : '[["'.(implode('","', $itemArray['weapons_tools'])).'"],['.($this->mk_count_string(count($itemArray['weapons_tools']))).']]';
		$items = (count($itemArray['items']) <= 0) ? '[[],[]]' : '[["'.(implode('","', $itemArray['items'])).'"],['.($this->mk_count_string(count($itemArray['items']))).']]';
		$packs = (count($itemArray['packs']) <= 0) ? '[[],[]]' : '[["'.(implode('","', $itemArray['packs'])).'"],['.($this->mk_count_string(count($itemArray['packs']))).']]';

		return "[".$weapons_tools.",".$items.",".$packs."]";
	}
	
	// Gear teamplates
	private function template($vehicle) {
		$vehicles  = array(
			"UAZ_MG_CDF_DZ_VLM" => "w2w3t2t2t3i1i2i2i3i3i3i3i3i3p1p2",
			"UAZ_Unarmed_TK_EP1" => "w2w3t2t2t3i1i2i2i3i3i3i3p1p2",
			"UAZ_Unarmed_TK_CIV_EP1" => "w2w3t2t2t3i1i2i2i3i3i3i3p1p2",
			"UAZ_Unarmed_UN_EP1" => "w2w3t2t2t3i1i2i2i3i3i3i3p1p2",
			"UAZ_RU" => "w3t2t2t3i1i2i3i3i3i3p1p2",
			"ATV_US_EP1" => "w2t2t2t3i3i3i3p2",
			"ATV_CZ_EP1" => "t2t2t3i2i3i3i3p2",
			"SkodaBlue" => "w3w3t2t3t3i3i3i3i3i3i3i3i3p2",
			"Skoda" => "w3t2t3t3i3i3i3i3i3i3i3i3p2",
			"SkodaGreen" => "w2w3t2t3t3i3i3i3i3p2",
			"SkodaRed" => "w3t2t3t3i3i3i3i3i3i3i3i3p2",
			"TT650_Civ" => "t2i1",
			"TT650_Gue" => "t2i1",
			"TT650_Ins" => "t2i1",
			"TT650_TK_EP1" => "t2i1",
			"TT650_TK_CIV_EP1" => "t2i1",
		//	"Old_bike_TK_CIV_EP1" => "",
		//	"Old_bike_TK_INS_EP1" => "",
			"C130J" => "w1w2t1i1i2i2i3i3i3p1",
			"C130J_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"Mi17_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"AH6X_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"AN2_DZ" => "w2t1i1i2i3i3i3p1",
			"kh_maule_m7_white_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"UH1H_DZ" => "w2w2t1i1i2i2i3i3i3p1",
			"MV22_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"Mi17_medevac_RU_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"Mi17_Civilian_DZ" => "w2t1i1i2i2i3i3i3p1",
			"Mi17_Civilian_Nam" => "w2t1i1i2i2i3i3i3p1",
			"Mi17_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"Mi17_medevac_RU_DZ" => "w1w2t1i1i2i2i3i3i3p1",
			"hilux1_civil_3_open" => "w2w3t2t2t3i2i2i3i3i3i3i3i3i3i3p2p2",
			"datsun1_civil_2_covered" => "w2w3t2t2t3i2i3i3i3i3i3p2p2",
			"datsun1_civil_3_open" => "w2w3t2t2t3i2i2i3i3i3i3p2p2",
			"hilux1_civil_2_covered" => "w2w3t2t2t3i2i2i3i3i3i3i3i3i3i3p2p2",
			"Ikarus_TK_CIV_EP1" => "w3t2t3t3t3i3i3i3i3i3i3i3i3i3i3i3i3i3i3i3p1p2p2",
			"Ikarus" => "w3t2t3t3t3i3i3i3i3i3i3i3i3i3i3i3i3i3i3i3p1p2p2",
			"Tractor" => "t3i2i2i3i3i3i3i3p2",
			"S1203_TK_CIV_EP1" => "w1w2w3t1t2t3i1i2i3p1p2",
			"V3S_Civ" => "w2w2w2w3w3t1t2t2t3t3t3i1i1i2i2i2i2i3i3i3i3i3i3i3i3p1p2p2",
			"UralCivil" => "w2w2w3w3w3t2t2t3t3t3i2i2i2i2i3i3i3i3i3i3i3i3i3i3p1p2p2",
			"UralCivil2" => "w2w2w2w3w3t2t2t3t3t3i2i3i3i3i3i3i3i3i3i3i3i3i3p1p2p2",
			"UralRefuel_INS" => "w1w2w2w3t2t2t3t3t3i2i2i2i2i3i3i3i3i3i3i3i3i3i3i3i3i3i3i3i3p1p2p2",
			"Ural_INS" => "w1w2w2w3w3t2t2t3t3t3i2i2i3i3i3i3i3i3i3i3i3i3i3p1p2p2",
			"GAZ_Vodnik" => "w1w3w3t2t2t3t3t3i2i2i2i3i3i3i3i3i3i3p1p2p2",
			"GAZ_Vodnik_DZ_VLM" => "w1w2w3t2t2t3t3t3i2i2i2i3i3i3i3i3i3i3p1p2p2",
			"GAZ_Vodnik_MedEvac" => "w1w3w3t2t2t3t3t3i2i2i2i3i3i3i3i3i3i3p1p2p2",
			"car_hatchback" => "w3w3t2t3t3i3i3i3i3i3i3i3i3p2",
		//	"Fishing_Boat" => "",
			"PBX" => "w2i2i3i3i3p1",
		//	"Smallboat_1" => "",
			"Volha_2_TK_CIV_EP1" => "w3w3w1t2t3t3i3i3i3i3i3i3i3i3p2",
			"Volha_1_TK_CIV_EP1" => "w3w3w2t2t3t3i3i3i3i3i3i3i3i3p2",
			"VolhaLimo_TK_CIV_EP1" => "w3t2t3t3i3i3i3i3i3i3i3i3p2",
			"BAF_Offroad_D" => "w2w3t2t2i2i2i3i3p1p2",
			"BAF_Offroad_W" => "w2w3t2t2i2i2i3i3p1p2",
			"SUV_TK_CIV_EP1" => "w2t2i1i1i3i3i3i3i3i3p1p1",
			"HMMWV_DES_EP1" => "w1w1t1i1i1i3i3i3i3i3i3p1p1",
			"HMMWV_Ambulance_DZ" => "w1w1w2t1i1i1i3i3i3i3i3i3p1p1",
			"car_sedan" => "w3t2t3t3i3i3i3i3i3i3i3i3p2",
		//	"player" => "",
			"VWGolf" => "w3w2t2t3t3i3i3i3i3i3i3i3i3p2"
		);
		
		if (!isset($vehicles[$vehicle])){
			return false;
		} else {
			return $vehicles[$vehicle];
		}
	}
	private function template_code($classcode){
		switch($classcode) {
			default:
				return false;
				break;
			case "w1":
				if (rand(1, 100) <= 5) { // 5% chance to spawn
					return $this->random_weapon(1);
				}
				break;
			case "w2":
				if (rand(1, 100) <= 10) { // 10% chance to spawn
					return $this->random_weapon(2);
				}
				break;
			case "w3":
				if (rand(1, 100) <= 20) { // 20% chance to spawn
					return $this->random_weapon(3);
				}
				break;
			case "t1":
				if (rand(1, 100) <= 5) { // 5% chance to spawn
					return $this->random_tool(1);
				}
				break;
			case "t2":
				if (rand(1, 100) <= 15) { // 15% chance to spawn
					return $this->random_tool(2);
				}
				break;
			case "t3":
				if (rand(1, 100) <= 25) { // 25% chance to spawn
					return $this->random_tool(3);
				}
				break;
			case "i1":
				if (rand(1, 100) <= 5) { // 5% chance to spawn
					return $this->random_item(1);
				}
				break;
			case "i2":
				if (rand(1, 100) <= 10) { // 10% chance to spawn
					return $this->random_item(2);
				}
				break;
			case "i3":
				if (rand(1, 100) <= 20) { // 20% chance to spawn
					return $this->random_item(3);
				}
				break;
			case "p1":
				if (rand(1, 100) <= 25) { // 25% chance to spawn
					return $this->random_pack(1);
				}
				break;
			case "p2":
				if (rand(1, 100) <= 30) { // 30% chance to spawn
					return $this->random_pack(2);
				}
				break;
		}
		
		return false;
	}
	
	// Fetch a random item by rarity class
	private function random_weapon($class) {
		$weapon_list = array(
			"class1" =>
				array(
					"M107_DZ",
					"FN_FAL_ANPVS4",
					"Mk_48_DZ",
					"M240_DZ",
					"M4A1_AIM_SD_camo",
					"bizon_silenced",
					"M24",
					"SVD_CAMO",
					"DMR"
				),
			"class2" =>
				array(
					"M9SD",
					"M9",
					"glock17_EP1",
					"UZI_EP1",
					"M249_DZ",
					"Remington870_lamp",
					"Winchester1866",
					"MP5A5","MP5SD",
					"AK_47_M",
					"AKS_74_kobra",
					"FN_FAL",
					"M4A1_Aim",
					"M4A1_HWS_GL_camo",
					"M16A4_ACG",
					"Huntingrifle",
				),
			"class3" =>
				array(
					"MeleeHatchet",
					"MeleeCrowbar",
					"Colt1911",
					"Makarov",
					"revolver_EP1",
					"Crossbow",
					"M1014",
					"MR43",
					"AK_74",
					"AKS_74_U",
					"LeeEnfield",
					"M4A1",
					"M16A2",
					"M16A2GL",
					"M14_EP1"
				)
			);
		
		return $weapon_list["class".$class][rand(0, count($weapon_list["class".$class]) - 1)];
	}
	private function random_tool($class) {
		$tool_list = array(
			"class1" =>
				array(
					"NVGoggles",
					"ItemGPS"
				),
			"class2" =>
				array(
					"ItemMap",
					"ItemCompass",
					"ItemFlashlightRed",
					"ItemToolbox"
				),
			"class3" =>
				array(
					"Binocular",
					"ItemWatch",
					"ItemFlashlight",
					"ItemKnife",
					"ItemHatchet",
					"ItemMatchbox"
				)
			);
	
		return $tool_list["class".$class][rand(0, count($tool_list["class".$class]) - 1)];
	}
	private function random_item($class){
		$item_list = array(
			"class1" =>
				array(
					"ItemTent",
					"Skin_Camo1_DZ",
					"Skin_Sniper1_DZ",
					"ItemAntibiotic",
					"PartEngine"
				),
			"class2" =>
				array(
					"PartFueltank",
					"ItemMorphine",
					"ItemEpinephrine",
					"PartGeneric",
					"ItemTankTrap",
					"ItemWire",
					"PartGlass",
					"PartWheel",
					"ItemJerrycan",
					"PartVRotor",
					"ItemBloodbag"
				),
			"class3" =>
				array(
					"PartWoodPile",
					"ItemBandage",
					"FoodCanPasta",
					"ItemSodaCoke",
					"ItemPainkiller",
					"ItemHeatPack",
					"FoodCanBakedBeans",
					"FoodCanSardines",
					"ItemSodaPepsi",
					"HandRoadFlare",
					"HandChemGreen",
					"HandChemBlue",
					"HandChemRed",
					"FoodSteakCooked",
					"ItemWaterbottle"
				)
		);

		return $item_list["class".$class][rand(0, count($item_list["class".$class]) - 1)];
	}
	private function random_pack($class){
		$pack_list = array(
			"class1" => array(
				"DZ_ALICE_Pack_EP1",
				"DZ_CivilBackpack_EP1"
			),
			"class2" => array(
				"DZ_Patrol_Pack_EP1",
				"DZ_Assault_Pack_EP1"
			)
		);
	
		return $pack_list["class".$class][rand(0, count($pack_list["class".$class]) - 1)];
	}

	// Fetch Ammo For Weapon Type
	private function weapon_ammo($weaponType){
		$weapon_to_ammo = array(
			"MeleeHatchet" => "",
			"MeleeCrowbar" => "",
			"Colt1911" => "7Rnd_45ACP_1911",
			"Makarov" => "8Rnd_9x18_Makarov",
			"M9" => "15Rnd_9x19_M9",
			"M9SD" => "15Rnd_9x19_M9SD",
			"revolver_EP1" => "6Rnd_45ACP",
			"glock17_EP1" => "17Rnd_9x19_glock17",
			"UZI_EP1" => "30Rnd_9x19_UZI",
			"Crossbow" => "BoltSteel",
			"M240_DZ" => "100Rnd_762x51_M240",
			"M249_DZ" => "200Rnd_556x45_M249",
			"Mk_48_DZ" => "100Rnd_762x51_M240",
			"M1014" => array("8Rnd_B_Beneli_74Slug","8Rnd_B_Beneli_Pellets"),
			"Remington870_lamp" => array("8Rnd_B_Beneli_74Slug","8Rnd_B_Beneli_Pellets"),
			"Winchester1866" => "15Rnd_W1866_Slug",
			"MR43" => array("2Rnd_shotgun_74Slug","2Rnd_shotgun_74Pellets"),
			"bizon_silenced" => "64Rnd_9x19_SD_Bizon",
			"MP5A5" => "30Rnd_9x19_MP5",
			"MP5SD" => "30Rnd_9x19_MP5SD",
			"AK_74" => "30Rnd_545x39_AK",
			"AK_47_M" => "30Rnd_762x39_AK47",
			"AKS_74_kobra" => "30Rnd_545x39_AK",
			"AKS_74_U" => "30Rnd_545x39_AK",
			"FN_FAL" => "20Rnd_762x51_FNFAL",
			"FN_FAL_ANPVS4" => "20Rnd_762x51_FNFAL",
			"BAF_L85A2_RIS_CWS" => "30Rnd_556x45_Stanag",
			"LeeEnfield" => "10x_303",
			"M4A1" => "30Rnd_556x45_Stanag",
			"M4A1_Aim" => "30Rnd_556x45_Stanag",
			"M4A1_AIM_SD_camo" => "30Rnd_556x45_StanagSD",
			"M4A1_HWS_GL_camo" => "30Rnd_556x45_Stanag",
			"M4A1_Aim_camo" => "30Rnd_556x45_Stanag",
			"M16A2" => "30Rnd_556x45_Stanag",
			"M16A2GL" => "30Rnd_556x45_Stanag",
			"M16A4_ACG" => "30Rnd_556x45_Stanag",
			"BAF_AS50_scoped" => array("5Rnd_127x99_as50","10Rnd_127x99_m107"),
			"Huntingrifle" => "5x_22_LR_17_HMR",
			"DMR" => "20Rnd_762x51_DMR",
			"M14_EP1" => "20Rnd_762x51_DMR",
			"M24" => "5Rnd_762x51_M24",
			"M107_DZ" => "10Rnd_127x99_m107",
			"SVD_CAMO" => "10Rnd_762x54_SVD",
		);
	
		return (empty($weapon_to_ammo[$weaponType])) ? false : (is_array($weapon_to_ammo[$weaponType])) ? $weapon_to_ammo[$weaponType][rand(0, 1)] : $weapon_to_ammo[$weaponType];
	}
	
	// Private Functions
	private function mk_count_string($count){
		$array = array();
		for ($i = 0; $i < $count; $i++) { $array[] = "1"; }
		return implode(",", $array);
	}
}

?>
<?php
/**
* Vienara
*
*
* This file is licensed under the MIT license. You can use
* this project as a base for your own project, but you are
* not allowed to remove this header comment block. You may
* not use the name "Vienara" as name for your project
* either. Thanks for understanding.
*
* @version: 1.0 Alpha 1
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.co.cc
*
* @license MIT
*/

// Have we defined Vienara?
if(!defined('Vienara'))
	die();

// The timezone class
class Timezone {

	public $txt;

	// Get the timezones
	function zones()
	{
		// Setup the regions
		$zones = array(
			'Africa' => DateTimeZone::AFRICA,
			'America' => DateTimeZone::AMERICA,
			'Antarctica' => DateTimeZone::ANTARCTICA,
			'Asia' => DateTimeZone::ASIA,
			'Europe' => DateTimeZone::EUROPE,
			'India' => DateTimeZone::INDIAN,
			'Pacific' => DateTimeZone::PACIFIC
		);

		// Setup a clean array
		$return_zones = array();

		// Now walk through each value of the zone array
		foreach($zones as $key => $value) {

			// Get the sub zones
			$sub_zones = DateTimeZone::listIdentifiers($value);

			// Ksort it
			ksort($sub_zones);

			// Now add them all to the array
			foreach($sub_zones as $sub_key => $sub_value)
				$return_zones[] = $sub_value;
		}

		// Now return what we have
		return $return_zones;
	}
}

<?
include_once "../classes/address.class.php";
require_once "../classes/city.class.php";
require_once "../classes/state.class.php";
require_once "../classes/zipcode.class.php";

$A = new AddressClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;


if ( isset($_GET['q']) && $_GET['q'] != "" ) { 


	$searchKey = trim(strip_tags($_GET['q']));//retrieve the search term that autocomplete sends
	
	$cntRec = $A->getTotal('address_1',$searchKey);

	$aRec = $A->getSelectedFieldsRecordListByField('*', 'address_1', $searchKey);

	if ($cntRec > 0) {  

		 for ($i=0; $i < $cntRec; $i++) { 

				$aid = $aRec[$i]['id'];
				$address[0] = $aRec[$i]['address_1'];
				$address_2 = $aRec[$i]['address_2'];
				$city_id = $aRec[$i]['city_id'];
				if (!empty($address_2)) { 
						$address[0] .= ', '.$address_2; 
				}
  				
				$isCityId = $C->isIdPresent($city_id); 
				if ($isCityId == 1) { 
					
					$crec = $C->getRecordByField('city_id', $city_id,'exact');
					$city = $crec['city'];
					$address[0] .= ', '. $city;
				}
				$isStateId = $S->isIdPresent($state_id); 
				if ($isStateId == 1) { 
					
					$srec = $S->getRecordByField('state_id', $state_id,'exact');
					$state = $srec['state'];
					$address[0] .= ', '. $state;
				}
				$isZipId = $Z->isIdPresent($zip_id); 
				if ($isZipId == 1) { 
					
					$zrec = $Z->getRecordByField('zip_id', $zip_id,'exact');
					$zip = $zrec['zipcode'];
					$address[0] .= ', '. $zip;
				}
				
				
				$address[1] = $aid;
				echo $address[0] . ' ['. $address[1] . "]\n";
				
		 } 
		 
	}
	
	

}
else {
	 return;
}

?>
<?
/**
 * Auto Complete 5.1
 * April 13, 2010
 * Corey Hart @ http://www.codenothing.com
 */ 

/*
// Make request var preg safe
$value = trim($_POST['q']);

// Ensure there is a value to search for
if ((!isset($value) || $value == '') && ! $_POST['all']) exit;

// Get list of random words
$words = explode(',', file_get_contents('words.txt'));

// Set up the send back array
$found = array();
$num = rand(1, 100);
// Search through each standard val and match it if possible
foreach ($words as $word){
	if (!$word || $word == '') continue;
	// If all parameter is passed, load up all C/D values
	if ($_POST['all'] && (($_POST['letter'] == 'c' && strtolower($word[0]) == 'c') || ($_POST['letter'] == 'd' && strtolower($word[0]) == 'd'))){
		// Return Array
		$found[] = array(
			"value" => $word, 
			"display" => "<div style='float:right;'>$num Fake Results</div>$word",
		);
	}
	else if (!$_POST['all'] && strpos($word, $value) === 0){
		// Return Array
		$found[] = array(
			"value" => $word, 
			"display" => "<div style='float:right;'>$num Fake Results</div>$word",
		);
		if (count($found) >= 10)
			break;
	}
}

// JSON encode the array for return
echo json_encode($found);
*/?>
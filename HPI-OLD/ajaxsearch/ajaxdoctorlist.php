<?
include_once "../classes/doctor.class.php";
require_once "../classes/category.class.php";
require_once "../classes/specialty.class.php";

$D = new DoctorClass;
$C = new CategoryClass;
$S = new SpecialtyClass;


if ( isset($_GET['q']) && $_GET['q'] != "" ) { 


	$searchKey = trim(strip_tags($_GET['q']));//retrieve the search term that autocomplete sends
	
	$cntRec = $D->getTotal('fullname',$searchKey);

	$aRec = $D->getSelectedFieldsRecordListByField('*', 'fullname', $searchKey);

	if ($cntRec > 0) {  

		 for ($i=0; $i < $cntRec; $i++) { 

				$did = $aRec[$i]['doctor_id'];
				
				$doctor_name  = $D->prepare_output( $aRec[$i]["first_name"] );
					
				if (!empty($aRec[$i]["last_name"])) {
					$doctor_name .= ', ' .  $D->prepare_output( $aRec[$i]["last_name"] ); 
				}
				
				$returntext[0] = $doctor_name; 
				$npi = $aRec[$i]['npi'];
				$specialty_id_fk = $aRec[$i]['specialty_id_fk'];
				$category_id_fk	= $aRec[$i]['category_id_fk'];
				
				$isSpl = $S->isIdPresent($specialty_id_fk); 
				if ($isSpl == 1) { 
					
					$srec = $S->getRecordByField('specialty_id', $specialty_id_fk,'exact');
					$specialty = $srec['specialty_name'];
					$returntext[0] .= ', '. $specialty;
				}
				$isCat = $C->isIdPresent($category_id_fk); 
				if ($isCat == 1) { 
					
					$crec = $C->getRecordByField('cat_id', $category_id_fk,'exact');
					$cat = $crec['category_name'];
					$returntext[0] .= ', '. $cat;
				}
				
				if (!empty($npi))
					$returntext[0] .= ', '.  $D->prepare_output( $npi );
				
				
				$returntext[1] = $did;
				$returntext[2] = $specialty_id_fk;
				$returntext[3] = $category_id_fk;
				
				//echo $returntext[0] . ' ['. $returntext[1] . "]\n";
					echo $returntext[0] . ' ['. $returntext[1] . "," .  $returntext[2] .  ','. $returntext[3] . "]\n";
				
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
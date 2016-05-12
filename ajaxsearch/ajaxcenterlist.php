<?
include_once "../classes/center.class.php";
require_once "../classes/category.class.php";
require_once "../classes/specialty.class.php";

$D = new CenterClass;
$C = new CategoryClass;
$S = new SpecialtyClass;


if ( isset($_GET['q']) && $_GET['q'] != "" ) { 


	$searchKey = trim(strip_tags($_GET['q']));//retrieve the search term that autocomplete sends
	
	$cntRec = $D->getTotal('name',$searchKey);

	$aRec = $D->getSelectedFieldsRecordListByField('*', 'name', $searchKey);

	if ($cntRec > 0) {  

		 for ($i=0; $i < $cntRec; $i++) { 

				$cnId = $aRec[$i]['center_id'];
				$center_name  = $D->prepare_output( $aRec[$i]["name"] );
				
				$returntext[0] = $center_name; 
				$npi = $aRec[$i]['npi'];
				$specialty_id_fk = $aRec[$i]['specialty_id_fk'];
				$category_id_fk	= $aRec[$i]['category_id_fk'];
				
				$isSpl = $S->isIdPresent($specialty_id_fk); 
				if ($isSpl == 1) { 
					
					$srec = $S->getRecordByField('specialty_id', $specialty_id_fk,'exact');
					$specialty = $S->prepare_output( $srec['specialty_name'] );
					$returntext[0] .= '>> '. $specialty;
				}
				$isCat = $C->isIdPresent($category_id_fk); 
				if ($isCat == 1) { 
					
					$crec = $C->getRecordByField('cat_id', $category_id_fk,'exact');
					$cat = $C->prepare_output( $crec['category_name'] );
					$returntext[0] .= '>> '. $cat;
				}
				
				if (!empty($npi))
					$returntext[0] .= '>> '.  $D->prepare_output( $npi );
				
				
				$returntext[1] = $cnId;
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
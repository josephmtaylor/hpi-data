<?php

include_once "classes/session.class.php";
require_once dirname(__FILE__) . '/classes/PHPExcel.php';
require_once "classes/admin.class.php";
require_once "classes/address.class.php";
require_once "classes/center.class.php";
require_once "classes/specialty.class.php";
require_once "classes/category.class.php";
require_once "classes/chapter.class.php";
require_once "classes/county.class.php";
require_once "classes/centeraddress.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/centerhrop.class.php";
require_once "classes/publication.class.php";
require_once "classes/centerpublication.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();
$loggedUserId = $session->getUserId();


$A = new AdminClass;
$ADR = new AddressClass;
$M = new CenterClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CH = new ChapterClass;
$CN = new CountyClass;
$DA = new CenterAddressClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$CHR = new CenterHropClass;
$P = new PublicationClass;
$CP = new CenterPublicationClass;

 $query = $ADR->getQuery();


if (!empty($query)) { 

	$totalLimit = $ADR->getQueryTotal($query);
	$recordList = $ADR->getQueryResult($query);
	
}
//echo '<pre>'; print_r($recordList); exit();

if ($totalLimit > 0) { 




			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Heritage Publishing Inc")
										 ->setLastModifiedBy("HeritagePublishingInc")
										 ->setTitle("Heritage Publishing Doctors List")
										 ->setSubject("Heritage Publishing Doctors List");
										
			
			
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Speciality')
						->setCellValue('B1', 'Category')
						->setCellValue('C1', 'NPI')
						->setCellValue('D1', 'Chapter')
						->setCellValue('E1', 'Name')
						->setCellValue('F1', 'Address1')
						->setCellValue('G1', 'Address2')
						->setCellValue('H1', 'City')
						->setCellValue('I1', 'State')
						->setCellValue('J1', 'Zip')
						->setCellValue('K1', 'County')
						->setCellValue('L1', 'Phone')
						->setCellValue('M1', 'Fax')
						->setCellValue('N1', 'Website')
						->setCellValue('O1', 'Hours of operation')
						->setCellValue('P1', 'Admission')
						->setCellValue('Q1', 'Description')
						->setCellValue('R1', 'Notes')
						->setCellValue('S1', 'Publication')
						->setCellValue('T1', 'Main office Indicator');
						
						
	
		for($i=0, $cellNo = $i + 2; $i < $totalLimit ; $i++,$cellNo++)
		{
				$id = $recordList[$i]['center_id'];
				$center_name  = $recordList[$i]["name"];
				$county_id_fk = $recordList[$i]["county_id_fk"];
				$category_id_fk = $recordList[$i]["category_id_fk"];
				$specialty_id_fk = $recordList[$i]["specialty_id_fk"];
				$chapter_id_fk = $recordList[$i]["chapter_id_fk"];
				
				$address_1 = $ADR->prepare_output( $recordList[$i]["address_1"] );
				$address_2 = $ADR->prepare_output( $recordList[$i]["address_2"] );
				$city_id = $ADR->prepare_output( $recordList[$i]["city_id"] );
				$state_id = $ADR->prepare_output( $recordList[$i]["state_id"] );
				$zip_id = $ADR->prepare_output( $recordList[$i]["zip_id"] );
				$phone = $ADR->prepare_output( $recordList[$i]["phone"] );
				$fax = $ADR->prepare_output( $recordList[$i]["fax"] );
				$website = $ADR->prepare_output( $recordList[$i]["website"] );
				$npi = $ADR->prepare_output( $recordList[$i]["npi"] );
				$dstatus = $ADR->prepare_output( $recordList[$i]["dstatus"] );
				$notes = $ADR->prepare_output( $recordList[$i]["notes"] );
				
				
				$county_name = ''; $category_name = '';  $specialty_name = ''; $chapter_name = ''; $publication = '';
				if (!empty($category_id_fk)) {
					$lrec = $CT->getRecordByField('cat_id', $category_id_fk,'exact');
					$category_name = $CT->prepare_output( $lrec['category_name'] );
				}
				if (!empty($specialty_id_fk)) {
					$srec = $SP->getRecordByField('specialty_id', $specialty_id_fk,'exact');
					$specialty_name = $SP->prepare_output( $srec['specialty_name'] );
				}
				if (!empty($chapter_id_fk)) {
					$chrec = $CH->getRecordByField('chapter_id', $chapter_id_fk,'exact');
					$chapter_name = $CH->prepare_output( $chrec['chapter_name'] );
				}
				if (!empty($city_id)) { 			
					$isCity = $C->isIdPresent($city_id); 
					if ($isCity == 1) { 
						$lrec = $C->getRecordByField('city_id', $city_id,'exact');
						$city = $C->prepare_output($lrec['city']);
					}
				}
				if (!empty($state_id)) { 			
					$isState = $S->isIdPresent($state_id); 
					if ($isState == 1) { 
						$srec = $S->getRecordByField('state_id', $state_id,'exact');
						$state = $S->prepare_output( $srec['state'] );
					}
				}
				
				if (!empty($zip_id)) { 			
					$isZip = $Z->isIdPresent($zip_id); 
					if ($isZip == 1) { 
						$zrec = $Z->getRecordByField('zip_id', $zip_id,'exact');
						$zip = $Z->prepare_output( $zrec['zipcode'] );
					}
				}
				
				if (!empty($county_id)) { 			
					$isCnt = $CN->isIdPresent($county_id); 
					if ($isCnt == 1) { 
						$nrec = $CN->getRecordByField('county_id', $county_id,'exact');
						$county = $CN->prepare_output( $nrec['county'] );
					}
				}
				
				
				$centerPubTotal = $CP->isCenterPubRecordExists($id);
				if ($centerPubTotal > 0) {
					$cpRec = $CP->getCenterPublicationRecord('center_id', $id, 'exact');
				
					foreach ($cpRec as $key => $rec) {
						$publication = $CP->prepare_output( $rec['publication'] );
					}
				}
				
				
				$dstatus = $M->getStatus($dstatus);
				
				$chRec = $CHR->getCenterHropRecord($fieldName='center_id', $searchKey=$id, $searchStatus='exact');
				
				foreach ($chRec as $key => $rec) { 
					if (!isset($hours_of_operation))
						$hours_of_operation = $CHR->prepare_output( $rec['description'] );
					else{
						$hours_of_operation .= ', '. $rec['description'];
					}
				}
				
				$admission = ''; $description = ''; $main_office = '';
			
			// Miscellaneous glyphs, UTF-8
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$cellNo, $specialty_name)
						->setCellValue('B'.$cellNo, $category_name)
						->setCellValue('C'.$cellNo, $npi)
						->setCellValue('D'.$cellNo, $chapter_name)
						->setCellValue('E'.$cellNo, $center_name)
						->setCellValue('F'.$cellNo, $address_1)
						->setCellValue('G'.$cellNo, $address_2)
						->setCellValue('H'.$cellNo, $city)
						->setCellValue('I'.$cellNo, $state)
						->setCellValue('J'.$cellNo, $zip)
						->setCellValue('K'.$cellNo, $county)
						->setCellValue('L'.$cellNo, $phone)
						->setCellValue('M'.$cellNo, $fax)
						->setCellValue('N'.$cellNo, $website)
						->setCellValue('O'.$cellNo, $hours_of_operation)
						->setCellValue('P'.$cellNo, $admission)
						->setCellValue('Q'.$cellNo, $description)
						->setCellValue('R'.$cellNo, $notes)
						->setCellValue('S'.$cellNo, $publication)
						->setCellValue('T'.$cellNo, $main_office);
						
						
			
		
		} // end for 
		
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
			
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			$date = date('Y-m-d');
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="hpa-center-export-data-'.$date.'.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;


}
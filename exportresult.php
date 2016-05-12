<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Include PHPExcel */
include_once "classes/session.class.php";
require_once dirname(__FILE__) . '/classes/PHPExcel.php';
require_once "classes/admin.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/address.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();
$loggedUserId = $session->getUserId();


$A = new AdminClass;
$M = new DoctorClass;
$ADR = new AddressClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;

/*
$recordList = $_SESSION['recordlist'];
$totalLimit = $_SESSION['totalLimit'];*/



$query =	$ADR->getQuery();
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
						->setCellValue('B1', 'NPI')
						->setCellValue('C1', 'FullName')
						->setCellValue('D1', 'Address1')
						->setCellValue('E1', 'Address2')
						->setCellValue('F1', 'Phone')
						->setCellValue('G1', 'Fax')
						->setCellValue('H1', 'Email')
						->setCellValue('I1', 'Website')
						->setCellValue('J1', 'Status')
						->setCellValue('K1', 'City')
						->setCellValue('L1', 'State')
						->setCellValue('M1', 'Zip')
						->setCellValue('N1', 'County');
						
						
	
		for($i=0, $cellNo = $i + 2; $i < $totalLimit ; $i++,$cellNo++)
		{
				$id = $recordList[$i]['doctor_id'];
				$doctor_name  = $recordList[$i]["fullname"];
				if (empty($doctor_name)) { 
					$doctor_name  = $recordList[$i]["first_name"];
					
					if (!empty($recordList[$i]["middle_name"])) {
						$doctor_name .= ' ' . $recordList[$i]["middle_name"]; 
					}
					if (!empty($recordList[$i]["last_name"])) {
						$doctor_name .= ' ' . $recordList[$i]["last_name"]; 
					}
				}
				$county_id_fk = $recordList[$i]["county_id_fk"];
				$category_id_fk = $recordList[$i]["category_id_fk"];
				$specialty_id_fk = $recordList[$i]["specialty_id_fk"];
				
				$address_1 = $ADR->prepare_output( $recordList[$i]["address_1"] );
				$address_2 = $ADR->prepare_output( $recordList[$i]["address_2"] );
				$city = $ADR->prepare_output( $recordList[$i]["city"] );
				$state = $ADR->prepare_output( $recordList[$i]["state"] );
				$zip = $ADR->prepare_output( $recordList[$i]["zip"] );
				$email = $ADR->prepare_output( $recordList[$i]["email"] );
				$license_status = $ADR->prepare_output( $recordList[$i]["license_status"] );
				$phone = $ADR->prepare_output( $recordList[$i]["phone"] );
				$fax = $ADR->prepare_output( $recordList[$i]["fax"] );
				$website = $ADR->prepare_output( $recordList[$i]["website"] );
				$npi = $ADR->prepare_output( $recordList[$i]["npi"] );
				$dstatus = $ADR->prepare_output( $recordList[$i]["dstatus"] );
				
				$county_name = ''; $category_name = '';  $specialty_name = '';
				if (!empty($category_id_fk)) {
					$lrec = $CT->getRecordByField('cat_id', $category_id_fk,'exact');
					$category_name = $CT->prepare_output( $lrec['category_name'] );
				}
				if (!empty($specialty_id_fk)) {
					$srec = $SP->getRecordByField('specialty_id', $specialty_id_fk,'exact');
					$specialty_name = $SP->prepare_output( $srec['specialty_name'] );
				}
				if (!empty($county_id_fk)) {
					$cnrec = $CN->getRecordByField('county_id', $county_id_fk,'exact');
					$county_name = $CN->prepare_output( $cnrec['county_name'] );
				}
				
				$dstatus = $M->getStatus($dstatus);
					
					
			
			// Miscellaneous glyphs, UTF-8
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$cellNo, $specialty_name)
						->setCellValue('B'.$cellNo, $npi)
						->setCellValue('C'.$cellNo, $doctor_name)
						->setCellValue('D'.$cellNo, $address_1)
						->setCellValue('E'.$cellNo, $address_2)
						->setCellValue('F'.$cellNo, $phone)
						->setCellValue('G'.$cellNo, $fax)
						->setCellValue('H'.$cellNo, $email)
						->setCellValue('I'.$cellNo, $website)
						->setCellValue('J'.$cellNo, $dstatus)
						->setCellValue('K'.$cellNo, $city)
						->setCellValue('L'.$cellNo, $state)
						->setCellValue('M'.$cellNo, $zip)
						->setCellValue('N'.$cellNo, $county);
						
			
		
		} // end for 
		
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
			
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="doctor-list-export-data.xlsx"');
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
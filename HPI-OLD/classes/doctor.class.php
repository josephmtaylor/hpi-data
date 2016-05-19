<?php
/**
 * PHP Class to doctors access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('doctor.class.php');
 * $Ad = new DoctorClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: doctors.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */
require_once "constants.php";
require_once "db.class.php";
require_once "simpleimage.class.php";
require_once "address.class.php";
require_once "doctoraddress.class.php";

class DoctorClass extends Db { 

		private $table = 'doctors';
		
		private $data = array();
		private $insertId = '';
		/* Constructor
		 * 
		 */

		function __construct() {
		
			parent::__construct();
		}
		
		/* This function calls  getTableCount function and returns table total
		 * @param $fieldName
		 * @param $searchKeyword
		 * @param $searchStatus / (empty/exact) 
		 * @return int $sqlCount
		 */

		function getTotal($fieldName='', $searchKeyword='', $searchStatus='')
		{
			$sqlCount = $this->getTableCount($this->table, $fieldName , $searchKeyword, $searchStatus );
			return $sqlCount;
			
		}
		
		/* This function calls  getTableCountArray function and returns table total
		 * @param string $fieldName default Null 
		 * @param string $searchKeyword default Null 
		 * @param string $searchStatus default Null 
		 * @return int $sqlCount
		 */

		function getTotalArray($fieldName='', $searchKeyword='', $searchStatus='')
		{
			$sqlCount = $this->getTableCountByArray($this->table, $fieldName , $searchKeyword, $searchStatus );
			return $sqlCount;
			
		}
		
		
		/* This function calls  getTableRecord function and returns table record multiple array
		 * @param string $fieldName
		 * @param string $searchValue
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @param string $offset
		 * @param string $limit
		 * @return array $sqlRecord
		 */

		function getList($fieldName='', $searchValue='', $orderByField='', $orderByValue='', $offset ='', $limit ='')
		{
			$sqlRecord = $this->getTableRecordList($this->table, $fieldName, $searchValue, $orderByField, $orderByValue, $offset, $limit);
			return $sqlRecord;
		
		}
		
		
		/* This function calls  getTableRecord function and returns table record multiple array
		 * @param array  $fieldName
		 * @param array  $searchValue
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @param string $offset
		 * @param string $limit
		 * @return array $sqlRecord
		 */

		function getListArray($fieldName='', $searchValue='', $searchStatus='', $orderByField='', $orderByValue='', $offset ='', $limit ='')
		{
			$sqlRecord = $this->getTableRecordListArray($this->table,  $fieldName, $searchValue, $searchStatus, $orderByField, $orderByValue, $offset, $limit);
			return $sqlRecord;
		
		}
		
		/* This function calls  getTableRecordByField function and returns table record  single array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getRecordByField($fieldName, $searchKey, $searchStatus = '', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getTableRecordByField($this->table, $fieldName , $searchKey, $searchStatus,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}
		
		
		/* This function calls  getSelectedTableRecordByField function and returns table record  single array
		 * @param string $selectedFields - seperated by comma (e.g. str = "id, name"; )
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getSelectedFieldsRecordByField($selectedFields, $fieldName, $searchKey, $searchStatus='', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getSelectedTableRecordByField($this->table, $selectedFields, $fieldName , $searchKey, $searchStatus, $orderByField, $orderByValue );
			return $sqlRecord;
			
		}	
		
		/* This function calls  getTableRecordListByField function and returns table record  single array
		 * @param string $selectedFields - seperated by array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getSelectedFieldsRecordListByField($selectedFields, $fieldName, $searchKey, $searchStatus = '', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getSelectedTableRecordListByField($this->table, $selectedFields, $fieldName , $searchKey, $searchStatus,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}
		
		/* This function calls  getSelectedTableRecordListArray function and returns table record  multiple array
		 * @param string $selectedFields - seperated by comma (e.g. str = "id, name"; )
		 * @param array $fieldName
		 * @param array $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @param string $offset
		 * @param string $limit
		 * @return int $sqlCount
		 */
		
		function getSelectedFieldsRecordListArray($selectedFields, $fieldName, $searchKey, $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			$sqlRecord = $this->getSelectedTableRecordListArray($this->table, $selectedFields, $fieldName , $searchKey,$searchStatus,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}		
		
		
		
		/* This function calls  deleteTableRecord function and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function delete($fieldName, $recordKey) {
			
			$delImage = $this->deleteDoctorImage($recordKey, $image='image');
			
			$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $recordKey );
			return $sqlRecord;
		
		}
		
		
		
		/* This function calls  deleteTableRecord function and returns int
		 * @param string $fieldName
		 * @param array $searchKey
		 * @return int $sqlRecord
		 */
		
		function deleteArray($fieldName, $searchKeyArray) {
	
				$n = count($searchKeyArray);
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $searchKeyArray[$i];
						
						$delImage = $this->deleteDoctorImage($aid, $image='image');
						$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $aid );
					}
					
				}
					return 1;
		}
		
		
		
		
		function changeDoctorRecordArray($doctorIdArray, $status) {
	
				$n = count($doctorIdArray);
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $doctorIdArray[$i];
					
						$sqlRecord = $this->changeDoctorRecordStatus($aid, $status );
					}
					
				}
					return 1;
		}
		
		
		
		/* This function changes the status of doctors and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function changeDoctorRecordStatus($doctorId, $status) {
		
			$sqlQuery  =  "UPDATE ". $this->table ."  SET  `status` = '$status', `date_updated` = NOW() "; 
			
			if (!empty($doctorId))
				$sqlQuery .= " WHERE  `doctor_id` = '$doctorId'  ";
		
		
			$sqlResult = 	$this->dbquery($sqlQuery) or die(mysql_error());				
			return $sqlResult;
		}
		
		
		function isIdPresent($doctor_id)
		{
			$fieldName = 'doctor_id';
			$searchValue = $doctor_id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
		
		function isDoctorImage($doctorId, $selectedFields='image')
		{
			
			$sqlRec = $this->getSelectedFieldsRecordByField($selectedFields, $fieldName='doctor_id', $doctorId);;
			return $sqlRec;
		}
		
		
		function checkEmailForUpdate($id, $email) 
		{

			$sqlQuery = "SELECT doctor_id FROM `" . $this->table . "` WHERE `email` = '$email' and `doctor_id` != '$id' ";
			
			$sqlResult = $this->dbquery($sqlQuery) or die( "check email error " . __LINE__);
			$sqlCount = $this->dbnumrow($sqlResult); //or die(mysql_error());
			
			if (!empty($sqlCount))
				return $sqlCount;
			else
				return 0;	

		}
		
		 
		function createDoctor($sessionValues)
		{
				$doctorid			=	$this->prepare_input($sessionValues['doctor_id']);
				$first_name 		=	$this->prepare_input($sessionValues['first_name']);
				$middle_name 		=	$this->prepare_input($sessionValues['middle_name']);
				$last_name  		=	$this->prepare_input($sessionValues['last_name']);
				$fullname	  		=	$this->prepare_input($sessionValues['fullname']);
				$notes  			=	$this->prepare_input($sessionValues['notes']);
				$address_1  		=	$this->prepare_input($sessionValues['address_1']);
				$address_2 			=	$this->prepare_input($sessionValues['address_2']);
				$city_id 			=	$this->prepare_input($sessionValues['city_id']);
				$state_id 			=	$this->prepare_input($sessionValues['state_id']);
				$zip_id 			=	$this->prepare_input($sessionValues['zip_id']);
				$county_id_fk		=	$this->prepare_input($sessionValues['county_id_fk']);
				$specialty_id_fk  	=	$this->prepare_input($sessionValues['specialty_id_fk']);
				$category_id_fk 	=	$this->prepare_input($sessionValues['category_id_fk']);
				$email  			=	$this->prepare_input($sessionValues['email']);
				$license_status  	=	$this->prepare_input($sessionValues['license_status']);
				$phone 				=	$this->prepare_input($sessionValues['phone']);
				$fax  				=	$this->prepare_input($sessionValues['fax']);
				$website 			=	$this->prepare_input($sessionValues['website']);
				$npi  				=	$this->prepare_input($sessionValues['npi']);
				$main_office		=	$this->prepare_input($sessionValues['main_office']);

				$import = 0;
				if (isset($sessionValues['import'])) {
					$import = $this->prepare_input($sessionValues['import']);
				}
				$image = ''; 
				if (!empty($sessionValues['image']))
					$image 				=	$this->prepare_input($sessionValues['image']);
	
				$status 			=	$this->prepare_input($sessionValues['status']);
				$updated_by			=	$sessionValues['updated_by'];
				$maxfilesize		= 	$sessionValues['maxfilesize'];

				//echo ' + id : ' . $doctorid; 
				if ($doctorid == 0 or empty($doctorid)) {
					$DoctorCreated = 0 ;
				}
				else {
					//echo ' + id : ' . $doctorid; 
					$DoctorCreated = $this->isIdPresent($doctorid);
				}

				if (!empty($email)) { 
					$isEmailRegistered = $this->getTotal('email', $email, 'exact');	
					if ($isEmailRegistered > 0)
						$DoctorCreated = 1;
				}

				if ($import == 1) { 
					$fieldArray = array ('fullname', 'npi' , 'specialty_id_fk' , 'phone' , 'fax' );
					$keyArray = array($fullname, $npi,  $specialty_id_fk , $phone , $fax );
					$DoctorCreated = $this->getTotalDoctorBySearchArray($fieldArray, $keyArray, $searchStatus='exact');

					$docRec = $this->getSelectedFieldsRecordListArray($selectedFields='doctor_id', $fieldArray, $keyArray, $searchStatus='exact');
					$doctorid = $docRec[0]['doctor_id'];
				}

				if ($DoctorCreated == 0 ) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." ( `doctor_id` , `first_name` , `middle_name`,  `last_name` , `fullname`,  `notes` , `specialty_id_fk`,  `category_id_fk` ,  `email`  ,  `license_status`, `phone` , `fax` , `website`,  `npi` , `image` ,  `status` , `date_created` , `date_updated` , `updated_by`  )  
									 VALUES (  NULL , '$first_name' , '$middle_name' , '$last_name' , '$fullname' , '$notes' , '$specialty_id_fk' , '$category_id_fk' , '$email' , '$license_status' , '$phone' , '$fax' , '$website' , '$npi' , '$image' , '$status' , NOW() , NOW() , '$updated_by' ) ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die('ins doct:'.mysql_error());

						$doctorid = mysql_insert_id();
						$this->insertId = $_SESSION['doctid'] = $doctorid;
				}
				else if ($DoctorCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET
									      `first_name` = '$first_name' , 
										  `middle_name` = '$middle_name' , 
										  `last_name` = '$last_name' , 
										  `fullname` = '$fullname' ,  
										  `notes` = '$notes' ,  
										  `specialty_id_fk` = '$specialty_id_fk' ,  
										  `category_id_fk` = '$category_id_fk' , 
										  `email` = '$email' ,  
										  `license_status` = '$license_status' ,  
										  `phone` = '$phone' , 
										  `fax` = '$fax' ,
										  `website` = '$website' , 
										  `npi` = '$npi' ,  
										  `status` = '$status',
										  `date_updated` = NOW(),
										  `updated_by` = '$updated_by'
									WHERE `doctor_id` = '$doctorid'  ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die('edt doct:'.mysql_error());
						$_SESSION['doctid'] = $doctorid;
				}
				
				if (!empty($address_1) and !empty($city_id) and !empty($state_id) and !empty($zip_id)) { 
				
					$A = new AddressClass;
				
					$fieldArray = array ('address_1', 'city_id', 'state_id' , 'zip_id', 'county_id');
					$keyArray = array($address_1, $city_id, $state_id, $zip_id, $county_id_fk);

					$isAddressFound = $A->getRecordCountBySearchArray($fieldArray, $keyArray, $searchStatus='exact');

					if ($isAddressFound == 0) {
							$arrayValues = array (  'fl' => 'add', 'id' => 0, 'doctor_id' => $doctorid, 'address_1' => $address_1, 'address_2' => $address_2, 
													'city_id' => $city_id, 'state_id' => $state_id, 'zip_id' => $zip_id, 'county_id' => $county_id_fk , 
													'phone' => $phone, 'fax' => $fax, 'updated_by' => $loggedUser
												  );
							$editAddress = $A->editAddress($arrayValues);
							empty($arrayValues);
					}

					// find the address id
					$aRec = $A->getSelectedFieldsRecordListArray($selectedFields='id', $fieldArray, $keyArray, $searchStatus='exact');
					$address_id =	$aRec[0]['id'];

					if (!empty($address_id)) { 
						$DA = new DoctorAddressClass;
						$darrayVals = array( 'doctor_id' => $doctorid , 'address_id' => $address_id, 'main' => $main_office);
						$createDoctorAddress =	$DA->createDoctorAddress($darrayVals);
						//print_r($darrayVals); exit();
					}
				}

				if($_FILES['imgfile']['size'] > 0 && $_FILES['imgfile']['error'] == 0)
				{
						if ($fl == 'edit') {
							$this->deleteDoctorImage($doctorid, $img='image');
						}

						$fileName = $_FILES['imgfile']['name'];
						$tmpName  = $_FILES['imgfile']['tmp_name'];
						$fileSize = $_FILES['imgfile']['size'];
						$fileType = $_FILES['imgfile']['type'];
						$file_ext = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

						if ($fileSize > $maxfilesize ) {
								  $imageText = 'The file size ' . $maxfilesize .' is greater than 2 MB. Please upload image of smaller size.'; 
						}
						else {

								  $photoType = 'imglarge';
								  $photoId = 0;

								  $randID =  md5(rand(0,9999).time()); // substr(md5(rand()), 0, 5);
								  $propImage_pt2 = 'pt2_'.$randID. ".". $file_ext ;

								  $image = new SimpleImage();
								  $image->load($_FILES['imgfile']['tmp_name']);

								  $imageWidth =	 $image->getWidth();
								  $imageHeight=	 $image->getHeight();

								  $tempWidth = 391;
								  $tempHeight = 526;

								  if ($imageWidth >= $tempWidth and $imageHeight >= $tempHeight) {
									  $image->resizeToHeight($tempHeight , 'pt2' , $isStamp = 1 );
								  }
								  else if ($imageWidth <= $tempWidth and $imageHeight <= $tempHeight ) {
									  $image->resizeToHeight($imageHeight , 'pt2' , $isStamp = 1);
								  }	
								  else if ($imageWidth < $tempWidth and $imageHeight >= $tempHeight ) {
									  $image->resizeToHeight($tempHeight , 'pt2' , $isStamp = 1);
								  }
								  else if ($imageWidth > $tempWidth and $imageHeight <= $tempHeight ) {
									  $image->resizeToWidth($tempWidth , 'pt2' , $isStamp = 1);
								  }	    

									$image->save('images/doctor/'.$propImage_pt2);

									$sessionValues = array (    "fl" => $fl,
													"doctorid" => $doctorid,
													'photoId' => $photoId, 
													'photoType' => $photoType,  
													'pt2' => $propImage_pt2
												);

									$imageUploaded = $this->uploadDoctorImage($sessionValues, $image='image');
									empty($sessionValues);
						}	
				}
				if ($sqlQueryResult == 1 )
					return 1;
				else
					return 0;
		}	
		
		
		function updateDoctor($sessionValues)
		{
				$doctorid			=	$this->prepare_input($sessionValues['doctor_id']);
				$notes  			=	$this->prepare_input($sessionValues['notes']);
				$specialty_id_fk  	=	$this->prepare_input($sessionValues['specialty_id_fk']);
				$category_id_fk 	=	$this->prepare_input($sessionValues['category_id_fk']);
				$npi  				=	$this->prepare_input($sessionValues['npi']);
				$email  			=	$this->prepare_input($sessionValues['email']);
				$license_status  	=	$this->prepare_input($sessionValues['license_status']);
				$website 			=	$this->prepare_input($sessionValues['website']);
				$status 			=	$this->prepare_input($sessionValues['status']);
				$updated_by			=	$sessionValues['updated_by'];

				$DoctorCreated = $this->isIdPresent($doctorid);
				
				if ($DoctorCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET
									      `notes` = '$notes' ,  
										  `specialty_id_fk` = '$specialty_id_fk' ,  
										  `category_id_fk` = '$category_id_fk' , 
										  `email` = '$email' ,  
										  `license_status` = '$license_status' ,  
										  `website` = '$website' , 
										  `npi` = '$npi' ,  
										  `status` = '$status',
										  `date_updated` = NOW(),
										  `updated_by` = '$updated_by'
									WHERE `doctor_id` = '$doctorid'  ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						$_SESSION['doctid'] = $doctorid;
					
				}
				
				if ($sqlQueryResult == 1 )
					return 1;
				else
					return 0;
				

		}	
		
		
		function editDoctor($sessionValues)
		{
				$doctorid			=	$this->prepare_input($sessionValues['doctor_id']);
				$first_name 		=	$this->prepare_input($sessionValues['first_name']);
				$middle_name 		=	'';
				$last_name  		=	$this->prepare_input($sessionValues['last_name']);
				$fullname	  		=	$this->prepare_input($sessionValues['full_name']);
				$notes  			=	$this->prepare_input($sessionValues['notes']);
				$county_id_fk		=	0;
				$specialty_id_fk  	=	$this->prepare_input($sessionValues['specialty_id_fk']);
				$category_id_fk 	=	$this->prepare_input($sessionValues['category_id_fk']);
				$email  			=	'';
				$license_status  	=	'';
				$website 			=	'';
				$npi  				=	$this->prepare_input($sessionValues['npi']);
				$phone  			=	$this->prepare_input($sessionValues['phone']);
				$fax  				=	$this->prepare_input($sessionValues['fax']);
				$image = ''; 
				$status 			=	$this->prepare_input($sessionValues['status']);
				
				if (isset($sessionValues['advertiser']))
					$advertiser	= $this->prepare_input($sessionValues['advertiser']);
				else
					$advertiser = 'N';
				
				$updated_by			=	$sessionValues['updated_by'];

				if (!empty($doctorid))
					$DoctorCreated = 1;
				else
					$DoctorCreated = 0;

				if ($DoctorCreated == 0 ) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." ( `doctor_id` , `first_name` , `middle_name`,  `last_name` , `fullname`,  `notes` , `specialty_id_fk`,  `category_id_fk` ,  `email`  ,  `license_status`,  `phone` , `fax` , `website`,  `npi` , `image` ,  `status` , `date_created` , `date_updated` , `updated_by`  )  
									 VALUES (  NULL , '$first_name' , '$middle_name' , '$last_name' , '$fullname' , '$notes' , '$specialty_id_fk' , '$category_id_fk' , '$email' , '$license_status' , '$phone' , '$fax' , '$website' , '$npi' , '$image' , '$status' , NOW() , NOW() , '$updated_by' ) ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());

						$doctorid = mysql_insert_id();
						$this->insertId = $_SESSION['doctid'] = $doctorid;
						$_SESSION['new'] = 1;
						return 1;
				}
				else {
						$sqlQuery = "UPDATE ". $this->table ." SET
									      `first_name` = '$first_name' , 
										  `middle_name` = '$middle_name' , 
										  `last_name` = '$last_name' , 
										  `fullname` = '$fullname' ,  
										  `notes` = '$notes' ,  
										  `specialty_id_fk` = '$specialty_id_fk' ,  
										  `category_id_fk` = '$category_id_fk' , 
										  `npi` = '$npi' ,  
										  `phone` = '$phone' ,
										  `fax` = '$fax' , 
										  `status` = '$status',
										  `date_updated` = NOW(),
										  `updated_by` = '$updated_by',
										  `advertiser` = '$advertiser'
									WHERE `doctor_id` = '$doctorid'  ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						$_SESSION['doctid'] = $doctorid;
						$_SESSION['new'] = 0;

						return 1;
				}

		}

		/*
		 * This function sends User Level
		 * param null
		 * return string userlevel
		 */

		function lastUpdatedId() {
			if (!empty($this->insertId))
				return $this->insertId;
			else
				return 0;
		}

		function uploadDoctorImage($sessionValues, $image)
		{

			$photoType = $sessionValues['photoType'];
			$photoId = $sessionValues['photoId'];
			$thumbnail = $sessionValues['pt1'];
			$image = $sessionValues['pt2'];
			$doctorId = $sessionValues['doctorid'];

			if (!empty($photoType)) { 			
					if ($photoType == 'imglarge' ) { 
						$sqlQuery = "UPDATE " . $this->table ."  SET `image` = '$image' , `date_updated` = NOW()  WHERE doctor_id = '$doctorId'";
						$sqlResult = $this->dbquery($sqlQuery) or die ("Error LPhoto Update : ".mysql_error());
						if ($sqlResult == 1)
							return 1;
					}
			}
			else 
				return 0;
		}

		/* This function checks if the userphoto is present , delete if present
		 * @param int id
		 * return int result
		 */
		
		function deleteDoctorImage($doctorId, $image)
		{
				$sqlQuery = "SELECT ". $image ." FROM " . $this->table ." WHERE doctor_id = '$doctorId' ";
				$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());

				$pInfo = $this->dbfetch($sqlResult);

				if ($image == 'image') { 
					$photo = $pInfo['image'];

					if (!empty($photo)) {
						$imlocation = "images/doctor/";
						$file_location = $imlocation.$photo;
						if (file_exists($file_location)) {
							$unlink =unlink($file_location);
						}
						return 1;
					}
				}
		}
		
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */


		function getDoctorInfo($doctorId)
		{
			$sqlTotal  = $this->getTotal('doctor_id', $doctorId, 'exact');
			if(!$sqlTotal || ($sqlTotal < 1)){
				 return NULL;
			}
			$sqlRecord  = $this->getRecordByField('doctor_id', $doctorId, 'exact');
			return $sqlRecord;
		}

		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */

		function getTotalDoctor($userIdFk)
		{
			$sqlTotal  = $this->getTotal();
			if ($sqlTotal) {
				return $sqlTotal;
			}
			else if(!$sqlTotal || ($sqlTotal < 1)){
				 return NULL;
			}
			//$sqlRecord  = $this->getRecordByField('id', $doctorId, 'exact');
			//return $sqlRecord;
		}

		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */

		function getTotalDoctorBySearchArray($fieldName, $searchKeyword, $searchStatus)
		{
			$sqlTotal  = $this->getTotalArray($fieldName, $searchKeyword, $searchStatus);
			if ($sqlTotal) {
				return $sqlTotal;
			}
			else if(!$sqlTotal || ($sqlTotal < 1)){
				 return 0;
			}			
		}		
		
		function additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl, $fieldToAdd) 
		{
				if (!empty($fieldNameArray) && !empty($searchValueArray)) {
					$cntname = count($fieldNameArray);
					$cntval =  count($searchValueArray);
					if (($cntname > 0) && ($cntname == $cntval)) { 
	
						for($i=0; $i <$cntname; $i++) {
								if ($fieldNameArray[$i] == $fieldToAdd) { 

									if ($searchStatus == '')
										$sqlQuery .= " AND ". $Tbl .".`$fieldNameArray[$i]` like '%$searchValueArray[$i]%' " ;
	
									if ($searchStatus == 'exact')
										$sqlQuery .= " AND ". $Tbl .".`$fieldNameArray[$i]` = '$searchValueArray[$i]' " ;
								}
						} // end for
					} // end if
				} // end if
						
						return $sqlQuery;
		}

		function getTotalSearchArray($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{

			$sqlQuery = "SELECT DISTINCT(D.doctor_id), D.first_name,D.middle_name, D.last_name,D.fullname, D.npi, D.date_updated,  DS.specialty_name
						 FROM doctors D, doctor_address DA, address_master A, city C, state S, zipcodes Z, doctor_specialties DS ";

			/*if (in_array( 'category_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", doctor_categories CT ";				
			}*/
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", doctor_publication P ";
			}

				$sqlQuery .= "WHERE 1 = 1 AND DA.doctor_id = D.doctor_id 
									AND A.id = DA.address_id 
									AND C.city_id = A.city_id 
									AND S.state_id = A.state_id
									AND	Z.zip_id = A.zip_id
									AND	DS.specialty_id = D.specialty_id_fk";
			
			/*if (in_array( 'category_id_fk', $fieldNameArray )) {
				$sqlQuery .= "AND CT.cat_id = D.category_id_fk ";				
			}*/						
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= " AND P.doctor_id = D.doctor_id  ";
			}
			
			if (in_array( 'first_name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='first_name') ;
			}
			if (in_array( 'last_name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='last_name') ;
			}
			if (in_array( 'npi', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='npi') ;
			}
			if (in_array( 'status', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='status') ;
			}
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'specialty_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='specialty_id_fk') ;
			}
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='P', $fieldToAdd='pub_id') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {
				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}

			if (isset($offset) && !empty($limit)) {
				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
		//	echo '<pre>';echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());

			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;
		}
		
		
		function getTotalSearchList($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			$sqlQuery = "SELECT DISTINCT(D.doctor_id), D.first_name,D.middle_name, D.last_name,D.fullname, D.npi, D.date_updated, D.status, DS.specialty_name
						 FROM doctors D, doctor_address DA, address_master A, city C, state S, zipcodes Z, doctor_specialties DS ";
			
			/*if (in_array( 'category_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", doctor_categories CT ";				
			}*/
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", doctor_publication P ";
			}
				$sqlQuery .= "WHERE 1 = 1 AND DA.doctor_id = D.doctor_id 
									AND A.id = DA.address_id 
									AND C.city_id = A.city_id 
									AND S.state_id = A.state_id
									AND	Z.zip_id = A.zip_id
									AND	DS.specialty_id = D.specialty_id_fk";
			
			/*if (in_array( 'category_id_fk', $fieldNameArray )) {
				$sqlQuery .= "AND CT.cat_id = D.category_id_fk ";				
			}	*/					
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= " AND P.doctor_id = D.doctor_id  ";
			}
			
			if (in_array( 'first_name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='first_name') ;
			}
			if (in_array( 'last_name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='last_name') ;
			}
			if (in_array( 'npi', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='npi') ;
			}
			if (in_array( 'status', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='status') ;
			}
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'specialty_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='specialty_id_fk') ;
			}
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='P', $fieldToAdd='pub_id') ;
			}
			if (!empty($orderByField) && !empty($orderByValue)) {
				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}

			if (isset($offset) && !empty($limit)) {
				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}

		//	echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());

			while ($sqlArray = $this->dbfetch($sqlQueryResult)) {
				$sqlRecordArray[] = $sqlArray;
			}
				return $sqlRecordArray;

		}
		
		function addButtons() {

			$link = "   <select name=\"action\" id=\"action\">
							<option value=\"cnverified\">Set Status to Verified</option>
							<option value=\"cnunverified\">Set Status to Unverified</option>
							<!--<option value=\"cndeleted\">Delete Selected</option>-->
						</select>
					   <input type=\"submit\" name=\"submit\" value=\"Apply to Selected\" onClick=\"return checkdeletion();\" />
					   ";
						
			return $link;
		}

		function __destruct() {
			parent::__destruct();
		}
		
}
?>
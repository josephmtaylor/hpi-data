<?php
/**
 * PHP Class to centers access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('center.class.php');
 * $Ad = new CenterClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: centers.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */
require_once "constants.php";
require_once "db.class.php";
require_once "address.class.php";
require_once "centeraddress.class.php";

class CenterClass extends Db { 

		private $table = 'centers';
		
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
			
			$delImage = $this->deleteCenterImage($recordKey, $image='image');
			
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
						$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $aid );
					}
					
				}
					return 1;
		}
		
		
		
		
		function changeCenterRecordArray($centerIdArray, $status) {
	
				$n = count($centerIdArray);
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $centerIdArray[$i];
					
						$sqlRecord = $this->changeCenterRecordStatus($aid, $status );
					}
					
				}
					return 1;
		}
		
		
		
		/* This function changes the status of centers and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function changeCenterRecordStatus($centerId, $status) {
		
			$sqlQuery  =  "UPDATE ". $this->table ."  SET  `status` = '$status', `date_updated` = NOW() "; 
			
			if (!empty($centerId))
				$sqlQuery .= " WHERE  `center_id` = '$centerId'  ";
		
		
			$sqlResult = 	$this->dbquery($sqlQuery) or die(mysql_error());				
			return $sqlResult;
		}
		
		
		function isIdPresent($center_id)
		{
			$fieldName = 'center_id';
			$searchValue = $center_id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
/*		function checkEmailForUpdate($id, $email) 
		{

			$sqlQuery = "SELECT center_id FROM `" . $this->table . "` WHERE `email` = '$email' and `center_id` != '$id' ";
			
			$sqlResult = $this->dbquery($sqlQuery) or die( "check email error " . __LINE__);
			$sqlCount = $this->dbnumrow($sqlResult); //or die(mysql_error());
			
			if (!empty($sqlCount))
				return $sqlCount;
			else
				return 0;	

		}*/
		
		 
		function createCenter($sessionValues)
		{
				
			
				$centerid			=	$this->prepare_input($sessionValues['center_id']);
				$name		 		=	$this->prepare_input($sessionValues['name']);
				$specialty_id_fk  	=	$this->prepare_input($sessionValues['specialty_id_fk']);
				$category_id_fk 	=	$this->prepare_input($sessionValues['category_id_fk']);
				$chapter_id_fk  	=	$this->prepare_input($sessionValues['chapter_id_fk']);
				$description		=	$this->prepare_input($sessionValues['description']);
				$notes  			=	$this->prepare_input($sessionValues['notes']);
				$website 			=	$this->prepare_input($sessionValues['website']);
				$npi	  			=	$this->prepare_input($sessionValues['npi']);
				$admission			=	$this->prepare_input($sessionValues['admission']);
				$address_1  		=	$this->prepare_input($sessionValues['address_1']);
				$address_2 			=	$this->prepare_input($sessionValues['address_2']);
				$city_id 			=	$this->prepare_input($sessionValues['city_id']);
				$state_id 			=	$this->prepare_input($sessionValues['state_id']);
				$zip_id 			=	$this->prepare_input($sessionValues['zip_id']);
				$county_id_fk		=	$this->prepare_input($sessionValues['county_id_fk']);
				$phone 				=	$this->prepare_input($sessionValues['phone']);
				$fax 				=	$this->prepare_input($sessionValues['fax']);
				$main_office		=	$this->prepare_input($sessionValues['main_office']);
				
				$import = 0;
				if (isset($sessionValues['import'])) {
					$import = $this->prepare_input($sessionValues['import']);
				}
				
	
				$status 			=	$this->prepare_input($sessionValues['status']);
				$updated_by			=	$sessionValues['updated_by'];
				
				//echo ' + id : ' . $centerid; 
				if ($centerid == 0 or empty($centerid)) {
					$CenterCreated = 0 ;
				}
				else {
					//echo ' + id : ' . $centerid; 
					$CenterCreated = $this->isIdPresent($centerid);
				}
				
				/*if (!empty($email)) { 
					$isEmailRegistered = $this->getTotal('email', $email, 'exact');	
					if ($isEmailRegistered > 0)
						$CenterCreated = 1;
				}*/
				
				if ($import == 1) { 
					$fieldArray = array ('name', 'category_id_fk' , 'phone' , 'fax');
					$keyArray = array($name,  $category_id_fk , $phone , $fax);
					$CenterCreated = $this->getTotalCenterBySearchArray($fieldArray, $keyArray, $searchStatus='exact');
					
					$docRec = $this->getSelectedFieldsRecordListArray($selectedFields='center_id, status', $fieldArray, $keyArray, $searchStatus='exact');
					$centerid = $docRec[0]['center_id'];
					$cstatus = $docRec[0]['status']; 
				}
				//echo '<br /> '. $name . ' | ' . $category_id_fk . ' | ' . $phone . ' 	= ' . $CenterCreated;

				if ($CenterCreated == 0 ) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." ( `center_id` , `name` , `specialty_id_fk`,  `category_id_fk`,  `chapter_id_fk` , `description`, `notes` ,  `phone` , `fax` , `website`, `npi` , `admission` ,  `status` , `date_created` , `date_updated` , `updated_by`  )  
									 VALUES (  NULL , '$name' , '$specialty_id_fk' , '$category_id_fk' , '$chapter_id_fk' , '$description' , '$notes' , '$phone' , '$fax' ,  '$website' , '$npi' , '$admission' , '$status' , NOW() , NOW() , '$updated_by' ) ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die('Error Insert : ' .mysql_error());
						
						$centerid = mysql_insert_id();
						$this->insertId = $_SESSION['centid'] = $centerid;

				}
				else if ($CenterCreated > 0) 
				{
						if ($cstatus == 'V') {
							$_SESSION['centid'] = $centerid;
						}
						else {
						
							$sqlQuery = "UPDATE ". $this->table ." SET
											  `name` = '$name' , 
											  `specialty_id_fk` = '$specialty_id_fk' , 
											  `category_id_fk` = '$category_id_fk' , 
											  `chapter_id_fk` = '$chapter_id_fk' ,  
											  `description` = '$description' , 
											  `notes` = '$notes' ,  
											  `website` = '$website' , 
											  `phone` = '$phone' ,
											  `fax` = '$fax' ,
											  `npi` = '$npi' , 
											  `admission` = '$admission' ,  
											  `status` = '$status',
											  `date_updated` = NOW(),
											  `updated_by` = '$updated_by'
										WHERE `center_id` = '$centerid'  ";
							$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
							$_SESSION['centid'] = $centerid;
						}
				}
				
				//if (!empty($address_1) and !empty($city_id) and !empty($state_id) and !empty($zip_id)) { 
				if (!empty($address_1)) { 

					$A = new AddressClass;

					$isAddressFound = 0; $notes = ''; $status = 'N'; $overwritePhone = 0;
					
					$fieldArray = array ('address_1', 'city_id', 'state_id' , 'zip_id', 'county_id');
					$keyArray = array($address_1, $city_id, $state_id, $zip_id, $county_id_fk);
					
					$isAddressFound = $A->getRecordCountBySearchArray($fieldArray, $keyArray, $searchStatus='exact');

					if ($isAddressFound == 0) {
							$arrayValues = array (  "fl" => 'add', "id" => 0, "center_id" => $centerid, "address_1" => $address_1, "address_2" => $address_2, 
													'city_id' => $city_id, 'state_id' => $state_id, 'zip_id' => $zip_id, 'county_id' => $county_id_fk , 
													'phone' => $phone, 'fax' => $fax , 'notes' => $notes, 'status' => $status, 'updated_by' => $updated_by,
													'phoneFound' => $phoneFound
												  );
							$editAddress = $A->editAddress($arrayValues);
							empty($arrayValues);
					}

					// find the address id
					$aRec = $A->getSelectedFieldsRecordListArray($selectedFields='id', $fieldArray, $keyArray, $searchStatus='exact');
					$address_id =	$aRec[0]['id'];

					if (!empty($address_id)) { 
						$DA = new CenterAddressClass;
						$darrayVals = array( 'center_id' => $centerid , 'address_id' => $address_id, 'main' => $main_office);
						$createCenterAddress =	$DA->createCenterAddress($darrayVals);
					}
				}

						if ($sqlQueryResult == 1 )
							return 1;
						else
							return 0;
				

		}	
		
		
		function updateCenter($sessionValues)
		{
				$centerid			=	$this->prepare_input($sessionValues['center_id']);
				$notes  			=	$this->prepare_input($sessionValues['notes']);
				$chapter_id_fk  	=	$this->prepare_input($sessionValues['chapter_id_fk']);
				$category_id_fk 	=	$this->prepare_input($sessionValues['category_id_fk']);
				$admission			=	$this->prepare_input($sessionValues['admission']);
				$website 			=	$this->prepare_input($sessionValues['website']);
				$status 			=	$this->prepare_input($sessionValues['status']);
				$updated_by			=	$sessionValues['updated_by'];
				
				$CenterCreated = $this->isIdPresent($centerid);
				
				if ($CenterCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET
									      `notes` = '$notes' ,  
										  `category_id_fk` = '$category_id_fk' , 
										  `chapter_id_fk` = '$chapter_id_fk' ,  
										  `website` = '$website' , 
										  `admission` = '$admission' ,  
										  `status` = '$status',
										  `date_updated` = NOW(),
										  `updated_by` = '$updated_by'
									WHERE `center_id` = '$centerid'  ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						$_SESSION['centid'] = $centerid;
					
				}
				
				if ($sqlQueryResult == 1 )
					return 1;
				else
					return 0;
		}	
		
		
		function editCenter($sessionValues)
		{
				
			
				$centerid			=	$this->prepare_input($sessionValues['center_id']);
				$name		  		=	$this->prepare_input($sessionValues['name']);
				$description		=	$this->prepare_input($sessionValues['description']);
				$notes  			=	$this->prepare_input($sessionValues['notes']);
			
				$county_id_fk		=	0;
				$specialty_id_fk  	=	$this->prepare_input($sessionValues['specialty_id_fk']);
				$category_id_fk 	=	$this->prepare_input($sessionValues['category_id_fk']);
				$npi  				=	$this->prepare_input($sessionValues['npi']);
				$phone  			=	$this->prepare_input($sessionValues['phone']);
				$fax  				=	$this->prepare_input($sessionValues['fax']);
				
				$status 			=	$this->prepare_input($sessionValues['status']);

				if (isset($sessionValues['advertiser']))
					$advertiser	= $this->prepare_input($sessionValues['advertiser']);
				else
					$advertiser = 'N';
					
				$updated_by			=	$sessionValues['updated_by'];
				
				if (!empty($centerid))
					$CenterCreated = 1;
				else
					$CenterCreated = 0;
				
				if ($CenterCreated == 0 ) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." ( `center_id` , `name` , `description`,  `notes` , `specialty_id_fk`,  `category_id_fk` ,   `npi` , `phone` , `fax` ,  `status` , `date_created` , `date_updated` , `updated_by`  )  
									 VALUES (  NULL , '$name' , '$description' , '$notes' , '$specialty_id_fk' , '$category_id_fk' ,  '$npi' , '$phone' , '$fax' ,  '$status' , NOW() , NOW() , '$updated_by' ) ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$centerid = mysql_insert_id();
						$this->insertId = $_SESSION['doctid'] = $centerid;
						$_SESSION['new'] = 1;
						return 1;
				}
				else {
						$sqlQuery = "UPDATE ". $this->table ." SET
									      `name` = '$name' , 
										  `description` = '$description' ,  
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
									WHERE `center_id` = '$centerid'  ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						$_SESSION['cnid'] = $centerid;
						$_SESSION['new'] = 0;

						return 1;
				}
		}
		
		/*
		 * This function sends Last InsertedId
		 * param null
		 * return string userlevel
		 */

		function lastUpdatedId() {
			if (!empty($this->insertId))
				return $this->insertId;
			else
				return 0;
		}
		
		
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getCenterInfo($centerId)
		{
			
			$sqlTotal  = $this->getTotal('center_id', $centerId, 'exact');
			
			if(!$sqlTotal || ($sqlTotal < 1)){
				 return NULL;
			}

			$sqlRecord  = $this->getRecordByField('center_id', $centerId, 'exact');
			return $sqlRecord;
			
		}
				
		
		
		
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getTotalCenter($userIdFk)
		{
			
			$sqlTotal  = $this->getTotal();
			
			if ($sqlTotal) {
				return $sqlTotal;
			}
			else if(!$sqlTotal || ($sqlTotal < 1)){
				 return NULL;
			}
			
			
			
			//$sqlRecord  = $this->getRecordByField('id', $centerId, 'exact');
			//return $sqlRecord;
			
		}
			
			
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getTotalCenterBySearchArray($fieldName, $searchKeyword, $searchStatus)
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
			//	echo '<pre>'; print_r($fieldNameArray);
				
				if (!empty($fieldNameArray) && !empty($searchValueArray)) {

							$cntname = count($fieldNameArray);
							$cntval =  count($searchValueArray);
							
						//	echo   ' == '. $fieldToAdd . '<br /><br />';

							if (($cntname > 0) && ($cntname == $cntval)) { 

								for($i=0; $i <$cntname; $i++) {

										if ($fieldNameArray[$i] == $fieldToAdd) { 
											
											if ($searchStatus == '')
	
												$sqlQuery .= " AND ". $Tbl .".`$fieldNameArray[$i]` like '%$searchValueArray[$i]%' " ;
	
											if ($searchStatus == 'exact')
	
												$sqlQuery .= " AND ". $Tbl .".`$fieldNameArray[$i]` = '$searchValueArray[$i]' " ;
										}
										
									//	echo 'Query inside === '. $sqlQuery . '<br /><br />'; 
										
								} // end for
							} // end if
						} // end if
						
						return $sqlQuery;
		}
		
		
		function getTotalSearchArray($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			$sqlQuery = "SELECT DISTINCT(D.center_id), D.name, D.admission, D.date_updated,  DS.category_name, CH.chapter_name
						 FROM centers D, center_address DA, address_master A, city C, state S, zipcodes Z, doctor_categories DS, center_chapters CH  ";
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", center_publication P ";
			}
					
				$sqlQuery .= "WHERE 1 = 1 AND DA.center_id = D.center_id 
									AND A.id = DA.address_id 
									AND C.city_id = A.city_id 
									AND S.state_id = A.state_id
									AND	Z.zip_id = A.zip_id
									AND	DS.cat_id = D.category_id_fk
									AND	CH.chapter_id = D.chapter_id_fk";
									
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= " AND P.center_id = D.center_id  ";
			}
			
			if (in_array( 'name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='name') ;
			}
			if (in_array( 'admission', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='admission') ;
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
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='chapter_id_fk') ;
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
			
			$sqlQuery = "SELECT DISTINCT(D.center_id), D.name, D.admission, D.date_updated,  DS.category_name, CH.chapter_name
						 FROM centers D, center_address DA, address_master A, city C, state S, zipcodes Z, doctor_categories DS, center_chapters CH  ";
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", center_publication P ";
			}
					
				$sqlQuery .= "WHERE 1 = 1 AND DA.center_id = D.center_id 
									AND A.id = DA.address_id 
									AND C.city_id = A.city_id 
									AND S.state_id = A.state_id
									AND	Z.zip_id = A.zip_id
									AND	DS.cat_id = D.category_id_fk
									AND	CH.chapter_id = D.chapter_id_fk";
									
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= " AND P.center_id = D.center_id  ";
			}
			
			if (in_array( 'name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='name') ;
			}
			if (in_array( 'admission', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='admission') ;
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
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus, $Tbl='D', $fieldToAdd='chapter_id_fk') ;
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

			while ($sqlArray = $this->dbfetch($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}

				return $sqlRecordArray;

		}
		
		
		function addButtons() {
			
			$link = "   <select name=\"action\" id=\"action\">
							<option value=\"cnverified\">Set Status to Verified</option>
							<option value=\"cnunverified\">Set Status to Unverified</option>
							<option value=\"cnpending\">Set Status to Pending</option>
							<option value=\"cndone\">Set Status to Done</option>
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
<?php
/**
 * PHP Class to address access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('address.class.php');
 * $Ad = new AddressClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: address.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "session.class.php";
require_once "db.class.php";


class AddressClass extends Db { 

		private $table = 'address_master';
		private $data = array();

		/* Constructor
		 * 
		 */

		function __construct() {
		
			parent::__construct();
		}
		
		/* This function calls  getTableCount function and returns table total
		 * @param void
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
		
		/* This function calls  getTableRecordByField function and returns table record  single array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getRecordByField($fieldName, $searchKey, $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getTableRecordByField($this->table, $fieldName , $searchKey,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}
		
		
		/* This function calls  getTableRecordByField function and returns table record  single array
		 * @param string $selectedFields - seperated by array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getSelectedFieldsRecordByField($selectedFields, $fieldName, $searchKey,  $searchStatus='', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getSelectedTableRecordByField($this->table, $selectedFields, $fieldName , $searchKey, $searchStatus,  $orderByField, $orderByValue );
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
		
		
		
		
		function isIdPresent($id)
		{
			$fieldName = 'id';
			$searchValue = $id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		function getAddress($id) 
		{ 	
			$selectedFields = "address_1, address_2";
			$fieldName = 'id';
			$searchValue = $id;
			$dbrow = $this->getSelectedFieldsRecordByField($selectedFields, $fieldName, $searchValue);
			return $dbrow['address_1'] . ' ' . $dbrow['address_2'];
		}
		
		function isPhoneFound($id, $phone)
		{
			$selectedFields = 'phone';
			$fieldName = array('id', 'phone');
			$searchKey = array($id, $phone);
			$sqlCount = $this->getSelectedFieldsRecordListArray($selectedFields, $fieldName, $searchKey, $searchStatus='exact');
			return $sqlCount;
		}
		
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getRecordCountBySearchArray($fieldName, $searchKeyword, $searchStatus)
		{
			
			$sqlTotal  = $this->getTotalArray($fieldName, $searchKeyword, $searchStatus);
			
			if ($sqlTotal) {
				return $sqlTotal;
			}
			else if(!$sqlTotal || ($sqlTotal < 1)){
				 return 0;
			}
			
		}		
		
		function editAddress($sessionValues)
		{
				//print_r($sessionValues);exit();
				$fl 		  = 	$sessionValues['fl'];
				$id 		  = 	$this->prepare_input($sessionValues['id']);
				$address_1 	  =		$this->prepare_input($sessionValues['address_1']);
				$address_2	  =		$this->prepare_input($sessionValues['address_2']);
				$city_id	  =		$this->prepare_input($sessionValues['city_id']);
				$state_id	  =		$this->prepare_input($sessionValues['state_id']);
				$zip_id		  =		$this->prepare_input($sessionValues['zip_id']);
				$county_id	  =		$this->prepare_input($sessionValues['county_id']);
				$phone		  =		$this->prepare_input($sessionValues['phone']);
				$fax		  =		$this->prepare_input($sessionValues['fax']);
				$status 	  =		$this->prepare_input($sessionValues['status']);
				$notes	 	  =		$this->prepare_input($sessionValues['notes']);
				$updated_by	  =		$sessionValues['updated_by'];
				
				
				if (isset($sessionValues['phoneFound']))
					$phoneFound = $this->prepare_input($sessionValues['phoneFound']);
				else
					$phoneFound = 0;
				
				if (empty($status))
					$status = 'N';
				/*if (strlen($phone) > 12) {
								$notes .= '
Error: Phone length ('.strlen($phone).') 
Original Phone Text: ' . $phone;
					//$phone =  '';
					$status = 'E';
				}*/
			
				$isRecordFound = $this->isIdPresent($id);
				
				if ($isRecordFound == 0) 
				{
						$sqlQuery = "INSERT INTO  ". $this->table ."  ( `id` , `address_1` , `address_2` , `city_id` , `state_id` , `zip_id`, `county_id` ,  `notes` ,`status` , `date_created` , `date_updated` , `updated_by`)  
									 VALUES ( NULL, '$address_1' , '$address_2' , '$city_id' , '$state_id' , '$zip_id', '$county_id' , '$notes' , '$status' , NOW() , NOW() , '$updated_by' ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die('ins addr: '.mysql_error());
						$uid = mysql_insert_id();
						$sessionValues['id'] = $uid;
						
				}
				else if ($isRecordFound > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET `address_1`  = '$address_1' , `address_2` = '$address_2' , `city_id` = '$city_id' , `state_id` = '$state_id' , `zip_id` = '$zip_id' , `county_id` = '$county_id' ";
						
						if ($phoneFound == 0) { 
							$sqlQuery .= " , `phone` = '$phone' , `fax` = '$fax'  " ;
						}
						
						$sqlQuery .= " , `notes` = '$notes' , `status` = '$status' , `date_updated` = NOW(), `updated_by` = '$updated_by'  WHERE id = $id ";
						$sqlQuery = $this->dbquery($sqlQuery) or die('edt addr: '.mysql_error());	
						
				}
						
						if ($sqlQuery == 1 )
							return 1;
						else
							return 0;
				

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
										
										if ($fieldNameArray[$i] == 'doctor_status')
											$fieldNameArray[$i] = 'status';
										
										if ($fieldNameArray[$i] == 'center_status')
											$fieldNameArray[$i] = 'status';

										if ($fieldNameArray[$i] == $fieldToAdd) { 
											
											if ($searchStatus == '')
	
												$sqlQuery .= " AND ". $Tbl .".`$fieldNameArray[$i]` like '%$searchValueArray[$i]%' " ;
	
											if ($searchStatus == 'exact')
	
												$sqlQuery .= " AND ". $Tbl .".`$fieldNameArray[$i]` = '$searchValueArray[$i]' " ;
										}
										
										//echo 'Query inside === '. $sqlQuery . '<br /><br />'; 
										
								} // end for
							} // end if
						} // end if
						
						return $sqlQuery;
		}
		
		
		function getTotalSearchArray($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			$sqlQuery = "SELECT A.id, A.address_1, A.address_2,  A.status,  A.date_updated,  A.updated_by, C.city, S.state, Z.zipcode, D.doctor_id, 
								D.first_name, D.middle_name, D.last_name,D.fullname, D.email, D.website, D.status as dstatus, D.npi, D.phone, D.fax, D.specialty_id_fk, DS.specialty_name ";
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", CNT.county ";				
			}
			
			$sqlQuery .= "FROM address_master A, doctors D, doctor_address DA, city C, state S, zipcodes Z, doctor_specialties DS ";
			
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", doctor_publication P ";
			}
					
				$sqlQuery .= "WHERE 1 = 1 AND DA.address_id  = A.id
									AND D.doctor_id = DA.doctor_id 
									AND C.city_id = A.city_id 
									AND S.state_id = A.state_id
									AND	Z.zip_id = A.zip_id
									AND	DS.specialty_id = D.specialty_id_fk";
									
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= " AND P.doctor_id = D.doctor_id  ";
			}
			
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'specialty_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='specialty_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='P', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'doctor_status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}


			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}

			//echo '<pre>';echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());

			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;

		}
		
		
		function getTotalSearchList($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			$sqlQuery = "SELECT A.id, A.address_1, A.address_2, A.status,  A.date_updated,  A.updated_by, C.city, S.state, Z.zipcode, D.doctor_id, 
								D.first_name, D.middle_name, D.last_name,D.fullname, D.email, D.website, D.status as dstatus, D.npi, D.phone, D.fax, D.specialty_id_fk, D.status as dstatus, DS.specialty_name ";
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", CNT.county ";				
			}
			
			$sqlQuery .= "FROM address_master A, doctors D, doctor_address DA, city C, state S, zipcodes Z, doctor_specialties DS ";
			
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", doctor_publication P ";
			}
					
				$sqlQuery .= "WHERE 1 = 1 AND DA.address_id  = A.id
									AND D.doctor_id = DA.doctor_id 
									AND C.city_id = A.city_id 
									AND S.state_id = A.state_id
									AND	Z.zip_id = A.zip_id
									AND	DS.specialty_id = D.specialty_id_fk";
									
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= " AND P.doctor_id = D.doctor_id  ";
			}
			
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'specialty_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='specialty_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='P', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'doctor_status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}


			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
			
			$setquery = $this->setQuery($sqlQuery);

			//echo '<pre>'. $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());

			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}

				return $sqlRecordArray;

		}
		
		// --
		
		/*function getTotalCenterSearchArray($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, A.city_id, A.state_id, A.zip_id,  A.status, A.date_updated, A.updated_by, C.center_id, 
								C.name, C.phone, C.fax, C.status as dstatus, C.category_id_fk, DC.category_name, C.specialty_id_fk ";
			
			
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", CH.chapter_name ";				
			}
			
			$sqlQuery .= "FROM centers C , address_master A , doctor_categories DC , center_address CA, city CT ";
			
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", center_chapters CH ";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", center_publication CP ";
			}
					
				$sqlQuery .= "WHERE A.id = CA.address_id 
								AND CA.center_id = C.center_id 
								AND C.category_id_fk = DC.cat_id ";
									
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= " AND CH.chapter_id = C.chapter_id_fk ";
			}
			
			
			if (in_array( 'name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='C', $fieldToAdd='name') ;
			}
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='chapter_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='CP', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'center_status', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}

			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}

			//echo '<pre>';echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());

			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;

		}
		
		
		function getTotalCenterSearchList($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			//$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, A.city_id, A.state_id, A.zip_id, A.status, A.date_updated, A.updated_by, C.center_id, 
			//					C.name,  C.phone, C.fax, C.status as dstatus,  C.category_id_fk, DC.category_name ";
			
			
			$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, A.city_id, A.state_id, A.zip_id, A.status, A.date_updated, A.updated_by, C.center_id, C.name, 
						C.phone, C.fax, C.website, C.npi, C.admission, C.status as dstatus, C.category_id_fk, C.specialty_id_fk, C.chapter_id_fk, DC.category_name ";
			
			
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", CH.chapter_name ";				
			}
			
			$sqlQuery .= "FROM centers C , address_master A , doctor_categories DC , center_address CA, city CT ";
			
			
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= ", county CNT ";				
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", center_chapters CH ";
			}
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", center_publication CP ";
			}
					
				$sqlQuery .= "WHERE A.id = CA.address_id 
								AND CA.center_id = C.center_id 
								AND C.category_id_fk = DC.cat_id ";
									
			if (in_array( 'county_id', $fieldNameArray )) {
				$sqlQuery .= " AND CNT.county_id = A.county_id";
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= " AND CH.chapter_id = C.chapter_id_fk ";
			}
			
			
			if (in_array( 'name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='C', $fieldToAdd='name') ;
			}
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='chapter_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='CP', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'center_status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}

			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
			
			$setquery = $this->setQuery($sqlQuery);

			//echo '<pre>'. $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());

			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}

				return $sqlRecordArray;

		} */

		/* new created 5/4/2016 */

		function getTotalCenterSearchArray($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, A.city_id, A.state_id, A.zip_id,  A.status, A.date_updated, A.updated_by, C.center_id, 
								C.name, C.phone, C.fax, C.status as dstatus, C.category_id_fk, DC.category_name, C.specialty_id_fk ";
			
			
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", CH.chapter_name ";				
			}
			
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", P.publication ";				
			}
			
			$sqlQuery .= "FROM ( ";
			
			if (in_array( 'pub_id', $fieldNameArray )) {			
				$sqlQuery .= "	publications P INNER JOIN center_publication CP ON CP.pub_id = P.pub_id
								INNER JOIN centers C ON CP.center_id = C.center_id ";
			}
			else {
				$sqlQuery .= "centers C ";
			}	
						
				$sqlQuery .= "	INNER JOIN center_address CA ON CA.center_id = C.center_id 
								INNER JOIN doctor_categories DC ON C.category_id_fk = DC.cat_id 
								INNER JOIN address_master A ON A.id = CA.address_id ";
			
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= "	INNER JOIN center_chapters CH ON C.chapter_id_fk = CH.chapter_id ";
			}
					
			$sqlQuery .=" ) ";

			$sqlQuery .= "WHERE 1=1 ";

			if (in_array( 'name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='C', $fieldToAdd='name') ;
			}
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='chapter_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='CP', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'center_status', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}

			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}

			//echo '<pre>';echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());

			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;

		}
		
		function getTotalCenterSearchList($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, A.city_id, A.state_id, A.zip_id,  A.status, A.date_updated, A.updated_by, C.center_id, 
								C.name, C.phone, C.fax,C.website,C.npi,C.admission,C.notes, C.status as dstatus, C.category_id_fk, DC.category_name, C.specialty_id_fk,
								C.chapter_id_fk, DC.category_name ";
			

			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= ", CH.chapter_name ";				
			}
			
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", P.publication, CP.year ";				
			}
			
			$sqlQuery .= "FROM ( ";
			
			if (in_array( 'pub_id', $fieldNameArray )) {			
				$sqlQuery .= "	publications P INNER JOIN center_publication CP ON CP.pub_id = P.pub_id
								INNER JOIN centers C ON CP.center_id = C.center_id ";
			}
			else {
				$sqlQuery .= "centers C ";
			}
						
				$sqlQuery .= "	INNER JOIN center_address CA ON CA.center_id = C.center_id 
								INNER JOIN doctor_categories DC ON C.category_id_fk = DC.cat_id 
								INNER JOIN address_master A ON A.id = CA.address_id ";
			
			if (in_array( 'chapter_id_fk', $fieldNameArray )) {
				$sqlQuery .= "	INNER JOIN center_chapters CH ON C.chapter_id_fk = CH.chapter_id ";
			}
					
			$sqlQuery .=" ) ";

			$sqlQuery .= "WHERE 1=1 ";
			

			if (in_array( 'name', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='C', $fieldToAdd='name') ;
			}
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'category_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='category_id_fk') ;
			}
			if (in_array( 'chapter_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='chapter_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='CP', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'center_status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='C', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}

			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
			
			$setquery = $this->setQuery($sqlQuery);

			//echo '<pre>'. $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());

			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}

				return $sqlRecordArray;

		}

		// --- get total external search record ----
		
		function getTotalExtSearchArray($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			
			$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, CT.city, ST.state, Z.zipcode,  A.status, A.date_updated, A.updated_by, D.doctor_id,  
								D.first_name, D.middle_name, D.last_name,D.fullname, D.email, D.website, D.status as dstatus, D.npi, D.phone, D.fax, D.specialty_id_fk, DC.category_name, DS.specialty_name ";
			
			
			//if (in_array( 'specialty_id_fk', $fieldNameArray )) {
//				$sqlQuery .= ", DS.specialty_name ";				
//			}
			
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", P.publication ";				
			}
			
			$sqlQuery .= "FROM ( ";
			
			if (in_array( 'pub_id', $fieldNameArray )) {			
				$sqlQuery .= "	publications P INNER JOIN doctor_publication DP ON DP.pub_id = P.pub_id
								INNER JOIN doctors D ON DP.doctor_id = D.doctor_id ";
			}
			else {
				$sqlQuery .= "doctors D ";
			}	
						
				$sqlQuery .= "	INNER JOIN doctor_address DA ON DA.doctor_id = D.doctor_id 
								INNER JOIN doctor_categories DC ON D.category_id_fk = DC.cat_id 
								INNER JOIN address_master A ON A.id = DA.address_id						
								INNER JOIN city CT ON CT.city_id = A.city_id 
								INNER JOIN state ST ON ST.state_id = A.state_id 
								INNER JOIN zipcodes Z ON Z.zip_id = A.zip_id ";
			
			//if (in_array( 'specialty_id_fk', $fieldNameArray )) {
				$sqlQuery .= " INNER JOIN doctor_specialties DS ON DS.specialty_id = D.specialty_id_fk ";
			//}
					
			$sqlQuery .=" ) ";

			$sqlQuery .= "WHERE 1=1 ";
			
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'specialty_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='specialty_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='P', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'doctor_status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}


			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}

			//echo '<pre>';echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());

			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;

		}
		
		// --- get total external search list 
		
		function getTotalExtSearchList($fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit='')
		{
			
			
			$sqlQuery = "SELECT DISTINCT(A.id), A.address_1, A.address_2, CT.city, ST.state, Z.zipcode, A.status, A.date_updated, A.updated_by, D.doctor_id,  
								D.first_name, D.middle_name, D.last_name,D.fullname, D.email, D.website, D.status as dstatus, D.npi, D.phone, D.fax, D.specialty_id_fk, DC.category_name, DS.specialty_name ";
			
			
			//if (in_array( 'specialty_id_fk', $fieldNameArray )) {
//				$sqlQuery .= ", DS.specialty_name ";				
//			}
			
			if (in_array( 'pub_id', $fieldNameArray )) {
				$sqlQuery .= ", P.publication ";				
			}
			
			$sqlQuery .= "FROM ( ";
			
			if (in_array( 'pub_id', $fieldNameArray )) {			
				$sqlQuery .= "	publications P INNER JOIN doctor_publication DP ON DP.pub_id = P.pub_id
								INNER JOIN doctors D ON DP.doctor_id = D.doctor_id ";
			}
			else {
				$sqlQuery .= "doctors D ";
			}	
						
				$sqlQuery .= "	INNER JOIN doctor_address DA ON DA.doctor_id = D.doctor_id 
								INNER JOIN doctor_categories DC ON D.category_id_fk = DC.cat_id 
								INNER JOIN address_master A ON A.id = DA.address_id						
								INNER JOIN city CT ON CT.city_id = A.city_id 
								INNER JOIN state ST ON ST.state_id = A.state_id 
								INNER JOIN zipcodes Z ON Z.zip_id = A.zip_id ";
			
			//if (in_array( 'specialty_id_fk', $fieldNameArray )) {
				$sqlQuery .= " INNER JOIN doctor_specialties DS ON DS.specialty_id = D.specialty_id_fk ";
			//}
					
			$sqlQuery .=" ) ";

			$sqlQuery .= "WHERE 1=1 ";
			
			if (in_array( 'address_1', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_1') ;
			}
			if (in_array( 'address_2', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='address_2') ;
			} 
			if (in_array( 'phone', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='phone') ;
			}
			if (in_array( 'fax', $fieldNameArray )) {
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='', $Tbl='A', $fieldToAdd='fax') ;
			}
			if (in_array( 'city_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='city_id') ;
			}
			if (in_array( 'state_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='state_id') ;
			}
			if (in_array( 'zip_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='zip_id') ;
			}
			if (in_array( 'specialty_id_fk', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='specialty_id_fk') ;
			}
			if (in_array( 'county_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='county_id') ;
			}
			if (in_array( 'pub_id', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='P', $fieldToAdd='pub_id') ;
			}
			if (in_array( 'status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='A', $fieldToAdd='status') ;
			}
			if (in_array( 'doctor_status', $fieldNameArray )) { 
				$sqlQuery = $this->additionToQuery($sqlQuery, $fieldNameArray, $searchValueArray, $searchStatus='exact', $Tbl='D', $fieldToAdd='status') ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {

				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}


			if (isset($offset) && !empty($limit)) {

				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}

			//echo '<pre>';echo $sqlQuery; exit();

			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $this->table . " : ".mysql_error());

			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}

				return $sqlRecordArray;

		}
		
		// ---- end 
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
<?php
/**
 * PHP Class to doctor address access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('doctoraddress.class.php');
 * $Ad = new DoctorAddressClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: doctoraddress.class.php,v 0.01 
 * @copyright Copyright (c) 2015 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";
require_once "constants.php";


class DoctorAddressClass extends Db { 

		private $table = 'doctor_address';
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

		function getTotal($fieldName='', $searchKeyword='')
		{
			$sqlCount = $this->getTableCount($this->table, $fieldName , $searchKeyword );
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
		
		
		function deleteRecord($doctor_id, $address_id)
		{
			
			$sqlQuery = "DELETE FROM `" . $this->table . "` WHERE  doctor_id = '$doctor_id' AND address_id = '$address_id' ";
			$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			
			if (!empty($sqlQueryResult))
				return 1;
			else
				return 0;
			
		}
		
		
		
		function isDcotorRecordExists($doctor_id)
		{
			$fieldName = 'doctor_id';
			$searchValue = $doctor_id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
		function getDoctorAddressRecord($fieldName, $searchKey, $searchStatus='',  $orderByField='', $orderByValue='', $offset='', $limit='')
		{
				$sqlQuery = "SELECT A.id, D.doctor_id, A.address_1 , C.city, S.state, Z.zipcode , A.phone, A.fax
							 FROM `doctors` AS D, doctor_address AS DA, address_master AS A, city AS C, state AS S, zipcodes AS Z 
						 	 WHERE DA.doctor_id = D.doctor_id AND A.id = DA.address_id AND C.city_id = A.city_id AND S.state_id = A.state_id AND Z.zip_id = A.zip_id";
				
				if (!empty($fieldName) and!empty($searchKey)) {
					$sqlQuery .= " AND D.`$fieldName` = '$searchKey'";
				}
				
				if (!empty($orderByField) or !empty($orderByValue)) {
					$sqlQuery .= " ORDER BY `" . $orderByField . "` ". $orderByValue ;
				}
				
				if (!empty($offset) or !empty($limit)) {
					$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
				}
				
				$sqlResult = 	$this->dbquery($sqlQuery) or die(mysql_error());
				while ($sqlArray = $this->dbfetchassoc($sqlResult))
				{
					$sqlRecordArray[] = $sqlArray;
				}
				
				return $sqlRecordArray;
				
		}



		function createDoctorAddress($sessionValues)
		{
				$fl 		= 	$sessionValues['fl'];
				$doctor_id	=	$this->prepare_input($sessionValues['doctor_id']);
				$address_id  	=	$this->prepare_input($sessionValues['address_id']);
				$main  	=	$this->prepare_input($sessionValues['main']);
				
				if (empty($main))
					$main = 0;
				
				$fieldArray = array ( 'doctor_id', 'address_id' );
				$keyArray = array ($doctor_id, $address_id );
				
				$isRecordExists = $this->getTotalArray($fieldArray, $keyArray, $searchStatus='exact');
				
				if ($isRecordExists == 0) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ."  ( `id` , `doctor_id` , `address_id` , `main` )  VALUES  ( NULL, '$doctor_id', '$address_id' , '$main' ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						$_SESSION['da_id'] = $id;
						if ($sqlQuery == 1 )
							return 1;
						else
							return 0;
				}
				else 
					return 0;
						
				

		}
		
		
		/* This function used to fetch list of doctors by address id
		 * @param string $selectedFields - seperated by array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $searchStatus
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return array $sqlArray
		 */
		
		function getAddressDoctorRecord($fieldName, $searchKey, $searchStatus='',  $orderByField='', $orderByValue='', $offset='', $limit='')
		{
				$sqlQuery = "SELECT A.id, D.doctor_id, D.first_name, D.middle_name, D.last_name, D.fullname, D.notes, D.email, D.license_status, D.website, D.npi, D.status, S.specialty_name
							 FROM address_master AS A, doctor_address AS DA, `doctors` AS D, `doctor_specialties` AS S 
						 	 WHERE DA.address_id = A.id AND D.doctor_id = DA.doctor_id  AND S.specialty_id = D.specialty_id_fk ";
				
				if (!empty($fieldName) and!empty($searchKey)) {
					$sqlQuery .= " AND A.`$fieldName` = '$searchKey'";
				}
				
				if (!empty($orderByField) or !empty($orderByValue)) {
					$sqlQuery .= " ORDER BY `" . $orderByField . "` ". $orderByValue ;
				}
				
				if (!empty($offset) or !empty($limit)) {
					$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
				}
				//echo '<pre>';echo $sqlQuery; exit();
				$sqlResult = 	$this->dbquery($sqlQuery) or die(mysql_error());
				while ($sqlArray = $this->dbfetchassoc($sqlResult))
				{
					$sqlRecordArray[] = $sqlArray;
				}
				
				return $sqlRecordArray;
				
		}

		

		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
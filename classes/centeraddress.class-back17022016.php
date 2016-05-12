<?php
/**
 * PHP Class to center address access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('centeraddress.class.php');
 * $Ad = new CenterAddressClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: centeraddress.class.php,v 0.01 
 * @copyright Copyright (c) 2015 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";
require_once "constants.php";


class CenterAddressClass extends Db { 

		private $table = 'center_address';
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
		
		
		function deleteRecord($center_id, $address_id)
		{
			
			$sqlQuery = "DELETE FROM `" . $this->table . "` WHERE  center_id = '$center_id' AND address_id = '$address_id' ";
			$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			
			if (!empty($sqlQueryResult))
				return 1;
			else
				return 0;
			
		}
		
		
		
		function isDcotorRecordExists($center_id)
		{
			$fieldName = 'center_id';
			$searchValue = $center_id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
		function getCenterAddressRecord($fieldName, $searchKey, $searchStatus='',  $orderByField='', $orderByValue='', $offset='', $limit='')
		{
				/*$sqlQuery = "SELECT A.id, D.center_id, A.address_1 , C.city, S.state, Z.zipcode , A.phone, A.fax
							 FROM `centers` AS D, center_address AS DA, address_master AS A, city AS C, state AS S, zipcodes AS Z 
						 	 WHERE DA.center_id = D.center_id AND A.id = DA.address_id AND C.city_id = A.city_id AND S.state_id = A.state_id AND Z.zip_id = A.zip_id";*/
				
				$sqlQuery = "SELECT A.id, D.center_id, A.address_1 , A.phone
							 FROM `centers` AS D, center_address AS DA, address_master AS A
						 	 WHERE DA.center_id = D.center_id AND A.id = DA.address_id";
				
				
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



		function createCenterAddress($sessionValues)
		{
				$fl 		= 	$sessionValues['fl'];
				$center_id	=	$this->prepare_input($sessionValues['center_id']);
				$address_id  	=	$this->prepare_input($sessionValues['address_id']);
				$main  	=	$this->prepare_input($sessionValues['main']);
				
				$fieldArray = array ( 'center_id', 'address_id' );
				$keyArray = array ( $center_id, $address_id );
				
				$isRecordExists = $this->getTotalArray($fieldArray, $keyArray, $searchStatus='exact');
				
				if ($isRecordExists == 0) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." ( `id` , `center_id` , `address_id` , `main`)  VALUES  ( NULL, '$center_id', '$address_id' , '$main' ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						$_SESSION['da_id'] = $id;
						
				}
						if ($sqlQuery == 1 )
							return 1;
						else
							return 0;
				

		}	
		
		
		/* This function used to fetch list of centers by address id
		 * @param string $selectedFields - seperated by array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $searchStatus
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return array $sqlArray
		 */
		
		function getAddressCenterRecord($fieldName, $searchKey, $searchStatus='',  $orderByField='', $orderByValue='', $offset='', $limit='')
		{
				$sqlQuery = "SELECT A.id, D.center_id, D.name, D.notes, D.description, D.website, D.npi, D.status, D.specialty_id_fk, C.category_name
							 FROM address_master AS A, center_address AS DA, `centers` AS D, `doctor_categories` AS C 
						 	 WHERE DA.address_id = A.id AND D.center_id = DA.center_id  AND C.cat_id = D.category_id_fk ";
				
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
<?php
/**
 * PHP Class to county access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('county.class.php');
 * $Ad = new CountyClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: county.class.php
 * @copyright Copyright (c) 2015 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";


class CountyClass extends Db { 

		private $table = 'county';
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

		function getTotal($fieldName='', $searchKeyword='',$searchStatus='')
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

		function getList($fieldName='', $searchValue='', $searchStatus='', $orderByField='', $orderByValue='', $offset ='', $limit ='')
		{
			$sqlRecord = $this->getTableRecordList($this->table, $fieldName, $searchValue, $searchStatus, $orderByField, $orderByValue, $offset, $limit);
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
			$sqlRecord = $this->getTableRecordByField($this->table, $fieldName , $searchKey, $searchStatus, $orderByField, $orderByValue );
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
			$sqlRecord = $this->getSelectedTableRecordByField($this->table, $selectedFields, $fieldName , $searchKey,  $searchStatus, $orderByField, $orderByValue );
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
		
		
		function isCountyExist($county , $currentCountyId) { 
		
				$sqlQuery1 = "SELECT county_id FROM  ". $this->table ." WHERE  `county` = '$county' AND `county_id` <> '$currentCountyId' ";
				$sqlQueryResult = $this->dbquery($sqlQuery1) or die(mysql_error());	
				$sqlCount = $this->dbnumrow($sqlQueryResult);
				//if ($sqlCount > 0 ) 
					return $sqlCount;
				//else
				//	return 0;			
					
		}
		
		
		
		function isIdPresent($county_id)
		{
			$fieldName = 'county_id';
			$searchValue = $county_id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		

		
		function editCounty($sessionValues)
		{
				//print_r($sessionValues);
				$fl 		  	 = 	$sessionValues['fl'];
				$county_id 		 = 	$sessionValues['county_id'];
				$county  	 =		$this->prepare_input($sessionValues['county']);
			
				
			
				$RecordCreated = $this->isIdPresent($county_id);
				
				if ($RecordCreated == 0) 
				{
						$sqlQuery = "INSERT INTO  ". $this->table ."  ( `county_id` , `county` )  VALUES ( NULL, '$county' ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$uid = mysql_insert_id();
						$sessionValues['county_id'] = $county_id;
						
				}
				else if ($RecordCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET `county`  = '$county'  WHERE county_id = $county_id ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());	
						
				}
						
						if ($sqlQuery == 1 )
							return 1;
						else
							return 0;
				

		}	

		
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
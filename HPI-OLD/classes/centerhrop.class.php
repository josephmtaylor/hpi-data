<?php
/**
 * PHP Class to doctor hours_of_operation access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('doctorhours_of_operation.class.php');
 * $Ad = new CenterHropClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: doctorhours_of_operation.class.php,v 0.01 
 * @copyright Copyright (c) 2015 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";
require_once "constants.php";


class CenterHropClass extends Db { 

		private $table = 'center_hours_of_operation';
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
		
		
		function deleteRecord($center_id, $hrop_id, $year='')
		{
			
			$sqlQuery = "DELETE FROM `" . $this->table . "` WHERE  center_id = '$center_id' AND hrop_id = '$hrop_id' ";
			
			if (!empty($year)) {
				$sqlQuery .= "AND year = '$year' " ;
			}
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
		
		
		function getCenterHropRecord($fieldName, $searchKey, $searchStatus='',  $orderByField='', $orderByValue='', $offset='', $limit='')
		{
				$sqlQuery  =   "SELECT  DP.center_id, DP.hrop_id, P.description
								FROM `center_hours_of_operation` AS DP INNER JOIN `hours_of_operation` AS P ON P.hrop_id = DP.hrop_id
								WHERE DP.`$fieldName` = '$searchKey' ";

				if (!empty($orderByField) or !empty($orderByValue)) {
					$sqlQuery .= " ORDER BY `" . $orderByField . "` ". $orderByValue ;
				}
				
				if (!empty($offset) or !empty($limit)) {
					$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
				}
				
				$sqlResult = 	$this->dbquery($sqlQuery) or die(mysql_error());
				$sqlCount  = 	$this->dbnumrow($sqlResult);
				if ($sqlCount > 0) { 
					while ($sqlArray = $this->dbfetchassoc($sqlResult))
					{
						$sqlRecordArray[] = $sqlArray;
					}
					
					return $sqlRecordArray;
				}
				else
					return 0;
		}
		
		
		
		function editCenterHrop($sessionValues)
		{
				$fl 		= 	$sessionValues['fl'];
				$center_id	=	$this->prepare_input($sessionValues['center_id']);
				$hrop_id  	=	$this->prepare_input($sessionValues['hrop_id']);
				
				$fieldArray = array ( 'center_id', 'hrop_id');
				$keyArray = array ($center_id, $hrop_id);
				
				$isRecordExists = $this->getTotalArray($fieldArray, $keyArray, $searchStatus='exact');
				
				if ($isRecordExists == 0) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ."  ( `center_id` , `hrop_id` )  VALUES  ( '$center_id', '$hrop_id'  ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						$sessionValues['id'] = $id;
						
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
<?php
/**
 * PHP Class to category access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('category.class.php');
 * $Ad = new CategoryClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: category.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";


class CategoryClass extends Db { 

		private $table = 'doctor_categories';
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
			$fieldName = 'cat_id';
			$searchValue = $id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		function getCategoryName($id) 
		{ 	
			$selectedFields = "category_name";
			$fieldName = 'cat_id';
			$searchValue = $id;
			$dbrow = $this->getSelectedFieldsRecordByField($selectedFields, $fieldName, $searchValue);
			return $dbrow['category_name'];

		}
		
		function editCategory($sessionValues)
		{
				//print_r($sessionValues);
				$fl 		  = 	$sessionValues['fl'];
				$id 		  = 	$sessionValues['cat_id'];
				$category_name  =		$this->prepare_input($sessionValues['category_name']);
			
				$UserCreated = $this->isIdPresent($id);
				
				if ($UserCreated == 0) 
				{
						$sqlQuery = "INSERT INTO  ". $this->table ."  ( `cat_id` , `category_name`  )  VALUES ( NULL, '$category_name'  ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$uid = mysql_insert_id();
						$sessionValues['id'] = $id;
						
				}
				else if ($UserCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET `category_name`  = '$category_name'  WHERE cat_id = $id ";
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
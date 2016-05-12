<?php
/**
 * PHP Class to newsletter  access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('newsletters.class.php');
 * $Ad = new NewsletterClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: newsletters.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";
require_once "constants.php";


class NewsletterClass extends Db { 

		private $table = 'newsletters';
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
		
		function getSelectedFieldsRecordByField($selectedFields, $fieldName, $searchKey, $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getSelectedTableRecordByField($this->table, $selectedFields, $fieldName , $searchKey,  $orderByField, $orderByValue );
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
		
		
		
		
		function isIdPresent($id)
		{
			$fieldName = 'id';
			$searchValue = $id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
		
		function isEmailRegistered($email)
		{
			$fieldName = 'email';
			$searchValue = $email;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		

		
		function editNewsletter($sessionValues)
		{
				//print_r($sessionValues);
				$id 		   =  $sessionValues['id'];
				$title  =  $this->prepare_input($sessionValues['title']);
				$description  =  addslashes($sessionValues['description']);
				$postingdate  =  $this->prepare_input($sessionValues['postingdate']);
				$scheduleddate  =  $this->prepare_input($sessionValues['scheduleddate']);
				$status  =  $this->prepare_input($sessionValues['status']);
				
				$postingdate = $this->change2ymd($postingdate);
				$scheduleddate = $this->change2ymd($scheduleddate);
				
				$time = " ". date('h:i:s');
				$postingdate   .= $time;
				$scheduleddate .= $time;
				
				$isNewseletterCreated = $this->isIdPresent($email);
				
				if ($isNewseletterCreated == 0) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." 
									( `id`  ,`title` ,`description` ,`postingdate` ,`scheduleddate` ,`status`  )  VALUES 
									( NULL , '$title',  '$description', '$postingdate', '$scheduleddate', '$status' ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						$sessionValues['id'] = $id;
						
				}
				else if ($isNewseletterCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET 
									 `title`  = '$title' , `description`  = '$description',
									 `postingdate` = '$postingdate' ,`scheduleddate` = '$scheduleddate' , `status`= '$status'
									 WHERE id = $id ";
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
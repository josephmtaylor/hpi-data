<?php
/**
 * PHP Class to admin access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('admin.class.php');
 * $Ad = new SettingsClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: admin.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";


class SettingsClass extends Db { 

		private $table = 'settings';
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
		
		/* This function calls  deleteTableRecord function and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function delete($fieldName, $recordKey) {
		
			$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $recordKey );
			return $sqlRecord;
		
		}
		
		
		function getSettingsInfoById()
		{
			$sqlQuery = "SELECT * from settings WHERE uid = '1' ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlRecordArray = $this->dbfetch($sqlMemberRes);
			return $sqlRecordArray;
			
		}
		
		function editSettings($sessionValues)
		{
				//print_r($sessionValues);
				$id				 		  = 	$sessionValues['id'];
				$loan_officer  		 	  =		$this->prepare_input($sessionValues['loan_officer']);
				$loan_officer_email  	  =		$this->prepare_input($sessionValues['loan_officer_email']);
				$insurance_officer  	  =		$this->prepare_input($sessionValues['insurance_officer']);
				$insurance_officer_email  =		$this->prepare_input($sessionValues['insurance_officer_email']);
				$contact_officer  		  =		$this->prepare_input($sessionValues['contact_officer']);
				$contact_officer_email    =		$this->prepare_input($sessionValues['contact_officer_email']);
				
				$isSettingsExist = $this->getTotal('id', $id);
				
				if ($isSettingsExist == 0) 
				{
						$sqlQuery = "INSERT INTO `settings` ( `id` , `loan_officer` , `loan_officer_email` , `insurance_officer` , `insurance_officer_email` , `contact_officer`, `contact_officer_email`  )
											    VALUES ( NULL, '$loan_officer', '$loan_officer_email', '$insurance_officer', '$insurance_officer_email',  '$contact_officer' , '$contact_officer_email' ) ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						$sessionValues['id'] = $id;
						
				}
				else if ($isSettingsExist > 0) 
				{
						$sqlQuery = "UPDATE `settings` SET  `loan_officer`  = '$loan_officer', `loan_officer_email`  = '$loan_officer_email' ,  
															`insurance_officer`  = '$insurance_officer' , `insurance_officer_email`  = '$insurance_officer_email' ,
															`contact_officer`  = '$contact_officer' , `contact_officer_email`  = '$contact_officer_email'
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
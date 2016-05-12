<?php
/**
 * PHP Class to CmsPage access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('CmsPage.class.php');
 * $Ad = new CmsPageClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: CmsPage.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";
require_once "constants.php";


class CmsPageClass extends Db { 

		private $table = 'cmspages';
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
		

		
		function editCmsPage($sessionValues)
		{
			//	print_r($sessionValues);exit();
				$id 		   =  $sessionValues['id'];
				$page_title  =  $this->prepare_input($sessionValues['page_title']);
				$page_description  = $this->prepare_input($sessionValues['page_description']);
				$meta_keywords  =  $this->prepare_input($sessionValues['meta_keywords']);
				$meta_description  =  $this->prepare_input($sessionValues['meta_description']);
			
				$CmsPageCreated = $this->isIdPresent($id);
				
				if ($CmsPageCreated == 0) 
				{
						$sqlQuery = "INSERT INTO `cmspages` (`id`  ,`page_title` ,`meta_keywords` ,`meta_description` ,`page_description` )
													VALUES ( NULL , '$page_title',  '$meta_keywords', '$meta_description', '$page_description') ";
						$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						
				}
				else if ($CmsPageCreated > 0) 
				{
						$sqlQuery = "UPDATE `cmspages` SET  `page_title`  = '$page_title' , `meta_keywords`  = '$meta_keywords',
									  `meta_description` = '$meta_description' ,`page_description` = '$page_description' 
									WHERE id = $id ";
						$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());	
						
				}
						
						if ($sqlResult == 1 )
							return $id;
						else
							return 0;
				

		}	
		
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
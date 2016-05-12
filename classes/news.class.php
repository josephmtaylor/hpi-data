<?php
/**
 * PHP Class to news access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('news.class.php');
 * $Ad = new CustomerClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: news.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */
require_once "constants.php";
require_once "db.class.php";



class NewsClass extends Db { 

		private $table = 'news';
		
		private $data = array();

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
		 * @param string $selectedFields - seperated by comma (e.g. str = "userid, title"; )
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
		
			$deletePhoto = $this->deletePhoto($recordKey);
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
						$deletePhoto = $this->deletePhoto($aid);
						$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $aid );
					}
					
				}
					return 1;
		}
		
		
		function changeNewsStatusArray($ArrayId, $status) {
	
				$n = count($ArrayId);
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $ArrayId[$i];
					
						$sqlRecord = $this->changeNewsStatus($aid, $status );
					}
					
				}
					return 1;
		}
		
		/* This function changes the status of property and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function changeNewsStatus($id, $status) {
		
			$sqlQuery  =  "UPDATE ". $this->table ."  SET  `status` = '$status'  WHERE 1=1 "; 
			
			if (!empty($id))
				$sqlQuery .= " AND  `id` = '$id'  ";
			
		//	echo $sqlQuery; exit();
		
			$sqlResult = 	$this->dbquery($sqlQuery) or die(mysql_error());				
			return $sqlResult;
		}
		
		
		
		function isIdPresent($id)
		{
			$fieldName = 'id';
			$searchValue = $id;
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
		function isNameExists($searchValue)
		{
			$fieldName = 'title';
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldName, $searchValue);
			return $sqlCount;
		}
		
		
		function editNews($sessionValues)
		{
				//print_r($sessionValues); exit();
				
				$fl 		  	  = 	$sessionValues['fl'];
				$id		 		  = 	$sessionValues['id'];
				$title		  	  =		$this->prepare_input($sessionValues['title']);
				$description  	  =		$this->prepare_input($sessionValues['description']);
				$image_pt5		  =		$sessionValues['pt6'];
				$image_pt1		  =		$sessionValues['pt0'];
				$status  		  =		$this->prepare_input($sessionValues['status']);
				$date_created  	  = 	date('Y-m-d h:i:s');
				
				if ($id==0 or empty($id)) {
					$isNewsCreated = 0 ;
				}
				else {
					$isNewsCreated = $this->isIdPresent($id);
				}
				
				if ($isNewsCreated == 0) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." 
									( `id` , `title`  ,`description` ,`image_pt5` ,`image_pt1` , `status`, `date_created` )  VALUES 
									(  NULL, '$title' , '$description','$image_pt5','$image_pt1', '$status', '$date_created'  ) ";
						$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						
				}
				else if ($isNewsCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET  `title` = '$title' ,  `description` = '$description' , `status` = '$status' 
									 WHERE id = $id ";
						$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());	
						
						if (!empty($image_pt5) or !empty($image_pt1)) {
							
							$deletePhoto = $this->deletePhoto($id);
							
							$sqlQuery1 = "UPDATE ". $this->table ." SET  `image_pt5` = '$image_pt5' ,  `image_pt1` = '$image_pt1'
										 WHERE id = $id ";
							$sqlResult1 = $this->dbquery($sqlQuery1) or die(mysql_error());		
						
						}
						
						
				}
						
						if ($sqlResult == 1 )
							return 1;
						else
							return 0;
				

		}	
		
		
		function deletePhoto($id)
		{
				$sqlQuery = "SELECT image_pt5, image_pt1 FROM ". $this->table ." WHERE id = '$id' ";
				$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
				$pInfo = $this->dbfetch($sqlResult);
				
				$imgLocation = "../images/news/";
				
				$photo = $pInfo['image_pt5'];
				$photoThumb = $pInfo['image_pt1'];
				
				if (!empty($photo)) {
					$file_location = $imgLocation.$photo;
					if (file_exists($file_location)) {
						unlink($file_location);
					}
				}	
				if (!empty($photoThumb)) {	
					$thumb_location = $imgLocation.$photoThumb;
					if (file_exists($thumb_location)) {
						unlink($thumb_location);
					}
				}
				return 1;
		}
		
		
		
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
<?php
/**
 * PHP Class to banner access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('banner.class.php');
 * $Ad = new BannerClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: banner.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */

require_once "db.class.php";


class BannerClass extends Db { 

		private $table = 'banner';
		private $data = array();

		/* Constructor
		 * 
		 */

		function __construct() {
		
			parent::__construct();
		}
		
		/* This function calls  getTableCount function and returns table total
		 * @param string $fieldName default Null 
		 * @param string $searchKeyword default Null 
		 * @param string $searchStatus default Null 
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

		function getList($fieldName='', $searchValue='', $orderByField='', $orderByValue='', $offset='', $limit ='')
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
		 * @param string $selectedFields - seperated by array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getSelectedFieldsRecordByField($selectedFields, $fieldName, $searchKey, $searchStatus='', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getSelectedTableRecordByField($this->table, $selectedFields, $fieldName , $searchKey, $searchStatus,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}
		
		/* This function calls  getSelectedTableRecordListArray function and returns table record  multiple array
		 * @param string $selectedFields - seperated by comma (e.g. str = "propertyid, name"; )
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
		
			$deleteBannerImage = $this->deleteBannerImage($recordKey);
			if ($deleteBannerImage == 1)
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
						$deleteBannerImage = $this->deleteBannerImage($aid);
						if ($deleteBannerImage == 1)
							$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $aid );
					}
					
				}
					return 1;
		}
		
		
		function deleteBannerImage($id)
		{
				$fieldName = 'id';
				$searchKey = $id;
				
				$pInfo = $this->getRecordByField($fieldName, $searchKey );
				$banner_image = $pInfo['banner_image'];
				$imglocation = "../images/banner/";
				$file_location = $imglocation.$banner_image;

				unlink($file_location);
				return 1;
		}
		
		
		
		function changeBannerStatusArray($ArrayId, $status) {
	
				$n = count($ArrayId);
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $ArrayId[$i];
					
						$sqlRecord = $this->changeBannerStatus($aid, $status );
					}
					
				}
					return 1;
		}
		
		/* This function changes the status of property and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function changeBannerStatus($id, $status) {
		
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
		
		
/*		function isUserBannerExists($userId)
		{
			$fieldNameArray = array('userid_fk');
			$searchValueArray = array($userId);
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldNameArray, $searchValueArray);
			return $sqlCount;
		}*/


		
		function updateBannerImageRecord($sessionValues)
		{
			
			$photoType = $sessionValues['photoType'];
			$photoId = $sessionValues['photoId'];
			$banner_image = $sessionValues['pt5'];
			$id = $sessionValues['id'];
			$status = $sessionValues['status'];
			
			$date_created	= 	date('Y-m-d');
			
			
			if (!empty($photoType)) { 
			
				if ($photoType == 'imgbanner' ) { 
					
						
						$sqlQuery = "INSERT INTO " . $this->table ." ( `id`,  `banner_image`,  `date_created` ,  `status` ) 
									                           VALUES (NULL, '$banner_image' ,  '$date_created',   '$status'  )";
						$sqlResult = $this->dbquery($sqlQuery) or die ("Error Photo Gallery Update : ".mysql_error());
						if ($sqlResult == 1)
							return 1;
					
				
				}
			
			
			}
			else 
				return 0;
		
		}
		
		
		
		/*function isBannerTableIdPresent($sessionValues)
		{
			$userId = $sessionValues['userId'];
			$photoType = $sessionValues['photoType'];
			
			$fieldNameArray = array('userid_fk' );
			$searchValueArray = array($userId);
			
			$sqlCount = $this->isTableFieldPresent($this->table, $fieldNameArray, $searchValueArray);
			return $sqlCount;
		}
		*/
		
		
		
		
		/* This function checks if the userphoto is present , delete if present
		 * @param int propertyid
		 * return int result
		 */
		
		
/*		function deleteUserBanner($sessionValues)
		{
				$photoId   = $sessionValues['photoId'];
				$photoType = $sessionValues['photoType'];
				$userId = $sessionValues['userId'];
				$banner_image = $sessionValues['pt5'];
		//		print_r($sessionValues);
		
				$sqlQuery = "SELECT banner_image FROM " . $this->table ." WHERE userid_fk = '$userId' AND id = '$photoId' ";
				$sqlResult = $this->dbquery($sqlQuery) or die("Delete User Banner 1:".mysql_error());
			
				$pInfo = $this->dbfetch($sqlResult);
				
				$photo = $pInfo['banner_image'];
				if (!empty($photo)) {
					$imlocation = "../images/sliders/";
					$file_location = $imlocation.$photo;
					if (file_exists($file_location)) {
						$unlink =unlink($file_location);
					}
					
					
					
					if ($unlink) { 
						
						$sqlQuery = "DELETE FROM " . $this->table ." WHERE userid_fk = '$userId'  AND id = '$photoId' ";
						$sqlResult = $this->dbquery($sqlQuery) or die ("Error Photo Banner Delete : ".mysql_error());
						
							return 1;
					}
					else 
							return 0;
					
				}
				
				
		}
*/
		
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
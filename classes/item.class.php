<?php
/**
 * PHP Class to items access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('item.class.php');
 * $Ad = new ItemClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: items.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */
require_once "constants.php";
require_once "db.class.php";
require_once "simpleimage.class.php";


class ItemClass extends Db { 

		private $table = 'collection';
		
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
		 * @param string $selectedFields - seperated by comma (e.g. str = "id, name"; )
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
		
		/* This function calls  getSelectedTableRecordListArray function and returns table record  multiple array
		 * @param string $selectedFields - seperated by comma (e.g. str = "id, name"; )
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
			
			$delThumb = $this->deleteItemImage($recordKey, $image='thumbnail');
			$delImage = $this->deleteItemImage($recordKey, $image='image');
			
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
						
						$delThumb = $this->deleteItemImage($aid, $image='thumbnail');
						$delImage = $this->deleteItemImage($aid, $image='image');
						$sqlRecord = $this->deleteTableRecord($this->table, $fieldName , $aid );
					}
					
				}
					return 1;
		}
		
		
		
		
		function changeItemRecordArray($itemIdArray, $status) {
	
				$n = count($itemIdArray);
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $itemIdArray[$i];
					
						$sqlRecord = $this->changeItemRecordStatus($aid, $status );
					}
					
				}
					return 1;
		}
		
		
		
		/* This function changes the status of items and returns string
		 * @param string $fieldName
		 * @param string $searchKey
		 * @return int $sqlRecord
		 */
		
		function changeItemRecordStatus($itemId, $status) {
		
			$sqlQuery  =  "UPDATE ". $this->table ."  SET  `status` = '$status'  WHERE 1=1 "; 
			
			if (!empty($itemId))
				$sqlQuery .= " AND  `id` = '$itemId'  ";
		
		
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
		
		
		
		function isItemImage($itemId, $selectedFields='image')
		{
			
			$sqlRec = $this->getSelectedFieldsRecordByField($selectedFields, $fieldName='id', $itemId);;
			return $sqlRec;
		}
		
		
		 
		function createItem($sessionValues)
		{
				//print_r($sessionValues); exit();
			
				  $itemid					=		$sessionValues['id'];
				  $item_name				=		$sessionValues['item_name'];
				  $item_type_id				=		$sessionValues['item_type_id'];
				  $price					=		$sessionValues['price'];
				  $description				=		$this->prepare_input($sessionValues['description']);
				  $available_sizes			=		$sessionValues['available_sizes'];
				  $status					=		$sessionValues['status'];
				  $maxfilesize				= 		$sessionValues['maxfilesize'];
				  
				if ($itemid==0 or empty($itemid)) {
					$ItemCreated = 0 ;
				}
				else {
					$ItemCreated = $this->isIdPresent($itemid);
				}
				
				if ($ItemCreated == 0) 
				{
						$sqlQuery = "INSERT INTO ". $this->table ." ( `id` , `item_name` , `item_type_id` , `price` , `description` , `available_sizes` , `status`) 
									 VALUES (  NULL , '$item_name' , '$item_type_id' , '$price' , '$description' , '$available_sizes' , '$status') ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$itemid = mysql_insert_id();
						$_SESSION['iid'] = $itemid;
						
				}
				else if ($ItemCreated > 0) 
				{
						$sqlQuery = "UPDATE ". $this->table ." SET
									  `item_name` = '$item_name', 
									  `item_type_id`  = '$item_type_id',
									  `price`  = '$price',
									  `description`  = '$description',
									  `available_sizes` = '$available_sizes',
									  `status`  = '$status'
									WHERE `id` = '$itemid'  ";
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						$_SESSION['iid'] = $itemid;
					
				}
				
				if($_FILES['thumbnail']['size'] > 0 && $_FILES['thumbnail']['error'] == 0)
				{
						
						if ($fl == 'edit') {
							$this->deleteItemImage($itemid, $img='thumbnail');
						}
						
						$fileName = $_FILES['thumbnail']['name'];
						$tmpName  = $_FILES['thumbnail']['tmp_name'];
						$fileSize = $_FILES['thumbnail']['size'];
						$fileType = $_FILES['thumbnail']['type'];
						$file_ext = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
					
						if ($fileSize > $maxfilesize ) {
							 $imageText = 'The file size ' . $maxfilesize .' is greater than 2 MB. Please upload image of smaller size.'; 
						}
						else {
							//	print_r($_FILES); exit();
								  $photoType = 'imgcol';
								  $photoId = 0;
								  
								  $propId =  md5(rand(0,9999).time()); // substr(md5(rand()), 0, 5);
								  $propImage_pt1 = 'pt1_'.$propId. ".". $file_ext ;
							
								 
								  $image = new SimpleImage();
								  $image->load($_FILES['thumbnail']['tmp_name']);
								  
								  $imageWidth =	 $image->getWidth();
								  $imageHeight=	 $image->getHeight();
								
								  $tempWidth = 158;
								  $tempHeight = 210;
								 
								  if ($imageWidth >= $tempWidth and $imageHeight >= $tempHeight) {
									  $image->resizeToHeight($tempHeight , 'pt1' , $isStamp = 1 );
								  }
								  else if ($imageWidth <= $tempWidth and $imageHeight <= $tempHeight ) {
									  $image->resizeToHeight($imageHeight , 'pt1' , $isStamp = 1);
								  }	
								  else if ($imageWidth < $tempWidth and $imageHeight >= $tempHeight ) {
									  $image->resizeToHeight($tempHeight , 'pt1' , $isStamp = 1);
								  }
								  else if ($imageWidth > $tempWidth and $imageHeight <= $tempHeight ) {
									  $image->resizeToWidth($tempWidth , 'pt1' , $isStamp = 1);
								  }	    
									 
									$image->save('../images/collection/'.$propImage_pt1);
									  
									$sessionValues = array (    "fl" => $fl,
													"itemid" => $itemid,
													'photoId' => $photoId, 
													'photoType' => $photoType,  
													'pt1' => $propImage_pt1
												);
												
									$imageUploaded = $this->uploadCollectionImage($sessionValues, $image='thumbnail');
									empty($sessionValues);
						}	
				}
				
				if($_FILES['imgfile']['size'] > 0 && $_FILES['imgfile']['error'] == 0)
				{
						
						
						if ($fl == 'edit') {
							$this->deleteItemImage($itemid, $img='image');
						}
						
						$fileName = $_FILES['imgfile']['name'];
						$tmpName  = $_FILES['imgfile']['tmp_name'];
						$fileSize = $_FILES['imgfile']['size'];
						$fileType = $_FILES['imgfile']['type'];
						$file_ext = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
					
						if ($fileSize > $maxfilesize ) {
							 $imageText = 'The file size ' . $maxfilesize .' is greater than 2 MB. Please upload image of smaller size.'; 
						}
						else {
							//	print_r($_FILES); exit();
								  $photoType = 'imglarge';
								  $photoId = 0;
								  
								  $propId =  md5(rand(0,9999).time()); // substr(md5(rand()), 0, 5);
								  $propImage_pt2 = 'pt2_'.$propId. ".". $file_ext ;
							
								 
								  $image = new SimpleImage();
								  $image->load($_FILES['imgfile']['tmp_name']);
								  
								  $imageWidth =	 $image->getWidth();
								  $imageHeight=	 $image->getHeight();
								
								  $tempWidth = 391;
								  $tempHeight = 526;
								 
								  if ($imageWidth >= $tempWidth and $imageHeight >= $tempHeight) {
									  $image->resizeToHeight($tempHeight , 'pt2' , $isStamp = 1 );
								  }
								  else if ($imageWidth <= $tempWidth and $imageHeight <= $tempHeight ) {
									  $image->resizeToHeight($imageHeight , 'pt2' , $isStamp = 1);
								  }	
								  else if ($imageWidth < $tempWidth and $imageHeight >= $tempHeight ) {
									  $image->resizeToHeight($tempHeight , 'pt2' , $isStamp = 1);
								  }
								  else if ($imageWidth > $tempWidth and $imageHeight <= $tempHeight ) {
									  $image->resizeToWidth($tempWidth , 'pt2' , $isStamp = 1);
								  }	    
									 
									$image->save('../images/collection/large/'.$propImage_pt2);
									  
									$sessionValues = array (    "fl" => $fl,
													"itemid" => $itemid,
													'photoId' => $photoId, 
													'photoType' => $photoType,  
													'pt2' => $propImage_pt2
												);
												
									$imageUploaded = $this->uploadCollectionImage($sessionValues, $image='image');
									empty($sessionValues);
						}	
				}
						
						if ($sqlQueryResult == 1 )
							return 1;
						else
							return 0;
				

		}	
		
		
		function uploadCollectionImage($sessionValues, $image)
		{
			
			$photoType = $sessionValues['photoType'];
			$photoId = $sessionValues['photoId'];
			$thumbnail = $sessionValues['pt1'];
			$image = $sessionValues['pt2'];
			$itemId = $sessionValues['itemid'];
			
			if (!empty($photoType)) { 			
					if ($photoType == 'imgcol' ) { 
					
							$sqlQuery = "UPDATE " . $this->table ."  SET `thumbnail` = '$thumbnail'  WHERE id = '$itemId'";
							$sqlResult = $this->dbquery($sqlQuery) or die ("Error Photo Update : ".mysql_error());
							if ($sqlResult == 1)
								return 1;
					}
					else if ($photoType == 'imglarge' ) { 
					
							$sqlQuery = "UPDATE " . $this->table ."  SET `image` = '$image'  WHERE id = '$itemId'";
							$sqlResult = $this->dbquery($sqlQuery) or die ("Error LPhoto Update : ".mysql_error());
							if ($sqlResult == 1)
								return 1;
					}
			}
			else 
				return 0;
		}
		
		
		
		/* This function checks if the userphoto is present , delete if present
		 * @param int id
		 * return int result
		 */
		
		
		function deleteItemImage($itemId, $image)
		{
				$sqlQuery = "SELECT ". $image ." FROM " . $this->table ." WHERE id = '$itemId' ";
				$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
			
				$pInfo = $this->dbfetch($sqlResult);
				
				if ($image == 'thumbnail') { 
					$photo = $pInfo['thumbnail'];
					
					if (!empty($photo)) {
						$imlocation = "../images/collection/";
						$file_location = $imlocation.$photo;
						if (file_exists($file_location)) {
							$unlink =unlink($file_location);
						}
						return 1;
					}
				
				}
				else if ($image == 'image') { 
					$photo = $pInfo['image'];
					
					if (!empty($photo)) {
						$imlocation = "../images/collection/large/";
						$file_location = $imlocation.$photo;
						if (file_exists($file_location)) {
							$unlink =unlink($file_location);
						}
						return 1;
					}
				
				}
				
		}
		
		
		
		
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getItemInfo($itemId)
		{
			
			$sqlTotal  = $this->getTotal('id', $itemId, 'exact');
			
			if(!$sqlTotal || ($sqlTotal < 1)){
				 return NULL;
			}

			$sqlRecord  = $this->getRecordByField('id', $itemId, 'exact');
			return $sqlRecord;
			
		}
				
		
		
		
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getTotalItem($userIdFk)
		{
			
			$sqlTotal  = $this->getTotal();
			
			if ($sqlTotal) {
				return $sqlTotal;
			}
			else if(!$sqlTotal || ($sqlTotal < 1)){
				 return NULL;
			}
			
			
			
			//$sqlRecord  = $this->getRecordByField('id', $itemId, 'exact');
			//return $sqlRecord;
			
		}
			
			
		/* This function calls  getRecordByField function and returns table row
		 * @param fieldName
		 * @param $searchKey
		 * @return int $sqlCount
		 */
		
		
		function getTotalItemBySearchArray($fieldName, $searchKeyword, $searchStatus)
		{
			
			$sqlTotal  = $this->getTotalArray($fieldName, $searchKeyword, $searchStatus);
			
				
			
			if ($sqlTotal) {
				return $sqlTotal;
			}
			else if(!$sqlTotal || ($sqlTotal < 1)){
				 return 0;
			}
			
		}		
		
		
		/* ----- Table Item Photo Gallery Info ------- */
		
		
		/* This function calls  getTableCount function and returns table total
		 * @param $fieldName
		 * @param $searchKeyword
		 * @param $searchStatus / (empty/exact) 
		 * @return int $sqlCount
		 */

		function getItemPhotoGalleryTotal($fieldName='', $searchKeyword='', $searchStatus='')
		{
			$sqlCount = $this->getTableCount($this->tableItemGallery, $fieldName , $searchKeyword, $searchStatus );
			return $sqlCount;
			
		}
		
		/* This function calls  getTableCountArray function and returns table total
		 * @param array $fieldNameArray default Null 
		 * @param array $searchKeywordArray default Null 
		 * @param string $searchStatus default Null 
		 * @return int $sqlCount
		 */

		function getItemPhotoGalleryTotalArray($fieldNameArray='', $searchKeywordArray='', $searchStatus='')
		{
			$sqlCount = $this->getTableCountByArray($this->tableItemGallery, $fieldNameArray , $searchKeywordArray, $searchStatus );
			return $sqlCount;
			
		}
		
		
		/* This function calls  getTableRecordByField function and returns table record  single array
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getItemPhotoGalleryTableRecordByField($fieldName, $searchKey, $searchStatus = '', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getTableRecordByField($this->tableItemGallery, $fieldName , $searchKey, $searchStatus,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}
		
		
		
		/* This function calls  getSelectedTableRecordByField function and returns table record  single array
		 * @param string $selectedFields - seperated by comma (e.g. str = "id, name"; )
		 * @param string $fieldName
		 * @param string $searchKey
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @return int $sqlCount
		 */
		
		function getItemPhotoGalleryTableSelectedFieldsRecordByField($selectedFields, $fieldName, $searchKey, $searchStatus='', $orderByField='', $orderByValue='')
		{
			$sqlRecord = $this->getSelectedTableRecordByField($this->tableItemGallery, $selectedFields, $fieldName , $searchKey, $searchStatus,  $orderByField, $orderByValue );
			return $sqlRecord;
			
		}	
		
		/* This function calls  getTableCountArray function and returns table total
		 * @param string $fieldName default Null 
		 * @param string $searchKeyword default Null 
		 * @param string $searchStatus default Null 
		 * @return int $sqlCount
		 */

		function getItemPhotoGalleryTableTotalArray($fieldName='', $searchKeyword='', $searchStatus='')
		{
			$sqlCount = $this->getTableCountByArray($this->tableItemGallery, $fieldName , $searchKeyword, $searchStatus );
			return $sqlCount;
			
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

		function getItemPhotoGalleryTableListArray($fieldName='', $searchValue='', $searchStatus='', $orderByField='', $orderByValue='', $offset ='', $limit ='')
		{
			$sqlRecord = $this->getTableRecordListArray($this->tableItemGallery,  $fieldName, $searchValue, $searchStatus, $orderByField, $orderByValue, $offset, $limit);
			return $sqlRecord;
		
		}
		
		
		/* ------------------- end ---------------- */
		
		
		
		function addButtons() {
			
			$link = "   <select name=\"action\" id=\"action\">
							<option value=\"cnactive\">Set Status to Active</option>
							<option value=\"cninactive\">Set Status to InActive</option>
							<option value=\"cndeleted\">Delete Selected</option>
						</select>
					   <input type=\"submit\" name=\"submit\" value=\"Apply to Selected\" onClick=\"return checkdeletion();\" />
					   ";
						
			return $link;
		}
		
		
		
		
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
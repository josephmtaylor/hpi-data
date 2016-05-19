<?php
/**
 * PHP Class to database access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('db.class.php');
 * $db = new Db();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: dbclass.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */
 
require_once "connect.class.php";


class Db extends Connect { 

		/* Constructor
		 * 
		 */
		function __construct() {
		
			parent::__construct();
		}

		/* This function process the database query 
		 * @param string $sql
		 * @return string $result
		 */

	
		function dbquery($sql)
		{
			if (!empty($sql))
			{
				$result = mysql_query($sql);
				return $result;
			}
		} 


		/* This function process the database result string and return the result array
		 * @param string $result
		 * @return array $row
		 */
		 		
		function dbfetch($result)
		{
			if ($row=mysql_fetch_array($result))
			{
				return $row;
			} 
		} 
		
		
		/* This function process the database result string and return the associated result array
		 * @param string $result
		 * @return array $row
		 */
		 		
		function dbfetchassoc($result)
		{
			if ($row=mysql_fetch_assoc($result))
			{
				return $row;
			} 
		} 

		/* This function process the database result string and return the total count of table
		 * @param string $result
		 * @return array $row
		 */
		
		function dbnumrow($result)
		{
			if ($rowtotal=mysql_num_rows($result))
			{
				return $rowtotal;
			} 
		}
		
		
		/* This function process the array  and return the total count
		 * @param string $result
		 * @return array $row
		 */
		
		function arrayCount($array)
		{
			if (isset($array))
			{
				return count($array);
			} 
		}
		
		
		/* This function process the database table and send total count back
		 * @param string $tableName
		 * @param string $fieldName
		 * @param string $searchValue
		 * @param string $searchStatus
		 * @return int $sqlTableCount
		 */
		
		function getTableCount($tableName, $fieldName='', $searchValue='', $searchStatus='')
		{
			$sqlQuery = "SELECT * FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (isset($fieldName) && !empty($searchValue)) {
			
				if ($searchStatus == '')
					$sqlQuery .= " AND `$fieldName` like '%$searchValue%' " ;
				if ($searchStatus == 'exact')
					$sqlQuery .= " AND `$fieldName` = '$searchValue' " ;
			
			}
		//	echo $sqlQuery;
			
			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $tableName . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());
			
			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;
			
		}
	
	
		
		/* This function process the database table and send total count back
		 * @param string $tableName
		 * @param string $fieldName
		 * @param string $searchValue
		 * @param string $searchStatus
		 * @return int $sqlTableCount
		 */
		
		function getTableCountByArray($tableName, $fieldNameArray='', $searchValueArray='', $searchStatus='')
		{
			$sqlQuery = "SELECT * FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (!empty($fieldNameArray) && !empty($searchValueArray)) {
			
				$cntname = count($fieldNameArray);
				$cntval =  count($searchValueArray);
			
				if (($cntname > 0) && ($cntname == $cntval)) { 
				
					for($i=0; $i <$cntname; $i++) {
					
						if ($searchStatus == '')
							$sqlQuery .= " AND `$fieldNameArray[$i]` like '%$searchValueArray[$i]%' " ;
						if ($searchStatus == 'exact')
							$sqlQuery .= " AND `$fieldNameArray[$i]` = '$searchValueArray[$i]' " ;
					}
				}
			}
			//echo $sqlQuery;
			
			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $tableName . " : ".mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());
			
			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;
			
		}
	
	
		
	
	
		
		/* This function process the database table and send total count back
		 * @param string $tableName
		 * @return int $sqlTableCount
		 */
		
		function getTableCountByField($tableName, $fieldName, $searchValue)
		{
			
			$sqlQuery = "SELECT * FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (!empty($fieldName) && !empty($searchValue)) {
				$sqlQuery .= "AND `$fieldName` like '%$searchValue%' " ;
			}
		//	echo $sqlQuery;		
			$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());
			
			if (!empty($sqlTableCount))
				return $sqlTableCount;
			else
				return 0;
			
		}
		
		/* This function process the database table and return if 1 the table field is present
		 * @param string $tableName
		 * @param string $searchKey
		 * @return int $sqlCount
		 */
		
		
		function isTableFieldPresent($tableName, $searchField,  $searchKey)
		{
		
				if (is_string($searchField)) {
						
					$sqlQuery = "SELECT ".$searchField ." FROM ". $tableName ." WHERE ".$searchField . "= '$searchKey'";
					$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
					$sqlCount = $this->dbnumrow($sqlResult);
				}
				else if (is_array($searchField)) {
					
					$sqlQuery = "SELECT ".$searchField[0] ." FROM ". $tableName ." WHERE 1=1 ";
					
						$cntname = count($searchField);
						$cntval =  count($searchKey);
					
						if (($cntname > 0) && ($cntname == $cntval)) { 
						
							for($i=0; $i <$cntname; $i++) {
									$sqlQuery .= " AND `".$searchField[$i]."` = '$searchKey[$i]' " ;
							}
						}
						
				
					$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
					$sqlCount = $this->dbnumrow($sqlResult);
				
				}
				
				
				if ($sqlCount > 0)
					return 1;
				else 
					return 0;
		}
		
		
		/* This function process the database table and send record array
		 * @param string $tableName
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @param string $offset
		 * @param string $limit
		 * @return array $sqlRecordArray
		 */
		
		function getTableRecordList($tableName,  $fieldName='', $searchValue='', $orderByField='', $orderByValue='', $offset='', $limit ='')
		{
			
			$sqlRecordArray = array();
			$sqlQuery = "SELECT * FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (isset($fieldName) && !empty($searchValue)) {
				$sqlQuery .= " AND `" . $fieldName . "` like '%$searchValue%' " ;
			}
			
			if (!empty($orderByField) && !empty($orderByValue)) {
				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}
			
			if (isset($offset) && !empty($limit)) {
				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
			//echo $sqlQuery;
			
			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $tableName . " :".  mysql_error());
			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}
			
				return $sqlRecordArray;
			
		}
		
		/* This function process the database table and send record array
		 * @param string $tableName
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @param string $offset
		 * @param string $limit
		 * @return array $sqlRecordArray
		 */
		
		function getTableRecordListArray($tableName,  $fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit ='')
		{
			
			$sqlRecordArray = array();
			$sqlQuery = "SELECT * FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (!empty($fieldNameArray) && !empty($searchValueArray)) {
				
			//	echo $fieldNameArray . "=". $searchValueArray; exit(); 
			
				$cntname = count($fieldNameArray);
				$cntval =  count($searchValueArray);
			
				if (($cntname > 0) && ($cntname == $cntval)) { 
				
					for($i=0; $i <$cntname; $i++) {
					
						if ($searchStatus == '')
							$sqlQuery .= " AND `$fieldNameArray[$i]` like '%$searchValueArray[$i]%' " ;
						if ($searchStatus == 'exact')
							$sqlQuery .= " AND `$fieldNameArray[$i]` = '$searchValueArray[$i]' " ;
					}
				}
			}
			
			
			
			if (!empty($orderByField) && !empty($orderByValue)) {
				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}
			
			if (isset($offset) && !empty($limit)) {
				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
			//echo $sqlQuery;
			
			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $tableName . " ==>".  mysql_error());
			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}
			
				return $sqlRecordArray;
			
		}
		
		
		/* This function process the database table and send record array
		 * @param string $tableName
		 * @param string $fieldName
		 * @param string $searchValue
		 * @return array $sqlRecordArray
		 */
		
		function getTableRecordByField($tableName, $fieldName, $searchValue, $searchStatus = '', $orderByField='', $orderByValue='')
		{
			
			$sqlQuery = "SELECT * FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (!empty($fieldName) && !empty($searchValue)) {
				//$sqlQuery .= "AND `$fieldName` like '%$searchValue%' " ;
				
				if ($searchStatus == '')
					$sqlQuery .= " AND `$fieldName` like '%$searchValue%' " ;
				if ($searchStatus == 'exact')
					$sqlQuery .= " AND `$fieldName` = '$searchValue' " ;
					
			}
			if (!empty($orderByField) && !empty($orderByValue)) {
				$sqlQuery .= " ORDER BY `" . $orderByField . "` ". $orderByValue ;
			}
//			echo $sqlQuery;
			
			
			$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlRecordArray = $this->dbfetch($sqlQueryResult);
			return $sqlRecordArray;
			
		}
		
		
		/* This function process the database table and send record array matching to search criteria
		 * @param string $tableName
		 * @param string $fieldName
		 * @param string $searchValue
		 * @return array $sqlRecordArray
		 */
		
		function getSelectedTableRecordByField($tableName, $selctedFields, $fieldName, $searchValue,  $searchStatus = '', $orderByField='', $orderByValue='')
		{
			if (!empty($selctedFields)) {
				$sqlQuery = "SELECT ". $selctedFields ." FROM `" . $tableName . "` WHERE 1=1 ";
			
				if (!empty($fieldName) && !empty($searchValue)) {
					//$sqlQuery .= "AND `$fieldName` like '%$searchValue%' " ;
					
					if ($searchStatus == '')
						$sqlQuery .= " AND `$fieldName` like '%$searchValue%' " ;
					if ($searchStatus == 'exact')
						$sqlQuery .= " AND `$fieldName` = '$searchValue' " ;
						
				}
				if (!empty($orderByField) && !empty($orderByValue)) {
					$sqlQuery .= " ORDER BY `" . $orderByField . "` ". $orderByValue ;
				}
			//	echo $sqlQuery;
			
			
				$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
				$sqlRecordArray = $this->dbfetch($sqlQueryResult);
				return $sqlRecordArray;
			}
			else 
				return NULL;
			
		}
		
		
		/* This function process the database table and send record array matching to search criteria
		 * @param string $tableName
		 * @param string $fieldName
		 * @param string $searchValue
		 * @return array $sqlRecordArray
		 */
		
		function getSelectedTableRecordListByField($tableName, $selctedFields, $fieldName, $searchValue,  $searchStatus = '', $orderByField='', $orderByValue='')
		{
			if (!empty($selctedFields)) {
				$sqlQuery = "SELECT ". $selctedFields ." FROM `" . $tableName . "` WHERE 1=1 ";
			
				if (isset($fieldName) && isset($searchValue)) {
					//$sqlQuery .= "AND `$fieldName` like '%$searchValue%' " ;
					
					if ($searchStatus == '')
						$sqlQuery .= " AND `$fieldName` like '%$searchValue%' " ;
					if ($searchStatus == 'exact')
						$sqlQuery .= " AND `$fieldName` = '$searchValue' " ;
						
				}
				if (!empty($orderByField) && !empty($orderByValue)) {
					$sqlQuery .= " ORDER BY `" . $orderByField . "` ". $orderByValue ;
				}
				//echo $sqlQuery;
			
			
				$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
				while ($sqlArray = $this->dbfetch($sqlQueryResult))
				{
					$sqlRecordArray[] = $sqlArray;
				}
				
				if (!empty($sqlRecordArray))
					return $sqlRecordArray;
				else
					return 0;
			}
			else 
				return NULL;
			
		}
		
		
		/* This function process the database table and send record array
		 * @param string $tableName
		 * @param string $orderByField
		 * @param string $orderByValue
		 * @param string $offset
		 * @param string $limit
		 * @return array $sqlRecordArray
		 */
		
		function getSelectedTableRecordListArray($tableName, $selctedFields,  $fieldNameArray='', $searchValueArray='', $searchStatus='', $orderByField='', $orderByValue='', $offset='', $limit ='')
		{
			
			$sqlRecordArray = array();
			$sqlQuery = "SELECT ". $selctedFields ."  FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (isset($fieldNameArray) && isset($searchValueArray)) {
				
			//	echo $fieldNameArray . "=". $searchValueArray; exit(); 
			
				$cntname = count($fieldNameArray);
				$cntval =  count($searchValueArray);
			
				if (($cntname > 0) && ($cntname == $cntval)) { 
				
					for($i=0; $i <$cntname; $i++) {
					
						if ($searchStatus == '')
							$sqlQuery .= " AND `$fieldNameArray[$i]` like '%$searchValueArray[$i]%' " ;
						if ($searchStatus == 'exact')
							$sqlQuery .= " AND `$fieldNameArray[$i]` = '$searchValueArray[$i]' " ;
					}
				}
			}
			
			
			
			if (!empty($orderByField) && !empty($orderByValue)) {
				$sqlQuery .= " ORDER BY " . $orderByField . " ". $orderByValue ;
			}
			
			if (isset($offset) && !empty($limit)) {
				$sqlQuery .= " LIMIT  " . $offset . ", ". $limit ;
			}
			//echo $sqlQuery;
			
			$sqlQueryResult = $this->dbquery($sqlQuery) or die( $tableName . " :".  mysql_error());
			while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
			{
				$sqlRecordArray[] = $sqlArray;
			}
			
				return $sqlRecordArray;
			
		}
		
		
		
		/* This function delete the record from table
		 * @param string $tableName
		 * @param string $fieldName
		 * @param string $searchValue
		 * @return int $sqlQueryResult
		 */
		
		function deleteTableRecord($tableName, $fieldName, $searchValue)
		{
			
			$sqlQuery = "DELETE FROM `" . $tableName . "` WHERE 1=1 ";
			
			if (isset($fieldName) && !empty($searchValue)) {
				$sqlQuery .= "AND `$fieldName` = '$searchValue' " ;
			}
			$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			
			if (!empty($sqlQueryResult))
				return 1;
			else
				return 0;
			
		}
		
		
		
		/* This function process the input string and checks for error/vulnerable code and send back
		 * @param string $string
		 * @return string string
		 */		
		
		function prepare_input($string) 
		{
				if (is_string($string)) {
						$string=htmlspecialchars($string);
						return trim(addslashes($string));
				} 
				elseif (is_array($string)) 
				{
					  reset($string);
					  while (list($key, $value) = each($string)) {
						$string[$key] = prepare_input($value);
					  }
				  return $string;
				} 
				else 
				{
				  return $string;
				}
	    }
	  
	    /* This function process the string and prepare it for output 
		 * @param string $string
		 * @return string string
		 */	
	  
		function prepare_output($string) 
		{
				if (is_string($string)) 
				{
					$string = strip_tags(htmlspecialchars_decode($string));
					//$string = nl2br($string);
					return trim(stripslashes($string));
				} 
				elseif (is_array($string)) 
				{
					  reset($string);
					  while (list($key, $value) = each($string)) {
						$string[$key] = prepare_output($value);
					  }
					 return $string;
				} 
				else 
				{
				  return $string;
				}
		} 
		
	    /* This function process the string for htaccess mod rewrite 
		 * @param string $string
		 * @return string string
		 */	
		
	   
		function prepare_modrewrite($string)
		{
			$string = strip_tags($string);
			$string = trim(stripslashes($string));
			
			$replacestr = array("'", "%", ":", ";", "[", "]", "{", "}", "/", ",", "|", "#", "!", "*", "~", "@", "`", "^", "+", ".", "<", ">", "?", "html", "htm");
			$string = str_replace($replacestr, "", $string);
			
			$string = str_replace("-", "_", $string);
			$string = str_replace(" ", "_", $string);
			
			return $string;
		}
		
	    /* This function generates the random password
		 * @param string $string
		 * @return string string
		 */	
		
	   
		function gen_md5_password($len = 6)
		{
			// function calculates 32-digit hexadecimal md5 hash  of some random data
			return substr(md5(rand().rand()), 0, $len);
		}
		
		/*function gen_promocode($firstname, $lastname, $len = 6)
		{
			// function calculates 32-digit hexadecimal md5 hash  of some random data
			$validchars = "0123456789";
			$flen = strlen($firstname);
			$llen = strlen($lastname);
			$r1 = rand(1,$flen);
			$r2 = rand(1,$llen);
			
			$f1name= substr($firstname,$r1,3);
			$f2name= substr($firstname,$r1,4);
			$l1name= substr($lastname,$r2,3);
			$l2name= substr($lastname,$r2,4);
			$xname = $firstname.$lastname;
			$names = array($firstname, $lastname, $f1name, $f2name, $l1name, $l2name, $xname);
			$nlen = count($names);
			
			$prefix =$names[rand(0,$nlen)];
			return $prefix. substr(md5(rand().rand()),rand(1,9), 3);
			//return substr(md5(rand().rand()), rand(0,9), $len);
		}*/
		
		function gen_promocode($prefix, $len = 6)
		{
			// function calculates 32-digit hexadecimal md5 hash  of some random data
			$validchars = "0123456789";
			//$flen = strlen($firstname);
//			
//			$r1 = rand(1,$flen);
//			
//			$f1name= substr($firstname,$r1,3);
//			$f2name= substr($firstname,$r1,4);
//			$names = array($firstname, $f1name, $f2name);
//			$nlen = count($names);
//			
//			$prefix =$names[rand(0,$nlen)];
			return $prefix. substr(rand().rand(),0, $len);
			//return substr(md5(rand().rand()), rand(0,9), $len);
		}
		


	    /* This function process paging and return the string
		 * @param int $tcount
		 * @param string $total
		 * @param string $page
		 * @return string $link
		 */	
		
		function recordsetWithPaging($tcount, $total, $page)
		{
			if ($total > 0)
			{
				   $link = "<div class=\"sTableOptions\">
        	
						<div class=\"pagination pgright\">";
					
					if ($page > 1)
					{
					   $link .="<a href=\"".$_SERVER['PHP_SELF']."?page=1\"\" class=\"first\">&laquo; First</a> ";
					   $prev = $page - 1;
					   $link .="<a href=\"".$_SERVER['PHP_SELF']."?page=".$prev."\" class=\"prev\">&lsaquo; Prev</a> ";
					}
					else {
					   $link .="<a href=\"#\" class=\"first disabled\">&laquo; First</a> 
								<a href=\"#\" class=\"prev disabled\">&lsaquo; Prev</a> ";
					}
					
					if ($page < $total)
					{
						$next = $page + 1;
						$link .="<a href=\"".$_SERVER['PHP_SELF']."?page=".$next."\" class=\"next\">Next</a> 
								<a href=\"".$_SERVER['PHP_SELF']."?page=".$total."\" class=\"last\">Last &raquo;</a>";
					}
					else
					{
						$link .= "<a href=\"#\" class=\"next disabled\">Next</a> ";
						$link .= "<a href=\"#\" class=\"last disabled\">Last &raquo;</a>";
					}
						
						$link .= "</div>
						
						<a class=\"button delete\"><span>Delete</span></a>
						</div>";
					return $link;
						
						
				
			}		
		
	
		}
		
		
		/* This function process paging and return the string
		 * @param int $tcount
		 * @param string $total
		 * @param string $page
		 * @param string $suffix
		 * @return string $link
		 */	
		
		function recordsetWithPagingWithSuffix($tcount, $total, $page, $suffix, $linkBtn='')
		{
			if ($total > 0)
			{
				 	 $link = "<div class=\"sTableOptions\">";
					 $link .="	<div class=\"pagination pgright\">";
			  		 
					 $link .="<a hre\"#\" class=\"button disabled\">Showing page <b>". $page ."</b> of <b>". $total ."</b> pages</a>&nbsp;&nbsp;";
					if ($page > 1)
					{
					   $link .="<a href=\"".$_SERVER['PHP_SELF']."?page=1".$suffix."\" class=\"first\">&laquo; First</a> ";
					   $prev = $page - 1;
					   $link .="<a href=\"".$_SERVER['PHP_SELF']."?page=".$prev.$suffix."\" class=\"prev\">&lsaquo; Prev</a> ";
					}
					else {
					   $link .="<a href=\"#\" class=\"first disabled\">&laquo; First</a> 
								<a href=\"#\" class=\"prev disabled\">&lsaquo; Prev</a> ";
					}
					
					if ($page < $total)
					{
						
						$next = $page + 1;
						$link .=" <a href=\"".$_SERVER['PHP_SELF']."?page=".$next.$suffix."\" class=\"next\">Next</a> 
								<a href=\"".$_SERVER['PHP_SELF']."?page=".$total.$suffix."\" class=\"last\">Last &raquo;</a>";
					}
					else
					{
						$link .= "<a href=\"#\" class=\"next disabled\">Next</a> ";
						$link .= "<a href=\"#\" class=\"last disabled\">Last &raquo;</a>";
					}
						
						$link .= "</div>";
						
						if (!empty($linkBtn))
							$link .=  $linkBtn;
						else 
							$link .= "<br /><br />";	
						/*$link .= "<button class=\"button delete\">Delete</button>
						<input name=\"action\" type=\"hidden\" value=\"delsel\" />";*/
						
						$link .= "</div>";	
				return $link;	
				
			}		
		
	
		}
		
		/* This function process paging and return the string
		 * @param int $tcount
		 * @param string $total
		 * @param string $page
		 * @param string $suffix
		 * @return string $link
		 */	
		
		function pagingWithSuffix($tcount, $total, $page, $suffix, $linkBtn='')
		{
			if ($total > 0)
			{
				 	
					$link = "<div class=\"page-buttons\">";
					//$link .= "<span class=\"big_font\">Showing page ". $page ." of ". $total ."</span> &nbsp; &nbsp;";
					if ($page > 1)
					{
						$link .= "<div class=\"page-prev\"><a href=\"".$_SERVER['PHP_SELF']."?page=1".$suffix."\" >&laquo; First</a></div> &nbsp;";
						$prev = $page - 1;
						$link .= "<div class=\"page-prev\"><a href=\"".$_SERVER['PHP_SELF']."?page=".$prev.$suffix."\">Prev</a></div> &nbsp;";
					}
					else {
						$link .= "<div class=\"page-prev\"><a href=\"#\">&laquo; First</a></div> &nbsp;";
						$link .= "<div class=\"page-prev\"><a href=\"#\">Prev</a></div> &nbsp;";
						
					}
					
					if ($page < $total)
					{
						
						$next = $page + 1;
						
						$link .="<div class=\"page-next\"><a href=\"".$_SERVER['PHP_SELF']."?page=".$next.$suffix."\" >Next</a></div>&nbsp;
								 <div class=\"page-next\"><a href=\"".$_SERVER['PHP_SELF']."?page=".$total.$suffix."\" >Last &raquo;</a></div>&nbsp;";
					}
					else {
						$link .= "<div class=\"page-next\"><a href=\"#\">Next</a></div>&nbsp;";
						$link .= "<div class=\"page-next\"><a href=\"#\">Last &raquo;</a></div>&nbsp;";
						
					}
						$link .= "</div>";
						
						
						
						
				return $link;	
				
			}		
		
	
		}
		
		
		
		function buildOptions($options, $selectedOption)
		{
			foreach($options as $options1)	
			{
					  foreach ($options1 as $value => $text)
					  {
					  
						if ($text == $selectedOption)
						{
						  echo '<option value="' . $text . 
							   '" selected="selected">' . $text . '</option>';
						}
						else
						{
						  echo '<option value="' . $text . '">' . $text . '</option>';
						}
					  }
			}
		}
		
		function buildSingleOptions($options, $selectedOption)
		{
		  foreach ($options as $value => $text)
		  {
			if ($value == $selectedOption)
			{
			  echo '<option value="' . $value . '" selected="selected">' . $text . '</option>';
			}
			else
			{
			  echo '<option value="' . $value . '">' . $text . '</option>';
			}
		  }
		}
		
		
		function getBackLink()
		{
			 $url = $_SERVER['REQUEST_URI'];
			 if (eregi('=/', $url)) {
				$len = strlen($url);
				$findme   = '=/';
				$pos = strpos($url, $findme);
				$pos =$pos +1;
				return $backlink =  substr($url, $pos);     
			 }
			 else
			 	return $url;
		}
		
		function getThisLink()
		{
			 $url = $_SERVER['REQUEST_URI'];
			 if (eregi('&', $url)) {
				$len = strlen($url);
				$findme   = '&';
				$pos = strpos($url, $findme);
				$pos =$pos ;
				return $backlink =  substr($url, 0, $pos);     
			 }
			 else
			 	return $url;
		}
		
		
		function datetojulian( $month, $day, $year )
		{
			if ( 2 < $month )
			{
				$month -= 3;
			}
			else
			{
				$month += 9;
				$year -= 1;
			}
			$c = floor( $year / 100 );
			$ya = $year - 100 * $c;
			$j = floor( 146097 * $c / 4 );
			$j += floor( 1461 * $ya / 4 );
			$j += floor( ( 153 * $month + 2 ) / 5 );
			$j += $day + 1721119;
			return $j;
		}
		
		function juliantodate( $julian )
		{
			$julian -= 1721119;
			$calc1 = 4 * $julian - 1;
			$year = floor( $calc1 / 146097 );
			$julian = floor( $calc1 - 146097 * $year );
			$day = floor( $julian / 4 );
			$calc2 = 4 * $day + 3;
			$julian = floor( $calc2 / 1461 );
			$day = $calc2 - 1461 * $julian;
			$day = floor( ( $day + 4 ) / 4 );
			$calc3 = 5 * $day - 3;
			$month = floor( $calc3 / 153 );
			$day = $calc3 - 153 * $month;
			$day = floor( ( $day + 5 ) / 5 );
			$year = 100 * $year + $julian;
			if ( $month < 10 )
			{
				$month += 3;
			}
			else
			{
				$month -= 9;
				$year += 1;
			}
			$day = $this->formatday($day);
			return "{$day}/".$this->format_month($month)."/{$year}";
		}	
		
		function format_month($month)
		{
				if($month<10)
					$month = "0$month";  
				else
					$month = "$month";  
			
				return $month;
		}
		
		function formatdate($dt)
		{
			$arr=split("/",$dt); // splitting the array
			$mm=$arr[1]; // first element of the array is month
			$dd=$arr[0]; // second element is date
			$yy=$arr[2]; // third element is year
			
			if($mm == 1 or $mm == 01)
					$month = "January";  
			else if($mm == 2 or $mm == 02)
					$month = "February";  
			else if($mm == 3 or $mm == 03)
					$month = "March";  
			else if($mm == 4 or $mm == 04)
					$month = "April";  
			else if($mm == 5 or $mm == 05)
					$month = "May";  
			else if($mm == 6 or $mm == 06)
					$month = "June";  
			else if($mm == 7 or $mm == 07)
					$month = "July";  
			else if($mm == 8 or $mm == 08)
					$month = "August";  
			else if($mm == 9 or $mm == 09)
					$month = "September";  
			else if($mm == 10)
					$month = "October";  
			else if($mm == 11)
					$month = "November";  
			else if($mm == 12)
					$month = "December";  
			
			return $date1 = $dd . " ".  $month ." ".  $yy; 
		}	
		
		function formatday($dt)
		{
			$dd=$dt; // second element is date
			
			if($dd == 1 or $dd == 01)
					$dd = "01";  
			else if($dd == 2 or $dd == 02)
					$dd = "02";  
			else if($dd == 3 or $dd == 03)
					$dd = "03";  
			else if($dd == 4 or $dd == 04)
					$dd = "04";  
			else if($dd == 5 or $dd == 05)
					$dd = "05";  
			else if($dd == 6 or $dd == 06)
					$dd = "06";  
			else if($dd == 7 or $dd == 07)
					$dd = "07";  
			else if($dd == 8 or $dd == 08)
					$dd = "08";  
			else if($dd == 9 or $dd == 09)
					$dd = "09";  
			
			return  $dd; 
		}
		
		function isDateDiff($date1, $date2) {
		
//		$date1 = "2010-10-24";
//		$date2 = "2010-10-26";
		
		$diff = abs(strtotime($date2) - strtotime($date1));
		
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		
		printf("%d years, %d months, %d days\n", $years, $months, $days);
		
		}
		
		
		function getMonthName($mm)
		{
		
			if($mm == 1 or $mm == 01)
					$month = "January";  
			else if($mm == 2 or $mm == 02)
					$month = "February";  
			else if($mm == 3 or $mm == 03)
					$month = "March";  
			else if($mm == 4 or $mm == 04)
					$month = "April";  
			else if($mm == 5 or $mm == 05)
					$month = "May";  
			else if($mm == 6 or $mm == 06)
					$month = "June";  
			else if($mm == 7 or $mm == 07)
					$month = "July";  
			else if($mm == 8 or $mm == 08)
					$month = "August";  
			else if($mm == 9 or $mm == 09)
					$month = "September";  
			else if($mm == 10)
					$month = "October";  
			else if($mm == 11)
					$month = "November";  
			else if($mm == 12)
					$month = "December";  
			
			return $month; 
		}	
		
		
		function mailsender($mailheaders, $toemail, $subject, $strMessageBody)
		{
			$getMessageBodyHeader = $this->getMessageBodyHeader();
			$getMessageBodyFooter = $this->getMessageBodyFooter();	
			
			$strMessageBodyAd = $getMessageBodyHeader. $strMessageBody . $getMessageBodyFooter;
			
		
			if (empty($mailheaders)) { 
				$fromemail = "noreply@heritagepublishing.com";
				$mailheaders = "From: Heritage Publishing<$fromemail> \nContent-type: text/html; charset=iso-8859-1\n";
			}
		
			$mailsent =	mail($toemail, $subject, $strMessageBodyAd, $mailheaders);

			if ($mailsent) 
				return 1;
			else
				return 0;			
			
		}
		
		
		function getMessageBodyHeader()
		{
		
				$strMessageBodyHeader ="<html>
										<body>
										<table width=100% border=0 cellspacing=0 cellpadding=0>
										<tr>
										  <td>Heritage Publishing Inc.</td>
										  </tr>
										<tr> 
										<td>&nbsp;</td>
										</tr>
									
										<tr> 
										<td style=\"padding-left:10px; \"><font face=Arial, Helvetica, sans-serif size=2>";
			
				return 	$strMessageBodyHeader;
		}
		
		function getMessageBodyFooter()
		{
		
				$strMessageBodyFooter ="	</font></td>
										</tr>
										</table>
										</body>
										</html>";
		
				return 	$strMessageBodyFooter;
		}
		
		
		function change2dmy($date) //input format: yyyy-m-d
		{
			$dtmp = explode("-",$date);
			$dadate = mktime(0,0,0,$dtmp[1],$dtmp[2],$dtmp[0]);
			return date('d/m/Y',$dadate);
		}
		
		function change2mdy($date) //input format: m-d-yyyy
		{
			$dtmp = explode("-",$date);
			$dadate = mktime(0,0,0,$dtmp[1],$dtmp[2],$dtmp[0]);
			return date('m/d/Y',$dadate);
		}
		
		function change2mdy2($date) //input format: yyyy-m-d
		{
			$dtmp = explode("-",$date);
			$dadate = mktime(0,0,0,$dtmp[1],$dtmp[2],$dtmp[0]);
			return date('m/d/Y',$dadate);
		}
		
		function change2ymd($date) //input format: d/m/yy or yyyy
		{
			$dtmp = explode("/",$date);
			$dadate = mktime(0,0,0,$dtmp[1],$dtmp[0],$dtmp[2]);
			return date('Y-m-d',$dadate);
		}
		
		function change2ymd2($date) //input format: yyyy-m-d
		{
			$dtmp = explode("-",$date);
			$dadate = mktime(0,0,0,$dtmp[1],$dtmp[2],$dtmp[0]);
			return date('M d, Y',$dadate);
		}
		
		function change2ymd1($date) //input format: m/d/yy or yyyy
		{
			$dtmp = explode("/",$date);
			$dadate = mktime(0,0,0,$dtmp[0],$dtmp[1],$dtmp[2]);
			return date('Y-m-d',$dadate);
		}
		
		
		
		
		function excel_number_to_date($num)
		{
			$num=$num-25570;  //this is because php date function work only for date after 1/1/1970
			return $this->excel_addday('1970/01/02',$num);
		}
		
		function excel_addday($dat,$days)  //’$dat’ will be in ‘YYYY/mm/dd’ format
		{
			$dat=str_replace("/","-",$dat);
			$dat=date("Y-m-d",strtotime($dat));
			return date("Y-m-d",strtotime($days.' days',strtotime($dat)));
		}
		
		
		function getStatusImage($status) {
		
			switch ($status) {
			
				case 'V':
					$status = '<img title="Verified" src="images/icons/active.png" alt="Verified" width="16" style="vertical-align:middle"/>';
					return $status; break;
				case 'UV':
					$status = '<img title="Unverified" src="images/icons/inactive.png" alt="Unverified" width="16" style="vertical-align:middle" />';
					return $status; break;
				case 'D':
					$status = '<img title="Done" src="images/icons/done.png" alt="Done" width="16" style="vertical-align:middle" />';
					return $status; break;
				case 'P':
					$status = '<img title="Pending" src="images/icons/pending.png" alt="Pending" width="16" style="vertical-align:middle" />';
					return $status; break;
				case 'N':
					$status = '<img title="New" src="images/icons/new.png" alt="New" width="16" style="vertical-align:middle" />';
					return $status; break;
				case 'E':
					$status = '<img title="New" src="images/icons/exl.png" alt="Error" width="16" style="vertical-align:middle" />';
					return $status; break;
			}
		}
		
		
		function getStatus($status) { 
		
			switch ($status) {
				case 'V':
					return 'Verified'; break;
				case 'UV':
					return 'Unverified'; break;
				case 'N':
					return 'New'; break;
				case 'P':
					return 'Pending'; break;
				case 'D':
					return 'Done'; break;
				case 'E':
					return 'Error'; break;
				
				case 'RT':
					return 'Retired'; break;
				case 'DC':	
				 	return 'Deceased'; break; 
				case 'OT':	
					return 'Out Of Area'; break;
				case 'NI':	
					return 'No Other Info'; break;
				case 'LC':
					return 'License Issue'; break;
					
			}
		
		}
		
		function showRecord($status) { 
		
			switch ($status) {
				case 'V':
				case 'UV':
				case 'N':
				case 'P':
				case 'D':
				case 'E':
					return TRUE; break;
				
				case 'RT':
				case 'DC':	
				case 'OT':	
				case 'NI':	
				case 'LC':
					return FALSE; break;
					
			}
		
		}
		
		
		function setQuery($lastquery) {
			$_SESSION['last_query'] = '';
			if(isset($lastquery))
				$_SESSION['last_query'] = $lastquery;
		}
		
		
		function getQuery() {
			$lastquery = '';
			if(isset($_SESSION['last_query']))
				$lastquery = $_SESSION['last_query'];
				
				return $lastquery;
		}
		
		
		function getQueryTotal($sqlQuery) {

				if (isset($sqlQuery)) { 			
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			
						$sqlTableCount = $this->dbnumrow($sqlQueryResult); //or die(mysql_error());
	
						if (!empty($sqlTableCount))
							return $sqlTableCount;
				}
				return 0;
		}
		
		function getQueryResult($sqlQuery) {

				if (isset($sqlQuery)) { 			
						$sqlQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
			
						while ($sqlArray = $this->dbfetchassoc($sqlQueryResult))
								$sqlRecordArray[] = $sqlArray;
						
						return $sqlRecordArray;
				}
				return false;
		}

		function checkdomain($domain) {
			
			if(filter_var(gethostbyname($domain), FILTER_VALIDATE_IP))
				return TRUE;
			else
				return FALSE;
		}
		
		/* Constructor
		 * 
		 */
		function __destruct() {
		
			parent::__destruct();
		}

		
}

?>
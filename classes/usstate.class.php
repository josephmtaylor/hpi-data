<?php
require_once "db.class.php";


class UsStateClass extends Db { 

		function __construct() {
		
			parent::__construct();
		}
		
		//  ----- |  CMS PAGE INFO  | ---------------------------------------------------------------
		
		function getTotalUsState()
		{
			$sqlQuery = "SELECT id FROM usstate ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlCount = $this->dbnumrow($sqlMemberRes); //or die(mysql_error());
			
			if (!empty($sqlCount))
				return $sqlCount;
			else
				return 0;
			
		}
		
		function getTotalUsStateByCountry($country)
		{
			$sqlQuery = "SELECT id from usstate WHERE country = '$country'";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlCount = $this->dbnumrow($sqlMemberRes); //or die(mysql_error());
			
			if (!empty($sqlCount))
				return $sqlCount;
			else
				return 0;
			
		}
		
		function getUsStateIdByUsStateName($state)
		{
			$sqlQuery = "SELECT id FROM usstate WHERE state = '$state' ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			
			$sqlRecordArray = $this->dbfetch($sqlMemberRes);
			return $sqlRecordArray['id'];
			
		}
		
		function isUsStateCreated($id)
		{
			$sqlQuery = "SELECT id FROM usstate WHERE id = '$id'  ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			
			$sqlRecordNo = $this->dbnumrow($sqlMemberRes);
			if ($sqlRecordNo > 0 )
				return 1;
			else 
				return 0;
			
		}
		
		function getUsStateNameById($id)
		{
			$sqlQuery = "SELECT state FROM usstate WHERE id = '$id'  ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			
			$sqlRecordArray = $this->dbfetch($sqlMemberRes);
			return $sqlRecordArray['state'];
			
		}
		
		function getUsStateById($id)
		{
			$sqlQuery = "SELECT * from usstate WHERE id = '$id' ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			
			$sqlRecordArray = $this->dbfetch($sqlMemberRes);
			return $sqlRecordArray;
			
		}
		
		function getUsStateList()
		{
			$sqlQuery = "SELECT * from usstate";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			while ($sqlMemberArray = $this->dbfetch($sqlMemberRes))
			{
				$returnArray[] = $sqlMemberArray;
			}
			return $returnArray;
			
		}
		
		function getUsStateListByCountry($country)
		{
			$sqlQuery = "SELECT * from usstate WHERE country = '$country'";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			while ($sqlMemberArray = $this->dbfetchassoc($sqlMemberRes))
			{
				$returnArray[] = $sqlMemberArray;
			}
			return $returnArray;
			
		}
		
		function getUsStateListByLimit($offset, $limit)
		{
			$sqlQuery = "SELECT * from usstate ORDER BY id LIMIT $offset, $limit ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			
			while ($sqlRecordArray = $this->dbfetch($sqlMemberRes))
			{
				$returnArray[] = $sqlRecordArray;
			}
			return $returnArray;
			
		}
		
		function deleteUsState($id)
		{
				
				$sqlUpdateQuery = "DELETE FROM `usstate` WHERE  id = '$id'";
				$sqlUpdateQueryResult = $this->dbquery($sqlUpdateQuery) or die(mysql_error());
				
				if ($sqlUpdateQueryResult)
					return 1;
				else
					return 0;
		}
		
		
		function deleteUsStateArray($id)
		{
				$n = count($id);
			
				if ($n > 0) { 
					
					for ($i=0; $i < $n ; $i++) {
						$aid = $id[$i];
					
						$sqlUpdateQuery = "DELETE FROM `usstate` WHERE  id = '$aid'";
						$sqlUpdateQueryResult = $this->dbquery($sqlUpdateQuery) or die(mysql_error());
					}
					
				}
					return 1;
			
		}
		
		
		function editUsState($sessionValues)
		{
				
				$id 		  = 	$sessionValues['id'];
				$statecode  =		$this->prepare_input($sessionValues['statecode']);
				$state  =		$this->prepare_input($sessionValues['state']);
				$country  =		$this->prepare_input($sessionValues['country']);

				$UsStateCreated = $this->isUsStateCreated($id);
				
				if ($UsStateCreated == 0) 
				{
						$sqlQuery = "INSERT INTO `usstate` (`id`  ,`statecode`,`state`, `country` )
													VALUES ( NULL ,  '$statecode',  '$state' , '$country') ";
						$sqlMemberQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$id = mysql_insert_id();
						
				}
				else if ($UsStateCreated > 0) 
				{
						$sqlQuery = "UPDATE `usstate` SET  `statecode`  = '$statecode', `state`  = '$state', `country` = '$country' WHERE id = $id ";
						$sqlMemberQueryResult = $this->dbquery($sqlQuery) or die(mysql_error());									  
				}
				
						if ($sqlMemberQueryResult == 1)
							return 1;
						else
							return 0;
				

		}	
		
		function isUsStateRegistered($state)
		{
		
				$sqlMemberQuery = "SELECT id FROM usstate WHERE state = '$state'";
				$sqlMemberRes = $this->dbquery($sqlMemberQuery) or die(mysql_error());
				$sqlCount = $this->dbnumrow($sqlMemberRes);
				
				if ($sqlCount > 0)
					return 1;
				else 
					return 0;
		}


		// --- GET RECORDS BY SEARCH KEYWORDS
		
		function getTotalUsStateBySearchKeyword($searchkey)
		{
			$sqlQuery = "SELECT id FROM usstate WHERE state like '%$searchkey%'";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlCount = $this->dbnumrow($sqlMemberRes); //or die(mysql_error());
			
			if (!empty($sqlCount))
				return $sqlCount;
			else
				return 0;
			
		}
		
		function getUsStateListByLimitBySearchKeyword($searchkey,$offset, $limit)
		{
			$sqlQuery = "SELECT * from usstate WHERE state like '%$searchkey%' ORDER BY id LIMIT $offset, $limit ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			
			while ($sqlRecordArray = $this->dbfetch($sqlMemberRes))
			{
				$returnArray[] = $sqlRecordArray;
			}
			
			return $returnArray;
			
		}
		
		
	

}
?>
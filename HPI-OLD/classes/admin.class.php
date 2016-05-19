<?php
/**
 * PHP Class to admin access (dbquery, dbfetch , etc)
 * 
 * <code><?php
 * include('admin.class.php');
 * $Ad = new AdminClass();
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


class AdminClass extends Db { 

		private $table = 'pwd';
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
		
		/* This function calls  getSelectedTableRecordByField function and returns table record  single array
		 * @param string $selectedFields - seperated by comma (e.g. str = "id, name"; )
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
		
		
		function getUserInfo($user)
		{
			$sqlQuery = "SELECT * from pwd WHERE username = '$user' ";
			$result = $this->dbquery($sqlQuery) or die(mysql_error());
			
			if(!$result || ($this->dbnumrow($result) < 1)){
				 return NULL;
			}

			$sqlRecord = $this->dbfetch($result);
			return $sqlRecord;
			
		}
		
		
		function isUserCreated($user)
		{
			
			$fieldName = 'username';
			$searchValue = $user;
			$sqlRecord = $this->getTableCountByField($this->table, $fieldName, $searchValue);
			
			
		}
		
		
		function isUserIdPresent($uid)
		{
			$sqlQuery = "SELECT uid from pwd WHERE uid = '$uid' ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlRecord = $this->dbnumrow($sqlMemberRes);
			return $sqlRecord;
			
		}
		
		
	 	/**
		* confirmUserID - Checks whether or not the given
		* username is in the database, if so it checks if the
		* given userid is the same userid in the database
		* for that user. If the user doesn't exist or if the
		* userids don't match up, it returns an error code
		* (1 or 2). On success it returns 0.
		*/
	    function confirmUserID($username, $userid){
			  /* Add slashes if necessary (for query) */
			  if(!get_magic_quotes_gpc()) {
				  $username = addslashes($username);
			  }
		
			  /* Verify that user is in database */
			  $sqlQuery = "SELECT uid FROM pwd WHERE username = '$username'";
			  $result = $this->dbquery($sqlQuery) or die(mysql_error());
			  if(!$result || ($this->dbnumrow($result) < 1)){
				 return 1; //Indicates username failure
			  }
		
			  /* Retrieve userid from result, strip slashes */
			  $dbarray = $this->dbfetch($result);
			  $dbarray['userid'] = stripslashes($dbarray['uid']);
			  $userid = stripslashes($userid);
		
			  /* Validate that userid is correct */
			  if($userid == $dbarray['uid']){
				 return 0; //Success! Username and userid confirmed
			  }
			  else{
				 return 2; //Indicates userid invalid
			  }
	    }
		
	   /**
		* confirmUserPass - Checks whether or not the given
		* username is in the database, if so it checks if the
		* given password is the same password in the database
		* for that user. If the user doesn't exist or if the
		* passwords don't match up, it returns an error code
		* (1 or 2). On success it returns 0.
		*/
	   function confirmUserPass($username, $password){
			  /* Add slashes if necessary (for query) */
			  if(!get_magic_quotes_gpc()) {
				  $username = addslashes($username);
			  }
		
			  /* Verify that user is in database */
			  $q = "SELECT password FROM pwd WHERE username = '$username'";
			  $result = $this->dbquery($q);
			  if(!$result || ($this->dbnumrow($result) < 1)){
				 return 1; //Indicates username failure
			  }
		
			  /* Retrieve password from result, strip slashes */
			  $dbarray = $this->dbfetch($result);
			  $dbarray['password'] = stripslashes($dbarray['password']);
			  $password = stripslashes($password);
	
			  /* Validate that password is correct */
			  if($password == $dbarray['password']){
				 return 0; //Success! Username and password confirmed
			  }
			  else{
				 return 2; //Indicates password failure
			  }
	   }
   	
		
		function isUserRegistered($uname)
		{
		
				$q = "SELECT uid FROM pwd WHERE username = '$uname'";
				$result = $this->dbquery($q) or die(mysql_error());
				$sqlCount = $this->dbnumrow($result);
				
				if ($sqlCount > 0)
					return 1;
				else 
					return 0;
		}
		
		function getAdminSettingsById()
		{
			$sqlQuery = "SELECT * from pwd WHERE uid = '1' ";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
			$sqlRecordArray = $this->dbfetch($sqlMemberRes);
			return $sqlRecordArray;
			
		}
		
		function editUser($sessionValues)
		{
				//print_r($sessionValues);
				$uid 		  		= 		$sessionValues['uid'];
				$name  			  	=		$this->prepare_input($sessionValues['name']);
				$address  	  	  	=		$this->prepare_input($sessionValues['address']);
				$uname  			=		$this->prepare_input($sessionValues['uname']);
				$usertype  			=		$this->prepare_input($sessionValues['usertype']);
				$email  		  	=		$this->prepare_input($sessionValues['email']);
				$specialty_id_fk	= 		$this->prepare_input($sessionValues['specialty_id_fk']);
				
				if (empty($specialty_id_fk))
					$specialty_id_fk = 0;
				
				if (!empty($sessionValues['psword'])) { 
					$psword = $this->prepare_input($sessionValues['psword']);
					$checkpass = sha1($psword  . HASH_SHA1);
				}
				else {
					$checkpass = '';
				}
				
				$UserCreated = $this->isUserIdPresent($uid);
				
				if ($UserCreated == 0) 
				{
						$sqlQuery = "INSERT INTO `pwd` ( `uid` , `name` , `address` , `username` , `password` , `email`, `usertype` , `specialty_id_fk` )
											    VALUES ( NULL, '$name', '$address', '$uname', '$checkpass',  '$email' , '$usertype' , '$specialty_id_fk' ) ";
						$sqlResult = $this->dbquery($sqlQuery) or die(mysql_error());
						
						$uid = mysql_insert_id();
						$sessionValues['uid'] = $uid;
						
				}
				else if ($UserCreated > 0) 
				{
						$sqlQuery = "UPDATE `pwd` SET `name`  = '$name', `address`  = '$address' ,  `username`  = '$uname' , `usertype`  = '$usertype' ,
											`email`  = '$email' , `specialty_id_fk` = '$specialty_id_fk' WHERE uid = $uid ";
						$sqlQuery = $this->dbquery($sqlQuery) or die(mysql_error());
						
						if (!empty($checkpass) or $checkpass != "") { 
							$sqlQueryPs = "UPDATE `pwd` SET  `password`  = '$checkpass'  WHERE uid = $uid ";
							$sqlResPs = $this->dbquery($sqlQueryPs) or die(mysql_error());
						}
						
				}
						
						if ($sqlQuery == 1 )
							return 1;
						else
							return 0;
				

		}	

		
		
		
		function resetPassword($email)
		{
			$sqlQuery = "SELECT * from pwd WHERE email = '$email'";
			$sqlMemberRes = $this->dbquery($sqlQuery) or die(mysql_error());
		//	$emailPresent = $this->dbnumrow($sqlMemberRes);
			
		//	if ($emailPresent > 0) { 
			
				$sqlResult = $this->dbfetch($sqlMemberRes);
				$user = $sqlResult['username'];
				$userid = $sqlResult['uid'];
				$newpassword = $this->gen_md5_password(6);
				
				
				//	send email to registrant for new account	-------------------------------------
				
						
					$strMessageBodyContent = "";
					$strMessageBodyContent ="
								<p>Dear $user,</p>
		
								<p>A request has been sent to recover the password! Please review the login information below.</p>
								<br /><br />
								<p> User Name : $user <br />
								    Password  : $newpassword </p>
								
								<br />
								
								<br /><br /><p>Thank you,</p>
								<p>The " . WEBSITE_TITLE ." Network</p>
								<p>Do not reply to this email. If you need assistance please contact us.</p> ";
								
					
					$senderemail =  NOREPLY_EMAIL; //"noreply@nashikpropertydeal.com";
					$mailheaders = "From:". WEBSITE_TITLE ."<$senderemail> \nContent-type: text/html; charset=iso-8859-1\n";
					
					$toemail = $email;
				
					$subject = "Heritage Publishing Inc - Your New Password";
					$mailsent =	$this->mailsender($mailheaders, $toemail, $subject, $strMessageBodyContent);
					
					if ($mailsent == 1) { 
						$setnewpassword = $this->setNewPasswordForUser($email, $userid, $newpassword);
						return 1;	
					}
					else 
						return 0;
						
					
					//	-------------------------------------------------------------------------
			
		//	}
		//	else {
		//		return 0;
		//	}
			
		}

		function setNewPasswordForUser($email, $userid, $password) 
		{
			if (!empty($password)) { 	
					$salt = '5c#1bspx@l';
					$checkpass = sha1( $password. sha1($salt) );	
					
					$sqlQuery1 = "UPDATE `pwd` SET `password` = '$checkpass' WHERE `uid` = '$userid' AND  `email` = '$email'";
					$sqlQueryResult = $this->dbquery($sqlQuery1) or die(mysql_error());		
			}
		
		}
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
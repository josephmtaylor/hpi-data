<?php
/**
 * The Session class is meant to simplify the task of keeping
 * track of logged in users and also guests.
 * 
 * <code><?php
 * include('session.class.php');
 * $session = new SessionClass();
 * ?></code>
 * 
 * ==============================================================================
 * 
 * @version $Id: session.class.php,v 0.01 2012/02/12 12:12:32 $
 * @copyright Copyright (c) 2012 Sagar Kshatriya
 * @author Sagar Kshatriya <sagarkshatriya@gmail.com>
 * 
 * ==============================================================================

 */
 
//ini_set('display_errors','On');
include_once  "constants.php" ;
include_once "db.class.php";

include_once "admin.class.php";
$admin = new AdminClass;


class SessionClass extends Db { 

		private $username;     //Username given on sign-up
		private $userid;       //Random value generated on current login
		private $userlevel;    //The level to which the user pertains
		private $useremail;    //The email of the user
		private $time;         //Time user was last active (page loaded)
		public $logged_in;    //True if user is logged in, false otherwise
		public $userinfo = array();  //The array holding all user info
		public $url;          //The page url current being viewed
		public $referrer;     //Last recorded site page viewed


		/* Constructor
		 * 
		 */

		function __construct() {
		
			parent::__construct();
			
			$this->time = time();
		    $this->startSession();
			
		}
		
	   /**
		* startSession - Performs all the actions necessary to 
		* initialize this session object. Tries to determine if the
		* the user has logged in already, and sets the variables 
		* accordingly. Also takes advantage of this page load to
		* update the active visitors tables.
		* @param void
		* @return void
		*/

		function startSession(){
		//  global $database;  //The database connection
		  session_start();   //Tell PHP to start the session
	
		  /* Determine if user is logged in */
		  $this->logged_in = $this->checkLogin();
	
		  /**
		   * Set guest value to users not logged in, and update
		   * active guests table accordingly.
		   */
		  if(!$this->logged_in){
			 
			// header("Location: login.php");
			 //exit();
			 
			 //$this->username = $_SESSION['username'] = GUEST_NAME;
			 //$this->userlevel = GUEST_LEVEL;
			 //$database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
		  }
		  /* Update users last active timestamp */
		  else {
			 //$database->addActiveUser($this->username, $this->time);
		  }
		  
		  /* Remove inactive visitors from database */
		//  $database->removeInactiveUsers();
		//  $database->removeInactiveGuests();
		  
		  /* Set referrer page */
		  if(isset($_SESSION['url'])){
			 $this->referrer = $_SESSION['url'];
		  }else{
			 $this->referrer = "/";
		  }
	
		  /* Set current url */
		  $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
	   }
	   


	   /**
		* checkLogin - Checks if the user has already previously
		* logged in, and a session with the user has already been
		* established. Also checks to see if user has been remembered.
		* If so, the database is queried to make sure of the user's 
		* authenticity. Returns true if the user has logged in.
		*/
	   function checkLogin(){
		  
		  global $admin;  //The admin connection
		  
		  /* Check if user has been remembered */
		  if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
			 $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
			 $this->userid   = $_SESSION['userid']   = $_COOKIE['cookid'];
		  }
	
		  /* Username and userid have been set and not guest */
		  if(isset($_SESSION['username']) && isset($_SESSION['userid'])){
			 /* Confirm that username and userid are valid */
			 if($admin->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0){
				/* Variables are incorrect, user not logged in */
				unset($_SESSION['username']);
				unset($_SESSION['userid']);
				return false;
			 }
	
			 /* User is logged in, set class variables */
			 $this->userinfo  = $admin->getUserInfo($_SESSION['username']);
			 $this->username  = $this->userinfo['username'];
			 $this->userid    = $this->userinfo['uid'];
			 $this->userlevel = $this->userinfo['usertype'];
			 $this->useremail = $this->userinfo['email'];
			 return true;
		  }
		  /* User not logged in */
		  else{
			 return false;
		  }
	   }
	   

	   /**
		* login - The user has submitted his username and password
		* through the login form, this function checks the authenticity
		* of that information in the database and creates the session.
		* Effectively logging in the user if all goes well.
		*/

	   function login($subuser, $subpass, $subremember){
		  global $admin;  //The database and form object
	
	
		  /* Checks that username is in database and password is correct */
		  $subuser = stripslashes($subuser);
		  $password = sha1($subpass  . HASH_SHA1);
	
		  $result = $admin->confirmUserPass($subuser, $password);
	
		  /* Check error codes */
		  /* Return if form errors exist */
		  if($result == 1){
			 return false;
		  }
		  else if($result == 2){
			 return false;
		  }
		  
	
		  /* Username and password correct, register session variables */
		  $this->userinfo  = $admin->getUserInfo($subuser);
		  $this->username  = $_SESSION['username'] = $this->userinfo['username'];
		  $this->userid    = $_SESSION['userid']   = $this->userinfo['uid']; //$this->generateRandID();
		  $this->userlevel = $this->userinfo['userlevel'];
		  $this->useremail = $this->userinfo['email'];
		  
		  /* Insert userid into database and update active users table */
		 // $database->updateUserField($this->username, "userid", $this->userid);
		 // $database->addActiveUser($this->username, $this->time);
		 // $database->removeActiveGuest($_SERVER['REMOTE_ADDR']);
	
		  /**
		   * This is the cool part: the user has requested that we remember that
		   * he's logged in, so we set two cookies. One to hold his username,
		   * and one to hold his random value userid. It expires by the time
		   * specified in constants.php. Now, next time he comes to our site, we will
		   * log him in automatically, but only if he didn't log out before he left.
		   */
		  if($subremember){
			 setcookie("cookname", $this->username, time()+COOKIE_EXPIRE, COOKIE_PATH);
			 setcookie("cookid",   $this->userid,   time()+COOKIE_EXPIRE, COOKIE_PATH);
		  }
	
		  /* Login completed successfully */
		  return true;
	   }


	   /**
		* logout - Gets called when the user wants to be logged out of the
		* website. It deletes any cookies that were stored on the users
		* computer as a result of him wanting to be remembered, and also
		* unsets session variables and demotes his user level to guest.
		*/
	   function logout(){
		  global $admin;  //The database connection
		  /**
		   * Delete cookies - the time must be in the past,
		   * so just negate what you added when creating the
		   * cookie.
		   */
		  if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
			 setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
			 setcookie("cookid",   "", time()-COOKIE_EXPIRE, COOKIE_PATH);
		  }
	
		  /* Unset PHP session variables */
		  unset($_SESSION['username']);
		  unset($_SESSION['userid']);
	
		  /* Reflect fact that user has logged out */
		  $this->logged_in = false;
		  
		  /**
		   * Remove from active users table and add to
		   * active guests tables.
		   */
		 // $database->removeActiveUser($this->username);
		 // $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
		  
		  /* Set user level to guest */
		  $this->username  = "";
		  $this->userid = "";
		  $this->useremail = "";
		  $this->userlevel = "";
	   }


		/*
		 * This function sends User Level
		 * param null
		 * return string userlevel
		 */

		function getUserLevel() {
			return $this->userlevel;
		}
	   
		/*
		 * This function sends Username
		 * param null
		 * return string username
		 */

		function getUser() {
			return $this->username;
		}
		
		/*
		 * This function sends UserId
		 * param null
		 * return int userid
		 */

		function getUserId() {
			return $this->userid;
		}
		
		/*
		 * This function sends Useremail
		 * param null
		 * return string username
		 */

		function getUserEmail() {
			return $this->useremail;
		}
	   
		
		
		function __destruct() {
		
			parent::__destruct();
		}
		
		
}


?>
<?php

/**
 * Connect Access - The business object class
 * 
 * @param string $dbName
 * @param string $dbHost 
 * @param string $dbUser
 * @param string $dbPass
 * @param string $dbTable
 */
 
class Connect { 
	
	private $dbHost = 'localhost';

	private $linkConnect;


	private $dbUser = 'root';
	private $dbPass = 'Kly4wEbM';
	private $dbName = 'heritagepublishing';
	



	/* Constructor
	 * @param
	 * return void
	 */

	function __construct() {

			$linkConnect = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
			if (!$linkConnect) {
				die("Error! The host is not connected. Please contact your server admin!". mysql_error());
			}
			else {
					
					if (isset($_SESSION['db'])) { 
						 $dbName = $_SESSION['db']; 
						 $this->dbName = $dbName;
						 mysql_select_db($dbName);
						 
					}
					else {
						mysql_select_db($this->dbName);
					}
			}
	
	} // End of __construct()


	/* This is test funciton
	 * @return string
	 */

	public function write() {
		echo "We are in... " . $this->dbName;
	}

	/* This is test funciton
	 * @return string
	 */

	public function writeAgain() {
		echo "<br />We are in again... " . $this->dbName;
	}


	/* This is to close the connection
	 * @return string
	 */	
	function close_connection() {
		if ($this->linkConnect) {
			mysql_close($this->linkConnect) or die('Error closing connection: '. mysql_error());
			echo "connection closed..";
		}
	}

	/* Destructor
	 * @return void
	 */	
	function __destruct() {
	
	}


} // End of class connect



?>
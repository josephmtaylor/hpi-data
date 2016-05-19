<?php

include_once "classes/session.class.php";

$session = new SessionClass;

   /**
    * procLogout - Simply attempts to log the user out of the system
    * given that there is no logout form to process.
    */
    $retval = $session->logout();
	header("Location: login.php?fl=logout");

?>
<?php
include_once "classes/session.class.php";


$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

$userType = $session->getUserLevel();

if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} 




include_once "header.php"; 

include_once "sidebar.php";
?>

<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent">

    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <span>Customers</span>
    </div><!-- breadcrumbs -->
	
    <div class="left">
    	
        <h1 class="pageTitle">Manage Customers </h1>
        
        
		 
            
    </div><!--left-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
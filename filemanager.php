<?php
include_once "classes/session.class.php";
require_once "classes/customer.class.php";


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

$M = new CustomerClass;



include_once "header.php"; 

include_once "sidebar.php";
?>
<link rel="stylesheet" href="css/plugins/elfinder.css" type="text/css"/>
<script type="text/javascript" src="js/plugins/elfinder.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#fileManager').elfinder({
		url : 'php/connector.php',
	})
});
</script>



<div class="maincontent">

    <div class="breadcrumbs">
    	<a href="dashboard.html">Dashboard</a>
        <span>File Manager</span>
    </div><!-- breadcrumbs -->
	
    <div class="left">
    	
        <h1 class="pageTitle">File Manager</h1>
        
		<div class="widgetbox">
			<h3><span>File Manager</span></h3>
			<div class="content nopadding">
				<div id="fileManager"></div>
            </div>
        </div><!-- widgetbox -->  
             
    </div><!--left-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>

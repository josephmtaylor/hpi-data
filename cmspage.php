<?php
include_once "classes/session.class.php";
include_once "classes/cmspage.class.php";


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


$C = new CmsPageClass;

$pageId = $_GET['id'];	

if (!empty($pageId)) {

	// ----  check if CmsPage exists --------
	
				if ($pageId == 1) 
					$pageTitle = "Home Page - About Us";
				if ($pageId == 2) 
					$pageTitle = "Prices";
				if ($pageId == 3) 
					$pageTitle = "Order";
				if ($pageId == 4) 
					$pageTitle = "Reviews";
				if ($pageId == 5) 
					$pageTitle = "Venues";
				if ($pageId == 6) 
					$pageTitle = "Collection - Stage Shirts";
				if ($pageId == 7) 
					$pageTitle = "Collection - Stage Jackets";
				if ($pageId == 8) 
					$pageTitle = "Collection - Stage Suits";
				if ($pageId == 9) 
					$pageTitle = "Collection - Stage Tops";
				if ($pageId == 10) 
					$pageTitle = "Collection - Stage Waist Coats";
				if ($pageId == 11) 
					$pageTitle = "Collection - Stage Shoes";
				if ($pageId == 12) 
					$pageTitle = "Collection - Stage Clearance";
				
				
				

	if (isset($pageId)) {
		$isCmsPageExists = $C->isIdPresent($pageId);
	}
	else {
		$isCmsPageExists = 0;
	}

	
	if ($isCmsPageExists == 1) { 
	
		
		$data = $C->getRecordByField( 'id', $pageId);
	
		$id 			  =  	$data["id"];
		$page_title		  =		$C->prepare_output($data['page_title']);
		$meta_keywords	  = 	$C->prepare_output($data['meta_keywords']);
		$meta_description =		$C->prepare_output($data['meta_description']);
		$page_description =		$C->prepare_output($data['page_description']);
	
			
	}
	/*else  {
		header("Location: index.php?msg=err");
		exit();
	}*/
	


} // end if 
else {
	 header("Location: index.php");
 	 exit();
}	



include_once "header.php"; 

include_once "sidebar.php";
?>

<script type="text/javascript" src="js/custom/table.js"></script>
<script type="text/javascript" language="javascript1.2">

jQuery(document).ready(function () {

	jQuery( "#tabs" ).tabs();

});

</script>

<div class="maincontent">

    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
		<a href="#">Cms Page</a>
        <span><?php echo $pageTitle; ?></span>
    </div>
    <!-- breadcrumbs -->
	
    <div class="left">
    	
        <h1 class="pageTitle"><?php echo $pageTitle?> Information </h1>
		<a href="newcmspage.php?id=<?php echo $pageId?>" class="addNewButton">Update <?php echo $pageTitle;?></a>
	   <br /><br />
        
        <div class="invoice full last">
        	<div class="invoice_inner">
            
            	<h2 class="title"> <?php echo $pageTitle?> Information</h2>
                
                <br clear="all" /><br />
                
                <div class="one_half">
                	&nbsp;
                   
                </div>
                <!--one_half -->
                <br clear="all" /><br /><br />
				
                <div class="one_fourth">
                	<strong>
                    	<?php if (!empty($page_title)) { ?>Page Title: <br /><?php } ?> 
						<?php if (!empty($meta_keywords)) { ?>Meta Keywords: <br /><?php } ?> 
						<?php if (!empty($meta_description)) { ?>Meta Description: <br /><?php } ?> 
						<?php if (!empty($page_description)) { ?>Page Description: <br /><?php } ?> 
                  </strong>
                </div><!-- one_third -->
                
                <div class="three_fourth last">
                	<?php if (!empty($page_title)) {  echo $page_title. "<br />";  } ?> 
					<?php if (!empty($meta_keywords)) { echo $meta_keywords . "<br />"; } ?> 
                    <?php if (!empty($meta_description)) { echo $meta_description . "<br />"; } ?> 
					<?php if (!empty($page_description)) { echo $page_description ; } ?> 
					
                </div><!-- three_fourth last -->
                
                <br clear="all" /><br />
                <br />
                <!-- one_third -->
                <!-- two_third last -->
          </div>
        	<!-- invoice_inner -->
        
        </div><!-- invoice three_fourth last -->
        
             
    </div><!--left-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
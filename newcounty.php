<?php
include_once "classes/session.class.php";
require_once "classes/county.class.php";

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

$M = new CountyClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$county_id = $_POST["county_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$county_id";
		
		
		$county  			  =		$_POST['county'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isCountyRegistered = $M->getTotal('county', $county, 'exact');
		//exit();	
			if ($isCountyRegistered >= 1) {
				header("Location: newcounty.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "county_id" => $county_id, 
									"county" => $county, 
								);
		
		$editCounty = $M->editCounty($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editCounty == 1)	 { 		
			header("Location: county.php?msg=update");
			exit();
		}
		else {
			header("Location: newcounty.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$county_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($county_id))
				{
					header("Location: county.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($county_id);
				$data  = $M->getRecordByField('county_id', $county_id);
				
				
				if (!empty($data))
				{	
					$county_id 			=  	$data["county_id"];
					$county		  	=	$data['county'];
				}
				
		
}
else {
					$fl 			  =  	"add";
					$county_id 			  =  	"";
					$county 		  	  =		"";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! County already exist.";						
					

}
else
{
	$txtMessage = "";
}


include_once "header.php"; 

include_once "sidebar.php";
?>

<script type="text/javascript" src="js/custom/table.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#form").validate({
		rules: {
			county: "required",
		},
		messages: {
			county: "Please enter County",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="county.php">County</a>
		<span>Update County </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Cities</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Countys</legend>
                    
                    <p>
                    	<label for="type">County</label>
                        <input name="county" type="text" class="sf"  id="county" value="<?php echo $county?>" />
						 <small>e.g. Calhoun County etc</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="county_id" value="<?php echo $county_id; ?>" />
                            <input type="hidden" name="fl" value="<?php echo $fl; ?>" />
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
<?php
include_once "classes/session.class.php";
require_once "classes/zipcode.class.php";

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

$M = new ZipcodeClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$zip_id = $_POST["zip_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$zip_id";
		
		
		$zipcode  			  =		$_POST['zipcode'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isZipcodeRegistered = $M->getTotal('zipcode', $zipcode, 'exact');
		//exit();	
			if ($isZipcodeRegistered >= 1) {
				header("Location: newzipcode.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "zip_id" => $zip_id, 
									"zipcode" => $zipcode, 
								);
		
		$editZipcode = $M->editZipcode($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editZipcode == 1)	 { 		
			header("Location: zipcode.php?msg=update");
			exit();
		}
		else {
			header("Location: newzipcode.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$zip_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($zip_id))
				{
					header("Location: zipcode.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($zip_id);
				$data  = $M->getRecordByField('zip_id', $zip_id);
				
				
				if (!empty($data))
				{	
					$zip_id 			=  	$data["zip_id"];
					$zipcode		  	=	$data['zipcode'];
				}
				
		
}
else {
					$fl 			  =  	"add";
					$zip_id 			  =  	"";
					$zipcode 		  	  =		"";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Zipcode already exist.";						
					

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
			zipcode: "required",
		},
		messages: {
			zipcode: "Please enter Zipcode",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="zipcode.php">Zipcode</a>
		<span>Update Zipcode </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Zipcodes</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Zipcodes</legend>
                    
                    <p>
                    	<label for="type">Zipcode</label>
                        <input name="zipcode" type="text" class="sf"  id="zipcode" value="<?php echo $zipcode?>" />
						 <small>e.g. 1109022 etc</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="zip_id" value="<?php echo $zip_id; ?>" />
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
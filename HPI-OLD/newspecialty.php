<?php
include_once "classes/session.class.php";
require_once "classes/specialty.class.php";

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

$M = new SpecialtyClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$specialty_id = $_POST["specialty_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$specialty_id";
		
		
		$specialty_name 	= $_POST['specialty_name'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isSpecialtyRegistered = $M->getTotal('specialty_name', $specialty_name, 'exact');
		//exit();	
			if ($isSpecialtyRegistered >= 1) {
				header("Location: newspecialty.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "specialty_id" => $specialty_id, 
									"specialty_name" => $specialty_name, 
								);
		
		$editSpecialty = $M->editSpecialty($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editSpecialty == 1)	 { 		
			header("Location: specialty.php?msg=update");
			exit();
		}
		else {
			header("Location: newspecialty.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$specialty_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($specialty_id))
				{
					header("Location: specialty.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($specialty_id);
				$data  = $M->getRecordByField('specialty_id', $specialty_id);
				
				
				if (!empty($data))
				{	
					$specialty_id 			=  	$data["specialty_id"];
					$specialty_name		=	$data['specialty_name'];
				}
				
		
}
else {
					$fl 			  =  	"add";
					$specialty_id 			  =  	"";
					$specialty_name 		  	  =		"";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Specialty already exist.";						
					

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
			specialty_name: "required",
		},
		messages: {
			specialty_name: "Please enter Specialty",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="specialty.php">Specialty</a>
		<span>Update Specialty </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Specialty</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Specialty</legend>
                    
                    <p>
                    	<label for="type">Specialty</label>
                        <input name="specialty_name" type="text" class="sf"  id="specialty_name" value="<?php echo $specialty_name?>" />
						 <small>e.g. Pathologist, Opthologist etc</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="specialty_id" value="<?php echo $specialty_id; ?>" />
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
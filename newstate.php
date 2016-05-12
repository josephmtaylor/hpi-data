<?php
include_once "classes/session.class.php";
require_once "classes/state.class.php";

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

$M = new StateClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$state_id = $_POST["state_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$state_id";
		
		
		$state = $_POST['state'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isStateRegistered = $M->getTotal('state', $state, 'exact');
		//exit();	
			if ($isStateRegistered >= 1) {
				header("Location: newstate.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "state_id" => $state_id, 
									"state" => $state, 
								);
		
		$editState = $M->editState($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editState == 1)	 { 		
			header("Location: state.php?msg=update");
			exit();
		}
		else {
			header("Location: newstate.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$state_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($state_id))
				{
					header("Location: state.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($state_id);
				$data  = $M->getRecordByField('state_id', $state_id);
				
				
				if (!empty($data))
				{	
					$state_id 	=  	$data["state_id"];
					$state 		=	$data['state'];
				}
				
		
}
else {
					$fl 		=  	"add";
					$state_id 	=  	"";
					$state 		=		"";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! State already exist.";						
					

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
			state: "required",
		},
		messages: {
			state: "Please enter State",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="state.php">State</a>
		<span>Update State </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage States</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>States</legend>
                    
                    <p>
                    	<label for="type">State</label>
                        <input name="state" type="text" class="sf"  id="state" value="<?php echo $state?>" />
						 <small>e.g. FL</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="state_id" value="<?php echo $state_id; ?>" />
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
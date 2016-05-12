<?php
include_once "classes/session.class.php";
require_once "classes/settings.class.php";

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

$M = new SettingsClass;


if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$id = $_POST["id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$id";
		
		
		$loan_officer  		 	  =		$_POST['loan_officer'];
		$loan_officer_email  	  =		$_POST['loan_officer_email'];
		$insurance_officer  	  =		$_POST['insurance_officer'];
		$insurance_officer_email  =		$_POST['insurance_officer_email'];
		$contact_officer  		  =		$_POST['contact_officer'];
		$contact_officer_email    =		$_POST['contact_officer_email'];
		
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isSettingsExist = $M->getTotal('id', '1');
			if ($isSettingsExist == 1)
				$id = 1;
			else 
				$id = 0;
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "id" => $id, 
									"loan_officer" => $loan_officer, 
									"loan_officer_email" => $loan_officer_email, 
									"insurance_officer" => $insurance_officer, 
									"insurance_officer_email" => $insurance_officer_email, 
									"contact_officer" => $contact_officer, 
									"contact_officer_email" => $contact_officer_email,
								);
		
		$editUser = $M->editSettings($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editUser == 1)	 { 		
			header("Location: settings.php?msg=update");
			exit();
		}
		else {
			header("Location: editsettings.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
				$data  = $M->getRecordByField('id', 1);
				
				
				if (!empty($data))
				{	
					$id 			  		  =  	$data["id"];
					$loan_officer  		 	  =		$data['loan_officer'];
					$loan_officer_email  	  =		$data['loan_officer_email'];
					$insurance_officer  	  =		$data['insurance_officer'];
					$insurance_officer_email  =		$data['insurance_officer_email'];
					$contact_officer  		  =		$data['contact_officer'];
					$contact_officer_email    =		$data['contact_officer_email'];
	
				}
				else {
									
									$id 			  =  	"";
									$loan_officer  		 	  =		"";
									$loan_officer_email  	  =		"";
									$insurance_officer  	  =		"";
									$insurance_officer_email  =		"";
									$contact_officer  		  =		"";
									$contact_officer_email    =		"";
									
				}

				$fl 			  =  	"edit";
// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Login Name Already Present.";						
	else if ($msg == "erre")
		$txtMessage = "Error! Email Already Present.";						
	else if ($msg == "errar")
		$txtMessage = "Error! Please Select Proper Area.";						

}
else
{
	$msg = '';
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
			loan_officer: "required",
			loan_officer_email: {
				required: true,
				email: true,
			},
			insurance_officer: "required",
			insurance_officer_email: {
				required: true,
				email: true,
			},
			contact_officer: "required",
			contact_officer_email: {
				required: true,
				email: true,
			},
		},
		messages: {
			loan_officer: "Please enter officer name",
			loan_officer_email: "Please enter officer email",
			insurance_officer: "Please enter officer name",
			insurance_officer_email: "Please enter officer email",
			contact_officer: "Please enter officer name",
			contact_officer_email: "Please enter officer email",
			
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="settings.php">Settings</a>
		<span>Update User </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Settings </h1>
     	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Settings </legend>
                    
                    <p>
                    	<label for="code"> Loan Officer Name</label>
                        <input name="loan_officer" type="text" class="sf"  id="loan_officer" value="<?php echo $loan_officer?>" />
				    </p>
					
					<p>
                    	<label for="code"> Loan Officer Email</label>
                        <input name="loan_officer_email" type="text" class="sf"  id="loan_officer_email" value="<?php echo $loan_officer_email?>" />
				    </p>
                   
				    <p>
                    	<label for="code"> Insurance Officer Name</label>
                        <input name="insurance_officer" type="text" class="sf"  id="insurance_officer" value="<?php echo $insurance_officer?>" />
				    </p>
					
					<p>
                    	<label for="code"> Insurance Officer Email</label>
                        <input name="insurance_officer_email" type="text" class="sf"  id="insurance_officer_email" value="<?php echo $insurance_officer_email?>" />
				    </p>
                   
				  
				    <p>
                    	<label for="code"> Contact Officer Name</label>
                        <input name="contact_officer" type="text" class="sf"  id="contact_officer" value="<?php echo $contact_officer?>" />
				    </p>
					
					<p>
                    	<label for="code"> Contact Officer Email</label>
                        <input name="contact_officer_email" type="text" class="sf"  id="contact_officer_email" value="<?php echo $contact_officer_email?>" />
				    </p>
                   
				  
				   
				    <p>
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
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
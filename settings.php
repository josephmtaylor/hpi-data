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
				
		
	


// Check if the error is present

if(isset($_GET['msg']))
{
	$error = 0;
	$msg = $_GET['msg']; 
	
	if ($msg == "update")
		$txtMessage = "Record Updated.";
	if ($msg == "dl")
		$txtMessage = "Record Deleted.";	
	if ($msg == "dlsl")
		$txtMessage = "Multiple Records Deleted.";		
	if ($msg == "err") {
		$error = 1;
		$txtMessage = "Error! Please try again.";
	}
	if ($msg == "errd") {
		$error = 1;
		$txtMessage = "Error! Record can not deleted.";
	}
	if ($msg == "errnf") {
		$error = 1;
		$txtMessage = "Record not found.";	
	}
}
else
{
	$txtMessage = "";
}




include_once "header.php"; 

include_once "sidebar.php";
?>
<script language="javascript1.2">

function deleteItem(item_id,item_name)
{
    if(confirm('Are you sure to delete record : ' + item_name))
	{
		document.location.href="settings.php?m=del&delid="+item_id;
	}
}

</script>
<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent">
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span>Settings</span> </div>
  <!-- breadcrumbs -->
  
  <div class="left">
    
    	<h1 class="pageTitle">Manage Settings </h1>
        <a href="editsettings.php" class="addNewButton">Edit Settings </a>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Settings </legend>
                    
                    <p>
                    	<label for="code"> Loan Officer Name</label>
                        <strong><?php echo $loan_officer?></strong>
				    </p>
					
					<p>
                    	<label for="code"> Loan Officer Email</label>
                        <strong><?php echo $loan_officer_email?></strong> 
					</p>
                   
				    <p>
                    	<label for="code"> Insurance Officer Name</label>
                        <strong><?php echo $insurance_officer?></strong> 
					</p>
					
					<p>
                    	<label for="code"> Insurance Officer Email</label>
                        <strong><?php echo $insurance_officer_email?></strong> 
					</p>
                   
				  
				    <p>
                    	<label for="code"> Contact Officer Name</label>
                        <strong><?php echo $contact_officer?></strong> 
					</p>
					
					<p>
                    	<label for="code"> Contact Officer Email</label>
                        <strong><?php echo $contact_officer_email?></strong>
				    </p>
                   
				  
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
  </div>
  <!--left-->
  <br clear="all" />
</div>
<!--maincontent-->

<?php include_once "footer.php"; ?>
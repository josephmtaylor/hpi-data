<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/specialty.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

$userType = $session->getUserLevel();
$loggedUserId = $session->getUserId();


if (empty($loggedUserId)) { 
	header("Location: login.php");
	exit();
} 

$M = new AdminClass;
$SP = new SpecialtyClass;

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$id = $_POST["id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$id";
		
		
		$name  			  =		$_POST['name'];
		$address  	  	  =		$_POST['address'];
		$uname			  =		$_POST['uname'];
		$psword  	  	  =		$_POST['psword'];
		$usertype  		  =		$_POST['usertype'];
		$email  		  =		$_POST['email'];
		$specialty_id_fk  =		$_POST['specialty_id_fk'];
		
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isUserRegistered = $M->getTotal('username', $uname);
			$isEmailRegistered = $M->getTotal('email', $email);			
		//exit();	
			if ($isUserRegistered >= 1) {
				header("Location: updateprofile.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			if ($isEmailRegistered >= 1) {
				header("Location: updateprofile.php?fl=$fl&msg=erre" . $suffix);
				exit();
			}
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "uid" => $id, 
									"name" => $name, 
									"address" => $address, 
									"uname" => $uname, 
									"psword" => $psword, 
									"usertype" => $usertype, 
									"email" => $email,
									"specialty_id_fk" => $specialty_id_fk
								);
		
		$editUser = $M->editUser($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editUser == 1)	 { 		
			header("Location: updateprofile.php?msg=update");
			exit();
		}
		else {
			header("Location: updateprofile.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (!empty($loggedUserId))
{

		
				$fl =	'edit';
				$id = 	$loggedUserId;
				$data  = $M->getRecordByField('uid', $loggedUserId);
				
				
				if (!empty($data))
				{	
					$id 			  =  	$data["uid"];
					$name  			  =		$data['name'];
					$address  	  	  =		$data['address'];
					$uname  		  =		$data['username'];
					$psword  	  	  =		$data['password'];
					$usertype  		  =		$data['usertype'];
					$email  		  =		$data['email'];
					$specialty_id_fk  =		$data['specialty_id_fk'];
				}
				
		
}

	$totalSpecialty = $SP->getTotal();
	$spRec = $SP->getList();
	
// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "update") {
		$error = 0;
		$txtMessage = "Success! Profile Updated.";
	}
	else if ($msg == "err") { 
		$error = 1;
		$txtMessage = "Error! Please try again.";
	}
	else if ($msg == "errd") {
		$error = 1;
		$txtMessage = "Error! Login Name Already Present.";						
	}
	else if ($msg == "erre") {
		$error = 1;
		$txtMessage = "Error! Email Already Present.";						
	}
	else if ($msg == "errar") {
		$error = 1;
		$txtMessage = "Error! Please Select Proper Area.";						
	}
	else if ($msg == "errsp") {
		$error = 1;
		$txtMessage = "Error! Please Select Specialty Before Performing Search.";						
	}
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
			name: "required",
			address: "required",
			email: {
				required: true,
				email: true,
			},
			uname: "required",
			//psword: "required",
		},
		messages: {
			name: "Please enter user's name",
			address: "Please enter user's address",
			email: "Please enter a valid email address",
			uname: "Please enter login username",
			//psword: "Please enter login password",
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="users.php">Users</a>
		<span>Update Profile </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Profile </h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        <?php echo  $RecordsPaging; ?>
	  	<?php  if (!empty($txtMessage)) {  
		  		if ( $error == 0 )
					$notify = "msgsuccess";
				else if ( $error == 1 )
					$notify = "msgerror";
		  ?>
			<div class="last">
					<div class="notification <?php echo $notify; ?>">
						<a class="close"></a>
						<p><?php echo $txtMessage;?></p>
					</div><!-- notification msginfo -->
				</div>	
			<?php } ?>
        
        	<div class="form_default">
                <fieldset>
                    <legend>My Profile</legend>
                    
                    <p>
                    	<label for="code"> Name</label>
                        <input name="name" type="text" class="sf"  id="name" value="<?php echo $name?>" />
				    </p>
                   
				    <p>
                    	<label for="code">Address</label>
                        <input name="address" type="text" class="sf"  id="address" value="<?php echo $address?>" />
					</p>
					
					<p>
                    	<label for="code">Email</label>
                        <input name="email" type="text" class="sf"  id="email" value="<?php echo $email?>" />
						<?php if ($msg == "erre" && !empty($txtMessage)) {  ?>
						<label for="code" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
					
				   
				    <p>
                    	<label for="code">Login Name</label>
                        <input name="uname" type="text" class="sf"  id="uname" value="<?php echo $uname?>" />
						<?php if ($msg == "errd" && !empty($txtMessage)) {  ?>
						<label for="code" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
					
					
					<p>
                    	<label for="code">Login Password</label>
                        <input name="psword" type="text" class="sf"  id="psword" value="" />
					</p>
                    
                    <p>
                    	<label for="type">Specialty </label>
                    	 <select id="specialty_id_fk" name="specialty_id_fk" style="width:250px;">
						<?php 
								if ($totalSpecialty > 0) { ?>
								<option value="">- Select Specialty -</option>
							<?php 	
								 for ($i=0; $i < $totalSpecialty; $i++) { 
									$spid = $spRec[$i]['specialty_id'];
									$specialty = $spRec[$i]['specialty_name'];
							?>	
								<option value="<?php echo $spid; ?>" <?php  if ($specialty_id_fk == $spid) { ?> selected="selected" <?php   } ?> ><?php echo $specialty; ?></option>
							<?php  	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php  } ?>
					  </select>
					</p>
					
					<p>
						<label for="usertype">User Type</label>
						<span><?php echo $usertype; ?></span>
					</p>
				   
				    <p>
							<input type="hidden" name="id" value="<?php  echo $id; ?>" />
                            <input type="hidden" name="usertype" value="<?php  echo $usertype; ?>" />
                            <input type="hidden" name="fl" value="<?php  echo $fl; ?>" />
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";

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

$M = new AdminClass;


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
		
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isUserRegistered = $M->getTotal('username', $uname);
			$isEmailRegistered = $M->getTotal('email', $email);			
		//exit();	
			if ($isUserRegistered >= 1) {
				header("Location: newuser.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			if ($isEmailRegistered >= 1) {
				header("Location: newuser.php?fl=$fl&msg=erre" . $suffix);
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
								);
		
		$editUser = $M->editUser($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editUser == 1)	 { 		
			header("Location: users.php?msg=update");
			exit();
		}
		else {
			header("Location: newuser.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($id))
				{
					header("Location: users.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($id);
				$data  = $M->getRecordByField('uid', $id);
				
				
				if (!empty($data))
				{	
					$id 			  =  	$data["uid"];
					$name  			  =		$data['name'];
					$address  	  	  =		$data['address'];
					$uname  		  =		$data['username'];
					$psword  	  	  =		$data['password'];
					$usertype  		  =		$data['usertype'];
					$email  		  =		$data['email'];
	
				}
				
		
}
else {
					$fl 			  =  	"add";
					$id 			  =  	"";
					$name  			  =		"";
					$address  	  	  =		"";
					$uname  		  =		"";
					$psword  	  	  =		"";
					$usertype  		  =		"Standard";
					$email  		  =		"";
					
}


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
			name: "required",
			address: "required",
			email: {
				required: true,
				email: true,
			},
			uname: "required",
			<?php if ($fl == "add") { ?>
			psword: "required",
			<?php } ?>
		},
		messages: {
			name: "Please enter user's name",
			address: "Please enter user's address",
			email: "Please enter a valid email address",
			uname: "Please enter login username",
			<?php if ($fl == "add") { ?>
			psword: "Please enter login password",
			<?php } ?>
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="users.php">Users</a>
		<span>Update User </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Users </h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Admin User Information</legend>
                    
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
						<?php  if ($msg == "erre" && !empty($txtMessage)) {  ?>
						<label for="code" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
					
				   
				    <p>
                    	<label for="code">Login Name</label>
                        <input name="uname" type="text" class="sf"  id="uname" value="<?php echo $uname?>" />
						<?php  if ($msg == "errd" && !empty($txtMessage)) {  ?>
						<label for="code" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
					
					
					<p>
                    	<label for="code">Login Password</label>
                        <input name="psword" type="text" class="sf"  id="psword" value="" />
					</p>
					
					<p>
						<label for="usertype">User Type</label>
						<select name="usertype" id="territory"  style="width:250px;">
							<option value="Admin" <?php if ($usertype == 'Admin') { ?> selected="selected" <?php  } ?> >Admin</option>
							<option value="Standard" <?php if ($usertype == 'Standard') { ?> selected="selected" <?php  } ?> >Standard</option>
						</select>
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
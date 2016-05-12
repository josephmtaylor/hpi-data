<?php
include_once "classes/session.class.php";
require_once "classes/publication.class.php";

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

$M = new PublicationClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$pub_id = $_POST["pub_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$pub_id";
		
		
		$publication 	= $_POST['publication'];
		$description	= $_POST['description'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isPublicationRegistered = $M->getTotal('publication', $publication, 'exact');
		//exit();	
			if ($isPublicationRegistered >= 1) {
				header("Location: newpublication.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "pub_id" => $pub_id, 
									"publication" => $publication, 
									"description" => $description
								);
		
		$editPublication = $M->editPublication($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editPublication == 1)	 { 		
			header("Location: publication.php?msg=update");
			exit();
		}
		else {
			header("Location: newpublication.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$pub_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($pub_id))
				{
					header("Location: publication.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($pub_id);
				$data  = $M->getRecordByField('pub_id', $pub_id);
				
				
				if (!empty($data))
				{	
					$pub_id 		=  	$data["pub_id"];
					$publication	=	$data['publication'];
					$description	=	$data['description'];
				}
				
		
}
else {
					$fl 			= "add";
					$pub_id 		= "";
					$publication 	= "";
					$description	= "";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Publication already exist.";						
					

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
			publication: "required",
		},
		messages: {
			publication: "Please enter Publication Code",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="publication.php">Publication</a>
		<span>Update Publication </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Publication</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Publication</legend>
                    
                    <p>
                    	<label for="type">Publication Code </label>
                        <input name="publication" type="text" class="sf"  id="publication" value="<?php echo $publication?>" />
						 
						<?  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<? } ?>
                    </p>
                    
                    <p>
                    	<label for="notes">Description</label>
                        <textarea name="description" class="mf" cols="" rows=""><?php echo $description; ?></textarea>
                    </p>
                   
				    <p>
						<input type="hidden" name="pub_id" value="<? echo $pub_id; ?>" />
                            <input type="hidden" name="fl" value="<? echo $fl; ?>" />
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
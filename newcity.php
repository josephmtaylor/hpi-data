<?php
include_once "classes/session.class.php";
require_once "classes/city.class.php";

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

$M = new CityClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$city_id = $_POST["city_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$city_id";
		
		
		$city = $_POST['city'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isCityRegistered = $M->getTotal('city', $city, 'exact');
		//exit();	
			if ($isCityRegistered >= 1) {
				header("Location: newcity.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "city_id" => $city_id, 
									"city" => $city, 
								);
		
		$editCity = $M->editCity($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editCity == 1)	 { 		
			header("Location: city.php?msg=update");
			exit();
		}
		else {
			header("Location: newcity.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$city_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($city_id))
				{
					header("Location: city.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($city_id);
				$data  = $M->getRecordByField('city_id', $city_id);
				
				
				if (!empty($data))
				{	
					$city_id 	=  	$data["city_id"];
					$city		=	$data['city'];
				}
				
		
}
else {
					$fl  		= "add";
					$city_id 	= "";
					$city 		= "";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! City already exist.";						
					

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
			city: "required",
		},
		messages: {
			city: "Please enter City",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="city.php">City</a>
		<span>Update City </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Cities</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Citys</legend>
                    
                    <p>
                    	<label for="type">City</label>
                        <input name="city" type="text" class="sf"  id="city" value="<?php echo $city?>" />
						 <small>e.g. New York etc</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="city_id" value="<?php echo $city_id; ?>" />
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
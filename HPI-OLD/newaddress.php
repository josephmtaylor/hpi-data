<?php
include_once "classes/session.class.php";
require_once "classes/address.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();

if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} 

$ADR = new AddressClass;

$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
	

$totalCities = $C->getTotal();
$ctRec = $C->getList();	

$totalStates = $S->getTotal();
$stRec = $S->getList();	

$totalZips = $Z->getTotal();
$zpRec = $Z->getList();	
	
	
if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$id = $_POST["id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$id";
		
		
		$address_1 	= $_POST['address_1'];
		$address_2	= $_POST['address_2'];
		$city_id 	= $_POST['city_id'];
		$state_id	= $_POST['state_id'];
		$zip_id 	= $_POST['zip_id'];
		$phone	 	= $_POST['phone'];
		$fax	 	= $_POST['fax'];
		$notes	 	= $_POST['notes'];
		$status 	= $_POST['status'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$fieldArray = array ('address_1', 'city_id', 'state_id' , 'zip_id');
			$keyArray = array($ADR->prepare_input($address_1), $ADR->prepare_input($city_id), $ADR->prepare_input($state_id), $ADR->prepare_input($zip_id));
			
			$isAddressRegistered = $ADR->getRecordCountBySearchArray($fieldArray, $keyArray, $searchStatus='exact');
		
			if ($isAddressRegistered >= 1) {
				header("Location: newaddress.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "id" => $id, 
									"address_1" => $address_1, 
									"address_2" => $address_2,
									'city_id' => $city_id,
									'state_id' => $state_id,
									'zip_id' => $zip_id,
									'phone' => $phone,
									'fax' => $fax,
									'notes' => $notes,
									'status' => $status,
									'updated_by' => $loggedUser
								);
		
		$editAddress = $ADR->editAddress($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editAddress == 1)	 { 		
			header("Location: address.php?msg=update");
			exit();
		}
		else {
			header("Location: newaddress.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($id))
				{
					header("Location: address.php?msg=err");
					exit();
				}
		
				//$data = $ADR->getUserById($id);
				$data  = $ADR->getRecordByField('id', $id);
				
				
				if (!empty($data))
				{	
					
					$id 		=  	$ADR->prepare_output( $data["id"] );
					$address_1	=	$ADR->prepare_output( $data['address_1'] );
					$address_2	=	$ADR->prepare_output( $data['address_2'] );
					$city_id	=	$ADR->prepare_output( $data['city_id'] );
					$state_id	=	$ADR->prepare_output( $data['state_id'] );
					$zip_id		=	$ADR->prepare_output( $data['zip_id'] );
					$phone		=	$ADR->prepare_output( $data['phone'] );
					$notes		=	$ADR->prepare_output( $data['notes'] );
					$fax		=	$ADR->prepare_output( $data['fax'] );
					$status		= 	$ADR->prepare_output( $data['status'] );
				}
				
		
}
else {
					$fl 		= "add";
					$id 		= "";
					$address_1 	= "";
					$address_2	= "";
					$phone		="";
					$fax 		= "";
					$notes 		= "";
					$status		= "";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Address already exist.";						
					

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
			address_1: "required",
			city_id: "required",
			state_id: "required",
			zip_id: "required",
			status: "required"
		},
		messages: {
			address_1: "Please enter Address 1",
			city_id: "Please select City",
			state_id: "Please select State",
			zip_id: "Please select Zip",
			status: "Please select Status"
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="address.php">Address</a>
		<span>Create Address </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Addresses</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Address</legend>
                    
                    <p>
                    	<label for="type">Address 1 </label>
                        <input name="address_1" type="text" class="lf"  id="address_1" value="<?php echo $address_1?>" />
						 
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                    
                    <p>
                    	<label for="notes">Address 2</label>
                        <input name="address_2" type="text" class="lf"  id="address_2" value="<?php echo $address_2?>" />
                    </p>
                    
                    <p>
                    	<label for="name">City</label>
                        <select id="city_id" name="city_id" style="width:250px;">
							<?php
								if ($totalCities > 0) { ?>
								<option value="">- Select City -</option>
							<?php	
								 for ($i=0; $i < $totalCities; $i++) { 
									$ctid = $ctRec[$i]['city_id'];
									$city = $ctRec[$i]['city'];
							?>	
								<option value="<?php echo $ctid; ?>" <?php if ($city_id == $ctid) { ?> selected="selected" <?php  } ?> ><?php echo $city; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                    </p>
                    
                    <p>
                    	<label for="name">State</label>
                        <select id="state_id" name="state_id" style="width:250px;">
							<?php
								if ($totalZips > 0) { ?>
								<option value="">- Select State -</option>
							<?php	
								 for ($i=0; $i < $totalStates; $i++) { 
									$stid = $stRec[$i]['state_id'];
									$state = $stRec[$i]['state'];
							?>	
								<option value="<?php echo $stid; ?>" <?php if ($state_id == $stid) { ?> selected="selected" <?php  } ?> ><?php echo $state; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                    </p>
                    
                    <p>
                    	<label for="name">Zipcode</label>
                        <select id="zip_id" name="zip_id" style="width:250px;">
							<?php
								if ($totalZips > 0) { ?>
								<option value="">- Select Zipcode -</option>
							<?php	
								 for ($i=0; $i < $totalZips; $i++) { 
									$zpid = $zpRec[$i]['zip_id'];
									$zipcode = $zpRec[$i]['zipcode'];
							?>	
								<option value="<?php echo $zpid; ?>" <?php if ($zip_id == $zpid) { ?> selected="selected" <?php  } ?> ><?php echo $zipcode; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                    </p>
                    
                    <p>
                    	<label for="phone">Phone</label>
                        <input name="phone" type="text" class="tf"  id="phone" value="<?php echo $phone?>" />
                    </p>
                    
                    <p>
                    	<label for="fax">Fax</label>
                        <input name="fax" type="text" class="tf"  id="fax" value="<?php echo $fax?>" />
                    </p>
                    
                    <p>
                    	<label for="notes">Notes</label>
                        <textarea name="notes" class="mf" cols="" rows=""><?php echo $notes; ?></textarea>
                    </p>
                    
                     <p>
                    	<label for="status">Status</label>
                        <select name="status" id="status" style="width:100px;">
							<option value="V" <?php if ($status == "V") {?> selected="selected"<?php } ?>>Verified</option>
							<option value="UV" <?php if ($status == "UV") {?> selected="selected"<?php } ?> >Unverified</option>
                            <option value="N" <?php if ($status == "N") {?> selected="selected"<?php } ?>>New</option>
                            <option value="P" <?php if ($status == "P") {?> selected="selected"<?php } ?>>Pending</option>
                            <option value="D" <?php if ($status == "D") {?> selected="selected"<?php } ?>>Done</option>
                            <option value="E" <?php if ($status == "E") {?> selected="selected"<?php } ?>>Error</option>
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
<?php
include_once "classes/session.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/address.class.php";
require_once "classes/doctoraddress.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();

/*if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} */

$M = new DoctorClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;

$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$DA = new DoctorAddressClass;


if (isset($_POST["fl"]))
{
		$updatedBy = $session->getUser();
		$fl =	$_POST["fl"];
		$id = $_POST["id"];
		$ctype = $_POST['ctype'];
		
		$first_name				=		$_POST['first_name'];
		$middle_name			=		$_POST['middle_name'];
		$last_name				=		$_POST['last_name'];
		$fullname				= 		$last_name;
		
		if (!empty($first_name))
			$fullname .= ', ' . $first_name;
		if (!empty($middle_name))
			$fullname .= ' ' . $middle_name;
			
		
		$notes					=		$_POST['notes'];
		$address_1				=		$_POST['address_1'];
		$address_2				=		$_POST['address_2'];
//		$city					=		$_POST['city'];
//		$state					=		$_POST['state'];
//		$zip					=		$_POST['zip'];
		$city_id				=		$_POST['city_id'];
		$state_id				=		$_POST['state_id'];
		$zip_id					=		$_POST['zip_id'];
		
		$category_id_fk			=		$_POST['category_id_fk'];
		$specialty_id_fk		=		$_POST['specialty_id_fk'];
		$county_id_fk			=		$_POST['county_id_fk'];
		$email					=		$_POST['email'];
		$license_status	 	    = 		$_POST['license_status'];
		$phone			 	    = 		$_POST['phone'];
		$fax			 	    = 		$_POST['fax'];
		$website		 	    = 		$_POST['website'];
		$npi			 	    = 		$_POST['npi'];
		//$publication	 	    = 		$_POST['publication'];
		$status					=		$_POST['status'];
	
		$maxfilesize	 	    = 		$_POST['MAX_FILE_SIZE'];
		
		
		$suffix = '';
		//if ($fl == "edit")
//			$suffix = "&id=".$id;
		$suffix = '';
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			$suffix .= "&id=".$id;
		}
		
		if (isset($_POST['pg'])) {
			$pg = $_POST['pg'];
			$suffix .= "&pg=".$pg;
		}
		

		
		// call getTotal function with parameters searchField, searchKey  === returns count
		if ($fl =="add") {		
			$isDoctorRegistered = $M->getTotal('email', $email, 'exact');
		}
		else if ($fl == "edit") { 
			$isDoctorRegistered = $M->checkEmailForUpdate($id, $email);
		}
		if ($isDoctorRegistered >= 1) {
			if (isset($_POST['pg']) and $_POST['pg'] == 'dp')
				header("Location: newdoctor.php?fl=$fl&msg=erre" . $suffix);
			else
				header("Location: newdoctor.php?fl=$fl&msg=erre" . $suffix);
			exit();
		}
			
		
		
		 $sessionValues = array ( 'doctor_id'				=>		$id,
								  'first_name'				=>		$first_name,
								  'middle_name'				=>		$middle_name,
								  'last_name'				=>		$last_name,
								  'fullname'				=>		$fullname,
								  'address_1'				=>		$address_1,
								  'address_2'				=>		$address_2,
								  'city_id'					=>		$city_id,
								  'state_id'				=>		$state_id,
								  'zip_id'					=>		$zip_id,
								  'county_id_fk'			=>		$county_id_fk,
								  'category_id_fk'			=>		$category_id_fk,
								  'specialty_id_fk'			=>		$specialty_id_fk,
								  'email'					=>		$email,
								  'license_status'			=>		$license_status,
								  'phone'					=>		$phone,
								  'fax'						=>		$fax,
								  'website'					=>		$website,
								  'npi'						=>		$npi,
								  //'publication'				=>		$publication,
								  'notes'					=>		$notes,
								  'status'					=>		$status,
								  'maxfilesize'				=>		$maxfilesize,
								  'updated_by'				=>		$loggedUser
							);
		
		$updateRecord = $M->createDoctor($sessionValues);
		
		empty($sessionValues);
	//exit();
	
		if ($updateRecord == 1)	 { 	
			if (isset($_POST['pg']) and $_POST['pg'] == 'dp')
				header("Location: doctorpublication.php?fl=$fl&msg=update" . $suffix);
			else	
				header("Location: doctor.php?msg=update" . $suffix);
			exit();
		}
		else {
			if (isset($_POST['pg']) and $_POST['pg'] == 'dp')
				header("Location: doctorpublication.php?fl=$fl&msg=erre" . $suffix);
			else
				header("Location: newdoctor.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]) && !empty($_GET["id"]))
{

		
		$id =	$_GET["id"];
		$fl =	$_GET["fl"];
		$ctype = $_GET['ctype'];
		$pg = $_GET['pg'];

				if($fl == "edit" && empty($id))
				{
					header("Location: doctor.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($id);
				$data  = $M->getRecordByField('doctor_id', $id);
				
				
				if (!empty($data))
				{	
					
					  	$id						=		$data['doctor_id'];
					  	$first_name				=		$data['first_name'];
						$middle_name			=		$data['middle_name'];
						$last_name				=		$data['last_name'];
						$fullname				=		$data['fullname'];
						$notes					=		$data['notes'];
//						$address_1				=		$data['address_1'];
//						$address_2				=		$data['address_2'];
//						$city					=		$data['city'];
//						$state					=		$data['state'];
//						$zip					=		$data['zip'];
						
						$category_id_fk			=		$data['category_id_fk'];
						$specialty_id_fk		=		$data['specialty_id_fk'];
						$county_id_fk			=		$data['county_id_fk'];
						$email					=		$data['email'];
						$license_status	 	    = 		$data['license_status'];
						$phone			 	    = 		$data['phone'];
						$fax			 	    = 		$data['fax'];
						$website		 	    = 		$data['website'];
						$npi			 	    = 		$data['npi'];
						//$publication	 	    = 		$data['publication'];
						$status					=		$data['status'];
						
						$daRec = $DA->getDoctorAddressRecord($fieldName='doctor_id', $id);
						
					
				}
				
		
}
else if(isset($_GET["msg"]) && $_GET["msg"]=='erre' && $_GET["fl"]=='add')
{
	$id =	$_GET["id"];
	$fl =	$_GET["fl"];
	$ctype = $_GET['ctype'];
	$pg = $_GET['pg'];


					
					  	$first_name				=		$_GET['first_name'];
						$middle_name			=		$_GET['middle_name'];
						$last_name				=		$_GET['last_name'];
						$fullname				=		$_GET['fullname'];
						$notes					=		$_GET['notes'];
						$address_1				=		$_GET['address_1'];
						$address_2				=		$_GET['address_2'];
						$city					=		$_GET['city'];
						$state					=		$_GET['state'];
						$zip					=		$_GET['zip'];
						
						$category_id_fk			=		$_GET['category_id_fk'];
						$specialty_id_fk		=		$_GET['specialty_id_fk'];
						$county_id_fk			=		$_GET['county_id_fk'];
						$email					=		$_GET['email'];
						$license_status	 	    = 		$_GET['license_status'];
						$phone			 	    = 		$_GET['phone'];
						$fax			 	    = 		$_GET['fax'];
						$website		 	    = 		$_GET['website'];
						$npi			 	    = 		$_GET['npi'];
						$publication	 	    = 		$_GET['publication'];
						$status					=		$_GET['status'];
						
						//$daRec = $DA->getDoctorAddressRecord($fieldName='doctor_id', $id);
						
					
}
else {
		  $fl = "add";
		  $id = "";
		  $status = 'N';
		  
		  if (!empty($_GET['ctype'])) {
			$ctype = $category_id_fk = $_GET['ctype'];
		  }
		  
		  if (!empty($_GET['m']) and $_GET['m'] =='flt') {
			
			if (isset($_SESSION['searchfilter'])) { 
				
				$fieldNameArray = $_SESSION['searchfilter']['fields'];
				$searchValueArray = $_SESSION['searchfilter']['keywords'];
				
				if (!empty($fieldNameArray) && !empty($searchValueArray)) {

						$cntname = count($fieldNameArray);
						$cntval =  count($searchValueArray);

						if (($cntname > 0) && ($cntname == $cntval)) { 

							for($i=0; $i <$cntname; $i++) {
							
									if ( $fieldNameArray[$i] == 'first_name' ) 
										 $first_name = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'last_name' ) 
										 $last_name = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'address_1' ) 
										 $address_1 = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'address_2' ) 
										 $address_2 = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'city' ) 
										 $city = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'state' ) 
										 $state = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'zip' ) 
										 $zip = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'category_id_fk' ) 
										 $category_id_fk = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'specialty_id_fk' ) 
										 $specialty_id_fk = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'county_id_fk' ) 
										 $county_id_fk = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'email' ) 
										 $email = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'phone' ) 
										 $phone = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'license_status' ) 
										 $license_status = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'npi' ) 
										 $npi = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'pub_id' ) 
										 $publication_id = $searchValueArray[$i];
									
							}

						}

					}
				
				
				
			}
		  }
		  
		  
}


	$totalCategories = $CT->getTotal();
	$typeRec = $CT->getList();
	
	$totalSpecialty = $SP->getTotal();
	$spRec = $SP->getList();

	$totalCounties = $CN->getTotal();
	$cnRec = $CN->getList();

	$totalCities = $C->getTotal();
	$ctRec = $C->getList();	

	$totalStates = $S->getTotal();
	$stRec = $S->getList();	

	$totalZips = $Z->getTotal();
	$zpRec = $Z->getList();		
	

// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "erre")
		$errEmail = "Error! Email Id already exists.";
					

}
else
{
	$txtMessage = "";
}


include_once "header.php"; 

include_once "sidebar.php";
?>

<link rel="stylesheet" href="css/plugins/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="js/custom/general.js"></script>
<script type="text/javascript" src="js/custom/table.js"></script>
<script type="text/javascript" src="js/custom/commonajax.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#form").validate({
		rules: {
			first_name: "required",
			last_name: "required",
<?php if ($fl != "edit" && empty($id)) { ?> 
			address_1: "required",
			city_id: "required",

			/*city: {
					required: function(element) {
						return jQuery("#city_id").val() == 'Other';
					}
			},*/
			state_id: "required",
			zip_id: "required",
<?php } ?>
			//county_id_fk: "required",
			//category_id_fk: "required",
			specialty_id_fk: "required",
			email: {
				required: true,
				email: true,
			},
			
<?php /*if($fl != "edit" && empty($id)) { ?> 
			imgfile: "required"
<?php }*/ ?>
		},
		messages: {
			first_name: "Please enter First Name",
			last_name: "Please enter Last Name",
			city_id: "Please select City",
			city: "Please enter City",
			state_id: "Please select State",
			zip_id: "Please select Zip",
			//county_id_fk: "Please select County",
			//category_id_fk: "Please select Category",
			specialty_id_fk: "Please select Specialty",
			email: "Please enter a valid email address",
<?php /*if($fl != "edit" && empty($id)) { ?> 
			imgfile: " Please select jpg file only",
<?php }*/ ?>
		}
	});

});	

function setfullname() {

	var fname = document.getElementById('first_name').value;
	var mname = document.getElementById('middle_name').value;
	var lname = document.getElementById('last_name').value;
	
	if(fname!='')
		fname = fname.trim() + ' ';
	if(mname!='')
		mname = mname.trim() + ' ';
	if(lname!='')
		lname = lname.trim();
	
	document.getElementById('fullname').value = fname +  mname  + lname;
}

/*function setOwner(obj) {
	
	var level	= obj.value;
	if (level == 1) {
		document.getElementById("ownerdiv").style.display ='none';	
	}
	else if (level == 2) {
		document.getElementById("ownerdiv").style.display ='block';	
	}
}*/

</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="doctor.php">Doctor</a>
		<span>Update Doctor</span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Doctor </h1>
    	
        <br />
        
		<form id="form" action="<?php echo  $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Doctor Information</legend>
                    
                    <p>
                    	<label for="name">First Name</label>
                        <input name="first_name" type="text" class="sf"  id="first_name" value="<?php echo $first_name?>" onblur="return setfullname();" onkeyup="return setfullname();" onchange="return setfullname();" />
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="name" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                    
                    <p>
                    	<label for="name">Middle Name</label>
                        <input name="middle_name" type="text" class="sf"  id="middle_name" value="<?php echo $middle_name?>" onblur="return setfullname();" onkeyup="return setfullname();" onchange="return setfullname();" />
					</p>
                    
                    <p>
                    	<label for="name">Last Name</label>
                        <input name="last_name" type="text" class="sf"  id="last_name" value="<?php echo $last_name?>" onblur="return setfullname();" onkeyup="return setfullname();" onchange="return setfullname();" />
                    </p>
                    
                    <p>
                    	<label for="name">Full Name</label>
                        <input name="fullname" type="text" class="sf"  id="fullname" value="<?php echo $fullname?>" />
                    </p>
                    
                    <?php if ($fl == 'edit') { ?>
                    <p>
                    	<label for="address">Current Addresses</label>
                        <select id="address" name="address" style="width:500px;">
							<?php
								if (!empty($daRec) and count($daRec) > 0) { ?>
							<?php	
								foreach($daRec as $key => $val) {
									$aid = $val['id'];
									$address = $val['address_1']. ', ' . $val['city']. ', ' . $val['state']. ', ' . $val['zipcode'];
							?>	
								<option value="<?php echo $aid; ?>"><?php echo $address; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                    </p>
                    <p>
                    	<label for="name">&nbsp; </label>
                        Add New Address
                    </p>
					<?php } ?>
                    
                    <p>
                    	<label for="name">Address Line 1</label>
                        <input name="address_1" type="text" class="sf"  id="address_1" value="<?php echo $address_1?>" />
                    </p>
                    
                    <p>
                    	<label for="name">Address Line 2</label>
                        <input name="address_2" type="text" class="sf"  id="address_2" value="<?php echo $address_2?>" />
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
                        <!--<input name="city" type="text" class="sf"  id="city" placeholder="Enter City if not in the list"  value="" />-->
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
                        <!--<input name="state" type="text" class="sf"  id="state" placeholder="Enter State if not in the list" value="" />-->
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
                      <!--<input name="zip" type="text" class="sf"  id="zip" placeholder="Enter Zip if not in the list" value="" />-->
                    </p>
					
					 <p>
                    	<label for="type">County </label>
                    	 <select id="county_id_fk" name="county_id_fk" style="width:250px;">
						<?php
								if ($totalCounties > 0) { ?>
								<option value="">- Select County -</option>
							<?php	
								 for ($i=0; $i < $totalCounties; $i++) { 
									$cntid = $cnRec[$i]['county_id'];
									$county = $cnRec[$i]['county'];
							?>	
								<option value="<?php echo $cntid; ?>" <?php if ($county_id_fk == $cntid) { ?> selected="selected" <?php  } ?> ><?php echo $county; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
					</p>
                    
                    <p>
                    	<label for="phone">Phone Number</label>
                        <input name="phone" type="text" class="sf"  id="phone" value="<?php echo $phone?>" /> 
					</p>
                    
                    <p>
                    	<label for="fax">Fax</label>
                        <input name="fax" type="text" class="sf"  id="fax" value="<?php echo $fax?>" /> 
					</p>
                    
                    <p>
                    	<label for="email">Email</label>
                        <input name="email" type="text" class="sf"  id="email" value="<?php echo $email?>" /> 
                        <?php  if (!empty($errEmail)) {  ?>
						<label for="name" generated="true" class="error" style=""><?php echo  $errEmail; ?></label>
						<?php } ?>
                        
					</p>
                     
                     <p>
                    	<label for="type">Category </label>
                    	 <select id="category_id_fk" name="category_id_fk" style="width:250px;">
						<?php
								if ($totalCategories > 0) { ?>
								<option value="">- Select Category -</option>
							<?php	
								 for ($i=0; $i < $totalCategories; $i++) { 
									$catid = $typeRec[$i]['cat_id'];
									$category = $typeRec[$i]['category_name'];
							?>	
								<option value="<?php echo $catid; ?>" <?php if ($category_id_fk == $catid) { ?> selected="selected" <?php  } ?> ><?php echo $category; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
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
								<option value="<?php echo $spid; ?>" <?php if ($specialty_id_fk == $spid) { ?> selected="selected" <?php  } ?> ><?php echo $specialty; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
					</p>
					
					
					
					<p>
                    	<label for="license_status">License Status</label>
                        <input name="license_status" type="text" class="sf"  id="license_status" value="<?php echo $license_status?>" />
					</p>
					
					
                    
                    <p>
                    	<label for="website">Website</label>
                        <input name="website" type="text" class="sf"  id="website" value="<?php echo $website?>" /> 
					</p>
                    
                    <p>
                    	<label for="npi">NPI</label>
                        <input name="npi" type="text" class="sf"  id="npi" value="<?php echo $npi?>" /> 
					</p>
                    
                    <?php /*?><p>
                    	<label for="publication">Publication</label>
                        <input name="publication" type="text" class="sf"  id="publication" value="<?php echo $publication?>" /> 
					</p><?php */?>
					
				    <p>
                    	<label for="notes">Notes</label>
                        <textarea name="notes" class="mf" cols="" rows=""><?php echo $notes; ?></textarea>
                    </p>
                    
                    <p>
                    	<label for="imgfile">Select Image</label>
                        <input name="imgfile" type="file" class="sf" id="imgfile" /> (max 5 mb file)
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
                        <?php if (!empty($pg)) { ?>	<input type="hidden" name="pg" value="<?php echo $pg; ?>" /> <?php } ?>
                        <input name="MAX_FILE_SIZE" value="5242880" type="hidden">
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
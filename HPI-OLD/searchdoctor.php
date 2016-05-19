<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/publication.class.php";
require_once "classes/doctorpublication.class.php";
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
$loggedUserId = $session->getUserId();

/*if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} */

$A = new AdminClass;
$M = new DoctorClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;
$P = new PublicationClass;
$DP = new DoctorPublicationClass;

$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;


$county_id_fk = '';
$category_id_fk = '';
	
/*if (!empty($loggedUserId))
{
		$id = 	$loggedUserId;
		$data = $A->getSelectedFieldsRecordByField($selectedFields='specialty_id_fk', $fieldName='uid', $id, $searchStatus='exact');
	
		if (!empty($data)) {
			$specialty_id_fk  = $data['specialty_id_fk'];
			if ($specialty_id_fk > 0) { 
				$spRec = $SP->getSelectedFieldsRecordByField($selectedFields='specialty_name', $fieldName='specialty_id', $specialty_id_fk, $searchStatus='exact');
				if (!empty($spRec)) {
					$specialty  =		$spRec['specialty_name'];
				}
			}
			else {
					header("Location: updateprofile.php?msg=errsp"); exit();
			}
		}// end if
		

}*/

	$totalCategories = $CT->getTotal();
	$typeRec = $CT->getList();
	
	$totalSpecialty = $SP->getTotal();
	$spRec = $SP->getList();

	$totalCounties = $CN->getTotal();
	$cnRec = $CN->getList();	
	
	$cntPubTotal = $P->getTotal();
	$pubRec = $P->getList();

	$totalCities = $C->getTotal();
	$cityRec = $C->getList();
	
	$totalStates = $S->getTotal();
	$stateRec = $S->getList();
	
	$totalZip = $Z->getTotal();
	$zipRec = $Z->getList();

// Check if the error is present

if(isset($_GET['msg']))
{
	$error = 0;
	$msg = $_GET['msg']; 
	
	if ($msg == "err") {
		$error = 1;
		$txtMessage = "Error! Please try again.";
	}
	else if ($msg == "norec") {
		$error = 1; 
		$txtMessage = "Error! No records found.";						
	}			

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
/*	jQuery("#form").validate({
		rules: {
			first_name: "required",
			last_name: "required",
			city: "required",
			state: "required",
			county_id_fk: "required",
			category_id_fk: "required",
			specialty_id_fk: "required",
			email: {
				required: true,
				email: true,
			}
		},
		messages: {
			first_name: "Please enter First Name",
			last_name: "Please enter Last Name",
			city: "Please enter City",
			state: "Please enter State",
			county_id_fk: "Please select County",
			category_id_fk: "Please select Category",
			specialty_id_fk: "Please select Specialty",
			email: "Please enter a valid email address",
		}
	});*/

});	


</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="doctor.php">Doctor</a>
		<span>Search Doctor</span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Search</h1>
    	
    <br />
        
		<form id="form" action="searchresult.php" method="post" enctype="multipart/form-data">
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
                    <legend>Search Doctor's Information</legend>
                    
                    
					<div class="one_half">	
                    <p>
                    	<label for="name">First Name</label>
                        <input name="first_name" type="text" class="sf"  id="first_name" value=""  />
					</p>
                    
                    <p>
                    	<label for="npi">NPI</label>
                        <input name="npi" type="text" class="sf"  id="npi" value="" /> 
					</p>
                    </div>
                    
                    <div class="one_half last">
                    <p>
                    	<label for="name">Last Name</label>
                        <input name="last_name" type="text" class="sf"  id="last_name" value=""  />
                    </p>
                    <p>
                    	<label for="email">Email</label>
                        <input name="email" type="text" class="sf"  id="email" value="" /> 
					</p>
                    </div>
					
                    <div class="one_half">	
                    <p>
                    	<label for="name">Address Line 1</label>
                        <input name="address_1" type="text" class="sf"  id="address_1" value="" />
                    </p>
                    
                    <p>
                    	<label for="name">Address Line 2</label>
                        <input name="address_2" type="text" class="sf"  id="address_2" value="" />
                    </p>
                     
                    <p>
                    	<label for="name">City</label>
                        <select id="city_id_fk" name="city_id_fk" style="width:250px;">
						<?php
								if ($totalCities > 0) { ?>
								<option value="">- Select City -</option>
							<?php	
								 for ($i=0; $i < $totalCities; $i++) { 
									$cid = $cityRec[$i]['city_id'];
									$city = $cityRec[$i]['city'];
							?>	
								<option value="<?php echo $cid; ?>" <?php /*if ($city_id_fk == $cid) { ?> selected="selected" <?php  }*/ ?> ><?php echo $city; ?></option>
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
                        <select id="state_id_fk" name="state_id_fk" style="width:250px;">
						<?php
								if ($totalStates > 0) { ?>
								<option value="">- Select State -</option>
							<?php	
								 for ($i=0; $i < $totalStates; $i++) { 
									$sid = $stateRec[$i]['state_id'];
									$state = $stateRec[$i]['state'];
							?>	
								<option value="<?php echo $sid; ?>" <?php /*if ($state_id_fk == $sid) { ?> selected="selected" <?php  }*/ ?> ><?php echo $state; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                    </p>
                    
                    </div>
                    <div class="one_half last">	
                    <p>
                    	<label for="name">Phone Number</label>
                        <input name="phone" type="text" class="sf"  id="phone" value="" />
                    </p>
                    
                    <p>
                    	<label for="name">Fax</label>
                        <input name="fax" type="text" class="sf"  id="fax" value="" />
                    </p>
                    
                    <p>
                    	<label for="name">Zipcode</label>
                        <select id="zip_id_fk" name="zip_id_fk" style="width:250px;">
						<?php
								if ($totalZip > 0) { ?>
								<option value="">- Select Zip -</option>
							<?php 	
								 for ($i=0; $i < $totalZip; $i++) { 
									$zid = $zipRec[$i]['zip_id'];
									$zip = $zipRec[$i]['zipcode'];
							?>	
								<option value="<?php echo $zid; ?>" <?php /*if ($zip_id_fk == $zid) { ?> selected="selected" <?php  }*/ ?> ><?php echo $zip; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
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
								<option value="<?php echo $cntid; ?>" <?php /*if ($county_id_fk == $cntid) { ?> selected="selected" <?php  }*/ ?> ><?php echo $county; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
					</p>
                    
                    </div>
                    
                    <div class="one_half">	
                    <p>
                    	<label for="type">Specialty </label>
                    	 <?php /*?><select id="specialty_id_fk" name="specialty_id_fk" style="width:250px;">
								<option value="<?php echo $specialty_id_fk; ?>"  selected="selected"><?php echo $specialty; ?></option>
						 </select><?php */?>
                         <select id="specialty_id_fk" name="specialty_id_fk" style="width:250px;">
							<?php 
                                    if ($totalSpecialty > 0) { ?>
                                    <option value="">- Select Specialty -</option>
                                <?php 	
                                     for ($i=0; $i < $totalSpecialty; $i++) { 
                                        $spid = $spRec[$i]['specialty_id'];
                                        $specialty = $spRec[$i]['specialty_name'];
                                ?>	
                                    <option value="<?php echo $spid; ?>" <?php /*if ($specialty_id_fk == $spid) { ?> selected="selected" <?php  }*/ ?> ><?php echo $specialty; ?></option>
                                <?php 	 } 
                                    }
                                    else {
                                ?>	
                                    <option value="">- N/A -</option>
                                <?php } ?>
                          </select>
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
								<option value="<?php echo $catid; ?>" <?php /*if ($category_id_fk == $catid) { ?> selected="selected" <?php  }*/ ?> ><?php echo $category; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
					</p>
                    </div>
                    <div class="one_half last">	
                     
                    
                    <p>
                    	<label for="type">Publication </label>
                    	 <select id="publication_id" name="publication_id" style="width:250px;">
						    <?php 
								if ($cntPubTotal > 0) { ?>
								<option value="">- Select Publication -</option>
							<?php 	
								 for ($i=0; $i < $cntPubTotal; $i++) { 
									$pub_id = $pubRec[$i]['pub_id'];
									$publication = $pubRec[$i]['publication'];
							?>	
								<option value="<?php echo $pub_id; ?>"><?php echo $publication; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                      
					</p>
                    
                    <p>
                    	<label for="status">Doctor Status</label>
                        <select name="status" id="status" style="width:250px;">
							<option value="">- Select All -</option>
                            <option value="V">Verified</option>
							<option value="UV">Unverified</option>
                            <option value="N">New</option>
                            <option value="P">Pending</option>
                            <option value="D">Done</option>
                            <option value="E">Error</option>
						</select>
					</p>
					
					</div>
                    <div class="one_half">
                    <p>
					    <input type="hidden" name="fl" value="search" />
                       	<button>Search Record</button>
                    </p>
                    </div>
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
<?php
include_once "classes/session.class.php";
require_once "classes/center.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/chapter.class.php";
require_once "classes/county.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/address.class.php";
require_once "classes/centeraddress.class.php";

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

$M = new CenterClass;
$CT = new CategoryClass;
$CH = new ChapterClass;
$CN = new CountyClass;
$SP = new SpecialtyClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$CA = new CenterAddressClass;


if (isset($_POST["fl"]))
{
		$updatedBy = $session->getUser();
		$fl =	$_POST["fl"];
		$id = $_POST["id"];
		$ctype = $_POST['ctype'];
		
		$name					=		$_POST['name'];
		$notes					=		$_POST['notes'];
		$address_1				=		$_POST['address_1'];
		$address_2				=		$_POST['address_2'];
		$city_id				=		$_POST['city_id'];
		$state_id				=		$_POST['state_id'];
		$zip_id					=		$_POST['zip_id'];
		
		$specialty_id_fk		=		$_POST['specialty_id_fk'];
		$category_id_fk			=		$_POST['category_id_fk'];
		$chapter_id_fk			=		$_POST['chapter_id_fk'];
		$county_id_fk			=		$_POST['county_id_fk'];
		$phone			 	    = 		$_POST['phone'];
		$fax			 	    = 		$_POST['fax'];
		$website		 	    = 		$_POST['website'];
		$admission			 	= 		$_POST['admission'];
		$status					=		$_POST['status'];
	
		$suffix = '';
		//if ($fl == "edit")
//			$suffix = "&id=".$id;
		$suffix = '';
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			$suffix .= "&id=".$id;
		}
		
		 $sessionValues = array ( 'center_id'				=>		$id,
								  'name'					=>		$name,
								  'address_1'				=>		$address_1,
								  'address_2'				=>		$address_2,
								  'city_id'					=>		$city_id,
								  'state_id'				=>		$state_id,
								  'zip_id'					=>		$zip_id,
								  'county_id_fk'			=>		$county_id_fk,
								  'specialty_id_fk'			=>		$specialty_id_fk,
								  'category_id_fk'			=>		$category_id_fk,
								  'chapter_id_fk'			=>		$chapter_id_fk,
								  'phone'					=>		$phone,
								  'fax'						=>		$fax,
								  'website'					=>		$website,
								  'admission'				=>		$admission,
								  'notes'					=>		$notes,
								  'status'					=>		$status,
								  'updated_by'				=>		$loggedUser
							);
		
		$updateRecord = $M->createCenter($sessionValues);
		empty($sessionValues);
		
		$cid = '';
		if (!empty($_SESSION['centid'])) {
			$cid = $_SESSION['centid'];
			$suffix .= '&id='.$cid;
		}
		
	//exit();
	
		if ($updateRecord == 1)	 { 	
			if ($_POST['submitpub'] == 1)
				header("Location: centerpublication.php?msg=cupdate" . $suffix);
			else if ($_POST['submit'] == 1)
				header("Location: center.php?msg=update" . $suffix);

			exit();
		}
		else {
			header("Location: newcenter.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$id =	$_GET["id"];
		$fl =	$_GET["fl"];
		$ctype = $_GET['ctype'];
		$pg = $_GET['pg'];

				if($fl == "edit" && empty($id))
				{
					header("Location: center.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($id);
				$data  = $M->getRecordByField('center_id', $id);
				
				
				if (!empty($data))
				{	
					
					  	$id						=		$data['center_id'];
					  	$name					=		$M->prepare_output($data['name']);
						$notes					=		$M->prepare_output($data['notes']);
						$specialty_id_fk		=		$data['specialty_id_fk'];
						$category_id_fk			=		$data['category_id_fk'];
						$chapter_id_fk			=		$data['chapter_id_fk'];
						$county_id_fk			=		$data['county_id_fk'];
						$website		 	    = 		$M->prepare_output($data['website']);
						$admission		 	    = 		$M->prepare_output($data['admission']);
						$status					=		$M->prepare_output($data['status']);
						
						$daRec = $CA->getCenterAddressRecord($fieldName='center_id', $id);
						
					
				}
				
		
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
							
									if ( $fieldNameArray[$i] == 'name' ) 
										 $name = $searchValueArray[$i];
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
									else if ( $fieldNameArray[$i] == 'chapter_id_fk' ) 
										 $chapter_id_fk = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'county_id_fk' ) 
										 $county_id_fk = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'phone' ) 
										 $phone = $searchValueArray[$i];
									else if ( $fieldNameArray[$i] == 'admission' ) 
										 $admission = $searchValueArray[$i];
									
									
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
		
	$totalChapter = $CH->getTotal();
	$chRec = $CH->getList();

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
			name: "required",
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
			category_id_fk: "required",
			specialty_id_fk: "required",
			//chapter_id_fk: "required",
			
		},
		messages: {
			name: "Please enter First Name",
			address_1: "Please enter Address 1",
			city_id: "Please select City",
			state_id: "Please select State",
			zip_id: "Please select Zip",
			//county_id_fk: "Please select County",
			category_id_fk: "Please select Category",
			specialty_id_fk: "Please select Specialty",
			//chapter_id_fk: "Please select Chapter",
			
		}
	});


	jQuery("#searchadr").autocomplete("ajaxsearch/ajaxaddresslist.php", {
		width: 350,
		matchContains: true,
	});
	
});	

function proceedForm(dataString,action) {
	
	var strurl = "ajaxclient.php";
	// alert(dataString);
	jQuery.ajax({
			type: "POST",
			url: strurl,
			data:  dataString, 
		//	cache: false,
			success: function(result) {
					if(action==4) {
						getAddressInfo(result,1);
					}
			},
			error: AjaxFailed
		}); 

		return false;
}


function getAddressInfo(result,fm) {
	
	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];
	
	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }
		 
		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			//alert('Updating..!');
			var aid = str.result['address_id'];
			var address_1 = str.result['address_1'];
			var address_2 = str.result['address_2'];
			var city_id = str.result['city_id'];
			var state_id = str.result['state_id'];
			var zip_id = str.result['zip_id'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			
			document.getElementById("address_1").value = address_1;
			document.getElementById("address_2").value = address_2;
			document.getElementById("city_id").selectedIndex = city_id;
			document.getElementById("state_id").selectedIndex = state_id;
			document.getElementById("zip_id").selectedIndex = zip_id;
			document.getElementById("phone").value = phone;
			document.getElementById("fax").value = fax;
			document.getElementById("searchadr").value = '';
		
		}
	}
	
}
function AjaxFailed(result){
	alert('Failed ' + result.status + ' ' + result.statusText);
	//var msg = 'Error in processing..';
	//alert(msg);
	return false;
}

</script>
<link rel="stylesheet" type="text/css" href="ajaxsearch/jquery.autocomplete.css" />
<script type='text/javascript' src='ajaxsearch/jquery.autocomplete.js'></script>

<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="center.php">Center</a>
		<span>Update Center</span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Center </h1>
    	
        <br />
        
		<form id="form" action="<?php echo  $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Center Information</legend>
                    
                    <p>
                    	<label for="name">Center Name</label>
                        <input name="name" type="text" class="mf"  id="name" value="<?php echo $name?>"  />
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="name" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
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
						<label for="address0"><span style="font-weight: bold">Search Address </span></label>
						<input name="searchadr" type="text" class="sf"  id="searchadr" autocomplete="off"  value=""    />
						<input type="hidden" id="project-id">
					</p>
										
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
                    
                    <?php /*?><p>
                    	<label for="email">Email</label>
                        <input name="email" type="text" class="sf"  id="email" value="<?php echo $email?>" /> 
                        <?php  if (!empty($errEmail)) {  ?>
						<label for="name" generated="true" class="error" style=""><?php echo  $errEmail; ?></label>
						<?php } ?>
                        
					</p><?php */?>
                     
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
                    	<label for="type">Chapter </label>
                    	 <select id="chapter_id_fk" name="chapter_id_fk" style="width:250px;">
						<?php
								if ($totalChapter > 0) { ?>
								<option value="">- Select Chapter -</option>
							<?php	
								 for ($i=0; $i < $totalChapter; $i++) { 
									$chid = $chRec[$i]['chapter_id'];
									$chapter = $chRec[$i]['chapter_name'];
							?>	
								<option value="<?php echo $chid; ?>" <?php if ($chapter_id_fk == $chid) { ?> selected="selected" <?php  } ?> ><?php echo $chapter; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
					</p>
					
					
					<p>
                    	<label for="website">Website</label>
                        <input name="website" type="text" class="sf"  id="website" value="<?php echo $website?>" /> 
					</p>
                    
                    <p>
                    	<label for="admission">Admission</label>
                        <input name="admission" type="text" class="sf"  id="admission" value="<?php echo $admission?>" /> 
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
                        <button name="submit" value="1" style="float:left;">Submit</button> &nbsp;<button name="submitpub" value="1" style="margin-left:5px; float:left;">Submit &amp; Add Publication</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
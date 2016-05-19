<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/center.class.php";
require_once "classes/category.class.php";
require_once "classes/county.class.php";
require_once "classes/chapter.class.php";
require_once "classes/doctorpublication.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/publication.class.php";
require_once "classes/specialty.class.php";

$session = new SessionClass;

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();
$loggedUserId = $session->getUserId();

/*if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} */

$A = new AdminClass;
$M = new CenterClass;
$CT = new CategoryClass;

$CN = new CountyClass;
$PB = new PublicationClass;
$P = new ChapterClass;
$DP = new DoctorPublicationClass;

$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$SP = new SpecialtyClass;

$county_id_fk = '';
$category_id_fk = '';
$pub_id = 0;
	
	if (isset($_GET['pub'])) {
		$pub = $_GET['pub'];
			
		$cntPubTotal = $PB->getTotal($field='publication', $pub, 'exact');
		
		if ($cntPubTotal > 0) {
			$pubRec = $PB->getList($field='publication', $pub, 'exact');
			$pub_id = $pubRec[0]['pub_id'];
		}
	}
	else if (isset($_GET['pubid'])) {
		$pub_id = $_GET['pubid'];
	}
	
//	$totalCategories = $CT->getTotal();
//	$typeRec = $CT->getList($fieldName='', $searchValue='', $orderByField='category_name', $orderByValue='ASC');
	
	$totalSpecialty = $SP->getTotal();
	$spRec = $SP->getList($fieldName='', $searchValue='', $orderByField='specialty_name', $orderByValue='ASC');
	
//	$cntPubTotal = $PB->getTotal();
//	$pubRec = $PB->getList();

	$totalCities = $C->getTotal();
	$cityRec = $C->getList($fieldName='', $searchValue='', $orderByField='city', $orderByValue='ASC');
	
	$totalStates = $S->getTotal();
	$stateRec = $S->getList($fieldName='', $searchValue='', $orderByField='state', $orderByValue='ASC');
	
	$totalZip = $Z->getTotal();
	$zipRec = $Z->getList($fieldName='', $searchValue='', $orderByField='zipcode', $orderByValue='ASC');
	

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" media="screen" href="css/style.css" />
</head>
<body class="bodywhite">

<div class="headerspace"></div>
<script type="text/javascript" src="js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/custom/general.js"></script>

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
<div class="maincontent1">
	
    <div class="left">
    
    	<h1 class="pageTitle">Find A Doctor</h1>
    	
    <br />
		<form id="form" action="extsearchresult.php" method="post" enctype="multipart/form-data">
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
        	<div class="form_external">
                <fieldset>
                    <legend></legend>
                    
                    
					<div class="one_half">	
                    <p>
                    	<label for="name">Doctor Name</label>
                        <input name="name" type="text" class="sf"  id="name" value=""  style="width:200px;" />
					</p>
                    
                    <p>
                    	<label for="name">Address Line 1</label>
                        <input name="address_1" type="text" class="sf"  id="address_1" value="" style="width:200px;" />
                    </p>
                    
                    <p>
                    	<label for="name">Address Line 2</label>
                        <input name="address_2" type="text" class="sf"  id="address_2" value="" style="width:200px;" />
                    </p>
                     
                    
                    <p>
                    	<label for="name">Zipcode</label>
                        <select id="zip_id_fk" name="zip_id_fk" style="width:200px;">
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
                    
                    
                    </div>
                    <div class="one_half last">	
					<p>
                    	<label for="type">Specialty </label>
                    	 <select id="specialty_id_fk" name="specialty_id_fk" style="width:200px;">
							<?php 
                                    if ($totalSpecialty > 0) { ?>
                                    <option value="">- Select Specialty -</option>
                                <?php 	
                                     for ($i=0; $i < $totalSpecialty; $i++) { 
                                        $spid = $spRec[$i]['specialty_id'];
                                        $specialty = $spRec[$i]['specialty_name'];
                                ?>	
                                    <option value="<?php echo $spid; ?>"><?php echo $specialty; ?></option>
                                <?php 	 } 
                                    }
                                    else {
                                ?>	
                                    <option value="">- N/A -</option>
                                <?php } ?>
                          </select>
					</p>
					
					<p>
                    	<label for="name">City</label>
                        <select id="city_id_fk" name="city_id_fk" style="width:200px;">
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
                        <select id="state_id_fk" name="state_id_fk" style="width:200px;">
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
 
                    <p>&nbsp;</p>
                  
					</div>
                    
                    
                    
                    <div class="one_half">
                    <p>
						 <input name="doctor_status" type="hidden" value="V">
						 <input name="publication_id" type="hidden" value="<?php echo $pub_id; ?>" />
					    <input type="hidden" name="fl" value="search" />
                       	<button>Search </button>
                    </p>
                    </div>
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->
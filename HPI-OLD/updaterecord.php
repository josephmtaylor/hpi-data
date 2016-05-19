<?php
include_once "classes/session.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/doctorpublication.class.php";
require_once "classes/doctoraddress.class.php";
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

/*if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} */

$M = new DoctorClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;
$P = new DoctorPublicationClass;
$DA = new DoctorAddressClass;

$ADR = new AddressClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;

$totalCategories = $CT->getTotal();
$typeRec = $CT->getList();

$totalSpecialty = $SP->getTotal();
$spRec = $SP->getList();	

$totalCities = $C->getTotal();
$ctRec = $C->getList();	

$totalStates = $S->getTotal();
$stRec = $S->getList();	

$totalZips = $Z->getTotal();
$zpRec = $Z->getList();	

if (isset($_GET['m']) && ($_GET['m']== "del"))
{
		$doctor_id = $_GET['delid'];
		$pub_id = $_GET['pub_id'];

		/* delete Record 
		 * @param string $doctor id,  @param string pub id
		 */
	
		$RecoredDeleted = $P->deleteRecord($doctor_id, $pub_id);
		
		if ($RecoredDeleted == 1)
			header("Location: doctorpublication.php?msg=dl&id=".$doctor_id);
		else if ($RecoredDeleted == 0)
			header("Location: doctorpublication.php?msg=err&id=".$doctor_id);
			
		exit();	
} 

$doctorId = $_GET['id'];	


if (!empty($doctorId)) {

	// ----  check if property exists --------

	if (isset($doctorId)) {
		$isRecordExists = $M->isIdPresent($doctorId);
	}
	else {
		$isRecordExists = 0;
		header("Location: doctor.php"); exit();
	}

	
	if ($isRecordExists == 1) { 
			
			$data = $M->getRecordByField( 'doctor_id', $doctorId, 'exact');
			
			//print_r($data);
			
			//$id						=		$data['doctor_id'];
			$first_name				=		$data['first_name'];
			$middle_name			=		$data['middle_name'];
			$last_name				=		$data['last_name'];
			$fullname				=		$data['fullname'];
			$notes					=		$data['notes'];
			$address_1				=		$data['address_1'];
			$address_2				=		$data['address_2'];
			$city					=		$data['city'];
			$state					=		$data['state'];
			$zip					=		$data['zip'];
			
			$category_id_fk			=		$data['category_id_fk'];
			$specialty_id_fk		=		$data['specialty_id_fk'];
			$county_id_fk			=		$data['county_id_fk'];
			$email					=		$data['email'];
			$license_status	 	    = 		$data['license_status'];
			$phone			 	    = 		$data['phone'];
			$fax			 	    = 		$data['fax'];
			$website		 	    = 		$data['website'];
			$npi			 	    = 		$data['npi'];
			$publication	 	    = 		$data['publication'];
			$status					=		$data['status'];
			
			if (empty($notes))
				$notes = ' - N/A - ';
			
			if (empty($license_status))
				$license_status = ' - N/A - ';
			
			if (empty($status))
				$status = ' - N/A - ';
			else {
				$status = $M->getStatus($status);
			}
			
			$doctor_name  = $fullname;
			if (empty($doctor_name)) { 
				$doctor_name  = $first_name;
				
				if (!empty($middle_name)) {
					$doctor_name .= ' ' . $middle_name; 
				}
				if (!empty($last_name)) {
					$doctor_name .= ' ' . $last_name; 
				}
			}
			
			$isSpecialtyExist = $SP->isIdPresent($specialty_id_fk); 
			if ($isSpecialtyExist == 1) { 
				
				$lrec = $SP->getRecordByField('specialty_id', $specialty_id_fk,'exact');
				$specialty_name = $lrec['specialty_name'];
			}
			else
				$specialty_name = "- N/A -";
				
			
			$isCategoryExist = $CT->isIdPresent($category_id_fk); 
			if ($isCategoryExist == 1) { 
				
				$crec = $CT->getRecordByField('cat_id', $category_id_fk,'exact');
				$category_name = $crec['category_name'];
			}
			else
				$category_name = "- N/A -";
				
			
			$isCountyExist = $CN->isIdPresent($county_id_fk); 
			if ($isCountyExist == 1) { 
				
				$cnrec = $CN->getRecordByField('county_id', $county_id_fk,'exact');
				$county = $cnrec['county'];
			}
			else
				$county = "- N/A -";
			
			
			$doctorPubTotal = $P->isDcotorRecordExists($doctorId);
			if ($doctorPubTotal > 0) {
				$dpRec = $P->getDoctorPublicationRecord('doctor_id', $doctorId, 'exact');
			}
			//echo '<pre>'; print_r($dpRec);
			
			$daRec = $DA->getDoctorAddressRecord($fieldName='doctor_id', $doctorId);
			
	}
	





} // end if 
else {
	 header("Location: index.php");
 	 exit();
}	


if(isset($_GET['msg']))
{
	$error = 0;
	$msg = $_GET['msg']; 
	
	if ($msg == "dl")
		$txtMessage = "Record Deleted.";
	if ($msg == "update")
		$txtMessage = "Publications Updated.";
	
	if ($msg == "err") {
		$error = 1;
		$txtMessage = "Error! Please try again.";
	}
						
}
else
{
	$txtMessage = "";
}

?>

<script type="text/javascript" src="js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/custom/general.js"></script>

<link rel="stylesheet" type="text/css" href="ajaxsearch/jquery.autocomplete.css" />
<script type='text/javascript' src='ajaxsearch/jquery.autocomplete.js'></script>

<script>

jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#form").validate({
		rules: {
			address_1: "required",
			city_id: "required",
			state_id: "required",
			zip_id: "required"
		},
		messages: {
			address_1: "Please enter Address 1",
			city_id: "Please select City",
			state_id: "Please select State",
			zip_id: "Please select Zip"
		},
		submitHandler: function() {
				
				$("#btnnewaddr").prop('disabled', true);
				var uid = $("#docid").val();
				var action = 'newdoctoraddress';
				var dataString = 'action=' + action + '&doctor_id=' + uid + '&address_1=' +  $("#address_1").val() + '&address_2=' +  $("#address_2").val() + '&city_id=' +  $("#city_id").val() + '&state_id=' +  $("#state_id").val() + '&zip_id=' +  $("#zip_id").val() + '&phone=' +  $("#phone_number").val() + '&fax=' +  $("#fax").val();
				proceedForm(dataString,1);
			},
	});
	
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#formud").validate({
		rules: {
			specialty_id_fk: "required",
			npi: "required"
		},
		messages: {
			specialty_id_fk: "Please select Specialty",
			npi: "Please enter NPI"
		},
		submitHandler: function() {
				
				//$("#btnupdatedoc").prop('disabled', true);
				var uid = $("#doctid").val();
				var action = 'updatedoctor';
				var dataString = 'action=' + action + '&doctor_id=' + uid + '&specialty_id_fk=' +  $("#specialty_id_fk").val() + '&category_id_fk=' +  $("#category_id_fk").val() + '&npi=' +  $("#npi").val() + '&email=' +  $("#email").val() + '&website=' +  $("#website").val() + '&license_status=' +  $("#license_status").val() + '&notes=' +  $("#notes").val() + '&status=' +  $("#status").val();
				proceedForm(dataString,5);
			},
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
					if(action==1) {
						createDoctorAddress(result,1);
					} else if(action==2) {
						deletePublication(result,1);
					} else if(action==3) {
						deleteDoctorAddress(result,1);
					} else if(action==4) {
						getAddressInfo(result,1);
					} else if(action==5) {
						updateDoctorInfo(result,1);
					}  
			},
			error: AjaxFailed
		}); 

		return false;
}


function deletePublication(result,fm) {
	
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
			var pid = str.result['pub_id'];
			return false;
		}
	}
	
	return false;
	
	
}

// --- delete doctor address
function deleteDoctorAddress(result,fm) {
	
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
			var daid = str.result['da_id'];
			return false;
		}
	}
	
	return false;
	
	
}


function createDoctorAddress(result,fm) {
	
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
			var daid = str.result['da_id'];
			var address = str.result['address'];
			var city = str.result['city'];
			var state = str.result['state'];
			var zip = str.result['zip'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			
			alert('New address created successfully.');
			
			document.getElementById("searchaddr").value = '';
			document.getElementById("address_1").value = '';
			document.getElementById("address_2").value = '';
			document.getElementById("city_id").selectedIndex = '';
			document.getElementById("state_id").selectedIndex = '';
			document.getElementById("zip_id").selectedIndex = '';
			document.getElementById("phone_number").value = '';
			document.getElementById("fax").value = '';
			
			var ol = document.getElementById('loadaddr').innerHTML;
			var stradr = '<div class="one_half" class="daddr_'+daid+'"><strong>Address #</strong><br />'+address+'<br />'+city+'&nbsp;'+state+'&nbsp;'+zip+'<br />'+'<strong>Phone #</strong> '+phone+'<br />'+'<strong>Fax #</strong> '+fax+ '<br /><br /></div>';
		
			document.getElementById('loadaddr').innerHTML = ol+stradr;	
		}
	}
	
	$("#btnnewaddr").removeAttr("disabled");
	return false;
	
	
}


function updateDoctorInfo(result,fm) {
	
	//alert(result);
	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];
	
	if (errors == 1) { 
		 if (fm==1) {
		 	if (errors) {
				alert(message);
			}
			else
				alert('Something went wrong. please try again!');
		 }
		 
		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var did = str.result['doctor_id'];
			var specialty = str.result['specialty'];
			var category = str.result['category'];
			var npi = str.result['npi'];
			var email = str.result['email'];
			var website = str.result['website'];
			var license_status = str.result['license_status'];
			var status = str.result['status'];
			var notes = str.result['notes'];
			
			alert('Doctor info updated successfully.');
			
			document.getElementById("docinfo1").innerHTML = specialty + '<br />' +  category + '<br />' + npi + '<br />' + notes;
			document.getElementById("docinfo2").innerHTML = email + '<br />' +  website + '<br />' + license_status + '<br />' + status;
			
			//$("#btnupdatedoc").removeAttr("disabled");
			toggle('0');
		}
	}
	
	
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
			document.getElementById("phone_number").value = phone;
			document.getElementById("fax").value = fax;
			
		
		}
	}
	
}

function AjaxFailed(result){
	alert('Failed ' + result.status + ' ' + result.statusText);
	//var msg = 'Error in processing..';
	//alert(msg);
	return false;
}

$(document).ready(function() {

	$("#pubTable .dlink").on("click",function() {

			if(confirm('Are you sure to delete record?'))
			{ 
				var pubid = $(this).closest('tr').find('td:eq(3)').text().trim();
				var docid = $(this).closest('tr').find('td:eq(2)').text().trim();
				var yr = $(this).closest('tr').find('td:eq(0)').text().trim();
				
				var action = 'deletePublication';
				var dataString = 'action=' + action + '&pub_id=' + pubid + '&doctor_id=' +  docid+ '&year=' +  yr;
				var tr = $(this).closest('tr');
				tr.css("background-color","#FF3700");
				 
				tr.fadeOut(400, function(){
					tr.remove();
				});
				proceedForm(dataString,2);
			} 
			return false;
    
    });
	
// --- updating address

	$('.deleteadr').click(function(){
			
			var url = $(this).attr('href');
		    var pieces = url.split("?");
			var pfx = pieces[1];
			
			
			if(confirm('Are you sure to remove address?'))
			{ 
				var action = 'deleteDoctorAddress';
				var dataString = 'action=' + action + '&'+ pfx; // '&address_id=' + addressid + '&doctor_id=' +  docid;
				var tr = $(this).closest('div');
				tr.css("background-color","#FF3700");
				 
				tr.fadeOut(400, function(){
					tr.remove();
				});
				
				proceedForm(dataString,3);
			} 			  
			return false;
	});
	
});


var j = jQuery.noConflict() ;

j(document).ready(function(){

	j("#searchaddr").autocomplete("ajaxsearch/ajaxaddresslist.php", {
		width: 350,
		matchContains: true,
	});
	
});


function toggle(flag) {
	
	if (flag == 1) {
		document.getElementById("showdoc").style.display = 'none';
		document.getElementById("updatedoc").style.display = 'block';
	} else if (flag == 0) {
		document.getElementById("showdoc").style.display = 'block';
		document.getElementById("updatedoc").style.display = 'none';
	}
}


</script>


<div class="left">
    	
        <h1 class="pageTitle">Doctor's Info</h1>
        
         <?php  if (!empty($txtMessage)) {  
		  		if ( $error == 0 )
					$notify = "msgsuccess";
				else if ( $error == 1 )
					$notify = "msgerror";
		  ?>
			<div class="three_fourth last">
					<div class="notification <?php echo $notify; ?>">
						<a class="close"></a>
						<p><?php echo $txtMessage;?></p>
					</div><!-- notification msginfo -->
				</div>	
			<?php } ?>
        
        <div class="invoice last">
        	<div class="invoice_inner">
            
            	<!--<span>[Logo here]</span>-->
                
                <h2 class="title"><?php echo $doctor_name; ?>&nbsp; <a title="Edit Doctor Information" href="#" onclick="javascript:toggle('1');"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a></h2> 
                
                <?php /*?><h2 class="title"><?php echo $doctor_name; ?>&nbsp; <a title="Edit Doctor Information" target="_blank" href="newdoctor.php?fl=edit&pg=dp&amp;id=<?php echo $doctorId;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a></h2> <?php */?>
                
                <br clear="all" /><br />
                
                <div>
                
                <div id="showdoc" style="display:block">
                
                <div class="one_half">
                    <div class="one_half">
                        <strong>
                            Specialty # <br />
                            Category # <br />
                            NPI # <br />
							Notes #</strong>
                    </div><!-- one_half -->
                    
                    <div id="docinfo1" class="one_half last alignright">
                        <?php echo $specialty_name; ?> <br />
                        <?php echo $category_name; ?> <br />
                        <?php echo $npi; ?><br />
                        <?php echo $notes; ?>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
                
                <div class="one_half last">
                    <div class="one_half">
                        <strong>
                            Email # <br />
                            Website # <br />
                            License Status # <br />
							Current Status # </strong>
                    </div><!-- one_half -->
                    
                    <div id="docinfo2" class="one_half last alignright">
                        <?php echo $email; ?> <br />
                        <?php echo $website; ?> <br />
                        <?php echo $license_status; ?><br />
                        <?php echo $status; ?>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                </div>
               
                <br clear="all" /><br />
                
                <div id="updatedoc" class="form_small" style="display:none">
                <form id="formud" name="formud" action="#" method="post">
                <div class="one_half">
                    <div class="one_half">
                        <strong>
                            Specialty # <br />
                            Category # <br />
                            NPI # </strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last">
                        <select id="specialty_id_fk" name="specialty_id_fk" style="width:220px;">
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
					  	</select> <br />
                        <select id="category_id_fk" name="category_id_fk" style="width:220px;">
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
					  </select> <br />
                         <input name="npi" type="text" class="sf200"  id="npi" value="<?php echo $npi; ?>" />
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
                
                <div class="one_half last">
                    <div class="one_half">
                        <strong>
                            Email # <br />
                            Website # <br />
                            License Status # <br /></strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last alignright">
                        <input name="email" type="text" class="sf200"  id="email" value="<?php echo $email; ?>" /> <br />
                        <input name="website" type="text" class="sf200"  id="website" value="<?php echo $website; ?>" /> <br />
                        <input name="license_status" type="text" class="sf200"  id="license_status" value="<?php  if ($license_status != ' - N/A - ') { echo $license_status; } ?>" />
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
               
                <br clear="all" /><br />
                
                 <div class="one_half">
                    <div class="one_half">
                        <strong>
                            Notes # </strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last alignright">
                        <textarea id="notes" name="notes" class="sf200" cols="" rows=""><?php if ($notes != ' - N/A - ') { echo $notes; } ?></textarea>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
                <div class="one_half last">
                    <div class="one_half">
                        <strong>
                            Current Status # </strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last alignright">
                        <select name="status" id="status" class="tf">
							<option value="V" <?php if ($status == "V") {?> selected="selected"<?php } ?>>Verified</option>
							<option value="UV" <?php if ($status == "UV") {?> selected="selected"<?php } ?> >Unverified</option>
                            <option value="N" <?php if ($status == "N") {?> selected="selected"<?php } ?>>New</option>
                            <option value="P" <?php if ($status == "P") {?> selected="selected"<?php } ?>>Pending</option>
                            <option value="D" <?php if ($status == "D") {?> selected="selected"<?php } ?>>Done</option>
						</select>
                    </div><!-- one_half last -->
                    
                    <div>
                        <input type="hidden" name="doctid"  id="doctid" value="<?php echo $doctorId; ?>" />
                        <input type="hidden" name="action" id="action" value="updatedoctor" />
                        <button id="btncancel" onclick="javascript:toggle('0');return false;">Cancel</button>  &nbsp; &nbsp; <button id="btnupdatedoc">Update</button>
                    </div>
                </div><!-- one_half last -->
                
                
                <br clear="all" /><br />
                </form>
                </div>
                
                
                <?php if (!empty($daRec)) {
					$i = 0;
					$atot = count($daRec);
					foreach($daRec as $key => $val) {
								
                                $aid = $val['id'];
                                $address_1 = $val['address_1'];
								$address_2 = $val['city']; 
								$state = $val['state'];
								$zip =  $val['zipcode'];
								$phone = $val['phone'];
								$fax = $val['fax'];
				 ?>
                
                        <div class="one_half">
                        
                            <h3>Address # &nbsp;&nbsp;<a id="<?php echo $aid; ?>" href="deldocaddress.php?address_id=<?php echo $aid; ?>&doctor_id=<?php echo $doctorId?>" class="deleteadr"><img src="images/icons/small/color/bin.png" width="18" border="0" alt="Delete Address" /></a></h3> <br />
                            <?php echo $address_1; ?> <br />
                            <?php if (!empty($address_2)) { echo $address_2 . '<br />'; } ?> 
                            <?php echo $city . ' ' . $state . ' ' . $zip; ?> <br />
                            <?php if (!empty($phone)) { echo '<strong>Phone #</strong> '. $phone . '<br />';  } ?> 
                            <?php if (!empty($fax)) { echo '<strong>Fax #</strong> '. $fax . '<br />'; } ?> <br /><br />
                        
                        </div><!--one_half -->
                        
                        <div id="loadaddr">

                        </div>
                        
                        <?php if ($i == $atot - 1) { ?>
                        <br /><br />
                        <div>
                            
                            
                            <form id="form" name="form" action="#" method="post">
        
                                <div class="form_new">
                                    <fieldset>
                                        <legend>Add New Address Here #</legend>
                                        
                                        <p>
                                            <label for="address0">Search Here</label>
                                            <input name="searchaddr" type="text" class="sf"  id="searchaddr" autocomplete="off"  value=""    />
                                            <input type="hidden" id="project-id">
                                        </p>
                                        
                                        <p>
                                            <label for="address1">Address 1 </label>
                                            <input name="address_1" type="text" class="sf"  id="address_1" value="" />
                                        </p>
                                        
                                        <p>
                                            <label for="address2">Address 2</label>
                                            <input name="address_2" type="text" class="sf"  id="address_2" value="" />
                                        </p>
                                        
                                        <p>
                                            <label for="city" >City</label>
                                            <select id="city_id" name="city_id" style="width:250px;">
                                                <?php
                                                    if ($totalCities > 0) { ?>
                                                    <option value="">- Select City -</option>
                                                <?php	
                                                     for ($i=0; $i < $totalCities; $i++) { 
                                                        $ctid = $ctRec[$i]['city_id'];
                                                        $city = $ctRec[$i]['city'];
                                                ?>	
                                                    <option value="<?php echo $ctid; ?>"><?php echo $city; ?></option>
                                                <?php 	 } 
                                                    }
                                                    else {
                                                ?>	
                                                    <option value="">- N/A -</option>
                                                <?php } ?>
                                          </select>
                                        </p>
                                        
                                        <p class="one_half">
                                            <label for="state">State</label>
                                            <select id="state_id" name="state_id" class="tf">
                                                <?php
                                                    if ($totalZips > 0) { ?>
                                                    <option value="">- Select State -</option>
                                                <?php	
                                                     for ($i=0; $i < $totalStates; $i++) { 
                                                        $stid = $stRec[$i]['state_id'];
                                                        $state = $stRec[$i]['state'];
                                                ?>	
                                                    <option value="<?php echo $stid; ?>"><?php echo $state; ?></option>
                                                <?php 	 } 
                                                    }
                                                    else {
                                                ?>	
                                                    <option value="">- N/A -</option>
                                                <?php } ?>
                                          </select>
                                        </p>
                                        
                                        <p class="one_half">
                                            <label for="zipcode"> Zipcode</label>
                                            <select id="zip_id" name="zip_id" class="tf">
                                                <?php
                                                    if ($totalZips > 0) { ?>
                                                    <option value="">- Select Zipcode -</option>
                                                <?php	
                                                     for ($i=0; $i < $totalZips; $i++) { 
                                                        $zpid = $zpRec[$i]['zip_id'];
                                                        $zipcode = $zpRec[$i]['zipcode'];
                                                ?>	
                                                    <option value="<?php echo $zpid; ?>"><?php echo $zipcode; ?></option>
                                                <?php 	 } 
                                                    }
                                                    else {
                                                ?>	
                                                    <option value="">- N/A -</option>
                                                <?php } ?>
                                          </select>
                                        </p>
                                        
                                        <p class="one_half">
                                            <label for="phone">Phone</label>
                                            <input name="phone_number" type="text" class="nf"  id="phone_number" value="" />
                                        </p>
                                        
                                        <p class="one_half">
                                            <label for="fax"> Fax</label>
                                            <input name="fax" type="text" class="nf"  id="fax" value="" />
                                        </p>
                                       
                                        <p class="one_half">
                                            <input type="hidden" name="docid"  id="docid" value="<?php echo $doctorId; ?>" />
                                            <input type="hidden" name="action" id="action" value="newdoctoraddress" />
                                            <button id="btnnewaddr">Submit</button>
                                        </p>
                                        
                                    </fieldset>
                                </div><!--form-->
                                
                            
                            </form>
                            
                            
                        </div><!--one_half -->
                        <?php } ?>
                
                		<?php /*if ($i == 0) { ?>
                        <div class="one_half last">
                            <div class="one_half">
                                <strong>
                                    Email: <br />
                                    Website # <br />
                                    License Status # </strong>
                            </div><!-- one_half -->
                            
                            <div class="one_half last alignright">
                                <?php echo $email; ?> <br />
                                <?php echo $website; ?> <br />
                                <?php echo $license_status; ?>
                            </div><!-- one_half last -->
                        </div><!-- one_half last -->
                		<?php } */?>
                        
                <?php $i++; } // end foreach
					} // end if
				?>
                
                
                
                
                <br clear="all" /><br />
                <?php if ($doctorPubTotal > 0 ) { ?>
                <table cellpadding="0" cellspacing="0" id="pubTable" width="100%">
                	<thead>
                    	<tr>
                        	<td width="25%">Year</td>
                            <td width="45%">Publication</td>
                            <td width="5%" style="display:none;"></td>
                            <td width="5%" style="display:none;"></td>
                            <td width="20%">&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                    	<tr>
                        	<td colspan="3">&nbsp;</td> 
                        </tr>
                    <?php
					foreach ($dpRec as $key => $rec) {
						$year = $rec['year'];
						$docid =$rec ['doctor_id'];
						$pubid =$rec ['pub_id'];
						$publication = $rec['publication'];
					
					?>
                    	<tr id="<?php echo $pubid; ?>">
                        	<td><?php echo $year; ?></td>
                            <td><?php echo $publication; ?></td> 
                            <td style="display:none;"><?php echo $docid; ?></td>
                            <td style="display:none;"><?php echo $pubid; ?></td>
                            <td align="right">
							<a class="dlink" href="delpub.php?docid=<?php echo $doctorId;?>&pubid=<?php echo $pubid;?>" title="Delete"><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a>
							</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
                <br />
                <div class="last alignright">
                	<a target="_blank" href="newdoctorpub.php?fl=add&did=<?php echo $doctorId; ?>" class="iconlink2"><img src="images/icons/small/black/calendar.png" class="mgright5"> Add Publication</a>
                </div>

                
            </div><!-- invoice_inner -->
        
        </div><!-- invoice three_fourth last -->
        
             
    </div>
<?php
include_once "classes/session.class.php";
require_once "classes/center.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/centerpublication.class.php";
require_once "classes/centeraddress.class.php";
require_once "classes/address.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/county.class.php";

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

$M = new CenterClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;
$P = new CenterPublicationClass;
$DA = new CenterAddressClass;

$ADR = new AddressClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$CNT = new CountyClass;

$totalCategories = $CT->getTotal();
$typeRec = $CT->getList($fieldName='', $searchValue='', $orderByField='category_name', $orderByValue='ASC');

$totalSpecialty = $SP->getTotal();
$spRec = $SP->getList($fieldName='', $searchValue='', $orderByField='specialty_name', $orderByValue='ASC');	

$totalCities = $C->getTotal();
$ctRec = $C->getList();	

$totalStates = $S->getTotal();
$stRec = $S->getList();	

$totalZips = $Z->getTotal();
$zpRec = $Z->getList();	

$totalCounty = $CNT->getTotal();
$cntRec = $CNT->getList();

$addressId = $_GET['id'];	


if (!empty($addressId)) {

	// ----  check if property exists --------

	if (isset($addressId)) {
		$isRecordExists = $ADR->isIdPresent($addressId);
	}
	else {
		$isRecordExists = 0;
		header("Location: address.php"); exit();
	}

	
	if ($isRecordExists == 1) { 
			
			$data = $ADR->getRecordByField( 'id', $addressId, 'exact');
			
			//print_r($data);
			
			
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
			
			if (empty($notes))
				$notes = ' - N/A - ';
			
			if (empty($status))
				$showStatus = ' - N/A - ';
			else {
				$showStatus = $ADR->getStatus($status);
			}
			
			$city = ' - N/A - '; $state = ' - N/A - '; $zip = ' - N/A - '; $county = ' - N/A - ';
			
			if (!empty($city_id)) { 			
				$isCity = $C->isIdPresent($city_id); 
				if ($isCity == 1) { 
					$lrec = $C->getRecordByField('city_id', $city_id,'exact');
					$city = $lrec['city'];
				}
			}
			
			if (!empty($state_id)) { 			
				$isState = $S->isIdPresent($state_id); 
				if ($isState == 1) { 
					$srec = $S->getRecordByField('state_id', $state_id,'exact');
					$state = $srec['state'];
				}
			}
			
			if (!empty($zip_id)) { 			
				$isZip = $Z->isIdPresent($zip_id); 
				if ($isZip == 1) { 
					$zrec = $Z->getRecordByField('zip_id', $zip_id,'exact');
					$zip = $zrec['zipcode'];
				}
			}
			
			if (!empty($county_id)) { 			
				$isCnt = $CNT->isIdPresent($county_id); 
				if ($isCnt == 1) { 
					$nrec = $CNT->getRecordByField('county_id', $county_id,'exact');
					$county = $nrec['county'];
				}
			}
			
			
			$docRec = $DA->getAddressCenterRecord($fieldName='id', $addressId);
			
			//echo '<pre>'; print_r($docRec);
			
	}
	
	$categoryname = 'Center';




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
<script type='text/javascript' src='ajaxsearch/jquery.updatecnaddress.js'></script>
<script type='text/javascript' src='js/custom/updateaddress.js'></script>


<div class="left">
    	
        <h1 class="pageTitle">Address Info</h1>
        
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
                
                <h2 class="title"><?php echo $address_1; ?>&nbsp; <a title="Edit Address" href="#" onclick="javascript:toggleadr('1');"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a></h2> 
                
                <br clear="all" /><br />
                
                <div>
                
                <div id="showadr" style="display:block">
                
                <div class="one_half">
                    <div class="one_half">
                        <strong>
                            Phone #  <br />
                            Fax # <br />
                            Current Status # <br />
							Notes #</strong>
                    </div><!-- one_half -->
                    
                    <div id="adrinfo1" class="one_half last alignright">
                        <?php echo $phone; ?> <br />
                        <?php echo $fax; ?> <br />
                        <?php echo $showStatus; ?><br />
                        <?php echo $notes; ?>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
                
                <div class="one_half last">
                    <div class="one_half">
                        <strong>
                            Address 1 # <br />
                            Address 2 # <br />
                            City # <br />
							State # <br />
                            Zip # <br />
                            County #</strong>
                    </div><!-- one_half -->
                    
                    <div id="adrinfo2" class="one_half last alignright">
                        <?php echo $address_1; ?> <br />
                        <?php echo $address_2; ?> <br />
                        <?php echo $city; ?><br />
                        <?php echo $state; ?><br />
                        <?php echo $zip; ?><br />
                        <?php echo $county; ?>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                </div>
               
                <br clear="all" /><br />
                
                <div id="updateadr" class="form_small" style="display:none">
                <form id="formud" name="formud" action="#" method="post">
                <div class="one_half">
                    <div class="one_half">
                        <strong>
                            Phone #  <br />
                            Fax # <br />
							Current Status # </strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last">
                         <input name="phone" type="text" class="sf200"  id="phone" value="<?php echo $phone; ?>" /> <br />
                        <input name="fax" type="text" class="sf200"  id="fax" value="<?php echo $fax; ?>" /><br />
                        <select name="status" id="status" style="width:220px;">
							<option value="V" <?php if ($status == "V") {?> selected="selected"<?php } ?>>Verified</option>
							<option value="UV" <?php if ($status == "UV") {?> selected="selected"<?php } ?> >Unverified</option>
                            <option value="N" <?php if ($status == "N") {?> selected="selected"<?php } ?>>New</option>
                            <option value="P" <?php if ($status == "P") {?> selected="selected"<?php } ?>>Pending</option>
                            <option value="D" <?php if ($status == "D") {?> selected="selected"<?php } ?>>Done</option>
                            <option value="E" <?php if ($status == "E") {?> selected="selected"<?php } ?>>Error</option>
						</select>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
                
                <div class="one_half last">
                    <div class="one_half">
                        <strong>
                            Address 1 # <br />
                            Address 2 # <br />
                            City # <br />
                            State # <br />
                            Zip # <br /></strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last alignright">
                        <input name="address_1" type="text" class="sf200"  id="address_1" value="<?php echo $address_1; ?>" /> <br />
                        <input name="address_2" type="text" class="sf200"  id="address_2" value="<?php echo $address_2; ?>" /> <br />
                        
                        <select id="city_id" name="city_id" style="width:220px;">
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
					  	</select> <br />
                        <select id="state_id" name="state_id" style="width:220px;">
						<?php
								if ($totalStates > 0) { ?>
								<option value="">- Select State -</option>
							<?php	
								 for ($i=0; $i < $totalStates; $i++) { 
									$sid = $stRec[$i]['state_id'];
									$state = $stRec[$i]['state'];
							?>	
								<option value="<?php echo $sid; ?>" <?php if ($state_id == $sid) { ?> selected="selected" <?php  } ?> ><?php echo $state; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  	</select> <br />
                        <select id="zip_id" name="zip_id" style="width:220px;">
						<?php
								if ($totalZips > 0) { ?>
								<option value="">- Select Zip -</option>
							<?php	
								 for ($i=0; $i < $totalZips; $i++) { 
									$zid = $zpRec[$i]['zip_id'];
									$zip = $zpRec[$i]['zipcode'];
							?>	
								<option value="<?php echo $zid; ?>" <?php if ($zip_id == $zid) { ?> selected="selected" <?php  } ?> ><?php echo $zip; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					 	</select>
                      	                        
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
               
                <br clear="all" /><br />
                
                 <div class="one_half">
                    <div class="one_fourth">
                        <strong>
                            Notes # </strong>
                    </div><!-- one_half -->
                    
                    <div class="three_fifth last alignright">
                        <textarea id="notes" name="notes" class="sf300 floatleft" cols="" rows=""><?php if ($notes != ' - N/A - ') { echo $notes; } ?></textarea>
                    </div><!-- one_half last -->
                </div><!-- one_half last -->
                
                <div class="one_half last">
                    <div class="one_half">
                        <strong>
                            County # </strong>
                    </div><!-- one_half -->
                    
                    <div class="one_half last alignright">
                        <select id="county_id" name="county_id" style="width:220px;">
						<?php
								if ($totalCounty > 0) { ?>
								<option value="">- Select County -</option>
							<?php	
								 for ($i=0; $i < $totalCounty; $i++) { 
									$cntid = $zpRec[$i]['county_id'];
									$county = $zpRec[$i]['county'];
							?>	
								<option value="<?php echo $zid; ?>" <?php if ($county_id == $cntid) { ?> selected="selected" <?php  } ?> ><?php echo $county; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="0">- N/A -</option>
							<?php } ?>
					  </select>

                    </div><!-- one_half last -->
                    
                    <div>
                        <input type="hidden" name="addrid"  id="addrid" value="<?php echo $addressId; ?>" />
                        <input type="hidden" name="action" id="action" value="updateaddress" />
                        <button id="btncancel" onclick="javascript:toggleadr('0');return false;">Cancel</button>  &nbsp; &nbsp; <button id="btnupdatedoc">Update</button>
                    </div>
                </div><!-- one_half last -->
                
                
                <br clear="all" /><br />
                </form>
                </div>
                
                
                <?php if (!empty($docRec)) {
					$i = 0;
					$atot = count($docRec);
					foreach($docRec as $key => $val) {
								
                                $aid 			= $ADR->prepare_output( $val['id'] );
								$did 			= $ADR->prepare_output( $val['center_id'] );
                                $center_name	= $ADR->prepare_output( $val['name'] );
								$notes 			= $ADR->prepare_output( $val['notes'] );
								$website 		= $ADR->prepare_output( $val['website'] );
								$npi 			= $ADR->prepare_output( $val['npi'] );
								$dstatus 		= $ADR->prepare_output( $val['status'] );
								$category 		= $ADR->prepare_output( $val['category_name'] );
								
								$showDocStatus = $ADR->getStatus($dstatus);
								
								$showRecord = $ADR->showRecord($dstatus);
								if ($showRecord) { 
				 ?>
                
                        <div id="doc_<?php echo $did;?>" class="one_half">
                        
                            <h3><?php echo $center_name; ?>  &nbsp;&nbsp; <a id="<?php echo $did; ?>" href="delcnaddress.php?center_id=<?php echo $did; ?>&address_id=<?php echo $addressId; ?>" class="deletecnadr" title="Not at this location"><img src="images/icons/small/color/bin.png" width="18" border="0" alt="Not at this location" /></a> &nbsp; <a id="<?php echo $did; ?>" href="editdocaddress.php?center_id=<?php echo $did; ?>&address_id=<?php echo $addressId; ?>" class="editcnadr" title="Edit Center"><img src="images/icons/small/color/pen1.png" width="18" border="0" alt="Edit Center" /></a> </h3>
                             <br />
                            <?php if (!empty($category)) { echo '<strong>Category # </strong>'. $category . '<br />'; } ?> 
							<?php if (!empty($website)) { echo '<strong>Website # </strong>'. $website . '<br />'; } ?> 
                            <?php if (!empty($npi)) { echo '<strong>NPI #</strong> '. $npi . '<br />';  } ?> 
                            <?php if (!empty($showDocStatus)) { echo '<strong>Current Status #</strong> '. $showDocStatus . '<br />'; } ?> 
                            <?php if (!empty($notes)) { echo '<strong>Notes #</strong> '. $notes . '<br />'; } ?><br /><br />
                        
                        </div><!--one_half -->
                        <?php } // end if showRecord ?>
                        
                        <?php if ($i == $atot - 1) { ?>
                        
                        <div id="loaddr">

                        </div>
                        <br /><br />
                        <div>
                            
                            
                            <form id="formcnr" name="formcnr" action="#" method="post">
        
                                <div id="divupd" class="form_new">
                                    <fieldset>
                                        <legend>Add Center to Location #</legend>
                                        
                                        <p>
                                            <label for="address0">Search Here</label>
                                            <input name="searchcn" type="text" class="sf"  id="searchcn" autocomplete="off"  value=""    />
                                            <input type="hidden" id="project-id">
                                        </p>
                                        
                                        <p>
                                            <label for="name">Center</label>
                                            <input name="name" type="text" class="sf"  id="name" value="" />
                                        </p>
                                        
                                        <p>
                                            <label for="specialty" >Specialty</label>
                                            <select id="specialty_id_fk" name="specialty_id_fk" class="sf">
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
                                            <label for="type">Category </label>
                                             <select id="category_id_fk" name="category_id_fk" class="sf">
                                            <?php
                                                    if ($totalCategories > 0) { ?>
                                                    <option value="">- Select Category -</option>
                                                <?php	
                                                     for ($i=0; $i < $totalCategories; $i++) { 
                                                        $catid = $typeRec[$i]['cat_id'];
                                                        $category = $typeRec[$i]['category_name'];
                                                ?>	
                                                    <option value="<?php echo $catid; ?>" <?php if ($category == $categoryname) { ?> selected="selected"<?php } ?>><?php echo $category; ?></option>
                                                <?php 	 } 
                                                    }
                                                    else {
                                                ?>	
                                                    <option value="">- N/A -</option>
                                                <?php } ?>
                                          </select>
                                        </p>
                                        
                                        <p>
                                            <label for="npi">NPI</label>
                                            <input name="npi" type="text" class="sf"  id="npi" value="" />
                                        </p>
                                        
                                        <p>
                                            <label for="dnotes">Description</label>
                                            <textarea id="center_description" name="center_description" class="sf" cols="" rows=""></textarea>
                                        </p>
                                        
                                        <p>
                                            <label for="dnotes">Notes</label>
                                            <textarea id="center_notes" name="center_notes" class="sf" cols="" rows=""></textarea>
                                        </p>
                                        <p>
                                            <label for="dnotes">Status</label>
                                            <select name="center_status" id="center_status" class="sf">
                                                <option value="V" <?php if ($status == "V") {?> selected="selected"<?php } ?>>Verified</option>
												<option value="UV" <?php if ($status == "UV") {?> selected="selected"<?php } ?> >Unverified</option>
                                                <option value="N" <?php if ($status == "N") {?> selected="selected"<?php } ?>>New</option>
                                                <option value="P" <?php if ($status == "P") {?> selected="selected"<?php } ?>>Pending</option>
                                                <option value="D" <?php if ($status == "D") {?> selected="selected"<?php } ?>>Done</option>
                                                <option value="E" <?php if ($status == "E") {?> selected="selected"<?php } ?>>Error</option>
                                                <option value="RT" <?php if ($status == "TR") {?> selected="selected"<?php } ?>>Retired</option>
                                                <option value="DC" <?php if ($status == "DC") {?> selected="selected"<?php } ?>>Deceased</option>
                                                <option value="OT" <?php if ($status == "OT") {?> selected="selected"<?php } ?>>Out Of Area</option>
                                                <option value="NI" <?php if ($status == "NI") {?> selected="selected"<?php } ?>>No Other Info</option>
                                                <option value="LC" <?php if ($status == "LC") {?> selected="selected"<?php } ?>>License Issue</option>
                                            </select>
                                        </p>
                                        <p class="one_half">
                                            <input type="hidden" name="cnid"  id="cnid" value="" />
                                            <input type="hidden" name="new"  id="new" value="1" />
                                            <input type="hidden" name="adrid"  id="adrid" value="<?php echo $addressId; ?>" />
                                            <input type="hidden" name="action" id="action" value="newaddresscenter" />
                                            <button id="btnnewcnr">Submit</button>
                                        </p>
                                        
                                    </fieldset>
                                </div><!--form-->
                                
                            
                            </form>
                            
                            
                        </div><!--one_half -->
                        <?php } ?>
                
                <?php $i++; } // end foreach
					} // end if
				?>
                
                
                <br clear="all" /><br />
                

                
            </div><!-- invoice_inner -->
        
        </div><!-- invoice three_fourth last -->
        
             
    </div>
<?php
include_once "classes/session.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/doctorpublication.class.php";
require_once "classes/doctoraddress.class.php";

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
			
			$id						=		$data['doctor_id'];
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


include_once "header.php"; 

include_once "sidebar.php";
?>

<script type="text/javascript" src="js/custom/table.js"></script>
<script language="javascript1.2">

function deleteItem(item_id, pub_id, item_name)
{
    if(confirm('Are you sure to delete record : ' + item_name))
	{
		document.location.href="doctorpublication.php?m=del&delid="+item_id+"&pub_id="+pub_id;
	}
}

</script>

<div class="maincontent">

    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="doctor.php">Doctors</a>
        <span>Publication</span>
    </div><!-- breadcrumbs -->
	
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
        
        <div class="invoice three_fourth last">
        	<div class="invoice_inner">
            
            	<!--<span>[Logo here]</span>-->
                
                <h2 class="title"><?php echo $doctor_name; ?>&nbsp; <a title="Edit Doctor Information" href="newdoctor.php?fl=edit&pg=dp&amp;id=<?php echo $doctorId;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a></h2> 
                
                <br clear="all" /><br />
                
                <div class="">
                	<div class="one_fifth">
                    	<strong>Specialty #<br />
                        Category # <br />
                        NPI #</strong>
                    </div><!-- one_half -->
                    <div class="four_fifth last">
                    	<?php echo $specialty_name; ?> <br />
                        <?php echo $category_name; ?><br />
						<?php echo $npi; ?>
                    </div><!-- one_half -->	
                </div><!-- one_third -->
                
                <br clear="all" /><br />
                
                <?php if (!empty($daRec)) {
					$i = 0;
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
                            <strong>Address #</strong> &nbsp;&nbsp;<a href="newaddress.php?id=<?php echo $aid; ?>" target="_blank"><img src="images/icons/small/black/edit.png" width="10" alt="Edit Address" /></a> <br />
                            <?php echo $address_1; ?> <br />
                            <?php if (!empty($address_2)) { echo $address_2 . '<br />'; } ?> 
                            <?php echo $city . ' ' . $state . ' ' . $zip; ?> <br />
                            <?php if (!empty($phone)) { echo '<strong>Phone #</strong> '. $phone . '<br />';  } ?> 
                            <?php if (!empty($fax)) { echo '<strong>Fax #</strong> '. $fax . '<br />'; } ?> <br /><br />
                        </div><!--one_half -->
                
                		<?php if ($i == 0) { ?>
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
                		<?php } ?>
                        
                <?php $i++; } // end foreach
					} // end if
				?>
                
                
                
                <?php if (!empty($notes)) { ?>
                <br clear="all" /><br />
                <div class="one_fourth">
                	<strong>
                    	Notes: </strong>
                </div><!-- one_third -->
                
                <div class="three_fourth last">
                	<?php echo $notes; ?>
                </div>
                <?php } ?>
                
                <br clear="all" /><br />
                <?php if ($doctorPubTotal > 0 ) { ?>
                <table cellpadding="0" cellspacing="0" class="invoicetable" width="100%">
                	<thead>
                    	<tr>
                        	<td width="25%">Year</td>
                            <td width="55%">Publication</td>
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
						$pubid =$rec ['pub_id'];
						$publication = $rec['publication'];

					?>
                    	<tr>
                        	<td><?php echo $year; ?></td>
                            <td><?php echo $publication; ?></td> 
                            <td align="right"><a title="Delete" href="javascript:deleteItem('<?php echo $doctorId;?>','<?php echo $pubid;?>','<?php echo $publication;?>');" class=""><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
                <br />
                <div class="last alignright">
                	<a href="newdoctorpub.php?fl=add&did=<?php echo $doctorId; ?>" class="iconlink2"><img src="images/icons/small/black/calendar.png" class="mgright5"> Add Publication</a>
                </div>

                
            </div><!-- invoice_inner -->
        
        </div><!-- invoice three_fourth last -->
        
             
    </div><!--left-->
    
    <br clear="all" />
    
</div>
<!--maincontent-->

<?php include_once "footer.php"; ?>
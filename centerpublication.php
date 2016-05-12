<?php
include_once "classes/session.class.php";
require_once "classes/center.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/centerpublication.class.php";
require_once "classes/centeraddress.class.php";

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
$CA = new CenterAddressClass;

if (isset($_GET['m']) && ($_GET['m']== "del"))
{
		$center_id = $_GET['delid'];
		$pub_id = $_GET['pub_id'];

		/* delete Record 
		 * @param string $center id,  @param string pub id
		 */
	
		$RecoredDeleted = $P->deleteRecord($center_id, $pub_id);
		
		if ($RecoredDeleted == 1)
			header("Location: centerpublication.php?msg=dl&id=".$center_id);
		else if ($RecoredDeleted == 0)
			header("Location: centerpublication.php?msg=err&id=".$center_id);
			
		exit();	
} 

$centerId = $_GET['id'];	


if (!empty($centerId)) {

	// ----  check if property exists --------

	if (isset($centerId)) {
		$isRecordExists = $M->isIdPresent($centerId);
	}
	else {
		$isRecordExists = 0;
		header("Location: center.php"); exit();
	}

	
	if ($isRecordExists == 1) { 
	
			$data = $M->getRecordByField( 'center_id', $centerId, 'exact');
			
			//echo '<pre>'; print_r($data);
 			
			$id						=		$M->prepare_output($data['center_id']);
			$center_name  			= 		$M->prepare_output($data["name"]);
			$notes					=		$M->prepare_output($data['notes']);
			
			$category_id_fk			=		$M->prepare_output($data['category_id_fk']);
			$specialty_id_fk		=		$M->prepare_output($data['specialty_id_fk']);
			$county_id_fk			=		$M->prepare_output($data['county_id_fk']);
			$phone			 	    = 		$M->prepare_output($data['phone']);
			$fax			 	    = 		$M->prepare_output($data['fax']);
			$website		 	    = 		$M->prepare_output($data['website']);
			$npi			 	    = 		$M->prepare_output($data['npi']);
			$status					=		$M->prepare_output($data['status']);
			
			if (empty($phone))
				$phone = '- N/A -'; 
			
			if (empty($fax) or $fax == '')
				$fax = '- N/A -';
			
			if (empty($website)) 
				$website = '- N/A -';
			
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
			
			
			
			$centerPubTotal = $P->isCenterPubRecordExists($centerId);
			if ($centerPubTotal > 0) {
				$dpRec = $P->getCenterPublicationRecord('center_id', $centerId, 'exact');
			}
			//echo '<pre>'; print_r($dpRec);
			
			$caRec = $CA->getCenterAddressRecord($fieldName='center_id', $centerId);
			
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
	if ($msg == "cupdate")
		$txtMessage = "Center Information Updated.";
	
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
		document.location.href="centerpublication.php?m=del&delid="+item_id+"&pub_id="+pub_id;
	}
}

</script>

<div class="maincontent">

    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="center.php">Centers</a>
        <span>Publication</span>
    </div><!-- breadcrumbs -->
	
    <div class="left">
    	
        <h1 class="pageTitle">Center's Info</h1>
        
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
                
                <h2 class="title"><?php echo $center_name; ?>&nbsp; <a title="Edit Center Information" href="newcenter.php?fl=edit&pg=dp&amp;id=<?php echo $centerId;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a></h2> 
                
                <br clear="all" /><br />
                
                <div class="one_half">
                	<div class="one_half">
                    	<strong>Specialty #<br />
                        Category # <br />
                        NPI #</strong>
                    </div><!-- one_half -->
                    <div class="one_half last">
                    	<?php echo $specialty_name; ?> <br />
                        <?php echo $category_name; ?><br />
						<?php echo $npi; ?>
                    </div><!-- one_half -->	
                </div>
				
				<div class="one_half last">
                	<div class="one_half">
                    	<strong>Center Phone #<br />
                        Center Fax # <br />
                        Center Website #</strong>
                    </div><!-- one_half -->
                    <div class="one_half last">
                    	<?php echo $phone; ?> <br />
                        <?php echo $fax; ?><br />
						<?php echo $website; ?>
                    </div><!-- one_half -->	
                </div>
                
                <br clear="all" /><br />
                
                <?php if (!empty($caRec)) {
					$i = 0;
					foreach($caRec as $key => $val) {
								
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
                            <?php if (!empty($phone)) { echo '<strong>Address Phone #</strong> '. $phone . '<br />';  } ?> 
                            <?php if (!empty($fax)) { echo '<strong>Address Fax #</strong> '. $fax . '<br />'; } ?> <br /><br />
                        </div><!--one_half -->
                
                		<?php /*if ($i == 0) { ?>
                        <div class="one_half last">
                            <div class="one_half">
                                <strong>
                                    Phone # <br />
                                    Fax # </strong>
							</div>
                            <!-- one_half -->
                            
                            <div class="one_half last alignright">
                                <?php echo $phone; ?> <br />
                                <?php echo $fax; ?>
                            </div><!-- one_half last -->
                        </div><!-- one_half last -->
                		<?php } $i++; */ ?>
                        
                <?php  } // end foreach
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
                <?php if ($centerPubTotal > 0 ) { ?>
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
                            <td align="right"><a title="Delete" href="javascript:deleteItem('<?php echo $centerId;?>','<?php echo $pubid;?>','<?php echo $publication;?>');" class=""><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
                <br />
                <div class="last alignright">
                	<a href="newcenterpub.php?fl=add&did=<?php echo $centerId; ?>" class="iconlink2"><img src="images/icons/small/black/calendar.png" class="mgright5"> Add Publication</a>
                </div>

                
            </div><!-- invoice_inner -->
        
        </div><!-- invoice three_fourth last -->
        
             
    </div><!--left-->
    
    <br clear="all" />
    
</div>
<!--maincontent-->

<?php include_once "footer.php"; ?>
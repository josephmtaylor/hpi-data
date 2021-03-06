<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/doctoraddress.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}


$userType = $session->getUserLevel();
$loggedUser = $session->getUser();
$loggedUserId = $session->getUserId();


$A = new AdminClass;
$M = new DoctorClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;
$DA = new DoctorAddressClass;


if (isset($_POST['fl']) && ($_POST['fl']== "search")) {

		$fl						=		$_POST['fl'];
		$first_name				=		$_POST['first_name'];
		$last_name				=		$_POST['last_name'];
		$address_1				=		$_POST['address_1'];
		$address_2				=		$_POST['address_2'];
		$city_id_fk				=		$_POST['city_id_fk'];
		$state_id_fk			=		$_POST['state_id_fk'];
		$zip_id_fk				=		$_POST['zip_id_fk'];
		
		$category_id_fk			=		$_POST['category_id_fk'];
		$specialty_id_fk		=		$_POST['specialty_id_fk'];
		$county_id_fk			=		$_POST['county_id_fk'];
		$email					=		$_POST['email'];
		$phone					=		$_POST['phone'];
		$license_status	 	    = 		$_POST['license_status'];
		$npi			 	    = 		$_POST['npi'];
		$publication_id			=		$_POST['publication_id'];
		$status					=		$_POST['status'];
		
		$fieldName = array(); 
		$searchKeyword = array();

		if (!empty($first_name)) {

			array_push ($fieldName , 'first_name');
			array_push ($searchKeyword , $first_name);
		}

		if (!empty($last_name)) {

			array_push ($fieldName , 'last_name');
			array_push ($searchKeyword , $last_name);
		}

		if (!empty($address_1)) {

			array_push ($fieldName , 'address_1');
			array_push ($searchKeyword , $address_1);
		}
		
		if (!empty($address_2)) {

			array_push ($fieldName , 'address_2');
			array_push ($searchKeyword , $address_2);
		}
		
		if (!empty($city_id_fk)) {

			array_push ($fieldName , 'city_id');
			array_push ($searchKeyword , $city_id_fk);
		}

		if (!empty($state_id_fk)) {

			array_push ($fieldName , 'state_id');
			array_push ($searchKeyword , $state_id_fk);
		}

		if (!empty($zip_id_fk)) {

			array_push ($fieldName , 'zip_id');
			array_push ($searchKeyword , $zip_id_fk);
		}

		if (!empty($county_id_fk)) {

			array_push ($fieldName , 'county_id');
			array_push ($searchKeyword , $county_id_fk);
		}
		
		if (!empty($category_id_fk)) {

			array_push ($fieldName , 'category_id_fk');
			array_push ($searchKeyword , $category_id_fk);
		}
		
		if (!empty($specialty_id_fk)) {

			array_push ($fieldName , 'specialty_id_fk');
			array_push ($searchKeyword , $specialty_id_fk);
		}
		
		if (!empty($email)) {

			array_push ($fieldName , 'email');
			array_push ($searchKeyword , $email);
		}
		
		if (!empty($phone)) {

			array_push ($fieldName , 'phone');
			array_push ($searchKeyword , $phone);
		}
		
		if (!empty($fax)) {

			array_push ($fieldName , 'fax');
			array_push ($searchKeyword , $fax);
		}
		
		if (!empty($npi)) {

			array_push ($fieldName , 'npi');
			array_push ($searchKeyword , $npi);
		}
		
		if (!empty($publication_id)) {

			array_push ($fieldName , 'pub_id');
			array_push ($searchKeyword , $publication_id);
		}
		
		if (!empty($status)) {

			array_push ($fieldName , 'status');
			array_push ($searchKeyword , $status);
		}
		
		$_SESSION['searchfilter']['fields'] = $fieldName;
		$_SESSION['searchfilter']['keywords'] = $searchKeyword;
		
		//echo '<pre>';print_r($_SESSION['searchfilter']); exit();
		$suffix = "&fl=".$fl;

		$orderByField ='';
		$orderByValue ='';			


		//echo LIMIT;

		/* get Total Records - call function getTotal -  @returns integer
		 * @param string $searchField,  string $searchKeyword
		 */

		$searchStatus = 'exact';
		$tCount = $M->getTotalSearchArray($fieldName , $searchKeyword,  $searchStatus);

		if ($tCount > 0 ) 
		{
				$total_pages = ceil($tCount / LIMIT); //total number of pages
				$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB


				/* call function getTotal -  returns array
				 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
				 */

				$recordList = $M->getTotalSearchList($fieldName, $searchKeyword, $searchStatus, $orderByField, $orderByValue); //, $offset, LIMIT);
				$totalLimit = $M->arrayCount($recordList); // call arrayCount - @param array, @return int count
				$_SESSION['recordlist'] = $recordList;
				$_SESSION['totalLimit'] = $totalLimit;
				
				//echo '<pre>';print_r($recordList);exit();
				//$RecordsPaging = $M->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix);

		}
		else {
				header("Location: searchdoctor.php?msg=norec");
				exit();
		}



} // end if mode
else {
			header("Location: searchdoctor.php?msg=norec");
			exit();
}



include_once "header.php"; 

include_once "sidebar.php";
?>

<link rel="stylesheet" href="nivolightbox/nivo-lightbox.css" type="text/css" />
<link rel="stylesheet" href="nivolightbox/themes/default/default.css" type="text/css" />
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="nivolightbox/nivo-lightbox.min.js"></script>
<script>
$(document).ready(function(){
    $('a').nivoLightbox({
    onInit: function(){},                         // Callback when lightbox has loaded
    beforeShowLightbox: function(){},             // Callback before the lightbox is shown
    afterShowLightbox: function(lightbox){},     // Callback after the lightbox is shown
    beforeHideLightbox: function(){},             // Callback before the lightbox is hidden
    afterHideLightbox: function(){   
   //  parent.location.reload(true); 
    ;},             // Callback after the lightbox is hidden
    onPrev: function(element){},                 // Callback when the lightbox gallery goes to previous item
    onNext: function(element){},                 // Callback when the lightbox gallery goes to next item
    errorMessage: 'The requested content cannot be loaded. Please try again later.' // Error message when content can't be loaded
});
});


$(document).ready(function() {
	$('#example a.purple').click(function(){
			
			//$(this).parents('tr').fadeOut();
			$(this).parents('tr').addClass('selected');
			
//			var nRow = $(this).parents('tr')[0];
//			var id  = $(this).closest('tr').find('td:eq(0)').text().trim();
		
	});
 });

 
/*$(document).ready(function() {
  $("a.iframeFancybox1").fancybox({
   'width': 800,
   'height': 450,   
   'onClosed': function() {   
     parent.location.reload(true); 
    ;}
   });
 });*/
 
</script>

<script type="text/javascript" src="js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<!--<script type="text/javascript" src="js/plugins/jquery.colorbox-min.js"></script>-->
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/custom/general.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#example').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
//	jQuery(".view").colorbox({rel:'view'});
	
});
</script>



<div class="maincontent1">
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span>Doctors</span> </div>
  <!-- breadcrumbs -->
  
  <div class="left">
    <h1 class="pageTitle">Search Result </h1>
    <a href="searchdoctor.php" class="addNewButton" style="margin-left: 5px;">New Search </a>
    <?php if ($totalLimit > 0) { ?><a href="exportresult.php" class="addNewButton">Export</a><?php } ?>
    <ul class="submenu">
		<li class="current"><a href="#">Total Records (<?php echo $totalLimit;?>)</a></li>
    </ul>
    
	<br />
	<?php if ($totalLimit > 0) { ?>
    
    <div class="notification msginfo">
            	<a class="close"></a>
            	<strong>Would you like to Add Doctor with selected criteria?</strong> <a href="newdoctor.php?m=flt" target="_blank">Click Here</a>
    </div>
    <br />
    
    <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                    <th class="head0" width="22%">Name</td>
                    <th class="head1" width="10%">NPI</td>
                    <th class="head0" width="20%">Specialty</td>
                    <th class="head1" width="15%" align="center">Address</td>
                    <th class="head0" width="10%">Phone</td>
                    <th class="head1" width="14%" align="center">Last Updated</td>
                    <th class="head0" width="9%" align="center">Action</td>
                </tr>
            </thead>
            <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            	<col class="con0" />
                <col class="con1" />
                <col class="con0" />
            </colgroup>
            <tbody>
            <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
								$category_name = '- N/A -';  $specialty_name = '- N/A -'; $phone = ' - N/A -'; $fax = ' - N/A -';
								
								$id = $recordList[$i]['doctor_id'];
								$doctor_name  = $recordList[$i]["fullname"];
								if (empty($doctor_name)) { 
									$doctor_name  = $recordList[$i]["first_name"];
									
									if (!empty($recordList[$i]["middle_name"])) {
										$doctor_name .= ' ' . $recordList[$i]["middle_name"]; 
									}
									if (!empty($recordList[$i]["last_name"])) {
										$doctor_name .= ' ' . $recordList[$i]["last_name"]; 
									}
								}
								$category_id_fk = $recordList[$i]["category_id_fk"];
								//$specialty_id_fk = $recordList[$i]["specialty_id_fk"];
								
								$npi = $recordList[$i]["npi"];
								$city = $recordList[$i]["city"];
								$state = $recordList[$i]["state"];
								$status = $recordList[$i]["status"];
								$phone = $recordList[$i]["phone"];
								$specialty_name = $recordList[$i]["specialty_name"];
								
								if (empty($phone))
									$phone = "- N/A -";
								
								
								if (!empty($category_id_fk)) {
									$lrec = $CT->getRecordByField('cat_id', $category_id_fk,'exact');
									$category_name = $lrec['category_name'];
								}
								
								$status = $M->getStatusImage($status);
									
								$date_updated = $recordList[$i]["date_updated"];
								$daRec = $DA->getDoctorAddressRecord($fieldName='doctor_id', $id);
						
				?>
                <tr>
                  <td  width="22%"><?php echo $doctor_name; ?> </td> 
                  <td  width="10%"><?php echo $npi; ?></td>
                  <td  width="15%"><?php echo $specialty_name; ?></td>
                  <td  width="15%"><?php //echo $city . ', '. $state; ?>
                  <select id="address" name="address" style="width:200px;">
						<?php
                            if (!empty($daRec) and count($daRec) > 0) { ?>
                        <?php	
                            foreach($daRec as $key => $val) {
                                $aid = $val['id'];
                                $address = $val['address_1']. ', ' . $val['city']. ', ' . $val['state']. ', ' . $val['zipcode'] . ' &nbsp;';
                        ?>	
                            <option value="<?php echo $aid; ?>"><?php echo $address; ?></option>
                        <?php 	 } 
                            }
                            else {
                        ?>	
                            <option value="">- N/A -</option>
                        <?php } ?>
                  </select>
                  </td>
                  <td  width="10%"><?php //echo $phone; ?>
                  <select id="phone" name="phone" style="width:110px;">
						<?php
                            if (!empty($daRec) and count($daRec) > 0) { ?>
                        <?php	
                            foreach($daRec as $key => $val) {
                                $aid = $val['id'];
                                $phone = $val['phone'];
                                if (!empty($val['fax'])) {
                                     $phone .= ' | Fax: ' .$val['fax'];
                                }
                                 $phone .=  ' &nbsp;';
                        ?>	
                            <option value="<?php echo $aid; ?>"><?php echo $phone; ?></option>
                        <?php 	 } 
                            }
                            else {
                        ?>	
                            <option value="">- N/A -</option>
                        <?php } ?>
                  </select>
                  </td>
                  <td  width="14%" align="center"><?php echo $date_updated; ?></td>
                  <td width="9%" align="center">
                    <a href="#"><?php echo $status; ?></a>&nbsp;
                    <a title="Update Doctor Information" href="updaterecord.php?id=<?php echo $id;?>" data-lightbox-type="ajax" class="purple"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" class="view" width="16" height="16" border="0" /></a>&nbsp;
                    <a title="View Doctor Info" target="_blank" href="doctorpublication.php?id=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/search.png" alt="View" width="16" height="16" border="0" /></a>
                  </td>
                </tr>
                <?php 
                            } 
                ?>
        
            
                
            </tbody>
            <tfoot>
                <tr>
                    <th class="head0" width="21%">Name</td>
                    <th class="head1" width="11%">NPI</td>
                    <th class="head0" width="20%">Specialty</td>
                    <th class="head1" width="15%" align="center">Address</td>
                    <th class="head0" width="10%">Phone</td>
                    <th class="head1" width="13%" align="center">Last Updated</td>
                    <th class="head0" width="10%" align="center">Action</td>
                </tr>
            </tfoot>
        </table>

	<?php } 
	   else { 
	 ?>
		<div class="two_third last">
        	<div class="notification msginfo">
            	<a class="close"></a>
            	<p>No Records Found.</p>
            </div><!-- notification msginfo -->
        </div>
	<?php } ?>
	
  </div>
  <!--left-->
  <br clear="all" />
</div>
<!--maincontent-->

<?php include_once "footer.php"; ?>
<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/address.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/doctoraddress.class.php";

$session = new SessionClass;

/*if(!$session->logged_in){

		header("Location: login.php");
		exit();
}
*/

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();
$loggedUserId = $session->getUserId();


$A = new AdminClass;
$D = new AddressClass;
$M = new DoctorClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;
$DA = new DoctorAddressClass;


if (isset($_POST['fl']) && ($_POST['fl']== "search")) {

		$fl						=		$_POST['fl'];
		$name					=		explode(" ",$_POST['name']);
		$first_name				= 		$name[0];
		$last_name				=		$name[1];
		$address_1				=		$_POST['address_1'];
		$address_2				=		$_POST['address_2'];
		$city_id_fk				=		$_POST['city_id_fk'];
		$state_id_fk			=		$_POST['state_id_fk'];
		$zip_id_fk				=		$_POST['zip_id_fk'];
		
//		$category_id_fk			=		$_POST['category_id_fk'];
		$specialty_id_fk		=		$_POST['specialty_id_fk'];
//		$county_id_fk			=		$_POST['county_id_fk'];
//		$email					=		$_POST['email'];
//		$phone					=		$_POST['phone'];

//		$status			 	    = 		$_POST['status'];
		$publication_id			=		$_POST['publication_id'];
		
		$fieldName = array(); 
		$searchKeyword = array();

		if ($publication_id == 0) {
			header("Location: externalsearch.php?". $suffix ."&msg=norec");
			exit();
		}

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
		
		/*if (!empty($email)) {

			array_push ($fieldName , 'email');
			array_push ($searchKeyword , $email);
		}*/
		
		if (!empty($phone)) {

			array_push ($fieldName , 'phone');
			array_push ($searchKeyword , $phone);
		}
		
		if (!empty($fax)) {

			array_push ($fieldName , 'fax');
			array_push ($searchKeyword , $fax);
		}
		
		
		if (!empty($publication_id)) {

			array_push ($fieldName , 'pub_id');
			array_push ($searchKeyword , $publication_id);
			
			$suffix = "pubid=".$publication_id;
			
		}
		
		
		if (!empty($status)) {

			array_push ($fieldName , 'status');
			array_push ($searchKeyword , $status);
		}
		
		$dstatus = $_POST['status'];
		
		if (!empty($dstatus)) {

			array_push ($fieldName , 'doctor_status');
			array_push ($searchKeyword , $dstatus);
		}
		
		$_SESSION['searchfilter']['fields'] = $fieldName;
		$_SESSION['searchfilter']['keywords'] = $searchKeyword;
		
		//echo '<pre>';print_r($_SESSION['searchfilter']); exit();
		$suffix .= "&fl=".$fl;

		$orderByField ='';
		$orderByValue ='';			


		//echo LIMIT;

		/* get Total Records - call function getTotal -  @returns integer
		 * @param string $searchField,  string $searchKeyword
		 */

		$searchStatus = 'exact';
		$tCount = $D->getTotalExtSearchArray($fieldName , $searchKeyword,  $searchStatus);

		if ($tCount > 0 ) 
		{
				$total_pages = ceil($tCount / LIMIT); //total number of pages
				$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB


				/* call function getTotal -  returns array
				 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
				 */

				$recordList = $D->getTotalExtSearchList($fieldName, $searchKeyword, $searchStatus, $orderByField, $orderByValue); //, $offset, LIMIT);
				$totalLimit = $D->arrayCount($recordList); // call arrayCount - @param array, @return int count
				
				$_SESSION['recordlist'] = $recordList;
				$_SESSION['totalLimit'] = $totalLimit;
				
				//echo '<pre>';print_r($recordList);exit();
				//$RecordsPaging = $M->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix);

		}
		else {
				header("Location: externalsearch.php?". $suffix ."&msg=norec");
				exit();
		}



} // end if mode
else {
			header("Location: externalsearch.php?". $suffix ."msg=norec");
			exit();
}
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Result | <?php echo WEBSITE_TITLE; ?></title>
<link rel="stylesheet" media="screen" href="css/style.css" />

<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="css/ie7.css"/>
<![endif]-->

<script type="text/javascript" src="js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/custom/general.js"></script>
<!--<script type="text/javascript" src="js/custom/dashboard.js"></script>-->
<!--<script type="text/javascript" src="js/plugins/additional-methods.min.js"></script>-->

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
			
			var nRow = $(this).parents('tr')[5];
			var id  = $(this).closest('tr').find('td:eq(5)').text().trim();
			
			$('#example tr').each(function (i, row) {
				 var $row = $(row),
                 $text = $row.find('td:eq(5)').text().trim();
				
				 if (id == $text)
				 	$(this).closest('tr').addClass('selected');
			});
			
	});
	
	jQuery('#example').dataTable( {
		"sPaginationType": "full_numbers"
	});

 });

</script>

<script type="text/javascript" src="js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<!--<script type="text/javascript" src="js/plugins/jquery.colorbox-min.js"></script>-->
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/custom/general.js"></script>

</head>

<body class="bodywhite">

<div class="headerspace"></div>





<div class="maincontent1">
  
  <div class="left">
    <h1 class="pageTitle">Search Result </h1>
    <a href="externalsearch.php?<?php echo $suffix; ?>" class="addNewButton" style="margin-left: 5px;">New Search </a>
    <ul class="submenu">
		<li class="current"><a href="#">Total Records (<?php echo $totalLimit;?>)</a></li>
    </ul>
    
	<br />	
	
	<?php if ($totalLimit > 0) { ?>
    
    <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                    <th class="head0" width="30%">Doctor Name</td>
                    <th class="head1" width="20%">Specialty</td>
                    <th class="head0" width="30%">Address</td>
                    <th class="head0" width="20%" align="center">Phone</td>
					
                </tr>
            </thead>
            <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            </colgroup>
            <tbody>
            <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
								$category_name = '- N/A -';  $specialty_name = '- N/A -'; $phone = ' - N/A -'; $fax = ' - N/A -';
								
								$id = $recordList[$i]['id'];
								$docid = $recordList[$i]['doctor_id'];
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
								
								$address_1 = $recordList[$i]["address_1"];
								$address_2 = $recordList[$i]["address_2"];
								$city = $recordList[$i]["city"];
								$state = $recordList[$i]["state"];
								$zip = $recordList[$i]["zipcode"];
								$status = $recordList[$i]["status"];
								$doctor_status = $recordList[$i]["dstatus"];
								$phone = $recordList[$i]["phone"];
								$specialty_name = $recordList[$i]["specialty_name"];
								
								$fullAddress = $address_1;
								if (!empty($address_2))
									$fullAddress .= ', '. $address_1;
								if (!empty($city))
									$fullAddress .= ', '. $city;
								if (!empty($state))
									$fullAddress .= ', '. $state;
								if (!empty($zip))
									$fullAddress .= ', '. $zip;
								
								
								if (empty($phone))
									$phone = "-";
								
								$astatus = $M->getStatus($status);
						
				?>
                <tr>
                  <td><?php echo $doctor_name; ?> </td> 
                  <td><?php echo $specialty_name; ?></td>
				  <td><?php echo $fullAddress; ?></td>
				  <td><?php echo $phone; ?></td>
                </tr>
                <?php 
                            } 
                ?>

            </tbody>
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

        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->
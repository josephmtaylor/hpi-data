<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/address.class.php";
require_once "classes/center.class.php";
require_once "classes/category.class.php";
require_once "classes/chapter.class.php";
require_once "classes/county.class.php";
require_once "classes/centeraddress.class.php";
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


$A = new AdminClass;
$ADR = new AddressClass;
$M = new CenterClass;
$CT = new CategoryClass;
$CH = new ChapterClass;
$CN = new CountyClass;
$DA = new CenterAddressClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;


if (isset($_POST['fl']) && ($_POST['fl']== "search")) {

		$fl						=		$_POST['fl'];
		$name					=	$ADR->prepare_input( $_POST['name'] );
		$address_1				=	$ADR->prepare_input( $_POST['address_1'] );
		$address_2				=	$ADR->prepare_input( $_POST['address_2'] );
		$city_id_fk				=	$ADR->prepare_input( $_POST['city_id_fk'] );
		$state_id_fk			=	$ADR->prepare_input( $_POST['state_id_fk'] );
		$zip_id_fk				=	$ADR->prepare_input( $_POST['zip_id_fk'] );
		
		$category_id_fk			=	$ADR->prepare_input( $_POST['category_id_fk'] );
		$chapter_id_fk			=	$ADR->prepare_input( $_POST['chapter_id_fk'] );
		$county_id_fk			=	$ADR->prepare_input( $_POST['county_id_fk'] );
		$phone					=	$ADR->prepare_input( $_POST['phone'] );
		$center_status	 	    = 	$ADR->prepare_input( $_POST['center_status'] );
		$publication_id			=	$ADR->prepare_input( $_POST['publication_id'] );
		
		$fieldName = array(); 
		$searchKeyword = array();

		if (!empty($name)) {

			array_push ($fieldName , 'name');
			array_push ($searchKeyword , $name);
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
		
		if (!empty($chapter_id_fk)) {

			array_push ($fieldName , 'chapter_id_fk');
			array_push ($searchKeyword , $chapter_id_fk);
		}
		
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
		}
		if (!empty($center_status)) {

			array_push ($fieldName , 'center_status');
			array_push ($searchKeyword , $center_status);
		}
		
		/*$dstatus = 'A';
		
		if (!empty($dstatus)) {

			array_push ($fieldName , 'center_status');
			array_push ($searchKeyword , $dstatus);
		}*/
		
		
		
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
		$tCount = $ADR->getTotalCenterSearchArray($fieldName , $searchKeyword,  $searchStatus);

		if ($tCount > 0 ) 
		{
				$total_pages = ceil($tCount / LIMIT); //total number of pages
				$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB


				/* call function getTotal -  returns array
				 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
				 */

				$recordList = $ADR->getTotalCenterSearchList($fieldName, $searchKeyword, $searchStatus, $orderByField, $orderByValue); //, $offset, LIMIT);
				$totalLimit = $ADR->arrayCount($recordList); // call arrayCount - @param array, @return int count
				
				$_SESSION['recordlist'] = $recordList;
				$_SESSION['totalLimit'] = $totalLimit;
				
				//echo '<pre>';print_r($recordList);exit();
				//$RecordsPaging = $M->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix);

		}
		else {
				header("Location: searchcenter.php?msg=norec");
				exit();
		}



} // end if mode
else {
			header("Location: searchcenter.php?msg=norec");
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

<?php if (!empty($publication_id)) { ?>

$(document).ready(function() {
	$('#example a.purple').click(function(){
			
			//$(this).parents('tr').fadeOut();
			$(this).parents('tr').addClass('selected');
			
			//var nRow = $(this).parents('tr')[5];
			//var id  = $(this).closest('tr').find('td:eq(6)').text().trim();
			var id  = $(this).closest('tr').find('span:eq(0)').text().trim();
			
			$('#example tr').each(function (i, row) {
				 var $row = $(row),
                 //$text = $row.find('td:eq(6)').text().trim();
				   $text = $row.find("span:eq(0)").html();
				// alert(id + ' = ' + $text);	
				 if (id == $text)
				 	$(this).closest('tr').addClass('selected');
			});
			
	});
 });

<?php } else if (empty($publication_id) or $publication_id == '') { ?>
$(document).ready(function() {
	$('#example a.purple').click(function(){
			
			//$(this).parents('tr').fadeOut();
			$(this).parents('tr').addClass('selected');
			
			//var nRow = $(this).parents('tr')[5];
			//var id  = $(this).closest('tr').find('td:eq(5)').text().trim();
			var id  = $(this).closest('tr').find('span:eq(0)').text().trim();
			
			$('#example tr').each(function (i, row) {
				 var $row = $(row),
                // $text = $row.find('td:eq(5)').text().trim();
				 $text = $row.find("span:eq(0)").html();
				// alert(id + ' = ' + $text);	
				 if (id == $text)
				 	$(this).closest('tr').addClass('selected');
			});
			
	});
 });
<?php } ?>
 
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
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span> Search</span> Result</div>
  <!-- breadcrumbs -->
  
  <div class="left">
    <h1 class="pageTitle">Search Result </h1>
    <a href="searchcenter.php" class="addNewButton" style="margin-left: 5px;">New Search </a>
    <?php if ($totalLimit > 0) { ?><a href="exportcresult.php" class="addNewButton">Export</a><?php } ?>
    <ul class="submenu">
		<li class="current"><a href="#">Total Records (<?php echo $totalLimit;?>)</a></li>
    </ul>
    
	<br />
	<?php if ($totalLimit > 0) { ?>
    
    <!--<div class="notification msginfo">
            	<a class="close"></a>
            	<strong>Would you like to Add Center with selected criteria?</strong> <a href="newcenter.php?m=flt" target="_blank">Click Here</a>
    </div>
    <br />-->
    
    <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
				<?php if (!empty($publication_id)) { ?>
					<th class="head1" width="15%">Address</td>
					<th class="head0" width="10%">Publication</td>
				<?php } else { ?>
                    <th class="head0" width="25%">Address</td>
                <?php } ?>
				    <th class="head1" width="7%">Adr Status</td>
                    <th class="head0" width="22%">Center Name</td>
                    <th class="head1" width="15%">Category</td>
                    <th class="head0" width="8%" align="center">Phone</td>
                    <th class="head1" width="7%" align="center">Status</td>
                    <th class="head0" width="13%" align="center">Last Updated</td>
                    <th class="head1" width="3%" align="center">-</td>
                </tr>
            </thead>
            <colgroup>
				<?php if (!empty($publication_id)) { ?>
					<col class="con1" />
					<col class="con0" />
				<?php } else { ?>
                   <col class="con0" />
                <?php } ?>
                
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            	<col class="con0" />
                <col class="con1" />
                <col class="con0" />
				<col class="col1" />
            </colgroup>
            <tbody>
            <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
						$category_name = '- N/A -';  $chapter_name = '- N/A -'; $phone = ' - N/A -'; $fax = ' - N/A -';
						
						$id = $recordList[$i]['id'];
						$centerid = $recordList[$i]['center_id'];
						$center_name  = $recordList[$i]["name"];
						$category_id_fk = $recordList[$i]["category_id_fk"];
						$chapter_id_fk = $recordList[$i]["chapter_id_fk"];
						
						$address_1 = $recordList[$i]["address_1"];
						$address_2 = $recordList[$i]["address_2"];
						$city_id = $M->prepare_output( $recordList[$i]["city_id"] );
						$state_id = $M->prepare_output( $recordList[$i]["state_id"] );
						$zip_id = $M->prepare_output( $recordList[$i]["zip_id"] );
						$status = $recordList[$i]["status"];
						$center_status = $recordList[$i]["dstatus"];
						$phone = $recordList[$i]["phone"];
						$category_name = $recordList[$i]["category_name"];
						
						$city = ''; $state = ''; $zip = '';
						
						if ($city_id > 0) { 		
							$isCity = $C->getTotal('city_id', $city_id ,'exact');
							if ($isCity > 0) { 
								$cnRec = $C->getSelectedFieldsRecordByField($selectedFields='city', $searchField='city_id', $city_id, 'exact');
								$city = $cnRec[0];
							}
						}
						if ($state_id > 0) { 
							$isState = $S->getTotal('state_id', $state_id ,'exact');
							if ($isState > 0) { 
								$sRec = $S->getSelectedFieldsRecordByField($selectedFields='state', $searchField='state_id', $state_id, 'exact');
								$state = $sRec[0];
							}
						}
						if ($zip_id > 0) { 
							$isZip = $Z->getTotal('zip_id', $zip_id ,'exact');
							if ($isZip > 0) { 
								$zRec = $Z->getSelectedFieldsRecordByField($selectedFields='zipcode', $searchField='zip_id', $zip_id, 'exact');
								$zip = $zRec[0];
							}
						}
						$fullAddress = ' - N/A - ';
						if (!empty($address_1))
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
							$phone = "- N/A -";
						
						$astatus = $M->getStatus($status);
						$cstatus = $M->getStatus($center_status);
							
						$date_updated = $recordList[$i]["date_updated"];
						$updated_by = $recordList[$i]["updated_by"];
						
						if (!empty($recordList[$i]['publication'])) { 
							$publication = $recordList[$i]['publication'];
							$pubyear = $recordList[$i]['year'];
							
							if (!empty($pubyear)) {
								$publication .= '<br />Year ' . $pubyear; 
							}
							
						}
						
						/*$chapter_name = "- N/A -";
						$cpRec = $CH->getSelectedFieldsRecordByField($selectedFields='chapter_name', $searchField='chapter_id', $CH->prepare_input($chapter_id_fk), 'exact');
						$chapter_name = $cpRec[0];*/
						
				?>
                <tr>
                  <td><?php echo $fullAddress; ?></td>
                  <?php if (!empty($publication_id)) { ?>
						<td><?php echo $publication; ?></td>
				  <?php } ?>
				  <td><?php echo $astatus; ?></td>
                  <td><?php echo $center_name; ?> </td> 
                  <td><?php echo $category_name; ?></td>
                  <td><?php echo $phone; ?></td>
                  <td align="center"><span id="aid" style="display:none;"><?php echo $id; ?></span>&nbsp;<span id="cstatus"><?php echo $cstatus; ?></span></td>
				  <td align="center"><?php echo $date_updated; if (!empty($updated_by)) { echo '<br />By: <b>'. $updated_by . '</b>'; } ?></td>
                  <td align="center"><span id="centerid" style="display:none;"><?php echo $centerid; ?></span>&nbsp;
                    <a title="Update Center Information" href="updateaddresscenter.php?id=<?php echo $id;?>" data-lightbox-type="ajax" class="purple"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" class="view" width="16" height="16" border="0" /></a>
                  </td>
                </tr>
                <?php 
                 } // end for 
                ?>
                
            </tbody>
            <tfoot>
                <tr>
                    <?php if (!empty($publication_id)) { ?>
						<th class="head1" width="15%">Address</td>
						<th class="head0" width="10%">Publication</td>
					<?php } else { ?>
						<th class="head0" width="25%">Address</td>
					<?php } ?>
                    <th class="head1" width="7%">Adr Status</td>
                    <th class="head0" width="22%">Center Name</td>
                    <th class="head1" width="15%">Category</td>
                    <th class="head0" width="8%" align="center">Phone</td>
                    <th class="head1" width="7%" align="center">Status</td>
                    <th class="head0" width="13%" align="center">Last Updated</td>
                    <th class="head1" width="3%" align="center">-</td>
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
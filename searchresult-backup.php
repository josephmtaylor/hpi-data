<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";

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



if (isset($_POST['fl']) && ($_POST['fl']== "search")) {

		$fl						=		$_POST['fl'];
		$first_name				=		$_POST['first_name'];
		$last_name				=		$_POST['last_name'];
		$city					=		$_POST['city'];
		$state					=		$_POST['state'];
		$zip					=		$_POST['zip'];
		
		$category_id_fk			=		$_POST['category_id_fk'];
		$specialty_id_fk		=		$_POST['specialty_id_fk'];
		$county_id_fk			=		$_POST['county_id_fk'];
		$email					=		$_POST['email'];
		$license_status	 	    = 		$_POST['license_status'];
		$npi			 	    = 		$_POST['npi'];
		
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

		if (!empty($city)) {

			array_push ($fieldName , 'city');
			array_push ($searchKeyword , $city);
		}

		if (!empty($state)) {

			array_push ($fieldName , 'state');
			array_push ($searchKeyword , $state);
		}

		if (!empty($zip)) {

			array_push ($fieldName , 'zip');
			array_push ($searchKeyword , $zip);
		}

		if (!empty($county_id_fk)) {

			array_push ($fieldName , 'county_id_fk');
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
		
		if (!empty($publication_id)) {

			array_push ($fieldName , 'pub_id');
			array_push ($searchKeyword , $publication_id);
		}


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

<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent">
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
    <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
      <colgroup>
        <col class="head0" width="20%" />
        <col class="head1" width="10%" />
        <col class="head0" width="15%" />
		<col class="head1" width="20%" />
        <col class="head0" width="15%" />
        <col class="head1" width="7%" />
		<col class="head0" width="6%" />
        <col class="head1" width="7%" />
      </colgroup>
      <tr>
        <td width="20%">Name</td>
        <td width="10%">Phone</td>
        <td width="15%">NPI</td>
		<td width="20%">Specialty</td>
		<td width="15%" align="center">City</td>
		<td width="7%" align="center">State</td>
		<td width="6%" align="center">Status</td>
        <td width="7%" align="center">Action</td>
      </tr>
    </table>
    <div class="sTableWrapper">
      <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
        <colgroup>
          <col class="con0" width="20%" />
          <col class="con1" width="10%" />
          <col class="con0" width="15%" />
		  <col class="con1" width="20%" />
          <col class="con0" width="15%" />
		  <col class="con1" width="7%" />
		  <col class="con0" width="6%" />
          <col class="con1" width="7%" />
        </colgroup>
        <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
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
								$specialty_id_fk = $recordList[$i]["specialty_id_fk"];
								
								$npi = $recordList[$i]["npi"];
								$city = $recordList[$i]["city"];
								$state = $recordList[$i]["state"];
								$status = $recordList[$i]["status"];
								$phone = $recordList[$i]["phone"];
								if (empty($phone))
									$phone = "- N/A -";
								
								$category_name = '- N/A -';  $specialty_name = '- N/A -';
								if (!empty($category_id_fk)) {
									$lrec = $CT->getRecordByField('cat_id', $category_id_fk,'exact');
									$category_name = $lrec['category_name'];
								}
								if (!empty($specialty_id_fk)) {
									$srec = $SP->getRecordByField('specialty_id', $specialty_id_fk,'exact');
									$specialty_name = $srec['specialty_name'];
								}
								
								if ($status == 'A')
									$status = '<b>Active</b>';
								else if ($status == 'I')
									$status = 'Inactive';
						
				?>
        <tr>
          <td width="20%"><?php echo $doctor_name; ?> </td> 
          <td width="10%"><?php echo $phone; ?> </td>
          <td width="15%"><?php echo $npi; ?></td>
		  <td width="15%"><?php echo $specialty_name; ?></td>
		  <td width="15%" align="center"><?php echo $city; ?></td>
		  <td width="7%" align="center"><?php echo $state; ?></td>
          <td width="6%" align="center"><?php echo $status; ?></td>
		  <td width="7%" align="center">
            <a title="Edit Doctor Information" target="_blank" href="newdoctor.php?fl=edit&amp;id=<?php echo $id . $suffix;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a>&nbsp;
			<a title="View Doctor Info" target="_blank" href="doctorpublication.php?id=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/search.png" alt="View" width="16" height="16" border="0" /></a>
          </td>
        </tr>
        <?php 
					} 
		?>
      </table>
    </div><!--sTableWrapper-->
	</form>
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
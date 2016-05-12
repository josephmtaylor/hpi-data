<?php
include_once "classes/session.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
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
$DA = new DoctorAddressClass;
		
		
	
	if (isset($_GET['m']) && ($_GET['m']== "del"))
	{
		$id = $_GET['delid'];
		if (isset($_GET['page']))
			$page = "&page=". $_GET['page'];
	
		/* delete Record - call function delete -  returns integer
		 * @param string $fieldName,  @param array $matchingValue
		 */
	
		$RecoredDeleted = $M->delete('doctor_id',$id);
		
		if ($RecoredDeleted == 1)
			header("Location: doctor.php?msg=dl".$page);
		if ($RecoredDeleted == 2)
			header("Location: doctor.php?msg=errd".$page);
		else  if ($RecoredDeleted == 0)
			header("Location: doctor.php?msg=err".$page);
			
		exit();	
	} 
	
	if (isset($_POST['action']) && ($_POST['action']== "cndeleted"))
	{
		$ArrayId = array();
		$ArrayId = $_POST['uid'];
		if (isset($_POST['page']))
			$page = "&page=". $_POST['page'];
			
		/* delete Record - call function deleteArray -  returns integer
		 * @param string $fieldName,  @param array $searchKey
		 */
	
		$RecoredDeleted =  $M->deleteArray('doctor_id',$ArrayId);
		
		if ($RecoredDeleted == 1)
			header("Location: doctor.php?msg=dlsl".$page);
		else 
			header("Location: doctor.php?msg=err".$page);
		
		exit();	
	} 
	
	if (isset($_POST['action']) && ($_POST['action']== "cnverified" or $_POST['action']== "cnunverified" ))
	{
		
		$action = $_POST['action'];
		$ArrayId = array();
		$ArrayId = $_POST['uid'];
		$suffix = $_POST['suffix'];
		if (isset($_POST['page']))
			$page = "&page=". $_POST['page'];
	
		if ($action == 'cnverified')
			$status = 'V';
		else if ($action == 'cnunverified')
			$status = 'UV';	
		
	
		$updateStatus = $M->changeDoctorRecordArray($ArrayId, $status);
			
		if ($updateStatus == 1)
			header("Location: doctor.php?msg=cst". $suffix . $page);
		else 
			header("Location: doctor.php?msg=err". $suffix . $page);
		
		exit();	
	} 
	
	
	
	
	
	
	$suffix = "";
	
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
		
	if (isset($_REQUEST['mode']) && ($_REQUEST['mode']== "search")) {
			$searchkey = $_REQUEST['searchkey'];
			
			$key = explode(" ", $searchkey);
			
			$searchField= array('first_name',  'last_name'); 
			$searchKeyword = array( $key[0] , $key[1]  );
			$searchStatus = '';
			$suffix .= "&mode=".$_REQUEST['mode']."&searchkey=". $searchkey;
			$orderByField ='';
			$orderByValue ='';
			$cid = 0;
			
					
	}
	else if (isset($_REQUEST['mode']) && ($_REQUEST['mode']== "status")) {
			
			$key = $_REQUEST['key'];
			$searchField= array('status'); 
			$searchKeyword= array($key);
			$suffix .= "&mode=".$_REQUEST['mode']."&key=". $key;
			$searchStatus='exact';
			$orderByField ='doctor_id';
			$orderByValue ='ASC';		
			
			if (isset($_GET['cid'])) {
				$cid = $_GET['cid'];
				$suffix .= '&cid='.$cid;
			}
	}
	/*else if (isset($_GET['cid'])) {
			$cid = $_GET['cid'];
			$suffix .= '&cid='.$cid;
			$searchField= array('category_id_fk'); 
			$searchKeyword= array($cid);
			$searchStatus='exact';
			$orderByField ='doctor_id';
			$orderByValue ='ASC';
	}
	else if (isset($_REQUEST['ctype'])) {
			
			$ctype = $_REQUEST['ctype'];
			$stat ='';
			if ($_REQUEST['mode']== "status") {
				$key1 = $_REQUEST['key'];
				$stat = "&key=". $key1;
			}

			$searchField= array('item_type_id'); 
			$searchKeyword= array($ctype);
			$suffix .= "&ctype=".$ctype.$stat;
			$searchStatus='exact';
			$orderByField ='id';
			$orderByValue ='ASC';		

			if (isset($_GET['cid'])) {
				$cid = $_GET['cid'];
				$suffix .= '&cid='.$cid;
			}
	}*/
	else {
			$searchField = ''; 
			$searchKeyword = '';
			$searchStatus='exact';
			$orderByField ='doctor_id';
			$orderByValue ='DESC';
			$offset = '';
			$suffix = '';
			$cid = 0;
	}

			/* get Total Records - call function getTotal -  @returns integer
			 * @param string $searchField,  string $searchKeyword
			 */

			$tCount = $M->getTotalArray($searchField , $searchKeyword, $searchStatus);

			if ($tCount > 0 ) 
			{
					$total_pages = ceil($tCount / LIMIT); //total number of pages
					$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB

					/* call function getTotal -  returns array
					 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
					 */

					$recordList = $M->getListArray($searchField, $searchKeyword, $searchStatus, $orderByField, $orderByValue, $offset, LIMIT);
					$totalLimit = $M->arrayCount($recordList); // call arrayCount - @param array, @return int count
					$linkBtn = $M->addButtons();
					$RecordsPaging = $M->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix, $linkBtn);
			}
			else {
					$totalLimit = 0;
					$errorText = "No Record Found..";
			}

			$tCountAll = $M->getTotalArray();
			
			$searchFieldArray = array('status');
			
			$searchKeywordArray = array('V');
			$tCountVerified = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');

			$searchKeywordArray = array('UV');
			$tCountUnverified = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');
			
			$searchKeywordArray = array('N');
			$tCountNew = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');
			
			$searchKeywordArray = array('P');
			$tCountPending = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');
			
			$searchKeywordArray = array('D');
			$tCountDone = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');

			$searchKeywordArray = array('E');
			$tCountError = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');



// Check if the error is present

if(isset($_GET['msg']))
{
	$error = 0;
	$msg = $_GET['msg']; 
	
	if ($msg == "update")
		$txtMessage = "Record Updated.";
	if ($msg == "dl")
		$txtMessage = "Record Deleted.";	
	if ($msg == "dlsl")
		$txtMessage = "Multiple Records Deleted.";		
	if ($msg == "err") {
		$error = 1;
		$txtMessage = "Error! Please try again.";
	}
	if ($msg == "errd") {
		$error = 1;
		$txtMessage = "Error! Record can not deleted.";
	}
	if ($msg == "errnf") {
		$error = 1;
		$txtMessage = "Record not found.";	
	}					
}
else
{
	$txtMessage = "";
}



include_once "header.php"; 

include_once "sidebar.php";
?>
<script language="javascript1.2">

function deleteItem(item_id,item_name, page)
{
    if(confirm('Are you sure to delete record : ' + item_name))
	{
		document.location.href="doctor.php?m=del&delid="+item_id+"&page="+page;
	}
}

function checkdeletion() {
	
	var x = document.getElementById("action").value;
	if( x == 'cndeleted') {
	
		if(confirm('Are you sure to delete selected records?')) {
			return true;
		}
		return false;
	}
		
}

</script>
<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent1">
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span>Doctors</span> </div>
  <!-- breadcrumbs -->
  
  <div class="left">
    <h1 class="pageTitle">Manage Doctors </h1>
    <a href="newdoctor.php?fl=new<?php echo $suffix; ?>" class="addNewButton">Add New Doctor </a>
	
	<ul class="submenu">
		<li class="current"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">All (<?php echo $tCountAll;?>)</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=V">Verified (<?php echo $tCountVerified;?>)</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=UV">Unverified (<?php echo $tCountUnverified;?>)</a></li>
        <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=N">New (<?php echo $tCountNew;?>)</a></li>
        <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=P">Pending (<?php echo $tCountPending;?>)</a></li>
        <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=D">Done (<?php echo $tCountDone;?>)</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=E">Error (<?php echo $tCountError;?>)</a></li>
		<li> <form id="fm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> <input name="searchkey" type="text" id="searchkey" value=""><input name="mode" type="hidden" value="search" /><button>Search</button></form></li>
    </ul>
	
	<br />
	<?php if ($totalLimit > 0) { ?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <?php echo  $RecordsPaging; ?>
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
  
    
    <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
      <colgroup>
        <col class="head0" width="2%" />
        <col class="head1" width="23%" />
        <col class="head0" width="21%" />
		<col class="head1" width="21%" />
        <col class="head0" width="12%" />
        <col class="head1" width="13%" />
		<col class="head0" width="8%" />
      </colgroup>
      <tr>
        <td align="center"><input name="checkbox" type="checkbox" class="checkall" /></td>
        <td>Name</td>
        <td>Specialty</td>
		<td>Address</td>
		<td align="center">Phone</td>
		<td align="center">Last Updated</td>
        <td align="center">Action</td>
      </tr>
    </table>
    <div class="sTableWrapper">
      <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
        <colgroup>
          <col class="con0" width="2%" />
          <col class="con1" width="23%" />
          <col class="con0" width="21%" />
		  <col class="con1" width="21%" />
          <col class="con0" width="12%" />
		  <col class="con1" width="13%" />
		  <col class="con0" width="8%" />
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
								
								$city = $recordList[$i]["city"];
								$state = $recordList[$i]["state"];
								$status = $recordList[$i]["status"];
								$phone = $recordList[$i]["phone"];
								if (empty($phone))
									$phone = "- N/A -";
									
								$category_name = 'N/A';  $specialty_name = 'N/A';
								if (!empty($category_id_fk)) {
									$lrec = $CT->getRecordByField('cat_id', $category_id_fk,'exact');
									$category_name = $lrec['category_name'];
								}
								if (!empty($specialty_id_fk)) {
									$srec = $SP->getRecordByField('specialty_id', $specialty_id_fk,'exact');
									$specialty_name = $srec['specialty_name'];
								}
								
								$status = $M->getStatusImage($status);
									
								$date_updated = $recordList[$i]["date_updated"];
								$updated_by = $recordList[$i]["updated_by"];
								$address = '';
								
								$daRec = $DA->getDoctorAddressRecord($fieldName='doctor_id', $id);
								/*foreach($daRec as $key => $val) {
									$address .= $val['address_1']. ', ' . $val['city']. ', ' . $val['state']. ', ' . $val['zipcode'] . '<br />';
								}*/
				?>
        <tr>
          <td align="center"><?php if ($userType == 'Admin') { ?>
              <input name="uid[]" type="checkbox" id="<?php echo $id;?>" value="<?php echo $id;?>" />
            <?php  } ?></td>
          <td><?php echo $doctor_name; ?> </td>
          <td><?php echo $specialty_name; ?></td>
          
		  <td align="center"><?php // echo $address; ?>
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
          <td><?php //echo $phone; ?>
          <select id="phone" name="phone" style="width:110px;">
				<?php
                    if (!empty($daRec) and count($daRec) > 0) { ?>
                <?php	
                    foreach($daRec as $key => $val) {
                        $aid = $val['id'];
                        $phone = $val['phone'];
						if (!empty($val['fax'])) {
							 $phone .= ' | &nbsp; Fax: ' .$val['fax'];
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
          <td align="center"><?php echo $date_updated; if (!empty($updated_by)) { echo '<br />By: <b>'. $updated_by . '</b>'; } ?></td>
		  <td align="center">
          	<a href="#"><?php echo $status; ?></a>&nbsp;&nbsp;
            <a title="Edit Doctor Information" href="newdoctor.php?fl=edit&amp;id=<?php echo $id . $suffix;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="16" height="16" border="0" /></a>&nbsp;
			<a title="View Doctor Info" href="doctorpublication.php?id=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/search.png" alt="View" width="16" height="16" border="0" /></a><?php /*?>&nbsp;<a title="Delete" href="javascript:deleteItem('<?php echo $id;?>','<?php echo $doctor_name;?>','<?php echo $page;?>');" class=""><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a><?php */?>
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
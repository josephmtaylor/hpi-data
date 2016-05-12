<?php
include_once "classes/session.class.php";
require_once "classes/customer.class.php";
require_once "classes/propertyusertype.class.php";
require_once "classes/featureduser.class.php";

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

$M = new CustomerClass;
$U = new PropertyUserTypeClass;
$FUsr = new FeaturedUserClass;
		
	if (isset($_REQUEST['m']) && ($_REQUEST['m']== "del"))
	{
		$id = $_REQUEST['delid'];
		
		/* delete Record - call function delete -  returns integer
		 * @param string $fieldName,  @param array $matchingValue
		 */
	
		$RecoredDeleted = $M->delete('id',$id);
		
		if ($RecoredDeleted == 1)
			header("Location: customer.php?msg=dl");
		if ($RecoredDeleted == 2)
			header("Location: customer.php?msg=errd");
		else  if ($RecoredDeleted == 0)
			header("Location: customer.php?msg=err");
			
		exit();	
	} 
	
	if (isset($_REQUEST['action']) && ($_REQUEST['action']== "delsel"))
	{
		$ArrayId = array();
		$ArrayId = $_REQUEST['uid'];
		
	
		/* delete Record - call function deleteArray -  returns integer
		 * @param string $fieldName,  @param array $searchKey
		 */
	
		$RecoredDeleted = $M->deleteArray('id',$ArrayId);
		
		if ($RecoredDeleted == 1)
			header("Location: customer.php?msg=dlsl");
		else 
			header("Location: customer.php?msg=err");
		
		exit();	
	} 
	
	if (isset($_POST['action']) && ($_POST['action']== "cnfeatured"))
	{
		$ArrayId = array();
		$ArrayId = $_POST['uid'];
	
		if (empty($ArrayId)) {
			header("Location: customer.php?msg=err");
			exit();
		}
		if (isset($_POST['page']))
			$page = "&page=". $_POST['page'];
			
		/* add Record to premium table  - call function createPremiumPropertyRecordArray -  returns integer
		 * @param string $fieldName,  @param array $searchKey
		 */
	
		$userAdded = $FUsr->createFeaturedUserRecordArray($ArrayId);
		
		if ($userAdded == 1)
			header("Location: customer.php?msg=cnf".$page);
		else if ($userAdded == 2)
			header("Location: customer.php?msg=cnfer".$page);
		
		exit();	
	} 
	if (isset($_REQUEST['action']) && ($_REQUEST['action']== "cnactive" or $_REQUEST['action']== "cninactive" ))
	{
	
		$action = $_REQUEST['action'];
		$ArrayId = array();
		$ArrayId = $_REQUEST['uid'];
		//$suffix = $_REQUEST['suffix'];
	
		if ($action == 'cnactive')
			$status = 'A';
		else if ($action == 'cninactive')
			$status = 'I';
		
		
		$updateStatus = $M->changeCustomerStatusArray($ArrayId, $status);
			
		if ($updateStatus == 1)
			header("Location: customer.php?msg=cst". $suffix);
		else 
			header("Location: customer.php?msg=err". $suffix);
		
		exit();	
	} 

	if (isset($_REQUEST['action']) && ($_REQUEST['action']== "cnverify" ))
	{
	
		$action = $_REQUEST['action'];
		$ArrayId = array();
		$ArrayId = $_REQUEST['uid'];
		//$suffix = $_REQUEST['suffix'];
	
		if ($action == 'cnverify')
			$status = 'Y';
		else if ($action == 'cnunverify')
			$status = 'N';
		
		
		$updateStatus = $M->changeCustomerVerificationStatusArray($ArrayId, $status);
			
		if ($updateStatus == 1)
			header("Location: customer.php?msg=vrf". $suffix);
		else 
			header("Location: customer.php?msg=err". $suffix);
		
		exit();	
	} 


	
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
		
	if (isset($_REQUEST['mode']) && ($_REQUEST['mode']== "search")) {
			$searchField = 'name';
			$searchKeyword = $_REQUEST['searchkey'];
			$suffix = "&mode=".$_REQUEST['mode']."&searchkey=". $searchKeyword;
			$orderByField ='';
			$orderByValue ='';			
	}
	else if (isset($_REQUEST['mode']) && ($_REQUEST['mode']== "status")) {
			$searchField = 'active';
			$searchKeyword = $_REQUEST['key'];
			$suffix = "&mode=".$_REQUEST['mode']."&key=". $searchKeyword;
			$orderByField ='';
			$orderByValue ='';			
	}
	else  {	
			$suffix = '';
			$searchField = '';
			$searchKeyword = '';
			$orderByField ='';
			$orderByValue ='';
	}

			/* get Total Records - call function getTotal -  @returns integer
			 * @param string $searchField,  string $searchKeyword
			 */
		 
			$tCount = $M->getTotal($searchField , $searchKeyword);
		
			if ($tCount > 0 ) 
			{
					$total_pages = ceil($tCount / LIMIT); //total number of pages
					$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB

					/* call function getTotal -  returns array
					 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
					 */
				
					$recordList = $M->getList($searchField, $searchKeyword, $orderByField, $orderByValue, $offset, LIMIT);
					$totalLimit = $M->arrayCount($recordList); // call arrayCount - @param array, @return int count
					$linkBtn = $M->addButtons();
					$RecordsPaging = $M->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix, $linkBtn);
			}
			else {
					$totalLimit = 0;
					$errorText = "No Record Found..";
			}
			
			$tCountAll = $M->getTotalArray();
			
			$searchFieldArray = array('active');
			$searchKeywordArray = array('Y');
			$tCountActive = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');
			
			$searchKeywordArray = array('N');
			$tCountInActive = $M->getTotalArray($searchFieldArray, $searchKeywordArray, 'exact');
			
			


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
	if ($msg == "cst")
		$txtMessage = "Status Changed For Multiple Records.";
	if ($msg == "vrf")
		$txtMessage = "Verification Status Changed For Multiple Records.";				
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

function deleteItem(item_id,item_name)
{
    if(confirm('Are you sure to delete record : ' + item_name))
	{
		document.location.href="customer.php?m=del&delid="+item_id;
	}
}


</script>
<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent">
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span>Customer</span> </div>
  <!-- breadcrumbs -->
  
  <div class="left">
    <h1 class="pageTitle">Manage Customer </h1>
    <a href="newcustomer.php" class="addNewButton">Add New Customer </a>
	
	<ul class="submenu">
		<li class="current"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">All (<?php echo $tCountAll;?>)</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=Y">Active (<?php echo $tCountActive;?>)</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?mode=status&key=N">Inactive (<?php echo $tCountInActive;?>)</a></li>
		<li> <form id="fm" action="" method="post"> <input name="searchkey" type="text" id="searchkey" value=""><input name="mode" type="hidden" value="search" /><button>Search</button></form></li>
    </ul>
	
	<br />
	<?php if ($totalLimit > 0) { ?>
	
	<form action="" method="post">
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
        <col class="head1" width="20%" />
        <col class="head0" width="20%" />
		<col class="head1" width="15%" />
        <col class="head0" width="10%" />
        <col class="head1" width="10%" />
        <col class="head0" width="6%" />
  		<col class="head1" width="7%" />
		<col class="head0" width="10%" />
      </colgroup>
      <tr>
        <td width="2%" align="center"><input name="checkbox" type="checkbox" class="checkall" /></td>
        <td width="20%">Name</td>
        <td width="20%">Email</td>
		<td width="15%">User Type</td>
		<td width="10%">Phone</td>
		<td width="10%">Mobile</td>
		<td width="6%">Status</td>
		<td width="7%" align="center">Verified</td>
        <td width="10%" align="center">Action</td>
      </tr>
    </table>
    <div class="sTableWrapper">
      <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
        <colgroup>
          <col class="con0" width="2%" />
          <col class="con1" width="20%" />
          <col class="con0" width="20%" />
		  <col class="con1" width="15%" />
          <col class="con0" width="10%" />
		  <col class="con1" width="10%" />
		  <col class="con0" width="6%" />
          <col class="con1" width="7%" />
		  <col class="con0" width="10%" />
        </colgroup>
        <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
								$id = $recordList[$i]['userid'];
								$customer_name = $recordList[$i]["name"];
								$country = $recordList[$i]["country"];
								$state = $recordList[$i]["state"];
								$city = $recordList[$i]["city"];
								$email = $recordList[$i]["email"];
								
								$location = $country . ", " . $state . ", " . $city;
								$location = substr($location,0,30);
								if (strlen($location) > 30)
									$location .= "..";
								
								$phone = $recordList[$i]["phone"];
								if (empty($phone))
									$phone = "-N/A-";
								$mobile = $recordList[$i]["mobile"];
								if (empty($phone))
									$mobile = "-N/A-";	
								$status = $recordList[$i]["active"];
								$verified = $recordList[$i]["verified"];
								
								$user_type_id_fk = $recordList[$i]["user_type_id_fk"];
								
								$urec = $U->getRecordByField('id', $user_type_id_fk,'exact');
								$user_type = $urec['user_type'];
						
				?>
        <tr>
          <td align="center" width="2%"><?php if ($userType == 'Admin') { ?>
              <input name="uid[]" type="checkbox" id="<?php echo $id?>" value="<?php echo $id?>" />
            <?php  } ?></td>
          <td width="20%"><?php echo $customer_name; ?></td>
          <td width="20%"><?php echo $email; ?></td>
		  <td width="15%"><?php echo $user_type; ?></td>
		  <td width="10%"><?php echo $phone; ?></td>
		  <td width="10%"><?php echo $mobile; ?></td>
		  <td width="6%" align="center"><?php echo $status; ?></td>
		  <td width="7%" align="center"><?php echo $verified; ?></td>
          <td width="10%" align="center"><?php if ($userType == 'Admin') { ?>
            <a  title="View Details" href="viewcustomer.php?id=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/search.png" alt="View" width="16" height="16" border="0" /></a>&nbsp;<a title="View Properties" href="properties.php?cid=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/tag.png" alt="View Properties" width="16" height="16" border="0" /></a>&nbsp;<a title="Edit" href="edit.php?fl=edit&amp;id=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="15" height="16" border="0" /></a>&nbsp;<a title="Delete" href="javascript:deleteItem('<?php echo $id;?>','<?php echo $customer_name;?>');" class=""><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a>
              <?php } ?>          </td>
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
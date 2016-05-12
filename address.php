<?php
include_once "classes/session.class.php";
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

if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} 

$AD = new AddressClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
		
	if (isset($_REQUEST['m']) && ($_REQUEST['m']== "del"))
	{
		$id = $_REQUEST['delid'];
		
		/* delete Record - call function delete -  returns integer
		 * @param string $fieldName,  @param array $matchingValue
		 */
	
		$RecoredDeleted = $AD->delete('id',$id);
		
		if ($RecoredDeleted == 1)
			header("Location: address.php?msg=dl");
		if ($RecoredDeleted == 2)
			header("Location: address.php?msg=errd");
		else  if ($RecoredDeleted == 0)
			header("Location: address.php?msg=err");
			
		exit();	
	} 
	
	if (isset($_REQUEST['action']) && ($_REQUEST['action']== "delsel"))
	{
		$ArrayId = array();
		$ArrayId = $_REQUEST['uid'];
	
		/* delete Record - call function deleteArray -  returns integer
		 * @param string $fieldName,  @param array $searchKey
		 */
		  if (count($ArrayId) > 0) { 
			$RecoredDeleted = $AD->deleteArray('id',$ArrayId);
			
			if ($RecoredDeleted == 1)
				header("Location: address.php?msg=dlsl");
			else 
				header("Location: address.php?msg=err");
		  }
		  else {
		  		header("Location: address.php?msg=err");
		  } 	
		
				exit();	
	} 


	
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
		
	if (isset($_REQUEST['mode']) && ($_REQUEST['mode']== "search")) {
			$searchField = 'address_1';
			$searchKeyword = $_REQUEST['searchkey'];
			$suffix = "&mode=".$_REQUEST['mode']."&searchkey=". $searchKeyword;
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
		 
			$tCount = $AD->getTotal($searchField , $searchKeyword);
		
			if ($tCount > 0 ) 
			{
					$total_pages = ceil($tCount / LIMIT); //total number of pages
					$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB

					/* call function getTotal -  returns array
					 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
					 */
				
					$recordList = $AD->getList($searchField, $searchKeyword, $orderByField, $orderByValue, $offset, LIMIT);
					$totalLimit = $AD->arrayCount($recordList); // call arrayCount - @param array, @return int count
					$RecordsPaging = $AD->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix);
			}
			else {
					$totalLimit = 0;
					$errorText = "No Record Found..";
			}
			


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

function deleteItem(item_id,item_name)
{
    if(confirm('Are you sure to delete record : ' + item_name))
	{
		document.location.href="address.php?m=del&delid="+item_id;
	}
}

</script>
<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent1">
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span>Address</span> </div>
  <!-- breadcrumbs -->
  
  <div class="left">
    <h1 class="pageTitle">Manage Addresses </h1>
    <a href="newaddress.php" class="addNewButton">Add New Address </a>
	
	<?php if ($totalLimit > 0) { ?>
	
	<ul class="submenu">
	  <li class="current"><a href="">All (<?php echo $tCount;?>)</a> </li>
      <li> <form id="fm" action="" method="post"> <input name="searchkey" type="text" id="searchkey" value=""><input name="mode" type="hidden" value="search" /><button>Search</button></form></li>
    </ul>
	
	<br />

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
        <col class="head1" width="38%" />
        <col class="head0" width="20%" />
        <col class="head1" width="15%" />
        <col class="head0" width="15%" />
        <col class="head1" width="10%" />
      </colgroup>
      <tr>
        <td align="center"><input name="checkbox" type="checkbox" class="checkall" /></td>
        <td>Address </td>
        <td>City </td>
        <td>State </td>
        <td>Zip </td>
        <td align="center">Action</td>
      </tr>
    </table>
    <div class="sTableWrapper">
      <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
        <colgroup>
          <col class="con0" width="2%" />
          <col class="con1" width="38%" />
          <col class="con0" width="20%" />
          <col class="con1" width="15%" />
          <col class="con0" width="15%" />
          <col class="con1" width="10%" />
        </colgroup>
        <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
								$id = $recordList[$i]['id'];
								$address_1 = $AD->prepare_output( $recordList[$i]["address_1"] );
								$address_2 = $AD->prepare_output( $recordList[$i]["address_2"] );
								$city_id = $AD->prepare_output( $recordList[$i]["city_id"] );
								$state_id = $AD->prepare_output( $recordList[$i]["state_id"] );
								$zip_id = $AD->prepare_output( $recordList[$i]["zip_id"] );
								$status = $recordList[$i]["status"];
								$city = ''; $state = ''; $zip = '';
								
								$isCity = $C->getTotal('city_id', $city_id ,'exact');
								if ($isCity > 0) { 
									$cnRec = $C->getSelectedFieldsRecordByField($selectedFields='city', $searchField='city_id', $city_id, 'exact');
									$city = $cnRec[0];
								}
								$isState = $S->getTotal('state_id', $state_id ,'exact');
								if ($isState > 0) { 
									$sRec = $S->getSelectedFieldsRecordByField($selectedFields='state', $searchField='state_id', $state_id, 'exact');
									$state = $sRec[0];
								}
								$isZip = $Z->getTotal('zip_id', $zip_id ,'exact');
								if ($isZip > 0) { 
									$zRec = $Z->getSelectedFieldsRecordByField($selectedFields='zipcode', $searchField='zip_id', $zip_id, 'exact');
									$zip = $zRec[0];
								}
								
								//$status = $AD->getStatusImage($status);
				?>
        <tr>
          <td align="center"><?php if ($userType == 'Admin') { ?>
              <input name="uid[]" type="checkbox" id="<?php echo $id?>" value="<?php echo $id?>" />
            <?php  } ?></td>
          <td><?php echo $address_1 . ' ' . $address_2; ?></td>
          <td><?php echo $city; ?></td>
          <td><?php echo $state; ?></td>
          <td><?php echo $zip; ?></td>
          <td align="center"><?php if ($userType == 'Admin') { ?>
              <a href="#"><?php echo $status; ?></a>&nbsp;&nbsp;<a href="newaddress.php?fl=edit&amp;id=<?php echo $id;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="15" height="16" border="0" /></a> &nbsp; <a href="javascript:deleteItem('<?php echo $id;?>','<?php echo $address_1;?>');" class=""><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a>
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
<?php
include_once "classes/session.class.php";
require_once "classes/chapter.class.php";

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

$CH = new ChapterClass;

		
	if (isset($_REQUEST['m']) && ($_REQUEST['m']== "del"))
	{
		$chapter_id = $_REQUEST['delid'];
		
		/* delete Record - call function delete -  returns integer
		 * @param string $fieldName,  @param array $matchingValue
		 */
	
		$RecoredDeleted = $CH->delete('chapter_id',$chapter_id);
		
		if ($RecoredDeleted == 1)
			header("Location: chapter.php?msg=dl");
		if ($RecoredDeleted == 2)
			header("Location: chapter.php?msg=errd");
		else  if ($RecoredDeleted == 0)
			header("Location: chapter.php?msg=err");
			
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
			$RecoredDeleted = $CH->deleteArray('chapter_id',$ArrayId);
			
			if ($RecoredDeleted == 1)
				header("Location: chapter.php?msg=dlsl");
			else 
				header("Location: chapter.php?msg=err");
		  }
		  else {
		  		header("Location: chapter.php?msg=err");
		  } 	
		
				exit();	
	} 


	
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
		
	if (isset($_REQUEST['mode']) && ($_REQUEST['mode']== "search")) {
			$searchField = 'chapter_name';
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
		 
			$tCount = $CH->getTotal($searchField , $searchKeyword);
		
			if ($tCount > 0 ) 
			{
					$total_pages = ceil($tCount / LIMIT); //total number of pages
					$offset = ($page - 1) * LIMIT; //starting number for displaying results out of DB

					/* call function getTotal -  returns array
					 * @param string $searchField , string $searchKeyword, string $orderByField , string $orderByValue , int $offset , int LIMIT
					 */
				
					$recordList = $CH->getList($searchField, $searchKeyword, $orderByField, $orderByValue, $offset, LIMIT);
					$totalLimit = $CH->arrayCount($recordList); // call arrayCount - @param array, @return int count
					$RecordsPaging = $CH->recordsetWithPagingWithSuffix($tCount, $total_pages, $page, $suffix);
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
		document.location.href="chapter.php?m=del&delid="+item_id;
	}
}

</script>
<script type="text/javascript" src="js/custom/table.js"></script>
<div class="maincontent1">
  <div class="breadcrumbs"> <a href="index.php">Dashboard</a> <span>Chapters</span> </div>
  <!-- breadcrumbs -->
  
  <div class="left">
    <h1 class="pageTitle">Manage Chapters </h1>
    <a href="newchapter.php" class="addNewButton">Add New Chapter </a>
	
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
        <col class="head1" width="88%" />
        <col class="head0" width="10%" />
      </colgroup>
      <tr>
        <td width="2%" align="center"><input name="checkbox" type="checkbox" class="checkall" /></td>
        <td width="88%">Chapter </td>
        <td width="10%" align="center">Action</td>
      </tr>
    </table>
    <div class="sTableWrapper">
      <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
        <colgroup>
          <col class="con0" width="2%" />
          <col class="con1" width="88%" />
          <col class="con0" width="10%" />
        </colgroup>
        <?php
					for($i=0; $i < $totalLimit ; $i++)
					{
								$chapter_id = $recordList[$i]['chapter_id'];
								$chapter_name = $recordList[$i]["chapter_name"];
						
				?>
        <tr>
          <td align="center" width="2%"><?php if ($userType == 'Admin') { ?>
              <input name="uid[]" type="checkbox" id="<?php echo $chapter_id?>" value="<?php echo $chapter_id?>" />
            <?php  } ?></td>
          <td width="88%"><?php echo $chapter_name; ?></td>
          <td width="10%" align="center"><?php if ($userType == 'Admin') { ?>
              <a href="newchapter.php?fl=edit&amp;id=<?php echo $chapter_id;?>"><img style="vertical-align:middle" src="images/icons/small/black/edit.png" alt="Edit" width="15" height="16" border="0" /></a> &nbsp; <a href="javascript:deleteItem('<?php echo $chapter_id;?>','<?php echo $chapter_name;?>');" class=""><img style="vertical-align:middle" src="images/icons/small/black/close.png" alt="Delete" width="16" height="16" border="0" /></a>
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
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

$M = new ChapterClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$chapter_id = $_POST["chapter_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$chapter_id";
		
		
		$chapter_name 	= $_POST['chapter_name'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isChapterRegistered = $M->getTotal('chapter_name', $chapter_name, 'exact');
		//exit();	
			if ($isChapterRegistered >= 1) {
				header("Location: newchapter.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "chapter_id" => $chapter_id, 
									"chapter_name" => $chapter_name, 
								);
		
		$editChapter = $M->editChapter($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editChapter == 1)	 { 		
			header("Location: chapter.php?msg=update");
			exit();
		}
		else {
			header("Location: newchapter.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$chapter_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($chapter_id))
				{
					header("Location: chapter.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($chapter_id);
				$data  = $M->getRecordByField('chapter_id', $chapter_id);
				
				
				if (!empty($data))
				{	
					$chapter_id 			=  	$data["chapter_id"];
					$chapter_name		=	$data['chapter_name'];
				}
				
		
}
else {
					$fl 			  =  	"add";
					$chapter_id 			  =  	"";
					$chapter_name 		  	  =		"";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Chapter already exist.";						
					

}
else
{
	$txtMessage = "";
}


include_once "header.php"; 

include_once "sidebar.php";
?>

<script type="text/javascript" src="js/custom/table.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#form").validate({
		rules: {
			chapter_name: "required",
		},
		messages: {
			chapter_name: "Please enter Chapter",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="chapter.php">Chapter</a>
		<span>Update Chapter </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Chapter</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Chapter</legend>
                    
                    <p>
                    	<label for="type">Chapter</label>
                        <input name="chapter_name" type="text" class="sf"  id="chapter_name" value="<?php echo $chapter_name?>" />
						 <small>e.g. Eastmonte Civic Center</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="chapter_id" value="<?php echo $chapter_id; ?>" />
                            <input type="hidden" name="fl" value="<?php echo $fl; ?>" />
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
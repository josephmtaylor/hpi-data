<?php
include_once "classes/session.class.php";
require_once "classes/category.class.php";

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

$M = new CategoryClass;

	
	$cntPType = $M->getTotal();
	$pTypeRec = $M->getList();

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$cat_id = $_POST["cat_id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$cat_id";
		
		
		$category_name 	= $_POST['category_name'];
			
		if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$isCategoryRegistered = $M->getTotal('category_name', $category_name, 'exact');
		//exit();	
			if ($isCategoryRegistered >= 1) {
				header("Location: newcategory.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}
		
		$sessionValues = array (    "fl" => $fl,
								    "cat_id" => $cat_id, 
									"category_name" => $category_name, 
								);
		
		$editCategory = $M->editCategory($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editCategory == 1)	 { 		
			header("Location: category.php?msg=update");
			exit();
		}
		else {
			header("Location: newcategory.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$cat_id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($cat_id))
				{
					header("Location: category.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($cat_id);
				$data  = $M->getRecordByField('cat_id', $cat_id);
				
				
				if (!empty($data))
				{	
					$cat_id 			=  	$data["cat_id"];
					$category_name		=	$data['category_name'];
				}
				
		
}
else {
					$fl 			  =  	"add";
					$cat_id 			  =  	"";
					$category_name 		  	  =		"";
					
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Category already exist.";						
					

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
			category_name: "required",
		},
		messages: {
			category_name: "Please enter Category",
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="category.php">Category</a>
		<span>Update Category </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Category</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Category</legend>
                    
                    <p>
                    	<label for="type">Category</label>
                        <input name="category_name" type="text" class="sf"  id="category_name" value="<?php echo $category_name?>" />
						 <small>e.g. Pathologist, Opthologist etc</small>
						<?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
						<input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" />
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
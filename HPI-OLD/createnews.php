<?php
include_once "classes/session.class.php";
require_once "classes/news.class.php";

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

$M = new NewsClass;



if (isset($_POST["fl"]))
{
		//print_r($_POST);exit();
		$fl =	$_POST["fl"];
		$id = $_POST["id"];
		
		$suffix = '';
		if ($fl == "edit")
			$suffix = "&id=$id";
		
		
		
		/* -------- upload new photo ---------------*/
		
		
		$MAX_FILE_SIZE = $_POST['MAX_FILE_SIZE'];
		
		if($_FILES['imgfile']['size'] > 0 && $_FILES['imgfile']['error'] == 0)
		{
				if (!empty($carId)) {
					$carId = $_POST['carId'];
				}
				else {
					$carId = 0;
				}
		
				$fileName = $_FILES['imgfile']['name'];
				$tmpName  = $_FILES['imgfile']['tmp_name'];
				$fileSize = $_FILES['imgfile']['size'];
				$fileType = $_FILES['imgfile']['type'];
				$file_ext = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
			
				if ($fileSize > $MAX_FILE_SIZE ) {
					$imageText = 'The file size is greater than 2 MB. Please upload image of smaller size.';
				}
				else {
						
						 // $photoType = $_POST['photoType'];
						  $photoId = 0;
						  $srNo = 'news';
						  
						  //$carId = $_POST['carId'];
						  
						  $propId =  substr(md5(rand()), 0, 5);
						  $srRnd =   substr( $srNo.rand(), 0, 4); 
							
						  $propImage_pt6 = 'pt6_'.$propId. ".". $file_ext ;
						  
						  $propImage_original = 'original_'.$propId.'_'.$srRnd. ".". $file_ext ;
						  $propImage_pt6 = 'pt6_'.$propId.'_'.$srRnd. ".". $file_ext ;
						  $propImage_pt0 = 'pt0_'.$propId.'_'.$srRnd. ".".$file_ext ;
						  
					
						  include('classes/simpleimage.class.php');
						  $image = new SimpleImage();
						  $image->load($_FILES['imgfile']['tmp_name']);
						  
						  $imageWidth =	 $image->getWidth();
						  $imageHeight=	 $image->getHeight();
						  
						 
						  /* -------------------------- Create Large Image -------------------------- */
						 
						  $tempWidth = 660;
						  $tempHeight = 400;
						 
						  if ($imageWidth >= $tempWidth and $imageHeight >= $tempHeight) {
							  $image->resizeToHeight($tempHeight , 'pt6' , $isStamp = 1 );
						  }
						  else if ($imageWidth <= $tempWidth and $imageHeight <= $tempHeight ) {
							  $image->resizeToHeight($imageHeight , 'pt6' , $isStamp = 1);
						  }	
						  else if ($imageWidth < $tempWidth and $imageHeight >= $tempHeight ) {
							  $image->resizeToHeight($tempHeight , 'pt6' , $isStamp = 1);
						  }
						  else if ($imageWidth > $tempWidth and $imageHeight <= $tempHeight ) {
							  $image->resizeToWidth($tempWidth , 'pt6' , $isStamp = 1);
						  }	  
							 
						  $image->save('../images/news/'.$propImage_pt6);
							
						  /* -------------------------- Create Thumb Image -------------------------- */
						  
						  $tempWidth = 220;
						  $tempHeight = 134;
						 
						  if ($imageWidth >= $tempWidth and $imageHeight >= $tempHeight) {
							  $image->resizeToHeight($tempHeight , 'pt0' );
						  }
						  else if ($imageWidth <= $tempWidth and $imageHeight <= $tempHeight ) {
							  $image->resizeToHeight($imageHeight , 'pt0' );
						  }	
						  else if ($imageWidth < $tempWidth and $imageHeight >= $tempHeight ) {
							  $image->resizeToHeight($tempHeight , 'pt0' );
						  }
						  else if ($imageWidth > $tempWidth and $imageHeight <= $tempHeight ) {
							  $image->resizeToWidth($tempWidth , 'pt0' );
						  }
						  $image->save('../images/news/'.$propImage_pt0);
						
							
				}	
		
	
		}
		else {
				$propImage_pt6 = "";
				$propImage_pt0 = "";
		}
		
		
		
		/*--------------- end photo upload ---------*/
		
		
		$news_title			=	$_POST['news_title'];
		$description		=	$_POST['description'];
		$status				=	$_POST['status'];
		
		$sessionValues = array (    "fl" => $fl,
								    "id" => $id, 
									"title" => $news_title,
									"description" => $description,
									"status"	=> $status,
									'pt6' => $propImage_pt6, 'pt0' => $propImage_pt0,
								);
		
		$editPackages = $M->editNews($sessionValues);
		empty($sessionValues);
	//exit();
	
		if ($editPackages == 1)	 { 		
			header("Location: news.php?msg=update");
			exit();
		}
		else {
			header("Location: createnews.php?fl=$fl&msg=err" . $suffix );
			exit();
		}
		
		

}


	
if (isset($_GET["id"]))
{

		
		$id =	$_GET["id"];
		$fl =	$_GET["fl"];

				if($fl == "edit" && empty($id))
				{
					header("Location: news.php?msg=err");
					exit();
				}
		
				//$data = $M->getUserById($id);
				$data  = $M->getRecordByField('id', $id);
				
				
				if (!empty($data))
				{	
					$id 		 	=  	$data["id"];
					$news_title		=	$data['title'];
					$description 	= 	$data['description'];
					$status			=	$data['status'];
				}
				
		
}
else {
					$fl				=  	"add";
					$id 			=  	"";
					$news_title 	=	"";
					$description 	=	"";
					$status = 'Y';
}


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Property feature already exist.";						
					

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
			news_title: "required",
			description: "required"
		},
		messages: {
			news_title: "Please enter news title",
			description: "Please enter description"
		
		}
	});

});	
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="news.php">Latest News</a>
		<span>Create News</span>
    </div>
	
	<!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Latest News </h1>
    	
        <br />
        
		<form id="form" action="" method="post" enctype="multipart/form-data">
        
        	<div class="form_default">
                <fieldset>
                    <legend>News</legend>
                    
                    <p>
                    	<label for="news_title">News Title </label>
                        <input name="news_title" type="text" class="sf"  id="news_title" value="<?php echo $news_title?>" />
						<?php  if (!empty($txtMessage)) {  ?>
						<label generated="true" class="error" style=""><?= $txtMessage; ?></label>
						<?php } ?>
                    </p>
                   
				    <p>
                    	<label for="description">Description</label>
						
								<textarea name="description" id="description" cols="60" rows="5"><?php echo $description; ?></textarea> 
						
				    </p>
					
					<?php /*?><p>
                    	<label for="imgfile">Select Image</label>
                        <input name="imgfile" type="file" class="sf" id="imgfile" />
				    </p><?php */?>
					
				    <p>
                    	<label for="status">Status </label>
                        <select id="status" name="status" style="width:250px;">
							<option value="A" <?php if ($status == "A") { ?> selected="selected" <?php  } ?> >Active</option>
							<option value="I" <?php if ($status == "I") { ?> selected="selected" <?php  } ?> >InActive</option>
						</select>
								
                    </p>
				   
                    <p>
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<input type="hidden" name="fl" value="<?php echo $fl; ?>" />
						<input name="mode" type="hidden" value="ImageUpload" />
						<input name="MAX_FILE_SIZE" value="2097152" type="hidden">
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
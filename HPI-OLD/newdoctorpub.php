<?php
include_once "classes/session.class.php";
require_once "classes/publication.class.php";
require_once "classes/doctorpublication.class.php";

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

$M = new PublicationClass;
$P = new DoctorPublicationClass;

$cyear = date('Y');

if (isset($_POST["fl"]))
{
		$fl =	$_POST["fl"];
		$doctor_id = $_POST["doctor_id"];
		$pub_id = $_POST["pub_id"];
		$year = $_POST["year"];
		$cyear = $year;
		
		$suffix = '';
		$suffix = "&id=".$doctor_id;
		$suffix1 = "&did=".$doctor_id;
		/*if ($fl !="edit") {		
			
			// call getTotal function with parameters searchField, searchKey  === returns count
			
			$fieldArray = array ( 'doctor_id', 'pub_id' , 'year');
			$keyArray = array ($doctor_id, $pub_id, $year);
			
			$isRecordExists = $P->getTotalArray($fieldArray, $keyArray, $searchStatus='exact');
				
		
		//exit();	
			if ($isRecordExists >= 1) {
				header("Location: newdoctorpub.php?fl=$fl&msg=errd" . $suffix);
				exit();
			}
			
		}*/
		
		$sessionValues = array (    "fl" => $fl,
								    "pub_id" => $pub_id, 
									"doctor_id" => $doctor_id, 
									"year" => $year
								);
		
		$editPublication = $P->editDoctorPublication($sessionValues);
		empty($sessionValues);
	
	
		if ($editPublication == 1)	 { 		
			header("Location: doctorpublication.php?msg=update" . $suffix);
			exit();
		}
		else {
			header("Location: newdoctorpub.php?fl=$fl&msg=errd" . $suffix1);
			exit();
		}
		
		

}


	
if (isset($_GET["did"]))
{
		$doctor_id =	$_GET["did"];
		$fl =	$_GET["fl"];
		$pub_id = "";
		$year 	= "";
					
		if($fl == "add" && empty($doctor_id))
		{
			header("Location: publication.php?msg=err");
			exit();
		}
}

$cntPubTotal = $M->getTotal();
$pubRec = $M->getList();


// Check if the error is present

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errd")
		$txtMessage = "Error! Publication already exist.";						
					

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
			pub_id: "required",
			year:"required"
		},
		messages: {
			pub_id: "Please select Publication",
			year: "Please select Year"
		}
	});

});	

function redirect(docid) {
	window.location = "doctorpublication.php?id="+docid;
}
</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="doctor.php">Doctors</a>
		<span>Add Publication </span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Manage Doctors</h1>
    	
        <br />
        
		<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Add Publication</legend>
                    
                    
                    <p>
                    	<label for="type">Publication </label>
                    	 <select id="pub_id" name="pub_id" style="width:250px;">
						    <?php
								if ($cntPubTotal > 0) { ?>
								<option value="">- Select Publication -</option>
							<?php 	
								 for ($i=0; $i < $cntPubTotal; $i++) { 
									$pub_id = $pubRec[$i]['pub_id'];
									$publication = $pubRec[$i]['publication'];
							?>	
								<option value="<?php echo $pub_id; ?>"><?php echo $publication; ?></option>
							<?php 	 } 
								}
								else {
							?>	
								<option value="">- N/A -</option>
							<?php } ?>
					  </select>
                      <?php  if (!empty($txtMessage)) {  ?>
						<label for="type" generated="true" class="error" style=""><?php echo  $txtMessage; ?></label>
						<?php } ?>
					</p>
                    
                    <p>
                    	<label for="year">Year </label>
                        <select id="year" name="year" style="width:250px;">
						    	<option value="">- Select Year -</option>
							<?php 	
								 for ($year=date('Y')-10; $year < date('Y')+10; $year++) {  
							?>	
								<option value="<?php echo $year; ?>" <?php if ($year == $cyear) { ?> selected="selected" <?php } ?>><?php echo $year; ?></option>
							<?php 	 }  ?>	
							
					  </select>
						
                    </p>
                    
                   <p>
						<input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>" />
                        <input type="hidden" name="fl" value="<?php echo $fl; ?>" />
                    	<button>Submit</button>
                        <a href="#" onclick="javascript: redirect('<?php echo $doctor_id; ?>');" class="iconlink2"> <span> &nbsp; Cancel &nbsp;</span></a>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
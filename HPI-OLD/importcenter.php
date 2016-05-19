<?php
include_once "classes/session.class.php";
require_once "classes/center.class.php";
require_once "classes/specialty.class.php";
require_once "classes/category.class.php";
require_once "classes/chapter.class.php";
require_once "classes/county.class.php";
require_once "classes/simplexlsx.class.php";

require_once "classes/hrop.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/address.class.php";
require_once "classes/centeraddress.class.php";
require_once "classes/centerhrop.class.php";
require_once "classes/centerpublication.class.php";
require_once "classes/publication.class.php";

$session = new SessionClass;

if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

$userType = $session->getUserLevel();
$loggedUser = $session->getUser();

/*if ($userType != 'Admin') { 
	header("Location: index.php?msg=err");
	exit();
} */

$M = new CenterClass;
$SP = new SpecialtyClass;
$CT = new CategoryClass;
$CH = new ChapterClass;
$CN = new CountyClass;
$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$DA = new CenterAddressClass;
$HR = new HropClass;
$CHR = new CenterHropClass;
$CP = new CenterPublicationClass;
$P = new PublicationClass;

if (isset($_POST["fl"]))
{
		
		set_time_limit(0);
		//ini_set('display_errors','On');
		ini_set('max_execution_time', '10800');
		ini_set('upload_max_filesize', '50M');

		$fl = $_POST["fl"];

		if($_FILES['file']['size'] > 0 && $_FILES['file']['error'] == 0)
		{
				$file = $_FILES['file']['tmp_name'];

				$fileName = $_FILES['file']['name'];
				$tmpName  = $_FILES['file']['tmp_name'];
				$fileSize = $_FILES['file']['size'];
				$fileType = $_FILES['file']['type'];
				$file_ext = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

				if ($file_ext == 'xls' or $file_ext == 'xlsx') {

				if ($file_ext == 'xlsx') { 	
					$xlsx = new SimpleXLSX($file);
					$records =  $xlsx->rows() ;
				}
				/*else if ($file_ext == 'xls') { 
					$data = new Spreadsheet_Excel_Reader($file);
					$recordsnew = $data->sheets[0]['cells']; 
					$records = array_slice($recordsnew, 1);
				}*/

					//echo '<pre> count : ' . count($records) ; print_r($records); exit();

					if (!isset($records) or $records == '') {
						header("Location: importcenter.php?msg=erremt");
						exit();
					}


					$total = 0; $isFirst = true; $tcount = 0; $hours_of_operation = ''; $wrongfile = 1;

					if (count($records) > 0) { 

						$tcount = (count($records[0]) - 1);

						foreach($records as $records1) {


							if ($file_ext == 'xlsx') { 	
									if ($isFirst) {

										foreach ($records1 as $v) {
											if (stripos(strtolower($v), 'chapter') !== false) {
												$wrongfile = 0;
											}
											if (stripos(strtolower($v), 'hours of operation') !== false) {
												$wrongfile = 0;
											}
										}

										if ($wrongfile){
											header("Location: importcenter.php?msg=errwf");
											exit();
										}

										$isFirst = false;
										continue;
									}  

									$id					=		0;
									$specialty  		=		$records1[0];
									$category	  		=		$records1[1];
									$npi		  	 	=		$records1[2];
									$chapter	  		=		$records1[3];
									$name		  	 	=		$records1[4];
									$address_1	  	  	=		$records1[5];
									$address_2	  	  	=		$records1[6];
									$city		  	  	=		$records1[7];
									$state		  	  	=		$records1[8];
									$zip		  	  	=		$records1[9];
									$county		  	  	=		$records1[10];
									$phone		  	  	=		$records1[11];
									$fax		  	  	=		$records1[12];
									$website	  	  	=		$records1[13];
									$hours_of_operation =		$records1[14];
									$admission	  	  	=		$records1[15];
									$description  	  	=		$records1[16];
									$notes		  	  	=		$records1[17];
									$publication	  	=		$records1[18];
									$main_office	  	=		$records1[19];
									
							}


							if (empty($category) and empty($name)) {
								continue;
							}

							$status = 'N'; $county_id_fk = 0; $city_id = 0; $state_id = 0; $zip_id = 0; 
							if (isset($_POST['preverified'])) {
								$status = 'V';
							}
														
							if ($main_office == 'T' or $main_office == 'TRUE' or $main_office == 1 )
								$main_office = 1;
							else
								$main_office = 0;

							if (empty($address_1)) {
								$notes .= '
Error: Address field is Empty';
								$status = 'E';
							}

							if (strlen($phone) > 12) {
								$notes .= '
Error: Phone length ('.strlen($phone).') 
Original Phone Text: ' . $phone;
								//$phone =  '';
								$status = 'E';
							}

							$specialty_id_fk = 0;
							if (!empty($specialty)) { 
								$isSpecialtyExist = $SP->getTotal('specialty_name', $SP->prepare_input($specialty), 'exact');
								if ($isSpecialtyExist == 0) { 
									$cntValues = array( "fl" => "add", "specialty_id" => "",  "specialty_name" => $specialty );
									$createSpecialty = $SP->editSpecialty($cntValues);
								}	
								$spRec = $SP->getSelectedFieldsRecordByField($selectedFields='specialty_id', $searchField='specialty_name', $SP->prepare_input($specialty), 'exact');
								$specialty_id_fk = $spRec[0];
							}

							$category_id_fk = 0;
							if (!empty($category)) { 
								$isCatExist = $CT->getTotal('category_name', $CT->prepare_input($category), 'exact');
								if ($isCatExist == 0) { 
									$ctValues = array( "fl" => "add", "cat_id" => 0,  "category_name" => $category );
									$createCat = $CT->editCategory($ctValues);
								}
								$ctRec = $CT->getSelectedFieldsRecordByField($selectedFields='cat_id', $searchField='category_name', $CT->prepare_input($category), 'exact');
								$category_id_fk = (int) $ctRec[0];
							}

							$chapter_id_fk = 0;
							if (!empty($chapter)) { 
								$iscpExist = $CH->getTotal('chapter_name', $CH->prepare_input($chapter), 'exact');
								if ($iscpExist == 0) { 
									$cpValues = array( "fl" => "add", "chapter_id" => 0,  "chapter_name" => $chapter , "description" => "" );
									$createCap = $CH->editChapter($cpValues);
								}
								$cpRec = $CH->getSelectedFieldsRecordByField($selectedFields='chapter_id', $searchField='chapter_name', $CH->prepare_input($chapter), 'exact');
								$chapter_id_fk = (int) $cpRec[0];
							}

							if (!empty($county)) { 
								$isCountyExist = $CN->getTotal('county', $CN->prepare_input($county) ,'exact');
								if ($isCountyExist == 0) { 
									$cntValues = array( "fl" => "add", "county_id" => 0,  "county" => $county );
									$createCounty = $CN->editCounty($cntValues);
								}	
									$cnRec = $CN->getSelectedFieldsRecordByField($selectedFields='county_id', $searchField='county', $CN->prepare_input($county), 'exact');
									$county_id_fk = $cnRec[0];
							}

							if (!empty($publication) and trim($publication) !='') { 
								$isPublicationExist = $P->getTotal('publication', $P->prepare_input($publication), 'exact');
								if ($isPublicationExist == 0) { 
									$pbValues = array( "fl" => "add", "pub_id" => 0,  "publication" => $publication , "description" => "" );
									$createPub = $P->editPublication($pbValues);
								}
								$pbRec = $P->getSelectedFieldsRecordByField($selectedFields='pub_id', $searchField='publication', $P->prepare_input($publication), 'exact');
								$pub_id = $pbRec[0];
							}

							$city_id = 0; $state_id = 0; $zip_id = 0; 
							if (!empty($city)) { 
								$isCityExist = $C->getTotal('city', $C->prepare_input($city), 'exact');
								if ($isCityExist == 0) { 
									$ctValues = array( "fl" => "add", "city_id" => 0,  "city" => $city );
									$createCity = $C->editCity($ctValues);
								}
								$ctRec = $C->getSelectedFieldsRecordByField($selectedFields='city_id', $searchField='city', $C->prepare_input($city), 'exact');
								$city_id = $ctRec[0];
								
							}

							if (!empty($state)) { 
								$isStateExist = $S->getTotal('state', $S->prepare_input($state), 'exact');
								if ($isStateExist == 0) { 
									$ctValues = array( "fl" => "add", "state_id" => 0,  "state" => $state );
									$createState = $S->editState($ctValues);
								}
								$stRec = $S->getSelectedFieldsRecordByField($selectedFields='state_id', $searchField='state', $S->prepare_input($state), 'exact');
								$state_id = $stRec[0];
								
							}

							if (!empty($zip)) { 
								$isZipExist = $Z->getTotal('zipcode', $Z->prepare_input($zip), 'exact');
								if ($isZipExist == 0) { 
									$ctValues = array( "fl" => "add", "zip_id" => 0,  "zipcode" => $zip );
									$createZip = $Z->editZipcode($ctValues);
								}
								$zpRec = $Z->getSelectedFieldsRecordByField($selectedFields='zip_id', $searchField='zipcode',  $Z->prepare_input($zip), 'exact');
								$zip_id = $zpRec[0];
								
							}

								 $sessionValues = array ( 'center_id'				=>		0,
														  'name'					=>		$name,
														  'specialty_id_fk'			=>		$specialty_id_fk,
														  'category_id_fk'			=>		$category_id_fk,
														  'chapter_id_fk'			=>		$chapter_id_fk,
														  'description'				=>		$description,
														  'notes'					=>		$notes,
														  'website'					=>		$website,
														  'npi'						=>		$npi,
														  'admission'				=>		$admission,
														  'hours_of_operation'		=>		$hours_of_operation,
														  'address_1'				=>		$address_1,
														  'address_2'				=>		$address_2,
														  'city_id'					=>		$city_id,
														  'state_id'				=>		$state_id,
														  'zip_id'					=>		$zip_id,
														  'county_id_fk'			=>		$county_id_fk,
														  'phone'					=>		$phone,
														  'fax'						=>		$fax,
														  'main_office'				=>		$main_office,
														  'status'					=>		$status,
														  'updated_by'				=>		$loggedUser,
														  'import'					=>		1
													);
								
								$updateRecord = $M->createCenter($sessionValues);
								$centerId = $M->lastUpdatedId();
							//	echo '<pre> CENTER ID :'. $centerId . '  == Total : ' . $total; print_r($sessionValues); 
								
								
								if (!empty($centerId) and !empty($pub_id)) {
									$year = date('Y');
									$pubVals = array ( "fl" => "add", "pub_id" => $pub_id,  "center_id" => $centerId, "year" => $year );
									$editCenterPublication = $CP->editCenterPublication($pubVals);
									empty($pubVals);
								}

								$hrop_id_fk = 0;
								if (!empty($hours_of_operation)) { 
									$ishrExist = $HR->getTotal('description', $HR->prepare_input($hours_of_operation), 'exact');
									if ($ishrExist == 0) { 
										$hrValues = array( "fl" => "add", "hrop_id" => 0,  "description" => $hours_of_operation );
										$createCap = $HR->editHrop($hrValues);
									}
									$hrRec = $HR->getSelectedFieldsRecordByField($selectedFields='hrop_id', $searchField='description', $HR->prepare_input($hours_of_operation), 'exact');
									$hrop_id_fk = $hrRec[0];
								}
								
								if (!empty($centerId) and !empty($hrop_id_fk)) {
									$pubVals = array ( "fl" => "add",  "center_id" => $centerId, "hrop_id" => $hrop_id_fk);
									$editPublication = $CHR->editCenterHrop($pubVals);
									empty($pubVals);
								}
								
								empty($sessionValues);
								$total++;
							
						}


					}
					else {
						header("Location: importcenter.php?msg=erremt");
						exit();
					}
			
			}
			else {
					header("Location: importcenter.php?msg=errfl");
					exit();
			}
			
			
			if ($total > 0)	 { 		
				header("Location: importcenter.php?msg=import&t=$total");
				exit();
			}
			else {
				header("Location: importcenter.php?msg=importerr");
				exit();
			}
			
		}
		else {
			header("Location: importcenter.php?msg=ext");
			exit();
		}

}


// Check if the error is present

if(isset($_GET['msg']))
{
	$error = 0; $trec = 0;
	$msg = $_GET['msg']; 
	$trec = $_GET['trec'];
	
	if ($msg == "import") { 
		$t = $_GET['t'];
		$txtMessage = "Success! ". $t ." Records Imported.";
	}
	else if ($msg == "err")
		$txtMessage = "Error! Please try again.";
	else if ($msg == "errm") { 
		$txtMessage = "Error! Incorrect Phone number found or wrong format file uploaded.";	
		$error = 1;
	}
	else if ($msg == "erremt" or $msg == "ext") { 
		$txtMessage = "Error! Empty file is uploaded.";	
		$error = 1;
	}
	else if ($msg == "errfl") { 
		$txtMessage = "Error! Uploaded file type is not supported.";	
		$error = 1;
	}
	else if ($msg == "errs") { 
		$txtMessage1 = "Success! Records are imported successfully.";	
		
		if ($trec > 0)
			$txtMessage = "Error! Few records are not imported due to missing required information like Center name or phone.";	
		else
			$txtMessage = "Error! Records are not imported due to missing required information like Center name or phone.";	
		
		$error = 1;
	}
	else if ($msg == "importerr") {
		$error = 1;
		$txtMessage = "Error! New records not found. Record importing failed.";
	}
	else if ($msg == "errd") { 
		$txtMessage = "Error! The center with phone number already exist. Please enter correct details.";						
		$error = 1;
	}
	else if ($msg == "erre") { 
		$txtMessage = "Error! Phone Number already exists.";						
		$error = 1;
	}					

}
else
{
	$msg = '';
	$txtMessage = "";
}





include_once "header.php"; 

include_once "sidebar.php";
?>

<link rel="stylesheet" href="css/plugins/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="js/custom/general.js"></script>
<script type="text/javascript" src="js/custom/table.js"></script>
<script type="text/javascript" src="js/custom/commonajax.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	/*jQuery("#form").validate({
		rules: {
			file: "required"
		},
		messages: {
			file: "Please select xls or xlsx file only",
		}
	});*/

});	

function checkimport() {
	//document.getElementById('btnimport').innerHTML = 'Please wait while importing document..';
	document.getElementById('btnimport').style.display = 'none';
	document.getElementById('ajloader').style.display = 'block';
}


</script>
<div class="maincontent">
	
    <div class="breadcrumbs">
    	<a href="index.php">Dashboard</a>
        <a href="center.php">Center</a>
		<span>Import Center</span>
    </div>
    <!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Import Center </h1>
    	
        <br />
        
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
        
        <?php 
		  /*	if (!empty($txtMessage)) { 
		  		if (isset($error) && $error == 1) { 
		  ?>
             
			 <?php if (isset($msg) && $msg = "errs" && ($trec > 0)) { ?> 
              <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button"> <i class="icon-times"></i> </button>
                <strong> <i class="icon-check"></i> </strong> <?php echo $txtMessage1; ?>
              </div>
              <?php } ?>
            
              <div class="alert alert-error">
                <button data-dismiss="alert" class="close" type="button"> <i class="icon-times"></i> </button>
                <strong> <i class="icon-times"></i> </strong> <?php echo $txtMessage; ?>
              </div>
              
          <?php } 
		  		else { ?>
          	  <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button"> <i class="icon-times"></i> </button>
                <strong> <i class="icon-check"></i> </strong> <?php echo $txtMessage; ?>
              </div>
          <?php }       
		  	}*/
		  ?>
        
		<form id="form" action="<?php echo  $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" onsubmit="javascript: return checkimport();">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Import Center List</legend>
                    
                    <p>
                    	<label for="imgfile">Select File</label>
                        <input name="file" type="file" class="sf" id="file"  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" title="Please upload .xls, .xlsx or .csv file only " /> (max 50 mb file) upload .xls, .xlsx file only 
                    </p>
                   
				   <p>
                    	<label for="preverified">Pre-Verified Records</label>
                        <input id="preverified" name="preverified" type="checkbox" value="Y" /><strong style="color:blue;"> Please check if you are importing pre-verifed records</strong>
                    </p>
                   
					
					<p id="btnimport">
						<input type="hidden" name="fl" value="import" />
                        <button>Start Import</button>
                    </p>
                    
                    <p id="ajloader" style="display:none"><label>&nbsp;</label>Please wait while importing records.. <img src="images/loaders/ajax-loader.gif" alt="Loading.." /></p>
                    
              </fieldset>
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
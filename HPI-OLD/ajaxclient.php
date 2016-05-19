<?php
include_once "classes/session.class.php";
require_once "classes/doctor.class.php";
require_once "classes/center.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/county.class.php";
require_once "classes/simplexlsx.class.php";
require_once "classes/publication.class.php";
require_once "classes/doctorpublication.class.php";
require_once "classes/city.class.php";
require_once "classes/state.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/address.class.php";
require_once "classes/doctoraddress.class.php";
require_once "classes/centeraddress.class.php";

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

$M = new DoctorClass;
$CT = new CategoryClass;
$SP = new SpecialtyClass;
$CN = new CountyClass;
$P = new PublicationClass;
$DP = new DoctorPublicationClass;

$C = new CityClass;
$S = new StateClass;
$Z = new ZipcodeClass;
$DA = new DoctorAddressClass;
$A = new AddressClass;
$CA = new CenterAddressClass;

if (isset($_POST['action'])) { 

		$action = $_POST['action'];
		
		/*if (!validateAuth() )
		{
			$errorarray = array( 'error' => 'invalid headers. unauthorized access.'); 
			$result = json_encode($errorarray);
			echo $result; exit();
		}*/
		
		
		if ($action == 'test') { 
			
			//echo $result = json_encode($_POST);exit();
			
			 echo  $M->prepare_input($_POST['name']); exit();
			
			
		}

		
		if ($action == 'newdoctoraddress') { 
				
			
				if (!empty($_POST['doctor_id']) and !empty($_POST['address_1']) and !empty($_POST['city_id']) and !empty($_POST['state_id']) and !empty($_POST['zip_id'])) { 
						
						$doctorid = $_POST['doctor_id'];
						$address_1 = $_POST['address_1'];
						$address_2 = $_POST['address_2'];
						$city_id = $_POST['city_id'];
						$state_id = $_POST['state_id'];
						$zip_id = $_POST['zip_id'];
						$county_id = $_POST['county_id'];
						$phone = $_POST['phone'];
						$fax = $_POST['fax'];
						
						
						
						$A = new AddressClass;
					
						$fieldArray = array ('address_1', 'city_id', 'state_id' , 'zip_id', 'county_id', 'phone' , 'fax');
						$keyArray = array($A->prepare_input($address_1), $A->prepare_input($city_id), $A->prepare_input($state_id), $A->prepare_input($zip_id) , $A->prepare_input($county_id_fk) , $A->prepare_input($phone) , $A->prepare_input($fax));
						
						$isAddressFound = $A->getRecordCountBySearchArray($fieldArray, $keyArray, $searchStatus='exact');
					
						if ($isAddressFound == 0) {
								$arrayValues = array (    	"fl" => 'add', "id" => 0, "doctor_id" => $doctorid,
															"address_1" => $address_1, "address_2" => $address_2, 'city_id' => $city_id, 
															'state_id' => $state_id, 'zip_id' => $zip_id, 'county_id' => $county_id_fk , 'phone' => $phone, 'fax' => $fax
													  );
								$editAddress = $A->editAddress($arrayValues);
								empty($arrayValues);
						}
						
						// find the address id
						$aRec = $A->getSelectedFieldsRecordListArray($selectedFields='id', $fieldArray, $keyArray, $searchStatus='exact');
						$address_id =	$aRec[0]['id'];
						
						$DA = new DoctorAddressClass;
						$darrayVals = array( 'doctor_id' => $doctorid , 'address_id' => $address_id);
						$createDoctorAddress =	$DA->createDoctorAddress($darrayVals);
						
						$da_id = 0;
						if (isset($_SESSION['da_id']) and !empty($_SESSION['da_id'])) {
							$da_id = $_SESSION['da_id'];
						}
						
						
						
						if ($createDoctorAddress == 1) {
						
							$C = new CityClass;
							$S = new StateClass;
							$Z = new ZipcodeClass;
							
							$crec = $C->getSelectedFieldsRecordByField($selectedFields='city','city_id', $city_id, 'exact');
							$city = $crec['city'];
							
							$srec = $S->getSelectedFieldsRecordByField($selectedFields='state', 'state_id', $state_id, 'exact');
							$state = $srec['state'];
							
							$zrec = $Z->getSelectedFieldsRecordByField($selectedFields='zipcode','zip_id', $zip_id, 'exact');
							$zip = $zrec['zipcode'];
							
							$address = $address_1;
							if (!empty($address_2))
								$address .= ', ' . $address_2;

							$jstr = array( 'success' => 1 , 'failure' => 0 , 'da_id' =>  $da_id , 'doctor_id' =>  $doctorid , 'address' =>  $address  , 'city' =>  $city  , 'state' =>  $state , 'zip' =>  $zip , 'phone' =>  $phone , 'fax' =>  $fax , 'message' => 'New address created successfully.'); 
							
							$arr_res=array();
							$arr_res['result']=$jstr;
							echo $returnarry = json_encode($arr_res, true);	exit();
							
							
							
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}


		}
		
		
		if ($action == 'deletePublication') { 
		
					if (!empty($_POST['doctor_id']) and !empty($_POST['pub_id']) and !empty($_POST['year'])) { 
						
						$doctor_id = $_POST['doctor_id'];
						$pub_id = $_POST['pub_id'];
						$year = $_POST['year'];
				
						/* delete Record 
						 * @param string $doctor id,  @param string pub id
						 */
						
						$P = new DoctorPublicationClass;
						$RecoredDeleted = $P->deleteRecord($doctor_id, $pub_id, $year);
						
						if ($RecoredDeleted == 1) {
						
							$jstr = array( 'success' => 1 , 'failure' => 0 , 'doctor_id' =>  $doctor_id , 'pub_id' =>  $pub_id , 'message' => 'Publication record deleted successfully.'); 
							
							$arr_res=array();
							$arr_res['result']=$jstr;
							echo $returnarry = json_encode($arr_res, true);	exit();
							
							
							
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}

		
		}
		
		if ($action == 'deleteDoctorAddress') { 
		
					if (!empty($_POST['doctor_id']) and !empty($_POST['address_id']) ) { 
						
						$doctor_id = (int) $_POST['doctor_id'];
						$address_id = (int) $_POST['address_id'];

				
						/* delete Record 
						 * @param string $doctor id,  @param string pub id
						 */
						
						$DA = new DoctorAddressClass;
						$resultString = $DA->deleteRecord($doctor_id, $address_id);
						
						if ($resultString == 1) {
						
							$jstr = array( 'success' => 1 ,'failure' => 0, 'doctor_id' =>  $doctor_id, 'address_id' => $address_id , 'message' => 'Adderss removed successfully.'); 
							
							$arr_res=array();
							$arr_res['result']=$jstr;
							echo $returnarry = json_encode($arr_res, true);	exit();
							
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}

		
		}
		
		if ($action == 'getaddressinfo') { 
				
			
				if (!empty($_POST['address_id'])) { 
						
						$address_id = $_POST['address_id'];
						
						$A = new AddressClass;
					
						$adrid = $A->prepare_input($address_id);
						
						$isAddressFound = $A->getTotal($field='id', $adrid, $searchStatus='exact');
					
						if ($isAddressFound > 0 ) {
								
								$data  		=	$A->getRecordByField('id', $adrid);
								$aid 		=  	$A->prepare_output( $data["id"] );
								$address_1	=	$A->prepare_output( $data['address_1'] );
								$address_2	=	$A->prepare_output( $data['address_2'] );
								$city_id	=	$A->prepare_output( $data['city_id'] );
								$state_id	=	$A->prepare_output( $data['state_id'] );
								$zip_id		=	$A->prepare_output( $data['zip_id'] );
								$phone		=	$A->prepare_output( $data['phone'] );
								$fax		=	$A->prepare_output( $data['fax'] );
								
								$jstr = array( 'success' => 1 , 'failure' => 0 , 'address_id' =>  $aid , 'address_1' =>  $address_1 , 'address_2' =>  $address_2 , 'city_id' =>  $city_id  , 'state_id' =>  $state_id , 'zip_id' =>  $zip_id , 'phone' =>  $phone , 'fax' =>  $fax , 'message' => 'address found.'); 
								
								$arr_res=array();
								$arr_res['result']=$jstr;
								echo $returnarry = json_encode($arr_res, true);	exit();
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}


		}
		
		
		
		if ($action == 'updatedoctor') { 
				
			
				if (!empty($_POST['doctor_id']) and !empty($_POST['specialty_id_fk']) and !empty($_POST['npi']) ) { 
						
						$doctor_id 				= 		$_POST['doctor_id'];
						
						$specialty_id_fk		=		$_POST['specialty_id_fk'];
						$category_id_fk			=		$_POST['category_id_fk'];
						$npi			 	    = 		$_POST['npi'];
						$email					=		$_POST['email'];
						$website		 	    = 		$_POST['website'];
						$license_status	 	    = 		$_POST['license_status'];
						$status					=		$_POST['status'];
						$notes					=		$_POST['notes'];
						
						$D = new DoctorClass;
						if (!empty($email)) { 
							$isEmail = $D->checkEmailForUpdate($doctor_id, $email);
							if ($isEmail > 0) {
								$errtxt = 'email already exist.';
	
								$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
								$arr_res=array();
								$arr_res['result']=$error;
								echo $returnarry = json_encode($arr_res, true);	exit();
							
							}
						}
						
						$sessionValues = array (  'doctor_id'				=>		$doctor_id,
												  'specialty_id_fk'			=>		$specialty_id_fk,
												  'category_id_fk'			=>		$category_id_fk,
												  'npi'						=>		$npi,
												  'email'					=>		$email,
												  'website'					=>		$website,
												  'license_status'			=>		$license_status,
												  'notes'					=>		$notes,
												  'status'					=>		$status,
												  'updated_by'				=>		$loggedUser
											);
						
						$updateRecord = $D->updateDoctor($sessionValues);
						
						if ($updateRecord == 1 ) {
						
								$CT = new CategoryClass;
								$SP = new SpecialtyClass;
								
								$crec = $SP->getSelectedFieldsRecordByField($selectedFields='specialty_name','specialty_id', $specialty_id_fk, 'exact');
								$specialty = $crec['specialty_name'];
								
								$srec = $CT->getSelectedFieldsRecordByField($selectedFields='category_name', 'cat_id', $category_id_fk, 'exact');
								$category = $srec['category_name'];
								
								$status = $D->getStatus($status);
								
								$jstr = array( 'success' => 1 , 'failure' => 0 , 'doctor_id' =>  $doctor_id , 'specialty' =>  $specialty , 'category' =>  $category , 'npi' =>  $npi  , 'notes' =>  $notes , 'email' =>  $email , 'website' =>  $website , 'license_status' =>  $license_status , 'status' => $status , 'message' => 'doctor info updated.'); 
								
								$arr_res=array();
								$arr_res['result']=$jstr;
								echo $returnarry = json_encode($arr_res, true);	exit();
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}


		}
		
		
		// --- update address
		
		if ($action == 'updateaddress') { 
				
			
				if (!empty($_POST['address_id']) and !empty($_POST['address_1']) and !empty($_POST['city_id']) and !empty($_POST['state_id']) and !empty($_POST['zip_id'])   ) { 
						
						$address_id 			= 		$_POST['address_id'];
						$address_1		 	    = 		$_POST['address_1'];
						$address_2		 	    = 		$_POST['address_2'];
						$city_id				=		$_POST['city_id'];
						$state_id				=		$_POST['state_id'];
						$county_id				=		$_POST['county_id'];
						$zip_id			 	    = 		$_POST['zip_id'];
						$phone					=		$_POST['phone'];
						$fax			 	    = 		$_POST['fax'];
						$status					=		$_POST['status'];
						$notes					=		$_POST['notes'];
						
						$ADR = new AddressClass;
						
						$sessionValues = array (  	'fl' => 'edit',
													'id' => $address_id,
												 	'address_1' => $address_1, 
													'address_2' => $address_2,
													'city_id' => $city_id,
													'state_id' => $state_id,
													'county_id' => $county_id,
													'zip_id' => $zip_id,
													'phone' => $phone,
													'fax' => $fax,
													'notes' => $notes,
													'status' => $status,
													'updated_by' => $loggedUser
											);
						
						$updateRecord = $ADR->editAddress($sessionValues);
						
						if ($updateRecord == 1 ) {
						
								$aRec = $ADR->getSelectedFieldsRecordByField($selectedFields='date_updated, updated_by', $fieldName='id', $address_id,  $searchStatus='exact');
								$last_updated = $aRec['date_updated'];
								$updated_by = $aRec['updated_by'];
								
								$C = new CityClass;
								$S = new StateClass;
								$Z = new ZipcodeClass;
								$CN = new CountyClass;
								
								$crec = $C->getSelectedFieldsRecordByField($selectedFields='city','city_id', $city_id, 'exact');
								$city = $crec['city'];
								
								$srec = $S->getSelectedFieldsRecordByField($selectedFields='state', 'state_id', $state_id, 'exact');
								$state = $srec['state'];
								
								$zrec = $Z->getSelectedFieldsRecordByField($selectedFields='zipcode', 'zip_id', $zip_id, 'exact');
								$zip = $zrec['zipcode'];
								
								$cnrec = $CN->getSelectedFieldsRecordByField($selectedFields='county', 'county_id', $county_id, 'exact');
								$county = $zrec['county'];
								
								
								$status = $ADR->getStatus($status);
								
								$jstr = array( 'success' => 1 , 'failure' => 0 , 'address_id' =>  $address_id , 'address_1' =>  $address_1 , 'address_2' =>  $address_2 , 'city' =>  $city  ,  'state' =>  $state , 'zip' =>  $zip , 'county' => $county , 'phone' =>  $phone , 'fax' =>  $fax , 'notes' =>  $notes , 'status' => $status , 'last_updated' => $last_updated, 'updated_by' => $updated_by , 'message' => 'address updated.'); 
								
								$arr_res=array();
								$arr_res['result']=$jstr;
								echo $returnarry = json_encode($arr_res, true);	exit();
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}


		}
		
		
		// --- get specialty and category
		
		/*if ($action == 'getspecialtycategory') { 
				
			
				if (!empty($_POST['specialty']) and !empty($_POST['category']) ) { 
						
						$specialty = $_POST['specialty'];
						$category = $_POST['category'];
						
								$CT = new CategoryClass;
								$SP = new SpecialtyClass;
								
								$crec = $SP->getSelectedFieldsRecordByField($selectedFields='specialty_id', 'specialty_name', $specialty, 'exact');
								$spid = $crec['specialty_id'];
								
								$srec = $CT->getSelectedFieldsRecordByField($selectedFields= 'cat_id', 'category_name', $category, 'exact');
								$ctid = $srec['cat_id'];
								
								$jstr = array( 'success' => 1 , 'failure' => 0 , 'spid' =>  $spid , 'ctid' =>  $ctid , 'message' => 'info found.'); 
								
								$arr_res=array();
								$arr_res['result']=$jstr;
								echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
						
					}


		}*/
		
		//--- create new address doctor 
		
		if ($action == 'newaddressdoctor') { 
				
			
				if (!empty($_POST['adrid']) and  !empty($_POST['first_name']) and !empty($_POST['last_name']) and !empty($_POST['specialty_id_fk']) and 
					!empty($_POST['category_id_fk']) and !empty($_POST['npi']) and !empty($_POST['status']) ) { 
						
						$doctorid = $_POST['docid'];
						$addressid = $_POST['adrid'];
						$full_name = $_POST['full_name'];
						$first_name = $_POST['first_name'];
						$last_name = $_POST['last_name'];
						$specialty_id_fk = $_POST['specialty_id_fk'];
						$category_id_fk = $_POST['category_id_fk'];
						$npi = $_POST['npi'];
						$phone = $_POST['phone'];
						$fax = $_POST['fax'];
						$notes = $_POST['notes'];
						$status = $_POST['status'];
						$new =  $_POST['new'];
						$advertiser =  $_POST['advertiser'];
						
						$D = new DoctorClass;
						
						if ($doctorid == '' or $doctorid == 0) { 
								$fieldArray = array ('first_name', 'last_name', 'specialty_id_fk' , 'category_id_fk', 'npi');
								$keyArray = array($D->prepare_input($first_name), $D->prepare_input($last_name), $D->prepare_input($specialty_id_fk), $D->prepare_input($category_id_fk) , $D->prepare_input($npi) );
								
								$isDoctorFound = $D->getTotalDoctorBySearchArray($fieldArray, $keyArray, $searchStatus='exact');
								$doctorid = 0;
						}

								$arrayValues = array (  "fl" => 'add', "doctor_id" => $doctorid, "full_name" => $full_name , "first_name" => $first_name, 
													"last_name" => $last_name, 'specialty_id_fk' => $specialty_id_fk, 'category_id_fk' => $category_id_fk, 
													'npi' => $npi,'phone' => $phone,'fax' => $fax,'notes' => $notes,'status' => $status ,'advertiser' => $advertiser
												  );
								$editDoctor = $D->editDoctor($arrayValues);
								//$doctorid = $D->lastUpdatedId();
								if (isset($_SESSION['doctid']))
									$doctorid = $_SESSION['doctid'];
									
								//if (isset($_SESSION['new']))
								//	$new = $_SESSION['new'];
								
								empty($arrayValues);
						
						
						$docRec = $D->getSelectedFieldsRecordByField('status', 'doctor_id', $doctorid);
						$status = $docRec['status'];
						$current_status = $D->getStatus($status);
						
						$DA = new DoctorAddressClass;
						$darrayVals = array( 'doctor_id' => $doctorid , 'address_id' => $addressid);
						$createDoctorAddress =	$DA->createDoctorAddress($darrayVals);
						
						$da_id = 0;
						if (isset($_SESSION['da_id']) and !empty($_SESSION['da_id'])) {
							$da_id = $_SESSION['da_id'];
						}
						
						
						
						if ($createDoctorAddress == 1) {
							$msg = 'Address linked to doctor successfully.';
						}
						else if ($createDoctorAddress == 0) {
							$msg = 'Doctor information updated and linked successfully.';
						}
							$S = new SpecialtyClass;
							
							$srec = $S->getSelectedFieldsRecordByField($selectedFields='specialty_name', 'specialty_id', $specialty_id_fk, 'exact');
							$specialty = $srec['specialty_name'];
							
							$doctor_name = $full_name;

							$jstr = array( 'success' => 1 , 'failure' => 0 , 'da_id' =>  $da_id , 'doctor_id' =>  $doctorid , 'doctor_name' =>  $doctor_name  , 'specialty' =>  $specialty  , 'npi' =>  $npi , 'notes' =>  $notes , 'phone' => $phone, 'fax' => $fax, 'current_status' =>  $current_status , 'new' => $new , 'message' => $msg); 
							
							$arr_res=array();
							$arr_res['result']=$jstr;
							echo $returnarry = json_encode($arr_res, true);	exit();
						
					}


		}
		
		// --- get doctor info
		
		if ($action == 'getdoctorinfo') { 
				
			
				if (!empty($_POST['doctor_id'])) { 
						
						$doctor_id = $_POST['doctor_id'];
						
						$D = new DoctorClass;
					
						$drid = $D->prepare_input($doctor_id);
						
						$isDoctorFound = $D->getTotal($field='doctor_id', $drid, $searchStatus='exact');
					
						if ($isDoctorFound > 0 ) {
								
								$data  		=	$D->getRecordByField('doctor_id', $drid);
								$did 		=  	$D->prepare_output( $data["doctor_id"] );
								$fullname	=	$D->prepare_output( $data['fullname'] );
								$first_name	=	$D->prepare_output( $data['first_name'] );
								$last_name	=	$D->prepare_output( $data['last_name'] );
								$npi		=	$D->prepare_output( $data['npi'] );
								$phone		=	$D->prepare_output( $data['phone'] );
								$fax		=	$D->prepare_output( $data['fax'] );
								$specialty_id_fk =	$D->prepare_output( $data['specialty_id_fk'] );
								$category_id_fk		=	$D->prepare_output( $data['category_id_fk'] );
								$status		=	$D->prepare_output( $data['status'] );
								$notes		=	$D->prepare_output( $data['notes'] );
								$advertiser	=	$D->prepare_output( $data['advertiser'] );
								
								$jstr = array( 'success' => 1 , 'failure' => 0 , 'doctor_id' =>  $did , 'fullname' =>  $fullname , 'first_name' =>  $first_name , 'last_name' =>  $last_name  , 'npi' =>  $npi , 'phone' => $phone , 'fax' => $fax , 'specialty_id_fk' =>  $specialty_id_fk , 'category_id_fk' =>  $category_id_fk , 'notes' =>  $notes , 'status' =>  $status , 'advertiser' => $advertiser, 'message' => 'doctor found.'); 
								
								$arr_res=array();
								$arr_res['result']=$jstr;
								echo $returnarry = json_encode($arr_res, true);	exit();
						}
						else {

							$errtxt = 'no doctor found.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}


		}
		
		
		//--- create new address center 
		
		if ($action == 'newaddresscenter') { 
				
				
			
				if (!empty($_POST['adrid']) and  !empty($_POST['name'])  and  !empty($_POST['category_id_fk']) and  !empty($_POST['status']) ) { 
						
						$centerid = $_POST['cnid'];
						$addressid = $_POST['adrid'];
						$name = $_POST['name'];
						$specialty_id_fk = $_POST['specialty_id_fk'];
						$category_id_fk = $_POST['category_id_fk'];
						$npi = $_POST['npi'];
						$phone = $_POST['phone'];
						$fax = $_POST['fax'];
						$description = $_POST['description'];
						$notes = $_POST['notes'];
						$status = $_POST['status'];
						$new =  $_POST['new'];
						$advertiser =  $_POST['advertiser'];
						$main = 0;
						
						$CNR = new CenterClass;
						
						if ($centerid == '' or $centerid == 0) { 
								$fieldArray = array ('name', 'category_id_fk');
								$keyArray = array($CNR->prepare_input($name), $CNR->prepare_input($category_id_fk) );
								
								$isCenterFound = $CNR->getTotalCenterBySearchArray($fieldArray, $keyArray, $searchStatus='exact');
								$centerid = 0;
						}
						
						
								$arrayValues = array (  "fl" => 'add', "center_id" => $centerid, "name" => $name, 'specialty_id_fk' => $specialty_id_fk, 
														'category_id_fk' => $category_id_fk, 'npi' => $npi, 'phone' => $phone , 'fax' => $fax , 'description' => $description, 'notes' => $notes, 'status' => $status, 'advertiser' => $advertiser  );
								$editCenter = $CNR->editCenter($arrayValues);
								//$centerid = $CNR->lastUpdatedId();
								if (isset($_SESSION['cnid']))
									$centerid = $_SESSION['cnid'];
								
								empty($arrayValues);
								
						
						$docRec = $CNR->getSelectedFieldsRecordByField('status', 'center_id', $centerid);
						$status = $docRec['status'];
						$current_status = $CNR->getStatus($status);
						
						$CA = new CenterAddressClass;
						$darrayVals = array( 'center_id' => $centerid , 'address_id' => $addressid , 'main' => $main);
						$createCenterAddress =	$CA->createCenterAddress($darrayVals);
						
						
						$da_id = 0;
						if (isset($_SESSION['da_id']) and !empty($_SESSION['da_id'])) {
							$da_id = $_SESSION['da_id'];
						}
						
						
						
						if ($createCenterAddress == 1) {
							$msg = 'Address linked to center successfully.';
						}
						else if ($createCenterAddress == 0) {
							$msg = 'Center information updated and linked successfully.';
						}
						
							$C = new CategoryClass;
							
							$srec = $C->getSelectedFieldsRecordByField($selectedFields='category_name', 'cat_id', $category_id_fk, 'exact');
							$category = $srec['category_name'];
							
							$center_name = $name;
							
							$jstr = array( 'success' => 1 , 'failure' => 0 , 'da_id' =>  $da_id , 'center_id' =>  $centerid , 'center_name' =>  $center_name  , 'category' =>  $category  , 'npi' =>  $npi , 'description' =>  $description , 'notes' =>  $notes , 'phone' => $phone , 'fax' => $fax , 'current_status' =>  $current_status , 'new' => $new , 'message' => $msg); 
							
							$arr_res=array();
							$arr_res['result']=$jstr;
							echo $returnarry = json_encode($arr_res, true);	exit();
						
					}


		}
		
		// --- get center info
		
		if ($action == 'getcenterinfo') { 
				
			
				if (!empty($_POST['center_id'])) { 
						
						$center_id = $_POST['center_id'];
						
						$CNR = new CenterClass;
					
						$drid = $CNR->prepare_input($center_id);
						
						$isCenterFound = $CNR->getTotal($field='center_id', $drid, $searchStatus='exact');
					
						if ($isCenterFound > 0 ) {
								
								$data  		=	$CNR->getRecordByField('center_id', $drid);
								$did 		=  	$CNR->prepare_output( $data["center_id"] );
								$name		=	$CNR->prepare_output( $data['name'] );
								$npi		=	$CNR->prepare_output( $data['npi'] );
								$phone		=	$CNR->prepare_output( $data['phone'] );
								$fax		=	$CNR->prepare_output( $data['fax'] );
								$specialty_id_fk =	$CNR->prepare_output( $data['specialty_id_fk'] );
								$category_id_fk	 =	$CNR->prepare_output( $data['category_id_fk'] );
								$status		=	$CNR->prepare_output( $data['status'] );
								$description =	$CNR->prepare_output( $data['description'] );
								$notes		=	$CNR->prepare_output( $data['notes'] );
								$advertiser		=	$CNR->prepare_output( $data['advertiser'] );
								
								$jstr = array( 'success' => 1 , 'failure' => 0 , 'center_id' =>  $did , 'name' =>  $name , 'npi' =>  $npi , 'phone' => $phone, 'fax' => $fax , 'specialty_id_fk' =>  $specialty_id_fk , 'category_id_fk' =>  $category_id_fk , 'notes' =>  $notes , 'description' => $description , 'status' =>  $status , 'advertiser' => $advertiser, 'message' => 'center found.'); 
								
								$arr_res=array();
								$arr_res['result']=$jstr;
								echo $returnarry = json_encode($arr_res, true);	exit();
						}
						else {

							$errtxt = 'no center found.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}


		}
		
		
		if ($action == 'deleteCenterAddress') { 
		
					if (!empty($_POST['center_id']) and !empty($_POST['address_id']) ) { 
						
						$center_id = (int) $_POST['center_id'];
						$address_id = (int) $_POST['address_id'];

				
						/* delete Record 
						 * @param string $doctor id,  @param string pub id
						 */
						
						$CA = new CenterAddressClass;
						$resultString = $CA->deleteRecord($center_id, $address_id);
						
						if ($resultString == 1) {
						
							$jstr = array( 'success' => 1 ,'failure' => 0, 'center_id' =>  $center_id, 'address_id' => $address_id , 'message' => 'Address Unlinked successfully.'); 
							
							$arr_res=array();
							$arr_res['result']=$jstr;
							echo $returnarry = json_encode($arr_res, true);	exit();
							
						}
						else {

							$errtxt = 'unknown error.';

							$error = array( 'success' => 0 , 'failure' => 1 , 'message' => $errtxt); 
							//$result = json_encode($error);
							//echo $result; exit();
							$arr_res=array();
							$arr_res['result']=$error;
							echo $returnarry = json_encode($arr_res, true);	exit();
						}
						
						
					}

		
		}
		
}





?>
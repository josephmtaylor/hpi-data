<?php
include_once "classes/session.class.php";
require_once "classes/admin.class.php";
require_once "classes/state.class.php";
require_once "classes/city.class.php";
require_once "classes/zipcode.class.php";
require_once "classes/doctor.class.php";
require_once "classes/category.class.php";
require_once "classes/specialty.class.php";
require_once "classes/center.class.php";
require_once "classes/address.class.php";
require_once "classes/publication.class.php";

$session = new SessionClass;
if(!$session->logged_in){

		header("Location: login.php");
		exit();
}

	$D = new DoctorClass;
	$cntDoctor = $D->getTotal();

	$CN = new CenterClass;
	$cntCenter = $CN->getTotal();

	$I = new StateClass;
	$cntState = $I->getTotal();

	$T = new CityClass;
	$cntCity = $T->getTotal();
	
	$B = new ZipcodeClass;
	$cntZip = $B->getTotal();

	$S = new SpecialtyClass;
	$cntSpl = $S->getTotal();

	$C = new CategoryClass;
	$cntCat = $C->getTotal();
	
	$AD = new AddressClass;
	$cntAdr = $AD->getTotal();

	$P = new PublicationClass;
	$cntPub = $P->getTotal();


$userType = $session->getUserLevel();

$admin = new AdminClass;

if(isset($_GET['msg']))
{
	$msg = $_GET['msg']; 
	
	if ($msg == "err")
		$txtMessage = "Restricted! You are restricted to certain areas in admin panel.";
}
else
{
	$txtMessage = "";
}



include_once "header.php"; 

include_once "sidebar.php";
?>
<script type="text/javascript" src="js/custom/dashboard.js"></script>

<div class="maincontent">
	<div class="two_third maincontent_inner ">
    	<div class="left">
        
			<!-- notification info -->
        	<?php if (!empty($txtMessage)) { ?>
			<div class="notification msginfo">
            	<a class="close"></a>
            	<?php echo $txtMessage; ?>
            </div>
			<?php } ?>
            <!-- START WIDGET LIST -->
            <ul class="widgetlist">
              	<li><a href="doctor.php"><img src="images/icons/doctor.png" alt="Doctors" width="70" border="0" /><span>Doctors</span></a></li>
                <li><a href="importdoctor.php"><img src="images/icons/import.png" alt="Import Doctor" width="70" border="0" /><span>Import Doctors</span></a></li>
                <li><a href="searchdoctor.php"><img src="images/icons/dsearch.png" alt="Search Doctor"  width="70" border="0" /><span>Search Doctor</span></a></li>
                <li><a href="address.php"><img src="images/icons/addressbook.png" alt="AddressBook"  width="70" border="0" /><span>Address Book</span></a></li>
                <li><a href="searchaddress.php"><img src="images/icons/navigation.png" alt="Search Doctor Address"  width="70" border="0" /><span>Search Address</span></a></li>
                <li><a href="center.php"><img src="images/icons/office.png" alt="Centers" width="70" border="0" /><span>Centers</span></a></li>
                <li><a href="importcenter.php"><img src="images/icons/import2.png" alt="Import Centers" width="70" border="0" /><span>Import Centers</span></a></li>
                <li><a href="searchcenter.php"><img src="images/icons/csearch.png" alt="Search Center"  height="70" border="0" /><span>Search Center</span></a></li>
                
            </ul>
            <!-- END WIDGET LIST -->
            
            <div class="clear"></div>
            
            <br />
                        
        </div><!-- left -->            
    </div><!-- two_third -->
    
    <div class="one_third last">
    	<div class="right">
        
            <div class="widgetbox">
                <h3><span>OVERVIEW</span></h3>
                <div class="content">
                
                	<div class="one_half bright"> 
                    	<h1 class="prize"><?php echo $cntDoctor; ?></h1>
                    	<p> Doctors</p>
                	</div>
                    
                    <div class="one_half last">
                   		<h1 class="prize"><?php  echo $cntCenter; ?></h1>
                    	<small>Centers </small>
                    </div>
                   
                    <div>&nbsp;</div>
                    
                	<div class="one_half bright"> 
                        <h1 class="prize"><?php  echo $cntAdr; ?></h1>
                        <small>Addresses </small>
                    </div>
                   
                   <div class="one_half last">
                   		<h1 class="prize"><?php  echo $cntPub; ?></h1>
                    	<small>Publications </small>
                   </div>
                    <br />
					
					<div>&nbsp;</div>
                    
                	<div class="one_half bright"> 
                        <h1 class="prize"><?php  echo $cntSpl; ?></h1>
                        <small>Specialties </small>
                    </div>
                   
                   <div class="one_half last">
                   		<h1 class="prize"><?php  echo $cntCat; ?></h1>
                    	<small>Categories </small>
                   </div>
                    <br />
					
                    
                </div><!-- content -->
				
            </div><!-- widgetbox -->
            
            
           
            
            
    	</div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<?php include_once "footer.php"; ?>
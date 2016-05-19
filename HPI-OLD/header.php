<?php
$pageName = basename($_SERVER['PHP_SELF']);


switch ($pageName) {
			case 'index.php' : 
					$title = 'Dashboard'; 
					$bodyclass = 'bodygrey';
					$liclass = 'index';
					break;
			case 'doctor.php' :	
					$title = 'Doctor'; 
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'newdoctor.php' :
					$title = 'Doctor'; 
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'importdoctor.php' :
					$title = 'Import Doctor'; 
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'category.php'  :
					$title = 'Doctor - Category'; 
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'newcategory.php' :
					$title = 'Doctor - Category'; 
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'specialty.php' :
					$title = 'Doctor - Specialty';
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'newspecialty.php' :
					$title = 'Doctor - Specialty';
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'publication.php' :
					$title = 'Doctor - Publication';
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'newpublication.php' :
					$title = 'Doctor - Publication';
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'address.php' :
					$title = 'Doctor - Address';
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'newaddress.php' :
					$title = 'Doctor - Address';
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'center.php' :
					$title = 'Center';
					$bodyclass = 'bodygrey1';
					$liclass = 'center';
					break;
			case 'newcenter.php' :
					$title = 'Center';
					$bodyclass = 'bodygrey';
					$liclass = 'center';
					break;
			case 'importcenter.php' :
					$title = 'Import Center';
					$bodyclass = 'bodygrey';
					$liclass = 'center';
					break;
			case 'chapter.php'  :
					$title = 'Center - Chapter';
					$bodyclass = 'bodygrey1';
					$liclass = 'center';
					break;
			case 'newchapter.php' :
					$title = 'Center - Chapter';
					$bodyclass = 'bodygrey';
					$liclass = 'center';
					break;
			case 'state.php'   :
					$title = 'Master - State';
					$bodyclass = 'bodygrey1';
					$liclass = 'master';
					break;
			case 'newstate.php' :
					$title = 'Master - State';
					$bodyclass = 'bodygrey';
					$liclass = 'master';
					break;
			case 'city.php' :
					$title = 'Master - City';
					$bodyclass = 'bodygrey1';
					$liclass = 'master';
					break;
			case 'newcity.php' :
					$title = 'Master - City';
					$bodyclass = 'bodygrey';
					$liclass = 'master';
					break;
			case 'zipcode.php'  :
					$title = 'Master - Zipcode';
					$bodyclass = 'bodygrey1';
					$liclass = 'master';
					break;
			case 'newzipcode.php' :
					$title = 'Master - Zipcode';
					$bodyclass = 'bodygrey';
					$liclass = 'master';
					break;
			case 'county.php' :
					$title = 'Master - County';
					$bodyclass = 'bodygrey1';
					$liclass = 'master';
					break;
			case 'newcounty.php' :
					$title = 'Master - County';
					$bodyclass = 'bodygrey';
					$liclass = 'master';
					break;
			case 'users.php' :
					$title = 'Admin Setting';
					$bodyclass = 'bodygrey1';
					$liclass = 'master';
					break;
			case 'newuser.php'  :
					$title = 'Admin Setting';
					$bodyclass = 'bodygrey';
					$liclass = 'admin';
					break;
			case 'searchdoctor.php' :
					$title = 'Search Doctor';
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'searchresult.php' :
					$title = 'Search Doctor - Result';
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'searchaddress.php' :
					$title = 'Search Address';
					$bodyclass = 'bodygrey';
					$liclass = 'doctor';
					break;
			case 'addressresult.php' :
					$title = 'Search Address - Result';
					$bodyclass = 'bodygrey1';
					$liclass = 'doctor';
					break;
			case 'searchcenter.php' :
					$title = 'Search Center';
					$bodyclass = 'bodygrey';
					$liclass = 'center';
					break;
			case 'centerresult.php' :
					$title = 'Search Center - Result';
					$bodyclass = 'bodygrey1';
					$liclass = 'center';
					break;
			default:
					$title = 'Welcome to Heritagepublishing.com';
					$bodyclass = 'bodygrey';
					$liclass = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?> | <?php echo WEBSITE_TITLE; ?> - Admin Control</title>
<link rel="stylesheet" media="screen" href="css/style.css" />

<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="css/ie7.css"/>
<![endif]-->

<script type="text/javascript" src="js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/custom/general.js"></script>
<!--<script type="text/javascript" src="js/custom/dashboard.js"></script>-->
<!--<script type="text/javascript" src="js/plugins/additional-methods.min.js"></script>-->

</head>

<body class="<?php echo $bodyclass; ?>">

<div class="headerspace"></div>

<div class="header">
  <a href=""><img src="images/logo.png" alt="Logo" width="200"  /></a>
  <div class="tabmenu">
    <ul>
      <li <?php if ($liclass == 'index') { ?> class="current" <?php } ?> ><a href="index.php" class="dashboard"><span>Dashboard</span></a></li>
	  <li <?php if ($liclass == 'doctor') { ?> class="current" <?php } ?>><a href="doctor.php" class="doctor"><span>Doctors</span></a>
          <ul class="subnav">
            <li><a href="doctor.php"><span>Doctors List</span></a></li>
            <li><a href="newdoctor.php"><span>Add Doctor</span></a></li>
            <li><a href="importdoctor.php"><span>Import Doctors</span></a></li>
            <li><a href="#"><span>-</span></a></li>
            <li><a href="category.php"><span>Doctor Categories</span></a></li>	
			<li><a href="newcategory.php"><span>Add Category</span></a></li>
            <li><a href="specialty.php"><span>Doctor Specialties</span></a></li>	
			<li><a href="newspecialty.php"><span>Add Specialty</span></a></li>
            <li><a href="publication.php"><span>Publications</span></a></li>	
			<li><a href="newpublication.php"><span>Add Publication</span></a></li>
            <li><a href="address.php"><span>Address List</span></a></li>	
			<li><a href="newaddress.php"><span>Create Address</span></a></li>
	      </ul>
      </li> 
      
      
      <li <?php if ($liclass == 'center') { ?> class="current" <?php } ?> ><a href="center.php" class="products"><span>Centers</span></a>
          <ul class="subnav">
            <li><a href="center.php"><span>Center List</span></a></li>
            <li><a href="newcenter.php"><span>Add Center</span></a></li>
            <li><a href="importcenter.php"><span>Import Centers</span></a></li>
            <li><a href="#"><span>-</span></a></li>
            <li><a href="chapter.php"><span>Chapters</span></a></li>	
			<li><a href="newchapter.php"><span>Add Chapter</span></a></li>
	      </ul>
      </li> 
	  
	  <li <?php if ($liclass == 'master')  { ?> class="current" <?php } ?>>
	  	  <a href="#" class="master"><span>Masters</span></a>
	  	  <ul class="subnav">
			<li><a href="state.php"><span>State List</span></a></li>	
			<li><a href="newstate.php"><span>Add State</span></a></li>
            <li><a href="city.php"><span>City List</span></a></li>	
			<li><a href="newcity.php"><span>Add City</span></a></li>
            <li><a href="zipcode.php"><span>Zip List</span></a></li>	
			<li><a href="newzipcode.php"><span>Add Zip</span></a></li>
            <li><a href="county.php"><span>County List</span></a></li>	
			<li><a href="newcounty.php"><span>Add County</span></a></li>
          </ul>
	  </li>
      <?php if ($userType == 'Admin') { ?>
	  <li <?php if ($liclass == 'admin') { ?> class="current" <?php } ?>>
	  	  <a href="#" class="users"><span>Settings</span></a>
	  	  <ul class="subnav">
            <li><a href="users.php"><span>Admin Users</span></a></li>
            <li><a href="newuser.php"><span>Create New User</span></a></li>
          </ul>
	  </li>
      <?php } ?>
    </ul>
  </div>
  
  <div class="accountinfo"> <img src="images/avatar.png" alt="Avatar" />
      <div class="info">
        <h3><?php echo ucfirst($session->getUser()); ?></h3>
        <small><?php echo ucfirst($session->getUserEmail()); ?></small>
        <p> <a href="updateprofile.php">Account Settings</a> <a href="logout.php">Logout</a> </p>
      </div>
  </div>

</div>
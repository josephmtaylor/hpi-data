<?php
include_once "classes/session.class.php";

$session = new SessionClass;

if($session->logged_in){

		header("Location: index.php");
		exit();
}

$admin = new AdminClass;



if (isset($_POST["mode"]))
{
	
	//echo "inside login"; exit();
	if ($_POST["mode"] == "login")
	{
			
			  /* Login attempt */
			  $retval = $session->login($_POST['username'], $_POST['password'], isset($_POST['remember']));
			//  print_r($retval);exit();
			  /* Login successful */
			  if($retval){
				 header("Location: ".$session->referrer);
				 exit();
			  }
			  /* Login failed */
			  else{
			  // $_SESSION['value_array'] = $_POST;
				 //$_SESSION['error_array'] = $form->getErrorArray();
				 header("Location: ".$session->referrer . "?err=usr");
				 exit();
			  }
		
	}
	
}


// Check if the error is present

if(isset($_GET['err']))
{
	$err = $_GET['err']; 
	
	if ($err == "usr")
		$txtMessage = "User Name / Password does not match.";
	if ($err == "err")
		$txtMessage = "Error! Please try again.";	
}
else
{
	$txtMessage = "";
}

if( isset($_GET['fl']) && $_GET['fl'] == 'logout'  ) 
{
		$txtLogout = "You are logged out!";			
}
else {
		$txtLogout = "";
}	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login Page | Welcome To <?php echo WEBSITE_TITLE; ?> Administrator Control Panel</title>
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
<script type="text/javascript" src="js/custom/general.js"></script>
</head>

<body>

<div class="loginlogo">
	<img src="images/logo.png" alt="Logo" />
</div><!--loginlogo-->

<div class="notification notifyError loginNotify">Invalid username or password.</div>

<?php if (!empty($txtMessage)) { ?>
<div id="LoginMessage" class="notification notifyError loginError"><?php echo  $txtMessage; ?></div>
<?php } ?>

<form id="loginform" action="login.php" method="post">
<div class="loginbox">
	<div class="loginbox_inner">
    	<div class="loginbox_content">
            <input type="text" name="username" class="username" />
            <input type="password" name="password" class="password" />
			<input name="mode" type="hidden" value="login" />
            <button name="submit" class="submit">Login</button>
        </div><!--loginbox_content-->
    </div><!--loginbox_inner-->
</div><!--loginbox-->

<div class="loginoption">
	<a href="" class="cant">Can't access your account?</a>
    <input type="checkbox" name="remember" /> Remember me on this computer.
</div><!--loginoption-->
</form>

</body>
</html>

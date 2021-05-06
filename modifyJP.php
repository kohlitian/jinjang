<?php
//load startup scripts
include("config.php");
include("control.php");

//if user is not logged in, bring him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}


//job finders are not allowed to edit job providers info
if(isset($user) && $user['type'] == "jobFinder"){
	$_SESSION['passThruMessage'] = "Sorry ! You are not allowed to access to this page ";
	header("Location: index.php");
}
	//if form is posted
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		$conPass = $_POST['conPass'];

		$usernameError = ""; $passwordError = ""; $nameError = ""; $emailError = ""; $cnomborError=""; $orgError="";$addressError="";$posError="";
		
		//validate form data
		if(!empty($_POST['companyName'])){
			$companyName = $_POST['companyName'];
		} else if($_POST['companyName'] == ""){
			$orgError = "Please enter company name";
		} else {
			$companyName = $user['companyName'];
		}

		if(!empty($_POST['companyAddress'])){
			$companyAddress = $_POST['companyAddress'];
		} else if($_POST['companyAddress'] == ""){
			$addressError = "Please enter company address";
		} else {
			$companyAddress = $user['companyAddress'];
		}

		if(!empty($_POST['position'])){
			$position = $_POST['position'];
		} else if($_POST['position'] == ""){
			$posError = "Please enter your position in company";
		} else {
			$position = $user['position'];
		}

		if(empty($_POST['password'])){
			$password = $user['password'];
		} else {
			if($conPass != $_POST['password']){
				$passwordError = "(Both password must be same)";
			} else {
				$password = $_POST['password'];
			}
		}

		if(empty($_POST['fname'])){
			$nameError = "Please enter a name";
		} else {
			if(!(preg_match("/^[a-zA-Z -]+$/", $_POST['fname']))){
				$nameError = "Only letters and white space allowed";
			} else {
				$fname = $_POST['fname'];
			}
		}

		if(!empty($_POST['cnombor'])){
			$cnombor = $_POST['cnombor'];
		} else if($_POST['cnombor'] == ""){
			$cnomborError = "Please enter your contact number";
		} else {
			$cnombor = $user['contactNo'];
		}

		if(empty($_POST['email'])){
			$emailError = "Please enter a email";
		} else {
			//make sure email is valid and not in use by others
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			 	$emailError = "Invalid email format";
			 } else {
			 	$find = "SELECT `email` FROM `JobProvider` WHERE `email` = '".addslashes($_POST['email'])."'  and `userID`!='".$user['userID']."';";
			 	$find2 = "SELECT `email` FROM `JobFinder` WHERE `email` = '".addslashes($_POST['email'])."' ;";
			 	$findMemberMail = mysqli_query($connect, $find);
			 	$findMemberMail2 = mysqli_query($connect, $find2);
			 	if(mysqli_num_rows($findMemberMail) >0 || mysqli_num_rows($findMemberMail2) >0 ){
			 		$emailError = "Someone have use this email already";
			 	} else {
			 		$email = $_POST['email'];
			 	}
			 }
		}

		//validate username and make sure username is not taken by others
		if(empty($_POST['username'])){
			$usernameError = "(Please enter a username)";
		} else {
			 	$find = "SELECT `username` FROM `JobProvider` WHERE `username` = '".addslashes($_POST['username'])."'  and `userID`!='".$user['userID']."';";
			 	$find2 = "SELECT `username` FROM `JobFinder` WHERE `username` = '".addslashes($_POST['username'])."' ;";
			 	$findMember = mysqli_query($connect, $find);
			 	$findMember2 = mysqli_query($connect, $find2);
			 	if(mysqli_num_rows($findMember) >0 || mysqli_num_rows($findMember2) >0 ){
			 		$usernameError = "Someone have use this username already";
			 	} else {
			 		$username = $_POST['username'];
			 	}
		}

		//if there is no errors, update trainer info
		if($emailError == "" && $usernameError == "" && $passwordError == "" && $conPassError == "" && $expError == ""&& $nameError == ""  && $cnomborError == "" && $cnomborError==""&& $orgError==""&&$addressError==""&&$posError==""){
			mysqli_query($connect, "UPDATE `JobProvider` SET `username` = '".addslashes($username)."',`password` = '".addslashes($password)."', `email` = '".addslashes($email)."', `fullName` = '".addslashes($fname)."', `companyName` = '".addslashes($companyName)."',`contactNo` = '".addslashes($cnombor)."',`companyAddress` = '".addslashes($companyAddress)."',`position` = '".addslashes($position)."' WHERE `userID` = '".$user['userID']."'");

			$_SESSION['name'] = $fname;
			$_SESSION['username'] = $username;
			$_SESSION['passThruMessage']="Your job provider account info modified successfully.";
			header("Location: index.php");
		}else{
			$passThruMessage="Please correct mentioned errors.";
		}

	}

?><!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Modify Job Provider</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/jinjang.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
</head>
<body>
	<!-- start of header -->
	<?php 
	include("header.php"); 
	?>
	<!-- end of header -->

	<!--content-->
	<div class="container marginTB">
		<br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" onsubmit="return validateTrainerForm();"  method="POST" action="modifyJP.php">
			<div class="formStyle">
				<p><Strong>Modify your job provider account info</Strong></p>
				<span>New Password:<br>(Leave password empty to keep unchanged)</span>
				<?php if(isset($passwordError)){ echo '<span style="color:#FF0000;">'.$passwordError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="psw"><i class="fa fa-key"></i></label></span>
					<input class="form-control" placeholder="Please enter a new password (optional)" type="password" id="psw" name="password">
				</div>

				<span>Repeat Password again:</span>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon3"><label for="psw"><i class="fa fa-key"></i></label></span>
					<input class="form-control" placeholder="Repeat Password" type="password" id="psw2" name="conPass">
				</div>

				<span>Username:</span>
				<?php if(isset($usernameError)){ echo '<span style="color:#FF0000;">'.$usernameError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="username"><i class="glyphicon glyphicon-user"></i></label></span>
					<input class="form-control" placeholder="Please enter your username" type="text" id="username" name="username" value="<?php echo $user['username']; ?>">
				</div>
				
				
				<span>Email:</span>
				<?php if(isset($emailError)){ echo '<span style="color:#FF0000;">'.$emailError.'</span>';;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="glyphicon glyphicon-envelope"></i></label></span>
					<input class="form-control" placeholder="Please enter your email" type="email" id="email" name="email" value="<?php echo $user['email']; ?>">
				</div>
				
				<span>Name:</span>
				<?php if(isset($nameError)){ echo '<span style="color:#FF0000;">'.$nameError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-user-circle"></i></label></span>
					<input class="form-control" placeholder="Please enter your Full Name" type="text" id="fname" name="fname" value="<?php echo $user['fullName']; ?>">
				</div>

				<span>Contact Number:</span>
				<?php if(isset($cnomborError)){ echo '<span style="color:#FF0000;">'.$cnomborError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="cnombor"><i class="fa fa-phone"></i></label></span>
					<input class="form-control" placeholder="Please enter contact Number" type="text" id="cnombor" name="cnombor" value="<?php echo $user['contactNo']; ?>">
				</div>


				<span>Company Name:</span>
				<?php if(isset($orgError)){ echo '<span style="color:#FF0000;">'.$orgError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="companyName"><i class="fa fa-bolt"></i></label></span>
					<input class="form-control" placeholder="Please enter company name" type="text" id="companyName" name="companyName" value="<?php echo $user['companyName']; ?>">
				</div>

				<span>Company Address:</span>
				<?php if(isset($addressError)){ echo '<span style="color:#FF0000;">'.$addressError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="companyAddress"><i class="fa fa-bolt"></i></label></span>
					<input class="form-control" placeholder="Please enter company address" type="text" id="companyAddress" name="companyAddress" value="<?php echo $user['companyAddress']; ?>">
				</div>



				<span>Your position:</span>
				<?php if(isset($posError)){ echo '<span style="color:#FF0000;">'.$posError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="position"><i class="fa fa-bolt"></i></label></span>
					<input class="form-control" placeholder="Please enter position" type="text" id="position" name="position" value="<?php echo $user['position']; ?>">
				</div>




				<button type="submit" class="btn btn-success btn-block btn-lg formButton">Update</button>
			</div>
		</form>
	</div>
<br>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	

	

</body>
</html>
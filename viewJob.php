<?php
//load startup scripts
include("config.php");
include("control.php");

//if user is already logged in, bring him to welcome page
if(isset($_SESSION['id']) && $_SESSION['id'] <= 0){
	header("Location: LogIn.php"); exit;
}

if(isset($_GET['jobID'])){
	$job = $_GET['jobID'];
	$detail = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `Jobs` WHERE `jobID` = '$job';"));
} 
//else {
// 	header("Location: jobs.php"); exit;
// }

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Login</title>
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
		<h2><strong>#<?php if(isset($detail)){echo $detail['jobID']." ".$detail['jobTitle'];} ?> Info</strong></h2>
		<div class="well"><?php if(isset($detail)){echo $detail['description'];} ?></div>
		<br>
		<div class="row">
			<div class="col-md-6 col-xs-12">Hourly Rate: RM<?php if(isset($detail)){echo $detail['hourlyRate'];} ?></div>
			<div class="col-md-6 col-xs-12">Location: <?php if(isset($detail)){echo $detail['location'];} ?></div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Requirement: <?php if(isset($detail)){echo $detail['requirement'];} ?></div>
			<div class="col-md-6 col-xs-12">Start Date: <?php if(isset($detail)){echo $detail['startDateTime'];} ?></div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Participants: <?php if(isset($detail)){echo $detail['maxParticipant'];} ?></div>
			<div class="col-md-6 col-xs-12">End Date: <?php if(isset($detail)){echo $detail['endDateTime'];} ?></div>
		</div>
		<br>
		<br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" method="POST" action="LogIn.php">
			<div class="formStyle">
				<p><Strong>Detail Information For Job #<?php if(isset($detail)){echo $detail['jobID'];} ?></Strong></p>
				<div class="input-group">
				  	<span class="input-group-addon" id="basic-addon1"><label for="jobTitle">Job Title: </label></span>
					<input type="text" class="form-control" id="jobTitle" name="jobTitle" value="<?php if(isset($detail)){echo $detail['jobTitle'];} ?>">
				</div>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2"><label for="pwd"><i class="fa fa-key"></i></label></span>
					<input class="form-control" required placeholder="Please enter your password" type="password" id="pwd" name="password">
				</div>
				<button type="submit" class="btn btn-success btn-block btn-lg formButton">Log In</button>
				<?php if(isset($usernameError)){echo $usernameError;}?>
				<?php if(isset($passwordError)){echo $passwordError;}?>
				<hr>
				<div class="center">
				<small>Or <a href="signup.php">sign up for a new account</a></small>
				</div>
			</div>
		</form>
		<div class="clear"></div>
		<br>
		<br>
		<br>
	</div>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>
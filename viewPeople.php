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

	$infoPp = mysqli_query($connect, "SELECT * FROM `jobFinder` WHERE `userID` = '".$_GET['UID']."'");
	if(mysqli_num_rows($infoPp) != 1){
		$_SESSION['passThruMessage'] = "This user is no longer in our database.";
		header("Location: modifyJob.php?jobID=".$_GET['JID'].""); die();
	} else {
		$info = mysqli_fetch_assoc($infoPp);
	}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Modify Job Finder</title>
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
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">
			<div class="formStyle">
				<p><Strong>Information about <?php echo $info['fullName']; ?></Strong></p>

				<span>Username:</span>
				<?php if(isset($usernameError)){ echo $usernameError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="username"><i class="glyphicon glyphicon-user"></i></label></span>
					<input class="form-control" readonly placeholder="Please enter your username" type="text" id="username" name="username" value="<?php echo $info['username']; ?>">
				</div>
				
				<span>Email:</span>
				<?php if(isset($emailError)){ echo $emailError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="glyphicon glyphicon-envelope"></i></label></span>
					<input class="form-control" readonly placeholder="Please enter your email" type="email" id="email" name="email" value="<?php echo $info['email']; ?>">
				</div>
				
				<span>Name:</span>
				<?php if(isset($nameError)){ echo $nameError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-user-circle"></i></label></span>
					<input class="form-control" readonly placeholder="Please enter your Full Name" type="text" id="fname" name="fname" value="<?php echo $info['fullName']; ?>">
				</div>

				<span>Contact Number:</span>
				<?php if(isset($cnomborError)){ echo $cnomborError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="cnombor"><i class="fa fa-phone"></i></label></span>
					<input class="form-control" readonly placeholder="Please enter contact Number" type="text" id="cnombor" name="cnombor" value="<?php echo $info['contactNo']; ?>">
				</div>

				<span>Expected Salary:</span>
				<?php if(isset($salaryError)){ echo $salaryError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="expectedSalary"><i class="fa fa-bolt"></i></label></span>
					<input class="form-control" readonly placeholder="Please enter expected salary" type="number" id="expectedSalary" name="expectedSalary" value="<?php echo $info['expectedSalary']; ?>">
				</div>


				<span>Experience History:</span>
				<?php if(isset($expError)){ echo $expError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="experienceHistory"><i class="fa fa-bolt"></i></label></span>
					<textarea class="form-control" readonly placeholder="Please enter experience history" type="text" id="experienceHistory" name="experienceHistory"><?php echo $info['experienceHistory']; ?></textarea>
				</div>





				<span>Education Level:</span>
				<?php if(isset($eduError)){ echo $eduError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="educationLevel"><i class="fa fa-bolt"></i></label></span>
							<select class="form-control" name="educationLevel" id="educationLevel">
								<option value="">Please choose your education level</option>
								<option value="Primary School"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='Primary School') echo ' selected'; ?>>Primary School</option>
								<option value="High School"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='High School') echo ' selected'; ?>>High School</option>
								<option value="Diploma"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='Diploma') echo ' selected'; ?>>Diploma</option>
								<option value="Degree"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='Degree') echo ' selected'; ?>>Degree</option>
								<option value="Master"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='Master') echo ' selected'; ?>>Master</option>
								<option value="PhD"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='PhD') echo ' selected'; ?>>PhD</option>
								<option value="ALevel"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='ALevel') echo ' selected'; ?>>ALevel</option>
								<option value="Foundation"  <?php if (isset($info['educationLevel'])&&$info['educationLevel']=='Foundation') echo ' selected'; ?>>Foundation</option>

							</select>
				</div>

				<span>Skills:</span>
				<?php if(isset($skillError)){ echo $skillError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="skills"><i class="fa fa-bolt"></i></label></span>
							<select name="skills[]" id="skills" class="form-control" multiple="true">
								<?php
								$skillsq=mysqli_query($connect,"select * from skills where hide=0;");
								while ($skill=mysqli_fetch_assoc($skillsq)){
									?>
									<option value="<?php echo $skill['skill']; ?>" <?php if (isset($skillArr)&&is_array($skillArr)&& in_array($skill['skill'], $skillArr)) echo ' selected'; ?>><?php echo $skill['skill']; ?></option>
									<?php
								}
								?>
							</select>
				</div>


				<span>Languages:</span>
				<?php if(isset($langError)){ echo $langError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="languages"><i class="fa fa-bolt"></i></label></span>
							<select class="form-control" name="languages[]" id="languages" multiple="true">
								<option value="Mandarin"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Mandarin", $languageArr)) echo ' selected'; ?>>Mandarin</option>
								<option value="Bahasa Malaysia"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Bahasa Malaysia", $languageArr)) echo ' selected'; ?>>Bahasa Malaysia</option>
								<option value="English"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("English", $languageArr)) echo ' selected'; ?>>English</option>
								<option value="Indonesian"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Indonesian", $languageArr)) echo ' selected'; ?>>Indonesian</option>
								<option value="Cantonese"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Cantonese", $languageArr)) echo ' selected'; ?>>Cantonese</option>
								<option value="Hokkien"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Hokkien", $languageArr)) echo ' selected'; ?>>Hokkien</option>
								<option value="Hakka"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Hakka", $languageArr)) echo ' selected'; ?>>Hakka</option>
								<option value="Tamil"  <?php if (isset($languageArr)&&is_array($languageArr)&& in_array("Tamil", $languageArr)) echo ' selected'; ?>>Tamil</option>
							</select>
				</div>

			</div>
		</form>
	</div>
<br>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	

	

</body>
</html>
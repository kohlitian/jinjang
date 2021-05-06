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


//job providers are not allowed to edit job finders info
if(isset($user) && $user['type'] == "jobProvider"){
	$_SESSION['passThruMessage'] = "Sorry ! You are not allowed to access to this page ";
	header("Location: index.php");
}
	//if form is posted
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		$conPass = $_POST['conPass'];

		$usernameError = ""; $passwordError = ""; $nameError = ""; $emailError = "";$expError = ""; $langError="";$skillError=""; $eduError=""; $cnomborError=""; 
		
		//validate form data
		if(!empty($_POST['experienceHistory'])){
			$experienceHistory = $_POST['experienceHistory'];
		} else if($_POST['experienceHistory'] == ""){
			$expError = "Please enter your experience history";
		} else {
			$experienceHistory = $user['experienceHistory'];
		}

		if(!empty($_POST['expectedSalary'])){
			$expectedSalary = $_POST['expectedSalary'];
		} else if($_POST['expectedSalary'] == ""){
			$salaryError = "Please enter expected salary";
		} else {
			$expectedSalary = $user['expectedSalary'];
		}


		if (is_array($_POST['skills'])&&(is_array($_POST['skills'])&&count($_POST['skills'])>0)){
			$skills = addslashes(implode(",",$_POST['skills']));
		} else if (!is_array($_POST['skills'])||(is_array($_POST['skills'])&&count($_POST['skills'])==0)){
			$skillError = "Please choose a skill";
		} else {
			$skills = $user['skills'];
		}

		if(!empty($_POST['educationLevel'])){
			$educationLevel = $_POST['educationLevel'];
		} else if($_POST['educationLevel'] == ""){
			$eduError = "Please enter education level";
		} else {
			$educationLevel = $user['educationLevel'];
		}


		if (is_array($_POST['languages'])&&(is_array($_POST['languages'])&&count($_POST['languages'])>0)){
			$languages = addslashes(implode(",",$_POST['languages']));
		} else if (!is_array($_POST['languages'])||(is_array($_POST['languages'])&&count($_POST['languages'])==0)){
			$langError = "Please choose a language";
		} else {
			$languages = $user['languages'];
		}



		if(empty($_POST['password'])){
			$password = $user['password'];
		} else {
			if($conPass != $_POST['password']){
				$passwordError = "Both password must be same";
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
			 	$find = "SELECT `email` FROM `JobProvider` WHERE `email` = '".addslashes($_POST['email'])."' ";
			 	$find2 = "SELECT `email` FROM `JobFinder` WHERE `email` = '".addslashes($_POST['email'])."'  and `userID`!='".$user['userID']."';";
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
			$usernameError = "Please enter a username";
		} else {
			 	$find = "SELECT `username` FROM `JobProvider` WHERE `username` = '".addslashes($_POST['username'])."' ;";
			 	$find2 = "SELECT `username` FROM `JobFinder` WHERE `username` = '".addslashes($_POST['username'])."'  and `userID`!='".$user['userID']."';";
			 	$findMember = mysqli_query($connect, $find);
			 	$findMember2 = mysqli_query($connect, $find2);
			 	if(mysqli_num_rows($findMember) >0 || mysqli_num_rows($findMember2) >0 ){
			 		$usernameError = "Someone have use this username already";
			 	} else {
			 		$username = $_POST['username'];
			 	}
		}

		//if there is no errors, update trainer info
		if($emailError == "" && $usernameError == "" && $passwordError == "" && $conPassError == "" && $expError == ""&& $nameError == ""  && $cnomborError == "" && $selectError == "" && $salaryError==""&&$langError==""&&$skillError==""  && $eduError==""){
			mysqli_query($connect, "UPDATE `JobFinder` SET `username` = '".addslashes($username)."',`password` = '".addslashes($password)."', `email` = '".addslashes($email)."', `fullName` = '".addslashes($fname)."' ,`contactNo` = '".addslashes($cnombor)."',`experienceHistory` = '".addslashes($experienceHistory)."',`expectedSalary` = '".addslashes($expectedSalary)."',`skills` = '".addslashes($skills)."',`educationLevel` = '".addslashes($educationLevel)."',`languages` = '".$languages."' WHERE `userID` = '".$user['userID']."';");

			$_SESSION['name'] = $fname;
			$_SESSION['username'] = $username;
			$_SESSION['passThruMessage']="Your job finder account info modified successfully.";
			header("Location: index.php");
		}else{
			$passThruMessage="Please correct mentioned errors.";
		}

	}

		$languageArr=explode(",", $user['languages']);
		$skillArr=explode(",", $user['skills']);

?><!DOCTYPE HTML>
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
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" onsubmit="return validateTrainerForm();"  method="POST" action="modifyJF.php">
			<div class="formStyle">
				<p><Strong>Modify your job finder account info</Strong></p>
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
				<?php if(isset($emailError)){ echo '<span style="color:#FF0000;">'.$emailError.'</span>';} ?>
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

				<span>Expected Salary:</span>
				<?php if(isset($salaryError)){ echo '<span style="color:#FF0000;">'.$salaryError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="expectedSalary"><i class="fa fa-bolt"></i></label></span>
					<input class="form-control" placeholder="Please enter expected salary" type="number" id="expectedSalary" name="expectedSalary" value="<?php echo $user['expectedSalary']; ?>">
				</div>


				<span>Experience History:</span>
				<?php if(isset($expError)){ echo '<span style="color:#FF0000;">'.$expError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="experienceHistory"><i class="fa fa-bolt"></i></label></span>
					<textarea class="form-control" placeholder="Please enter experience history" type="text" id="experienceHistory" name="experienceHistory"><?php echo $user['experienceHistory']; ?></textarea>
				</div>





				<span>Education Level:</span>
				<?php if(isset($eduError)){ echo '<span style="color:#FF0000;">'.$eduError.'</span>';} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="educationLevel"><i class="fa fa-bolt"></i></label></span>
							<select class="form-control" name="educationLevel" id="educationLevel">
								<option value="">Please choose your education level</option>
								<option value="Primary School"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='Primary School') echo ' selected'; ?>>Primary School</option>
								<option value="High School"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='High School') echo ' selected'; ?>>High School</option>
								<option value="Diploma"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='Diploma') echo ' selected'; ?>>Diploma</option>
								<option value="Degree"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='Degree') echo ' selected'; ?>>Degree</option>
								<option value="Master"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='Master') echo ' selected'; ?>>Master</option>
								<option value="PhD"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='PhD') echo ' selected'; ?>>PhD</option>
								<option value="ALevel"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='ALevel') echo ' selected'; ?>>ALevel</option>
								<option value="Foundation"  <?php if (isset($user['educationLevel'])&&$user['educationLevel']=='Foundation') echo ' selected'; ?>>Foundation</option>

							</select>
				</div>

				<span>Skills:</span>
				<?php if(isset($skillError)){ echo '<span style="color:#FF0000;">'.$skillError.'</span>';} ?>
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
				<?php if(isset($langError)){ echo '<span style="color:#FF0000;">'.$langError.'</span>';} ?>
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
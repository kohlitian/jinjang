
<?php
//load default startup scripts
include("config.php");
include("control.php");

//if user is already registered, don't allow register again
if(isset($_SESSION['id']) && $_SESSION['id'] > 0){
	$_SESSION['passThruMessage'] = "You have to log out first then only can sign up";
	header("Location: index.php"); exit;
}

$no_error=1;

	//if form is posted
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		//get data from form POST method
		$comfirmPassword = $_POST["comfirmPassword"];
		$ID = "";
		$signupDate = time();
		$loginDate = time();

		$contactNo=$_POST['cnombor'];
		
		$experienceHistory = $_POST['experienceHistory'];
		$expectedSalary = $_POST['expectedSalary'];
		$skills = $_POST['skills'];
		$languages = $_POST['languages'];
		$educationLevel = $_POST['educationLevel'];

		$companyName = $_POST['companyName'];
		$companyAddress = $_POST['companyAddress'];
		$position = $_POST['position'];

		//define required variables
		$usernameError=""; $emailError=""; $passwordError=""; $conPassError=""; $nameError=""; $expError = ""; $salaryError="";$langError="";$skillError=""; $eduError=""; $cnomborError=""; $orgError="";$addressError="";$posError="";

		//validate email
		if(empty($_POST['email'])){
			$emailError = "Email cannot be empty";
		}else {
			 if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			 	$emailError = "Invalid email format";
			 } else {
			 	//make sure email is not used before
			 	$find = "SELECT `email` FROM `JobFinder` WHERE `email` = '".addslashes($_POST['email'])."'";
			 	$findMemberMail = mysqli_query($connect, $find);
			 	$find = "SELECT `email` FROM `JobProvider` WHERE `email` = '".addslashes($_POST['email'])."'";
			 	$findproviderMail = mysqli_query($connect, $find);
			 	if(mysqli_num_rows($findMemberMail) >0 || mysqli_num_rows($findproviderMail) >0){
			 		$emailError = "Someone have use this email already";
			 	} else {
			 		$email = $_POST['email'];
			 	}
			 }
		}

		//validate username
		if(empty($_POST['username'])){
			$usernameError = "Username cannot be empty";
		}else {
			//make sure username is not used before
		 	$find = "SELECT `username` FROM `JobFinder` WHERE `username` = '".addslashes($_POST['username'])."'";
		 	$findMember = mysqli_query($connect, $find);
		 	$find = "SELECT `username` FROM `JobProvider` WHERE `username` = '".addslashes($_POST['username'])."'";
		 	$findprovider = mysqli_query($connect, $find);
		 	if(mysqli_num_rows($findMember) >0 || mysqli_num_rows($findprovider) >0){
		 		$usernameError = "Someone have use this username already";
		 	} else {
		 		$username = $_POST['username'];
		 	}
		}
		
		//validate password
		if(empty($_POST['password'])){
			$passwordError = "Password cannot be empty";
		} else {
			$password = $_POST["password"];
		}

		//validate password confirmation
		if(! $comfirmPassword == $password){
			$conPassError = "Both password must be same";
		}

		//validate user full name
		if(empty($_POST['fname'])){
			$nameError = "Name is required";
		} 
		else{
			//make sure name is valid
			if(!(preg_match("/^[a-zA-Z -]+$/", $_POST['fname']))){
				$nameError = "Only letters and white space allowed";
			} else {
				$fname = $_POST["fname"];
			}
		}

		//validate user full name
		if(empty($_POST['cnombor'])){
			$cnomborError = "Contact Number is required";
		}


		//validate user type and level and speciality
		if($_POST['userType'] == "jf"){
			if ($experienceHistory==""){
			$expError = "Please type your experience history";
			}
			if ($expectedSalary==""){
			$salaryError = "Please type your desired salary";
			}
			if (!is_array($skills)||(is_array($skills)&&count($skills)==0)){
			$skillError = "Please choose your skill";
			}
			if (!is_array($languages)||(is_array($languages)&&count($languages)==0)){
			$langError = "Please choose your language";
			}
			if ($educationLevel==""){
			$eduError = "Please choose your education level";
			}


		}else if ($_POST['userType'] == "jp"){
			if ($companyName==""){
			$orgError = "Please type your organization name";
			}
			if ($companyAddress==""){
			$addressError = "Please type your organization address";
			}
			if ($position==""){
			$posError = "Please type your position";
			}
		}

		//check if any error occured

		if($emailError == "" && $usernameError == "" && $passwordError == "" && $conPassError == "" && $expError == ""&& $nameError == ""  && $cnomborError == "" && $selectError == "" && $salaryError==""&&$langError==""&&$skillError==""  && $eduError==""&& $cnomborError==""&& $orgError==""&&$addressError==""&&$posError==""){
			//insert data into database if no errors
			if($_POST['userType']=='jf'){
				$signUp = "INSERT INTO `JobFinder` (`userID`, `email`, `username`, `password`, `fullName`, `contactNo`,`experienceHistory`,`educationLevel`,`expectedSalary`,`skills`,`languages`) VALUES ('$ID', '".addslashes($email)."','".addslashes($username)."', '".addslashes($password)."', '".addslashes($fname)."', '".addslashes($contactNo)."', '".addslashes($experienceHistory)."', '".addslashes($educationLevel)."', '".addslashes($expectedSalary)."', '".addslashes(implode(",",$skills))."', '".addslashes(implode(",",$languages))."')";
			} else {
				$signUp = "INSERT INTO `JobProvider` (`userID`, `email`, `username`, `password`, `fullName`, `contactNo`, `companyName`, `companyAddress`, `position`) VALUES ('$ID', '".addslashes($email)."', '".addslashes($username)."', '".addslashes($password)."', '".addslashes($fname)."', '".addslashes($contactNo)."', '".addslashes($companyName)."', '".addslashes($companyAddress)."', '".addslashes($position)."')";
			}
			if(mysqli_query($connect, $signUp)){
				//notify user about successfull signup
				$_SESSION['passThruMessage']="Your account has been created successfully. You can login now.";
				header('Location: logIn.php'); exit;
			}
		}else{
			$no_error=0;


		}
	}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Sign Up</title>
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
		<br><br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" method="POST" action="#" onsubmit="return validateSignupForm();">



			<div class="formStyle">

				<p><Strong>Register to your JinJang E-Business Account</Strong></p>
				
				<div class="reg-type center">
					<small>Please choose which user type describe your role:</small>
					<div>
							<a href="#" onclick="btnregJP();" class="regtypebtn btn btn-lg btn-primary col-xs-6 noround btnregtrainer" id="btnregtrainer">
								
								<i class="fa fa-slideshare verybigtext"  id="bigicon"></i>
								<div class="smartlinebreaker"></div>
								Job Provider
							</a>
		
							<a href="#" onclick="btnregJF();" class="regtypebtn btn btn-lg btn-success col-xs-6 noround btnregmember" id="btnregmember">
								<i class="fa fa-user-circle-o verybigtext" id="bigicon1"></i>
								<div class="smartlinebreaker"></div>
								Job Finder
							</a>
					</div>
					<div class="clear"></div>
				</div>

				<div class="reg-form hidden-always" id="reg-form">



					<div class="form-group hidden-always">
						<select class="form-control" name="userType" id="userType">
							<option value="jp">Job Provider</option>
							<option value="jf">Job Finder</option>
						</select>
					</div>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="username"><i class="glyphicon glyphicon-user"></i></label></span>
						<input class="form-control" placeholder="Please enter your username" type="text" id="username" name="username" required value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
					</div>
					<?php if(isset($usernameError)){ ?><span class="error"><?php echo $usernameError; ?></span><?php } ?>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="psw"><i class="fa fa-key"></i></label></span>
						<input class="form-control" placeholder="Enter a new Password" type="password" id="psw" name="password" required  value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>">
					</div>
					<?php if(isset($passwordError)){ ?><span class="error"><?php echo $passwordError; ?></span><?php } ?>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3"><label for="psw"><i class="fa fa-key"></i></label></span>
						<input class="form-control" placeholder="Repeat Password" type="password" id="psw2" name="comfirmPassword" required  value="<?php if (isset($_POST['comfirmPassword'])) echo $_POST['comfirmPassword']; ?>">
					</div>
					<?php if(isset($conPassError)){ ?><span class="error"><?php echo $conPassError; ?></span><?php } ?>

					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="glyphicon glyphicon-envelope"></i></label></span>
						<input class="form-control" placeholder="Please enter your email" type="email" id="email" name="email" required value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
					</div>
					<?php if(isset($emailError)){ ?><span class="error"><?php echo $emailError; ?></span><?php } ?>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-user-circle"></i></label></span>
						<input class="form-control" placeholder="Full Name" type="text" id="fname" name="fname" required  value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>">
					</div>
					<?php if(isset($nameError)){ ?><span class="error"><?php echo $nameError; ?></span><?php } ?>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="cnombor"><i class="fa fa fa-phone"></i></label></span>
						<input class="form-control" placeholder="Contact Number" type="number" id="cnombor" name="cnombor" required  value="<?php if (isset($_POST['cnombor'])) echo $_POST['cnombor']; ?>">
					</div>
					<?php if(isset($cnomborError)){ ?><span class="error"><?php echo $cnomborError; ?></span><?php } ?>


					<div id="memberelement"  style="width: 100%;">
						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="experienceHistory"><i class="fa fa-bolt"></i></label></span>
							<textarea class="form-control" placeholder="Experience History" type="text" id="experienceHistory" name="experienceHistory"><?php if (isset($_POST['experienceHistory'])) echo $_POST['experienceHistory']; ?></textarea>

						</div>
						<?php if(isset($expError)){ ?><span class="error"><?php echo $expError; ?></span><?php } ?>

						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="expectedSalary"><i class="fa fa-bolt"></i></label></span>
							<input class="form-control" placeholder="Expected Salary" type="number" id="expectedSalary" name="expectedSalary" value="<?php if (isset($_POST['expectedSalary'])) echo $_POST['expectedSalary']; ?>">

						</div>
						<?php if(isset($salaryError)){ ?><span class="error"><?php echo $salaryError; ?></span><?php } ?>

						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="skills"><i class="fa fa-bolt"></i></label></span>
							<select name="skills[]" id="skills" class="form-control" multiple="true">
								<?php
								$skillsq=mysqli_query($connect,"select * from skills where hide=0;");
								while ($skill=mysqli_fetch_assoc($skillsq)){
									?>
									<option value="<?php echo $skill['skill']; ?>" <?php if (isset($_POST['skills'])&&is_array($_POST['skills'])&& in_array($skill['skill'], $_POST['skills'])) echo ' selected'; ?>><?php echo $skill['skill']; ?></option>
									<?php
								}
								?>
							</select>


						</div>
						<?php if(isset($skillError)){ ?><span class="error"><?php echo $skillError; ?></span><?php } ?>



						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="languages"><i class="fa fa-bolt"></i></label></span>
		
							<select class="form-control" name="languages[]" id="languages" multiple="true">

								<option value="Mandarin"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Mandarin", $_POST['languages'])) echo ' selected'; ?>>Mandarin</option>
								<option value="Bahasa Malaysia"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Bahasa Malaysia", $_POST['languages'])) echo ' selected'; ?>>Bahasa Malaysia</option>
								<option value="English"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("English", $_POST['languages'])) echo ' selected'; ?>>English</option>
								<option value="Indonesian"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Indonesian", $_POST['languages'])) echo ' selected'; ?>>Indonesian</option>
								<option value="Cantonese"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Cantonese", $_POST['languages'])) echo ' selected'; ?>>Cantonese</option>
								<option value="Hokkien"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Hokkien", $_POST['languages'])) echo ' selected'; ?>>Hokkien</option>
								<option value="Hakka"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Hakka", $_POST['languages'])) echo ' selected'; ?>>Hakka</option>
								<option value="Tamil"  <?php if (isset($_POST['languages'])&&is_array($_POST['languages'])&& in_array("Tamil", $_POST['languages'])) echo ' selected'; ?>>Tamil</option>

							</select>



						</div><small>Hold Ctrl/CMD to choose multiple</small>
						<?php if(isset($langError)){ ?><span class="error"><?php echo $langError; ?></span><?php } ?>
						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="educationLevel"><i class="fa fa-bolt"></i></label></span>
							<select class="form-control" name="educationLevel" id="educationLevel">
								<option value="">Please choose your education level</option>
								<option value="Primary School"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='Primary School') echo ' selected'; ?>>Primary School</option>
								<option value="High School"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='High School') echo ' selected'; ?>>High School</option>
								<option value="Diploma"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='Diploma') echo ' selected'; ?>>Diploma</option>
								<option value="Degree"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='Degree') echo ' selected'; ?>>Degree</option>
								<option value="Master"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='Master') echo ' selected'; ?>>Master</option>
								<option value="PhD"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='PhD') echo ' selected'; ?>>PhD</option>
								<option value="ALevel"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='ALevel') echo ' selected'; ?>>ALevel</option>
								<option value="Foundation"  <?php if (isset($_POST['educationLevel'])&&$_POST['educationLevel']=='Foundation') echo ' selected'; ?>>Foundation</option>

							</select>

						</div>
						<?php if(isset($eduError)){ ?><span class="error"><?php echo $eduError; ?></span><?php } ?>
					</div>
					

					<div  id="trainerelement" style="width: 100%;">
			
						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="companyName"><i class="fa fa-bolt"></i></label></span>
							<input class="form-control" placeholder="Company Name" type="text" id="companyName" name="companyName" value="<?php if (isset($_POST['companyName'])) echo $_POST['companyName']; ?>">

						</div>
						<?php if(isset($orgError)){ ?><span class="error"><?php echo $orgError; ?></span><?php } ?>
						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="companyAddress"><i class="fa fa-bolt"></i></label></span>
							<input class="form-control" placeholder="Company Address" type="text" id="companyAddress" name="companyAddress" value="<?php if (isset($_POST['companyAddress'])) echo $_POST['companyAddress']; ?>">

						</div>
						<?php if(isset($addressError)){ ?><span class="error"><?php echo $addressError; ?></span><?php } ?>
						<div class="input-group">

							<span class="input-group-addon" id="basic-addon2"><label for="position"><i class="fa fa-bolt"></i></label></span>
							<input class="form-control" placeholder="Your Position" type="text" id="position" name="position" value="<?php if (isset($_POST['position'])) echo $_POST['position']; ?>">

						</div>
						<?php if(isset($posError)){ ?><span class="error"><?php echo $posError; ?></span><?php } ?>
					</div>

					<?php if(isset($selectError)){ ?><span class="error"><?php echo $selectError; ?></span><?php } ?>
					<button type="submit" class="btn btn-success btn-block btn-lg formButton">Register</button>
				</div>
				<hr>
					<div class="center">
					<small>Or <a href="LogIn.php">login to your account</a></small>
					</div>
			</div>
		</form>
	</div><br><br><br>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

<?php
if ($no_error==0){

	if($_POST['userType'] == 'jf'){
?>
<script type="text/javascript">btnregJF();

	bootbox.alert("Please correct mentioned errors.");

</script>
<?php 
	} else {
?>
<script type="text/javascript">btnregJP();

	bootbox.alert("Please correct mentioned errors.");

</script>
<?php }} ?>

</body>
</html>
<?php
//load startup scripts
include("config.php");
include("control.php");

//check if user is logged in
// if(!isset($_SESSION['id'])){
// 	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
// 	header("Location: login.php");
// 	die();
// }

//if a member came to this page, bring him out to home page
if($user['type'] == "jobFinder"){
	$_SESSION['passThruMessage'] = "Sorry! You are not allowed to access to the page!";
	header("Location: Home.php"); exit;
}
else {
	//if form is posted
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){


		$titleError = ""; $salaryError = ""; $dateError = ""; $classError = ""; $trainingError = "";

		//validate enteries
		if(empty($_POST['titleName'])){
			$titleError = "please enter a job title";
		} else {
			$title = $_POST['titleName'];
		}
		if(!empty($_POST['salary'])){
			if($_POST['salary'] < 0){
				$salaryError = "Salary cannot be negative";
			} else {
				if(is_numeric($_POST['salary'])){
					$salary = $_POST['salary'];
				} else {
					$salaryError = "Salary must be numeric";
				}
			}
		} else {
			$salaryError = "Please enter a salary";
		}

		//validate start date of job
		if(empty($_POST['startDate'])){
			$startDateError = "Please choose starting date for this job";
		} else {
			if(time()>strtotime($_POST['startDate'])){
				$dateError = "Today is ".date("Y-m-d H:i:s", time())." new Job must be set after.";
			} else {
				$startDateC = strtotime($_POST['startDate']);
			}
		}

		//validate end date of job
		if(empty($_POST['endDate'])){
			$endDateError = "Please choose ending date for this job";
		} else {
			if(time()>strtotime($_POST['endDate'])){
				if(strtotime($_POST['endDate'])<strtotime($_POST['startDate'])){
					$endDateError = "End working date cannot be early than start working date.";
				} else {
					$endDateError = "Today is".date("Y-m-d H:i:s", time())." new Job must be set after.";
				}
			} else {
				$endDateC = strtotime($_POST['endDate']);
			}
		}

		//validate deadline date of job recruitment 
		if(empty($_POST['deadline'])){
			$deadlineError = "Please choose a deadline for this job recruitment.";
		} else {
			if(time()>strtotime($_POST['deadline'])){
				$deadlineError = "Today is ".date("Y-m-d H:i:s", time())." deadline must be after.";
			} else {
				$deadlineC = strtotime($_POST['deadline']);
			}
		}

		//validate time and make sure time is valid
		if(empty($_POST['datetimepicker'])){
			$dateError = "Please choose a date and time";
		} else {
			if(time()>strtotime($_POST['datetimepicker']) ){
				$dateError = "Today is ".date("Y-m-d H:i:s", time())." new Session must be set after this time.";
			} else {
				$time = strtotime($_POST['datetimepicker']);
			}
		}


		if($_POST['classtype'] == ""){
			$classError = "Please choose a training type";
		} else {
			$classType = $_POST['classtype'];
		}

		if(isset($_POST['trainingType'])){
			if($_POST['trainingType'] == ""){
				if ($_POST["classtype"] == "Group")
						$trainingError = "Please choose a activity";
			} else {
				$trainingType = $_POST['trainingType'];
			}
			if($_POST["classtype"] == "Personal"){
				$participant = 1;
			} else{
				$participant = $_POST['participant'];
			}
		}


		//if there is no error, insert training session into database
		if($titleError =="" && $salaryError =="" && $dateError =="" && $classError =="" && $trainingError ==""){
			$newSession = "INSERT INTO `TrainingSessions` (`sessionID`, `title`, `datetime`, `fee`, `status`, `note`, `trainingType`, `classType`, `maxParticipants`, `trainerID`) VALUES ('', '".addslashes($name)."', '$time', '$fee', 'Available', '', '$classType', '$trainingType', '$participant' , '".$user['trainerID']."') ";
			if(mysqli_query($connect, $newSession)){
				$_SESSION['passThruMessage']="Your new session has been added successfully.";
				header('Location: myTraining.php'); exit;
			} 
		}else{
			$passThruMessage="Please correct mentioned errors";
		}
	}
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>New Training</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.min.css">
	<link rel="stylesheet" type="text/css" href="css/helpfit.css">
	
	<style type="text/css">
		form div span{
			color: #9E9F9E;
		}
	</style>
</head>
<body>
	<!-- start of header -->
	<?php 
		if($_SESSION['id'] > 0){
			include("headerAfterLog.php");
		} else{
			include("header.php"); 
		}
	?>
	<!-- end of header -->

	<!--content-->
	<div class="container marginTB">
		<h3>Add New Job By <?php echo $user['fullName'] ?></h3>
		<form method="POST" action="newJob.php">
			<div class="marginTB">
				<span>Job Title</span>
				<?php if(isset($titleError)){echo $titleError;} ?>
				<input class="input-lg form-control" type="text" name="jobTitle" placeholder="Please enter title of training session" required value="<?php if (isset($_POST['jobTitle'])&&$_POST['jobTitle']) echo $_POST['jobTitle']; ?>">
			</div>
			
			<div class="row">
				<div class="col-sm-6">
					<span>Salary</span>
					<?php if(isset($salaryError)){echo $salaryError;} ?>
					<div class="input-group noSpaceTop">
						<span class="input-group-addon" id="basic-addon1"><label for="price">RM</label></span>
						<input class="form-control" type="number" name="salary" required id="price" placeholder="Type amount of Salary for job"  value="<?php if (isset($_POST['salary'])&&$_POST['salary']) echo $_POST['salary']; ?>">
						<span class="input-group-addon" id="basic-addon1" style="padding-bottom:0px;"><label for="number"><span>.00</span></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>Max Participants:</span><br/>
					<div id="maxParti">
						<input class="slider" id="slider" type="text" name="participant" data-slider-min="1" data-slider-step="1" data-slider-max ="50" data-slider-value="<?php if (isset($_POST['participant'])&&$_POST['participant']){echo $_POST['participant'];}else{ echo 10;} ?>" required>
					</div>
					
				</div>
				
			</div>

			<div class="row">
				<div class="col-sm-6">
					<span>Start Date</span>
					<?php if(isset($startDateError)){echo $startDateError;} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="startDate" placeholder="Choose start date for job"  value="<?php if (isset($_POST['startDate'])&&$_POST['startDate']) echo $_POST['startDate']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>End Date</span>
					<?php if(isset($endDateError)){echo $endDateError;} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="endDate" placeholder="Choose end date for job"  value="<?php if (isset($_POST['endDate'])&&$_POST['endDate']) echo $_POST['endDate']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">
					<span>Deadline</span>
					<?php if(isset($deadlineError)){echo $deadlineError;} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="deadline" placeholder="Choose deadline for job recuitment"  value="<?php if (isset($_POST['deadline'])&&$_POST['deadline']) echo $_POST['deadline']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>requirement</span>
					<?php if(isset($requirementError)){echo $requirementError;} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="requirement" placeholder="Choose requirement for job"  value="<?php if (isset($_POST['requirement'])&&$_POST['requirement']) echo $_POST['requirement']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
			</div>
			
			<div class="marginTB">
				<span>Location</span>
				<?php if(isset($locationError)){echo $locationError;} ?>
				<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
					<input class="form-control" type="text" required name="location" placeholder="Choose location for job"  value="<?php if (isset($_POST['location'])&&$_POST['location']) echo $_POST['location']; ?>">
					<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
				</div>
			</div>

			<div class="marginTB">
				<span>description</span>
				<?php if(isset($descriptionError)){echo $descriptionError;} ?>
				<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
					<input class="form-control" type="text" required name="description" placeholder="Description for the job"  value="<?php if (isset($_POST['location'])&&$_POST['location']) echo $_POST['location']; ?>">
					<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
				</div>
			</div>

			<br>

			title 

			salary 			max

			start 			end

			deadline 		requirement

			location 

			description

		</form>
	</div>








	<!-- start of footer code -->
	<?php
	$no_helpfit_js=1;
	include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	<script type="text/javascript" src = "js/moment.js"></script>
	<script type="text/javascript" src = "js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src = "js/bootstrap-slider.min.js"></script>
	<script type="text/javascript" src = "js/jinjang.js"></script>

</body>
</html>
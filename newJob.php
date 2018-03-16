<?php
//load startup scripts
include("config.php");
include("control.php");

//check if user is logged in
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}

//if a member came to this page, bring him out to home page
if($user['type'] == "jobFinder"){
	$_SESSION['passThruMessage'] = "Sorry! You are not allowed to access to the page!";
	header("Location: Home.php"); exit;
}
else {
	//if form is posted
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$titleError = ""; $salaryError = ""; $startDateError = ""; $endDateError = ""; $deadlineError = ""; $requirementError = ""; $locationError = ""; $descriptionError = "";

		//validate enteries
		if(empty($_POST['jobTitle'])){
			$titleError = "please enter a job title";
		} else {
			$title = $_POST['jobTitle'];
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
				$startDateError = "Today is ".date("Y-m-d H:i:s", time())." new Job must be set after.";
				echo time();
			} else {
				$startDateC = strtotime($_POST['startDate']);
			}
		}

		//validate end date of job
		if(empty($_POST['endDate'])){
			$endDateError = "Please choose ending date for this job";
		} else {
			if(time()>strtotime($_POST['endDate'])){
				$endDateError = "Today is".date("Y-m-d H:i:s", time())." new Job must be set after.";
			}
			else if(strtotime($_POST['endDate'])<strtotime($_POST['startDate'])){
				$endDateError = "End working date cannot be early than start working date.";
			}
			else {
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

		if(empty($_POST['requirement'])){
			$requirementError = "Please choose a requirement for this job.";
		} else {
			if($_POST['requirement'] == "noChoose"){
				$requirementError = "Please choose a requiremnet for this job.";
			}
			$requirement = $_POST['requirement'];
		}

		if(empty($_POST['location'])){
			$locationError = "Please enter a location for this job.";
		} else {
			$location = $_POST['location'];
		}

		if(empty($_POST['description'])){
			$descriptionError = "Please enter a description for this job.";
		} else {
			$description = $_POST['description'];
		}

		$participant = $_POST['participant'];
		//if there is no error, insert training session into database
		if($titleError == "" && $salaryError == "" && $startDateError == "" && $endDateError == "" && $deadlineError == "" && $locationError == "" && $descriptionError == "" && $requirementError == ""){
			$newJob = "INSERT INTO `Jobs` (`jobID`, `jobTitle`, `description`, `requirement`, `hourlyRate`, `location`, `postDateTime`, `startDateTime`, `endDateTime`, `deadlineDays`, `maxParticipant`, `noParticipant`, `status`, `jpID`) VALUES ('', '".addslashes($title)."', '".addslashes($description)."', '$requirement', '$salary', '".addslashes($location)."', '".time()."', '$startDateC', '$endDateC', '$deadlineC', '$participant', 0, 'Available', '".$user['userID']."')";
			if(mysqli_query($connect, $newJob)){
				$_SESSION['passThruMessage'] = "Your new session has been added successfully.";
				header('Location: jobs.php'); exit;
			} else {
				$passThruMessage = "Please correct mentioned errors";
			}
		}
	}
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>New Job</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.min.css">
	<link rel="stylesheet" type="text/css" href="css/jinjang.css">
	
	<style type="text/css">
		form div span{
			color: #9E9F9E;
		}
	</style>
</head>
<body>
	<!-- start of header -->
	<?php 
		include("header.php"); 
	?>
	<!-- end of header -->

	<!--content-->
	<div class="container marginTB">
		<h3>Add New Job By <?php echo $user['fullName'] ?></h3>
		<form method="POST" action="newJob.php">
			<div class="marginTB">
				<span>Job Title</span>
				<?php if(isset($titleError)){echo '<span style="color:#AFA;">'.$titleError.'</span>';} ?>
				<input class="input-lg form-control" type="text" name="jobTitle" placeholder="Please enter title of job" required value="<?php if (isset($_POST['jobTitle'])&&$_POST['jobTitle']) echo $_POST['jobTitle']; ?>">
			</div>
			
			<div class="row">
				<div class="col-sm-6">
					<span>Salary</span>
					<?php if(isset($salaryError)){echo '<span style="color:#FF0000;">'.$salaryError.'</span>';} ?>
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
			<br>
			<div class="row">
				<div class="col-sm-6">
					<span>Start Date</span>
					<?php if(isset($startDateError)){echo '<span style="color:#FF0000;">'.$startDateError.'</span>';} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="startDate" placeholder="Choose start date for job"  value="<?php if (isset($_POST['startDate'])&&$_POST['startDate']) echo $_POST['startDate']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>End Date</span>
					<?php if(isset($endDateError)){echo '<span style="color:#FF0000;">'.$endDateError.'</span>';} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="endDate" placeholder="Choose end date for job"  value="<?php if (isset($_POST['endDate'])&&$_POST['endDate']) echo $_POST['endDate']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-6">
					<span>Deadline</span>
					<?php if(isset($deadlineError)){echo '<span style="color:#FF0000;">'.$deadlineError.'</span>';} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="deadline" placeholder="Choose deadline for job recuitment"  value="<?php if (isset($_POST['deadline'])&&$_POST['deadline']) echo $_POST['deadline']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>requirement</span>
					<?php if(isset($requirementError)){echo '<span style="color:#FF0000;">'.$requirementError.'</span>';} ?>
					<select class="form-control" name="requirement">
						<option value="noChoose" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "noChoose") echo "selected"; ?> >Please choose a requirement</option>
						<option value="" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "") echo "selected"; ?> >No requirement</option>
						<option value="Primary School" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "Primary School") echo "selected"; ?> >Primary School</option>
						<option value="High School" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "High School") echo "selected"; ?> >High School</option>
						<option value="ALevel" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "ALevel") echo "selected"; ?> >ALevel</option>
						<option value="Foundation" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "Foundation") echo "selected"; ?> >Foundation</option>
						<option value="Diploma" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "Diploma") echo "selected"; ?> >Diploma</option>
						<option value="Degree" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "Degree") echo "selected"; ?> >Degree</option>
						<option value="Master" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "Master") echo "selected"; ?> >Master</option>
						<option value="Phd" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "Phd") echo "selected"; ?> >Professor</option>
					</select>
				</div>
			</div>
			
			<div class="marginTB">
				<span>Location</span>
				<?php if(isset($locationError)){echo '<span style="color:#FF0000;">'.$locationError.'</span>';} ?>
				<input class="form-control" type="text" required name="location" placeholder="Choose location for job"  value="<?php if (isset($_POST['location'])&&$_POST['location']) echo $_POST['location']; ?>">
			</div>

			<div class="marginTB">
				<span>description</span>
				<?php if(isset($descriptionError)){echo '<span style="color:#FF0000;">'.$descriptionError.'</span>';} ?>
				<textarea rows="5" class="input-lg form-control" name="description" required placeholder="Description of the job"><?php if(isset($_POST['description'])) echo $_POST['description']; ?></textarea>
			</div>


			<br>
			<button type="submit" class="btn btn-primary btn-lg">Add This Job</button>

		</form>
	</div>








	<!-- start of footer code -->
	<?php
	//problem
	$no_helpfit_js=1;
	include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	<script type="text/javascript" src = "js/moment.js"></script>
	<script type="text/javascript" src = "js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src = "js/bootstrap-slider.min.js"></script>
	<script type="text/javascript" src = "js/jinjang.js"></script>

</body>
</html>
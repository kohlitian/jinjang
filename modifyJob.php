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



//if user is member, dont allow him access this page
if(isset($user) && $user['type'] == "jobFinder"){
	$_SESSION['passThruMessage'] = "Sorry ! You are not allowed to access to the page !";
	header("Location: LogIn.php"); exit;
} else {
	//get session data from database
	if(isset($_GET['jobID'])){
		$theJobID = $_GET['jobID'];
		$job = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `Jobs` WHERE `jobID` = ".$_GET['jobID'].""));
		$skills = $job['skills'];
	}

	//if form is POSTED
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$nameError = ""; $hourlyError = ""; $startDateError = ""; $endDateError = ""; $locationError = ""; $requirementError = ""; $deadlineError = ""; $participantError = ""; $descriptionError = ""; $statusError = "";

		$description = $_POST['description'];
		$participant = $_POST['participant'];

		//validate forms entry 

		if(empty($_POST['status']) || $_POST['status'] == ""){
			$statusError = "Please choose a status";
		} else {
			$status = $_POST['status'];
		}

		if(empty($_POST['jobTitle'])){
			$nameError = "please enter a job title";
		} else {
			$name = $_POST['jobTitle'];
		}


		if (!is_array($_POST['skills'])||(is_array($_POST['skills'])&&count($_POST['skills'])==0)){
			$skillError = "Please choose a skill";
		} else if (is_array($_POST['skills'])&&(is_array($_POST['skills'])&&count($_POST['skills'])>0)){
			$skills = addslashes(implode(",",$_POST['skills']));
		}else{
			$skills = $job['skills'];
		}





		if(!empty($_POST['hourlyRate'])){
			if($_POST['hourlyRate'] < 0){
				$hourlyError = "hourly rate cannot be negative";
			} else {
				if(is_numeric($_POST['hourlyRate'])){
					$hourlyRate = $_POST['hourlyRate'];
					$job['hourlyRate']=$hourlyRate;
				} else {
					$hourlyError = "hourly rate must be numeric";
				}
			}
		} else {
			$hourlyError = "Please enter a hourly rate";
		}

		if(empty($_POST['startDate'])){
			$startDateError = "Please choose starting date for this job";
		} else {
			if(time()>strtotime($_POST['startDate'])){
				$startDateError = "Today is ".date("Y-m-d H:i:s", time())." new Job must be set after.";
				
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
		
		//if there is no validation error, update session in database
		if($nameError =="" && $hourlyError =="" && $startDateError =="" && $endDateError =="" && $deadlineError =="" && $descriptionError == "" && $requirementError == "" && $statusError == "" && $locationError == ""){
			$newSession = "UPDATE `Jobs` SET `jobTitle` = '".$name."', `hourlyRate` = '".$hourlyRate."', `maxParticipant` = '".$participant."', `startDateTime` = '".$startDateC."', `endDateTime` = '".$endDateC."', `deadlineDays` = '".$deadlineC."', `requirement` = '".$requirement."', `location` = '".$location."', `skills` = '".$skills."', `status` = '".$status."', `description` = '".$description."' WHERE `jobID` = '".$job['jobID']."' AND `jpID` = '".$user['userID']."'";


			if(mysqli_query($connect, $newSession)){
				$_SESSION['passThruMessage']="Your job has been modified successfully.";

				header("Location: myJob.php"); die();
			} 
		}else{
			$passThruMessage="Please correct mentioned mistakes.";
			$_GET['type']='edit';
		}
	}
}


$skillArr=explode(",", $skills);


?><!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Modify Job</title>
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
	<?php 
	if($user['userID'] == $job['jpID'] && $user['type'] == "jobProvider"){ ?>
	<div class="container marginTB">
		<h3><strong>#<?php if(isset($job)){echo $job['jobID']." ".$job['jobTitle'];} ?> Info</strong></h3>
		<form method="POST" action="modifyJob.php?jobID=<?php echo $_GET['jobID']; ?>&type=view">
			<div class="marginTB">
				<span>Job Title</span>
				<?php if(isset($nameError)){echo '<span style="color:#FF0000;">'.$nameError.'</span>';} ?>
				<input class="input-lg form-control" type="text" name="jobTitle" placeholder="Please enter title of job" required value="<?php if (isset($job)) {echo $job['jobTitle'];} ?>">
			</div>
			

			<div class="row">
				<div class="col-sm-6">
					<span>Hourly Rate</span>
					<?php if(isset($salaryError)){echo '<span style="color:#FF0000;">'.$salaryError.'</span>';} ?>
					<div class="input-group noSpaceTop">
						<span class="input-group-addon" id="basic-addon1"><label for="price">RM</label></span>
						<input class="form-control" type="number" name="hourlyRate" required id="price" placeholder="Type amount of hourly rate for job"  value="<?php if (isset($job)) echo $job['hourlyRate']; ?>">
						<span class="input-group-addon" id="basic-addon1" style="padding-bottom:0px;"><label for="number"><span>.00</span></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>Max Participants:</span><br/>
					<div id="maxParti">
						<input class="slider" id="slider" type="text" name="participant" data-slider-min="1" data-slider-step="1" data-slider-max ="50" data-slider-value="<?php if (isset($job)){echo $job['maxParticipant'];}else{ echo 10;} ?>" required>
					</div>
					
				</div>
				
			</div>
			<br>
			<div class="row">
				<div class="col-sm-6">
					<span>Start Date</span>
					<?php if(isset($startDateError)){echo '<span style="color:#FF0000;">'.$startDateError.'</span>';} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="startDate" placeholder="Choose start date for job"  value="<?php if(isset($job)){echo date("m/d/Y H:i A", $job['startDateTime']);}?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>End Date</span>
					<?php if(isset($endDateError)){echo '<span style="color:#FF0000;">'.$endDateError.'</span>';} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="endDate" placeholder="Choose end date for job"  value="<?php if (isset($job)){echo date("m/d/Y H:i A", $job['endDateTime']);} ?>">
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
						<input class="form-control" type="text" required name="deadline" placeholder="Choose deadline for job recuitment"  value="<?php if (isset($job)) echo date("m/d/Y H:i A", $job['deadlineDays']); ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
				<div class="col-sm-6">
					<span>requirement</span>
					<?php if(isset($requirementError)){echo '<span style="color:#FF0000;">'.$requirementError.'</span>';} ?>
					<select class="form-control" name="requirement">
						<option value="noChoose" <?php if(isset($_POST['requirement']) && $_POST['requirement'] == "noChoose") echo "selected"; ?> >Please choose a requirement</option>
						<option value="" <?php if(isset($job) && $job['requirement'] == "none") echo "selected"; ?> >No requirement</option>
						<option value="Primary School" <?php if(isset($job) && $job['requirement'] == "Primary School") echo "selected"; ?> >Primary School</option>
						<option value="High School" <?php if(isset($job) && $job['requirement'] == "High School") echo "selected"; ?> >High School</option>
						<option value="ALevel" <?php if(isset($job) && $job['requirement'] == "ALevel") echo "selected"; ?> >ALevel</option>
						<option value="Foundation" <?php if(isset($job) && $job['requirement'] == "Foundation") echo "selected"; ?> >Foundation</option>
						<option value="Diploma" <?php if(isset($job) && $job['requirement'] == "Diploma") echo "selected"; ?> >Diploma</option>
						<option value="Degree" <?php if(isset($job) && $job['requirement'] == "Degree") echo "selected"; ?> >Degree</option>
						<option value="Master" <?php if(isset($job) && $job['requirement'] == "Master") echo "selected"; ?> >Master</option>
						<option value="Phd" <?php if(isset($job) && $job['requirement'] == "Phd") echo "selected"; ?> >Professor</option>
					</select>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-6">
					<span>Location</span>
					<?php if(isset($locationError)){echo '<span style="color:#FF0000;">'.$locationError.'</span>';} ?>
					<input class="form-control" type="text" required name="location" placeholder="Choose location for job"  value="<?php if (isset($job)) echo $job['location']; ?>">
				</div>
				<div class="col-sm-6">
					<span>Status</span>
					<?php if(isset($statusError)){echo '<span style="color:#FF0000;">'.$statusError.'</span>';}
					?>
					<select class="form-control" name="status">
						<option value="">Choose status</option>
						<option value="Available" <?php if(isset($job) && $job['status'] == "Available") echo "selected"; ?>>Available</option>
						<option value="Cancelled" <?php if(isset($job) && $job['status'] == "Cancelled") echo "selected"; ?>>Cancelled</option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<span>Skills</span>
					<?php if(isset($skillError)){echo '<span style="color:#FF0000;">'.$skillError.'</span>';} ?>


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
				<div class="col-sm-6">
	
				</div>
			</div>

			<div class="marginTB">
				<span>description</span>
				<?php if(isset($descriptionError)){echo '<span style="color:#FF0000;">'.$descriptionError.'</span>';} ?>
				<textarea rows="5" class="input-lg form-control" name="description" placeholder="Description of the job"><?php if(isset($job)) echo $job['description']; ?></textarea>
			</div>


			<button type="submit" class="btn btn-danger btn-lg">Modify This Job</button>

		</form>
		<br>
		<br>
		<?php
		$check = mysqli_fetch_array(mysqli_query($connect,"select count(*) from requestedJobs where jobID='".$job['jobID']."';"));
		?>
		<h3><strong><?php echo $check[0] ?> Participants: </strong></h3>
		<br>
		<?php 
		if($check[0] > 0){ ?>
		<div class="marginTBL border tabletraining">
			<div class="row hidden-xs " style="padding-top:10px; padding-bottom:10px; border-top: 0px;">
				<div class="col-sm-3 hidden-xs ">
					<span>Job Finder</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Language & Salary</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Skills & Education </span>
				</div>
				<div class="col-sm-3 hidden-xs">
					<span>Action</span>
				</div>
			</div>
		</div>

		<?php 

		$find = mysqli_query($connect, "SELECT * FROM `jobFinder`, `requestedJobs` WHERE `requestedJobs`.`jfID` = `jobFinder`.`userID` AND `requestedJobs`.`jobID` = '".$job['jobID']."'");


		if((mysqli_num_rows($find))>0){

			while($result = mysqli_fetch_assoc($find)){

				echo "<hr style=\"border:1px solid #808080; margin-top: 0px; margin-bottom: 2px;\"><div class=\"row\">
				<div class=\"col-sm-3\">";
				if($result['status'] == "Requested"){
					echo "<a class=\"btn btn-link\" href=\"viewPeople.php?UID=".$result['userID']."&jobID=".$job['jobID']."&type=Choose\" >".$result['fullName']."</a>";
				} else if($result['status'] == "Accepted"){
					echo "<a class=\"btn btn-link\" href=\"viewPeople.php?UID=".$result['userID']."&jobID=".$job['jobID']."&type=Accepted\" >".$result['fullName']."</a>";
				} else {
					echo "<a class=\"btn btn-link\" href=\"viewPeople.php?UID=".$result['userID']."&jobID=".$job['jobID']."&type=Rejected\" >".$result['fullName']."</a>";
				}
				echo "
				</div>
				<div class=\"col-sm-3\">
					<span><small><small>".$result['languages']."</small></small></span><br>
					<span><small><small>".$result['expectedSalary']."</small></small></span>
				</div>
				<div class=\"col-sm-3\">
					<span><small><small>".$result['skills']."</small></small></span><br>
					<span><small><small>".$result['educationLevel']."</small></small></span>
				</div>
				<div class=\"col-sm-3\">";
				if($result['status'] == "Requested"){ echo "
					<a class=\"btn btn-success btn-sm col-xs-6\" href=\"accept.php?jobID=".$result['jobID']."&chooseWorker=".$result['userID']."\" onclick=\"return ";
					if ($user["userID"]==0){
						echo "confirm('Please login to system to join events');";
					}
					echo "confirm('Are you sure you want to choose this worker for ".$job['jobTitle']." by ".$user['companyName']." ?')\"; >";
					if ($user['userID']!=0){
						echo "Choose worker ";
					}
					echo "</a> <a class=\"btn btn-danger btn-sm col-xs-6\" href=\"reject.php?jobID=".$result['jobID']."&chooseWorker=".$result['userID']."\" onclick=\"return ";
					if($user['userID']==0){
						echo "confirm('Please login to system to join events');";
					} 
					echo "confirm('Are you sure you want to reject ".$result['fullName']." ?')\";>";
					if($user['userID']!=0){
						echo "Reject";
					}
					echo "</a>";
				} else if($result['status'] == "Accepted") {
					echo "<button class=\"btn btn-primary btn-sm disabled fullwidth\">Accepted</button>";
				} else if ($result['status'] == "Rejected") {
					echo "<button class=\"btn btn-danger btn-sm disabled fullwidth\">Rejected</button>";
				}
				echo "</div></div>";
			}

		}
		}else{?>
		There is no participants yet
		<?php } ?>

<?php } else{ ?>

<div class="container marginTB">
		<h2><strong>#<?php if(isset($job)){echo $job['jobID']." ".$job['jobTitle'];} ?> Info</strong></h2>
		<br>
		<div class="well"><?php if(isset($job)){echo $job['description'];} ?></div>
		<br>
		<div class="row">
			<div class="col-md-6 col-xs-12">Hourly Rate: RM<?php if(isset($job)){echo $job['hourlyRate'];} ?></div>
			<div class="col-md-6 col-xs-12">Location: <?php if(isset($job)){echo $job['location'];} ?></div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Requirement: <?php if(isset($job)){echo $job['requirement'];} ?></div>
			<div class="col-md-6 col-xs-12">Start Date: <?php if(isset($job)){echo date("m/d/Y H:i A", $job['startDateTime']);} ?></div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Active Participants: <?php if(isset($job)){echo $job['noParticipant'];} ?>/<?php if(isset($job)){echo $job['maxParticipant'];} ?></div>
			<div class="col-md-6 col-xs-12">End Date: <?php if(isset($job)){echo date("m/d/Y H:i A", $job['endDateTime']);} ?></div>
			
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Intrested Participant: <?php echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from requestedJobs where jobID='".$job['jobID']."';"))[0]; ?></div>
			<div class="col-md-6 col-xs-12">Skills: <?php if(isset($job)){echo $job['skills'];} ?></div>

		</div>

	<?php } ?>

	</div>
	<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	<script type="text/javascript" src = "js/moment.js"></script>
	<script type="text/javascript" src = "js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src = "js/bootstrap-slider.min.js"></script>
	<script type="text/javascript" src = "js/jinjang.js"></script>
	<script>
		applyTrainingType();
	</script>

</body>
</html>
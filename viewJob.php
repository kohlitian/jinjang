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
	$company = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `companyName` FROM `JobProvider` WHERE `userID` = '".$detail['jpID']."';"));
} 
//else {
// 	header("Location: jobs.php"); exit;
// }

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$rateError = ""; 
	if(empty($_POST['rating'])){
		$rateError = "Please select a rating";
	} else {
		$rate = $_POST['rating'];
	}
	$comment = $_POST['comment'];

	if($rateError == ""){
		mysqli_query($connect, "INSERT INTO `Review` (`reviewID`, `timeStamp`, `rating`, `comment`, `jfID`, `jobID`) VALUES ('', '".time()."', '$rate', '$comment', '".$user['userID']."', '".$_GET['jobID']."');");
		$_SESSION['passThruMessage']="Your review has been added successfully.";
		header("Location: myJob.php"); exit;
	}
}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>View Job</title>
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
		<br>
		<div class="well"><?php if(isset($detail)){echo $detail['description'];} ?></div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Hourly Rate: RM<?php if(isset($detail)){echo $detail['hourlyRate'];} ?></div>
			<div class="col-md-6 col-xs-12">Location: <?php if(isset($detail)){echo $detail['location'];} ?></div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Requirement: <?php if(isset($detail)){echo $detail['requirement'];} ?></div>
			<div class="col-md-6 col-xs-12">Start Date: <?php if(isset($detail)){echo date("m/d/Y H:i A", $detail['startDateTime']);} ?></div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">Participants: <?php if(isset($detail)){echo $detail['maxParticipant'];} ?></div>
			<div class="col-md-6 col-xs-12">End Date: <?php if(isset($detail)){echo date("m/d/Y H:i A", $detail['endDateTime']);} ?></div>
		</div>
		<br>
		<?php 
		$check = mysqli_num_rows(mysqli_query($connect, "SELECT `requestID` FROM `requestedJobs` WHERE `jobID` = ".$detail['jobID']." AND `jfID` = ".$user['userID'].""));
		echo mysqli_error($connect);
		if($check == 0){
			echo "<a class=\"btn btn-success btn-lg\" href=\"joinJob.php?jobID=".$job."\" onclick=\"return ";
			if($user['userID']==0){
				echo "confirm('Please login to system to join events');";
			}
			echo "confirm('Are you sure you want to join this ".$detail['jobTitle']." by ".$company['companyName']."?')\";>";
			if($user['userID']!=0){
				echo "Join";
			} 
			echo "</a>";
		} else if($check >0) {
			echo "<button class=\"btn btn-warning disabled btn-lg\">Joined</button>";
		}

		?>
		<br>
		<br>
		<br>
		<?php if($detail['endDateTime'] < time()){ ?>
		<h3><strong>Review Job Provider: </strong></h3>
		<form method="POST" action="<?php echo "viewJob.php?jobID=".$detail['jobID'] ?>">
			<h2>Your Review</h2>
			<input required id="input-id" name="rating" type="text" class="ratinginput" data-size="sm" >
			<textarea name="comment" rows="6" class="form-control" required></textarea>
			<br><button type="submit" class="btn btn-primary btn-lg">Submit</button>
		</form>

		<?php } ?>

	</div>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	<script type="text/javascript" src = "js/moment.js"></script>
	<script type="text/javascript" src = "js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src = "js/bootstrap-slider.min.js"></script>
	<script type="text/javascript" src = "js/jinjang.js"></script>
	
	

</body>
</html>
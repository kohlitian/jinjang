<?php
//load startup files
include("config.php");
include("control.php");

//if user is not logged in, redirect him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php"); die();
} else {
	//get session info
	$ID = "";
	$session = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `Jobs` WHERE `jobID` = ".$_GET['jobID'].""));
	//if session is valid and date of session is in future, join the member to that session
	if(isset($session['deadlineDays']) && isset($user)){
		$check = mysqli_query($connect, "SELECT `userID` FROM `requestedJobs` WHERE `jfID` = '".$user['userID']."' AND `jobID` = '".$session['jobID']."'");
		if($session['status'] == 'Available' && $session['deadlineDays'] > time() && $user['type'] == "jobFinder" && mysqli_num_rows($check) == 0){
				mysqli_query($connect, "INSERT INTO `requestedJobs` (`requestID`, `jfID`, `jobID`, `status`) VALUES ('$ID', '".$user['userID']."', '".$session['jobID']."', 'Requested')");
				
		}
	}
}

//a quick control to check and set sessions where participants are full
// $session = mysqli_query($connect, "SELECT * FROM `Jobs`");
// if(mysqli_num_rows($session) > 0){
// 	while($row = mysqli_fetch_assoc($session)){
// 		$numParticipant = mysqli_num_rows(mysqli_query($connect, "SELECT `requestID` FROM `requestedJobs` WHERE `jobID` = '".$row['jobID']."'"));
// 		if($row['maxParticipant'] == $numParticipant ){
// 			mysqli_query($connect, "UPDATE `Jobs` SET `status` = 'Full' WHERE `jobID` = '".$row['jobID']."'");

// 		}
// 	}
// }

//show message to user about joining session
$_SESSION['passThruMessage']="You've requested to join the job #".$_GET['jobID']." successfully.";
header('Location: myJob.php');
?>
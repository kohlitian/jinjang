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
	if(isset($session['endDateTime']) && isset($user)){
		$check = mysqli_query($connect, "SELECT `jfID` FROM `requestedJobs` WHERE `jfID` = '".$_GET['chooseWorker']."' AND `jobID` = '".$session['jobID']."'");
		if($session['status'] == 'Available' && $session['endDateTime'] > time() && mysqli_num_rows($check) >0){
				mysqli_query($connect, "UPDATE `requestedJobs` SET `status` = 'Accepted' WHERE `jobID` = '".$session['jobID']."' AND `jfID` = ".$_GET['chooseWorker']."");
				mysqli_query($connect, "UPDATE `Jobs` SET `noParticipant` = `noParticipant` + 1 WHERE `jobID` = '".$session['jobID']."' AND `jpID` = '".$user['userID']."'");
				
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
header("Location: modifyJob.php?jobID=".$session['jobID']."");
?>
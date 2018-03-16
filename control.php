<?php

//if user is not logged in, but there is cookies available, log user in via cookie
if (!isset($_SESSION['id'])&&isset($_COOKIE['id'])){
	$_SESSION['id']=$_COOKIE['id'];
	$_SESSION['username'] = $_COOKIE['username'];
	$_SESSION['name'] = $_COOKIE['name'];
}


//this debug function is being used during our development phase
function debug($manualID=""){
	global $connect,$autoID;
	if ($manualID=="")
	{
	$autoID++;

	echo "Debug #".$autoID.". ";
	}else{
	echo "Debug #".$manualID.". ";

	}
    echo mysqli_error($connect);
}

	//if user is logged in, verify his account data with database
	if(isset($_SESSION['id'])&&$_SESSION['id'] > 0){
		$jP = mysqli_query($connect, "SELECT * FROM `JobProvider` WHERE `fullName` = '".$_SESSION['name']."' AND `username` = '".$_SESSION['username']."' AND `userID` = '".$_SESSION['id']."'");

		$jF = mysqli_query($connect, "SELECT * FROM `JobFinder` WHERE `fullName` = '".$_SESSION['name']."' AND `username` = '".$_SESSION['username']."' AND `userID` = '".$_SESSION['id']."'");

		//if user account found in database, get his full data:
		if(mysqli_num_rows($jF) == 1){
			$user = mysqli_fetch_assoc($jF);
			$user['type'] = 'jobFinder';
		} else if(mysqli_num_rows($jP) == 1){
			$user = mysqli_fetch_assoc($jP);
			$user['type'] = 'jobProvider';
		} else {
			header("Location: logOut.php");
			exit;
		}
	}

	//control training sessions and set their status to passed if session time is passed
	$jobs = mysqli_query($connect, "SELECT * FROM `Jobs`;");
	if(mysqli_num_rows($jobs) > 0){
		while($row = mysqli_fetch_assoc($jobs)){
			if($row['deadlineDays'] < time()){
				mysqli_query($connect, "UPDATE `Jobs` SET `status` = 'Passed' WHERE `jobID` = '".$row['jobID']."'");
			} else if ($row['maxParticipant'] != mysqli_num_rows(mysqli_query($connect, "SELECT `requestID` FROM `requestedJobs` WHERE `jobID` = '".$row['jobID']."'"))){
				$row['status'] = 'Available';
			}
		}
	}

//if there is a popup message to show to user, make popup variable ready
if (isset($_SESSION['passThruMessage'])&&$_SESSION['passThruMessage']!=''){
	$passThruMessage=$_SESSION['passThruMessage'];
	$_SESSION['passThruMessage']='';
}else{
	$passThruMessage='';
}
?>
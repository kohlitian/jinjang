<?php
//load startup scripts
include("config.php");
include("control.php");

//if user is not logged in, bring him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: LogIn.php");
	die();
}

	$infoPp = mysqli_query($connect, "SELECT * FROM `JobFinder` WHERE `userID` = '".$_GET['UID']."'");
	if(mysqli_num_rows($infoPp) != 1){
		$_SESSION['passThruMessage'] = "This user is no longer in our database.";
		header("Location: modifyJob.php?jobID=".$_GET['JID'].""); die();
	} else {
		$info = mysqli_fetch_assoc($infoPp);
		$job = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `jobTitle`, `jobID` FROM `Jobs` WHERE `jobID` = '".$_GET['jobID']."'"));
	}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>View Profile</title>
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

				<span>Username: </span><?php echo $info['username']; ?>
				<br>
				<span>Email: </span><?php echo $info['email']; ?>
				<br>
				<span>Contact No.: </span><?php echo $info['contactNo']; ?>
				<br>
				<span>Expected Salary: </span><?php echo $info['expectedSalary']; ?>
				<br>
				<span>Experience History: </span><?php echo $info['experienceHistory']; ?>
				<br>
				<span>Education Level: </span><?php echo $info['educationLevel']; ?>
				<br>
				<span>Skills: </span><span class=" skillslist" style="margin-left:5px;color: blue; text-decoration: underline; background: #eee; font-size: 12pt;"><?php echo count(explode(",",$info['skills'])); ?> Skill<?php if (count(explode(",",$info['skills']))>1) echo "s"; ?><div class="skillslistinner"><?php foreach (explode(",",$info['skills']) as $skill) echo "<div class='skilllistitem'>".$skill."</div>"; ?></div></span>
				<br>
				<span>Languages: </span><span class=" skillslist" style="margin-left:5px;color: blue; text-decoration: underline;background: #eee; font-size: 12pt;"><?php echo count(explode(",",$info['languages'])); ?> Language<?php if (count(explode(",",$info['languages']))>1) echo "s"; ?><div class="skillslistinner"><?php foreach (explode(",",$info['languages']) as $skill) echo "<div class='skilllistitem'>".$skill."</div>"; ?></div></span>
				
				<br>
				<br>

				<?php echo"
				<div class=\"col-sm-12\">";
				if($_GET['type'] == "Choose"){
					echo "<a class=\"btn btn-success btn-sm col-xs-6\" href=\"accept.php?jobID=".$_GET['jobID']."&chooseWorker=".$info['userID']."\" onclick=\"return ";
					if ($user["userID"]==0){
						echo "confirm('Please login to system to join events');";
					}
					echo "confirm('Are you sure you want to choose this worker for ".$job['jobTitle']." by ".$user['companyName']." ?')\"; >";
					if ($user['userID']!=0){
						echo "Choose worker ";
					}
					echo "</a> <a class=\"btn btn-danger btn-sm col-xs-6\" href=\"reject.php?jobID=".$_GET['jobID']."&chooseWorker=".$info['userID']."\" onclick=\"return ";
					if($user['userID']==0){
						echo "confirm('Please login to system to join events');";
					} 
					echo "confirm('Are you sure you want to reject ".$info['fullName']." ?')\";>";
					if($user['userID']!=0){
						echo "Reject";
					}
					echo "</a>";
				} else if($_GET['type'] == "Accepted"){
					echo "<button type=\"buttton\" disabled class=\"btn btn-primary btn-block btn-sm \">Accepted</button>";
				} else if($_GET['type'] == "Rejected"){
					echo "<button type=\"buttton\" disabled class=\"btn btn-danger btn-block btn-sm \">Rejected</button>";
				}
				echo "</div>";
			?>
			<br>

			</div>
		</form>
	</div>
<br>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	

	

</body>
</html>
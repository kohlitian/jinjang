<?php
//load startup files
include("config.php");
include("control.php");

//if user is not logged in, redirect him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>My Subscribed Jobs</title>
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
	<div>
		<div class="container marginTBL">
			
			<?php if($user['type'] == "jobProvider"){ ?>
				<h2><i class="fa fa-calendar verybigtext lefty marginright10" style="color: #05C3F7;"></i> <?php echo $user['fullName']."'s Jobs"; ?></h2>Here are jobs that you've created
			<?php } else { ?>
				<h2><i class="fa fa-calendar verybigtext lefty marginright10" style="color: #05C3F7;"></i> <?php echo $user['fullName']."'s Jobs"; ?></h2>Here are jobs that you've requested
			<?php } ?>
			
		</div>
		<div class="container marginTBL border tabletraining">
			

<?php

//populate default pagination data
if (isset($_GET['pid'])){
	$pid = addslashes($_GET['pid']);if (round($pid)==0){$pid=1;}
}else{
	$pid = 1;
}

//create sql page id statement
$sqlpid=($pid-1)*$limit;

			//get list of user created or joined sessions from database
			if($user['type'] == "jobFinder"){
				$result = mysqli_query($connect, "SELECT *,requestedJobs.status as rstatus,Jobs.status as jstatus FROM `requestedJobs`, `Jobs` WHERE `requestedJobs`.`jobID` = `Jobs`.`jobID` AND `requestedJobs`.`jfID` = '".$user['userID']."' LIMIT ".$sqlpid.", ".$limit.";");
			} else if($user['type'] == "jobProvider") {
				$result = mysqli_query($connect, "SELECT * FROM `Jobs` WHERE `jpID` = '".$user['userID']."' LIMIT ".$sqlpid.", ".$limit.";");
			}
			$i = 1;
			if(mysqli_num_rows($result) > 0){
				

				?>			<div class="row hidden-xs " style="padding-top:10px; padding-bottom:10px; border-top: 0px;">
				<div class="col-sm-2 hidden-xs ">
					<span class="lefty marginright10 ">ID</span>
					<span>Title</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Job Provider</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Job Info <small style="color: grey;">(no. part., hourly, status)</small></span>
				</div>
				<div class="col-sm-2 hidden-xs">
					<span>Start Date</span>
				</div>
				<div class="col-sm-2 hidden-xs">
					<span></span>
				</div>
			</div><?php
			//get sessions row by row
				while($row = mysqli_fetch_assoc($result)){
					$trainer = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `JobProvider` WHERE `userID` = '".$row['jpID']."'"));

					//get ratings for session and trainer from database
					if($user['type'] == "jobFinder"){
					$rating = mysqli_query($connect, "SELECT `rating` FROM `Review` WHERE `jobID` = '".$row['jobID']."' and jfID='".$user['userID']."';");

					}else{
					$rating = mysqli_query($connect, "SELECT `rating` FROM `Review` WHERE `jobID` = '".$row['jobID']."'");
					}
					if($user['type'] == "jobFinder"&&$user['userID']>0){
						$check = mysqli_num_rows(mysqli_query($connect, "SELECT `requestID` FROM `requestedJobs` WHERE `jfID` = '".$user['userID']."' AND `jobID` = '".$row['jobID']."';"));
					}else{
						$check=0;
					}
					echo mysqli_error($connect);
					$totalR = 0;
					$avgR = 0;
						//calculate the rating to show
						if(mysqli_num_rows($rating) > 0){
							while($findRate = mysqli_fetch_assoc($rating)){
								$totalR += $findRate['rating'];
							}
							$avgR = round($totalR / mysqli_num_rows($rating));
						}

				echo "<div class=\"row\">
					<div class=\"col-xs-6 col-sm-2 marginTBL\">
						<span class=\"lefty marginright10 hidden-xs idcol\">".$row['jobID']."</span>
						<span>".$row['jobTitle']."</span>
					</div>
					<div class=\"col-xs-6 col-sm-3 marginTBL\">
						<div class=\"trainer\">";
							echo "<i class=\"glyphicon glyphicon-user lefty hidden-xs \"></i>
							<span>".$trainer['fullName']."</span><br>
							<span><small>".$trainer['companyName']."</small>&nbsp;";

							if ($avgR>0){ ?>
							<div class="ratingstarcontainer" style="padding-left:0px;">
							<?php for ($star=1;$star<=5;$star++){ ?>
							<i class="glyphicon glyphicon-star<?php if ($avgR<$star){ echo '-empty';} ?> ratingstar"></i>
							<?php } ?>
						</div>
						<?php  }else{ ?><small>(No rating yet)</small><?php }
							
						
					echo "</span></div>
						
					</div>
					<div class=\"col-xs-6 col-sm-3 marginTBL\">"; ?>
					<span class="label label-primary"><?php echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from requestedJobs where jobID='".$row['jobID']."';"))[0]; ?></span>
						<?php 

						if($user['type'] == "jobFinder"){
							echo "<span class=\"label label-success\">".$row['hourlyRate']."</span>";?><?php 
							if($check >0){ 
								if($row['jstatus'] == 'Cancelled'){ echo "<span class=\"label label-default\" style=\"margin-left:5px;\">".$row['jstatus']."</span>"; 
								} else if($row['jstatus'] == 'Passed') { echo "<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['jstatus']."</span>"; 
								} else { 
									if ($row['rstatus'] == 'Accepted'){
										echo "<span class=\"label label-primary\" style=\"margin-left:5px;\">".$row['rstatus']."</span>"; 
									} else if ($row['rstatus'] == 'Rejected'){
										echo "<span class=\"label label-danger\" style=\"margin-left:5px;\">".$row['rstatus']."</span>"; 
									}
										else { echo "<span class=\"label label-warning\" style=\"margin-left:5px;\">Joined</span>";
									}
								}
							} else if($row['jstatus'] == 'Available'){ echo"<span class=\"label label-success\" style=\"margin-left:5px;\">".$row['jstatus']."</span>";
							} else if($row['jstatus'] == 'Cancelled') {echo"<span class=\"label label-default\" style=\"margin-left:5px;\">".$row['jstatus']."</span>";
							} else {echo"<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['jstatus']."</span>";
							}
						} else if($user['type'] = "jobProvider") {
							echo "<span class=\"label label-info\">".$row['hourlyRate']."</span>";?><?php if($row['status'] == 'Available'){ echo"<span class=\"label label-success\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Cancelled') {echo"<span class=\"label label-default\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Full'){echo"<span class=\"label label-danger\" style=\"margin-left:5px;\">".$row['status']."</span>";} else {echo"<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['status']."</span>";}
						}
						echo "</div>
						<div class=\"col-xs-6 col-sm-2 marginTBL\">
							<span>".date("d M, H:i", $row['startDateTime'])."<small class=\"transWord hidden-sm\">".date("Y", $row['startDateTime'])."</small></span>
						</div>
						<div class=\"col-xs-12 col-sm-2 marginTBL\">";?>
						<?php
						if($user['type'] == "jobFinder"){ 
							echo "<a class=\"btn btn-primary btn-sm fullwidth\" href=\"viewJob.php?jobID=".$row['jobID']."\">View</a>";
						} else if ($user['type'] == "jobProvider") {
								echo "<a class=\"btn btn-primary btn-sm fullwidth\" href=\"modifyJob.php?jobID=".$row['jobID']."&type=view\">View</a>";
						}
						echo "</div></div>";
					}
				}

		else{
		?>

		<div class="well">You don't have any jobs yet.</div>
		<?php

		}
			?>
		
		</div>

	<!-- pagination -->
	<div class="center">
		<ul class="pagination">
			<?php if ($pid>1){ ?>
			<li><a href="?pid=<?php echo $pid-1 ?>"><< Previous Page</a></li>
			<?php }else{ ?>
			<li class="disabled"><a href="#" ><< Previous Page</a></li>
			<?php } ?>
				<li class="disabled"><a href="#">Page <?php echo $pid; ?></a></li>
			<?php if (mysqli_num_rows($result)==$limit){ ?>
				<li><a href="?pid=<?php echo $pid+1 ?>">Next page >></a></li>
			<?php }else{ ?>
				<li class="disabled"><a href="#">Next page >></a></li>
			<?php } ?>
		</ul>
	</div>
	<!-- end of pagination -->

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	

	

</body>
</html>
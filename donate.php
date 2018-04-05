<?php
//load startup scripts
include("config.php" );
include("control.php");
$is_home=1;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$fullName = $_POST['fullName'];
	$email = $_POST['email'];
	$contactNo = $_POST['contactNo'];
	$amount = $_POST['amount'];

	if($amount<=0){
		$passThruMessage = "Sorry amount cannot be less than or equal to 0.";
	} else {
		header("Location: afterDonate.php?name=$fullName&money=$amount");
	}
}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Donation</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/jinjang.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
</head>
<body class="Site">
	<!-- start of header -->
	<?php 
	include("header.php"); 
	?>
	<!-- end of header -->
	
	<!--donate-->

	<div class="container marginTB">
		<br>
		<br>
		<br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" method="POST" action="donate.php" onsubmit="return askDonate();">
			<div class="formStyle">
				<p><Strong>Donation Form</Strong></p>
				<p>Please fill in your information</p>
				<br>
				<div class="input-group">
				  	<span class="input-group-addon" id="basic-addon1"><label for="fullName"><i class="glyphicon glyphicon-user"></i></label></span>
					<input type="text" class="form-control" placeholder="Please enter your full name (can be empty)" id="fullName" name="fullName" value="<?php if(isset($user['fullName'])){echo $user['fullName'];} ?>">
				</div>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="fa fa-key"></i></label></span>
					<input class="form-control" placeholder="Please enter your email" type="email" id="email" name="email" value="<?php if(isset($user['email'])){echo $user['email'];} ?>">
				</div>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2"><label for="contactNo"><i class="fa fa-key"></i></label></span>
					<input class="form-control" placeholder="Please enter your contact Number" type="tel" id="contactNo" name="contactNo" value="<?php if(isset($user['contactNo'])){echo $user['contactNo'];} ?>">
				</div>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1"><label for="amount">RM</label></span>
					<input class="form-control" type="number" name="amount" required id="amount" placeholder="Amount of donation"  value="<?php if (isset($_POST['amount'])&&$_POST['amount']) echo $_POST['amount']; ?>">
					<span class="input-group-addon" id="basic-addon1" style="padding-bottom:0px;"><label for="number"><span>.00</span></label></span>
				</div>
				<button type="submit" class="btn btn-success btn-block btn-lg formButton" >Donate !</button>

			</div>
		</form>
		<div class="clear"></div>
		<br>
		<br>
		<br>
	</div>

	<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	<script type="text/javascript">
		function askDonate(){
			bootbox.confirm({message: "Are you sure you want to donate " + amount + " for us ?", 
			buttons: { confirm: { label: 'Yes', className: 'btn-success'}, 
			cancel: {label: 'No', className: 'btn-danger'}},
			Callback: function(result){
				if(result){
					return true;
				} else {
					return false;
				}
			}});
		}
	</script>
	
	

</body>
</html>
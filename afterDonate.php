<?php
//load startup scripts
include("config.php");
include("control.php");


?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Thank You</title>
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
		<br>
		<br>
		<?php 
		if(isset($_GET['money'])){
		?>
		<p><strong>Thank You Mr. <?php if(isset($_GET['name'])){echo $_GET['name'];} else {echo "Guess";} ?> for Donating RM<?php if(isset($_GET['money'])){echo $_GET['money'];} ?> to us, the money will go through bank system and will reach us soon. We will use the money to improve our system to help more people that live in jinjang utara. THANK YOU ! and have a nice day ! </strong></p>
		<div class="clear"></div>
		<?php ;} else {header("Location: index.php");} ?>
		<br>
		<br>
		<br>
	</div>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>
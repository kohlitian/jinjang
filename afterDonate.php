<?php
//load startup scripts
include("config.php");
include("control.php");


?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Login</title>
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
		<p><strong>Thank You Mr. <?php if(isset($_GET['name'])){echo $_GET['name'];} else {echo "Guess";} ?> for Donating to us, the money will process to other payment system and we will receive it soon. We will use the money to improve our system to help more people that live in jinjang utara. We will contact you if something happen, and you are welcome to contact us for more information. THANK YOU ! </strong></p>
		<div class="clear"></div>
		<br>
		<br>
		<br>
	</div>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>
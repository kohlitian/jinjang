<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Home</title>
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
	<!--slide show-->
	<div>
		<div id="newCarousel" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#newCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#newCarousel" data-slide-to="1"></li>
				<li data-target="#newCarousel" data-slide-to="2"></li>
				<li data-target="#newCarousel" data-slide-to="3"></li>
				<li data-target="#newCarousel" data-slide-to="4"></li>
			</ol>
			<div class="carousel-inner">
				<div class="item active">
					<!-- by using div to display photos instead of <img>, we can benefit using CSS3 background-size: cover where background of a picture resize to available space to cover it -->
					<div class="img" style="background-image: url('img/jinjang1.jpg');"></div></div>
				<div class="item">
					<div class="img" style="background-image: url('img/jinjang2.jpg');"></div></div>
				<div class="item">
					<div class="img" style="background-image: url('img/jinjang3.jpg');"></div></div>
				<div class="item">
					<div class="img" style="background-image: url('img/jinjang4.jpg');"></div></div>
				<div class="item">
					<div class="img" style="background-image: url('img/jinjang5.jpg');"></div></div>
			</div>
		<a class="left carousel-control" href="#newCarousel" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#newCarousel" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
			<span class="sr-only">Next</span>
		</a>
		</div>
	</div>

	<!--content-->
	<div class="container marginTB">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<h3>What is Jinjang Project?</h3>
				<p>Jinjang project allow Job Provider from any place to create a recruitment, so stay-home mother who live in jinjang utara and apply for the job and earn extra income. Job Provider will be able to choose worker by stating requirement inside the job recruitment and by looking at profile of people who already applied for the job.</p>
			</div>
			<div class="col-xs-12 col-md-6">
				<h3>What is e-business?</h3>
				<p>E-business is a conduct of business processes on the internet. In this case, it provide services for job provider to post job recuitment and job finder to find job to earn extra income. It also provide processing payments service so after job, job provider can pay job finder and donation can be done.
					</p>
			</div>
		</div>
	</div>

	<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>
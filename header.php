<header>
	<nav class="navbar-style navbar-default" id="nav">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php"><img class="logo" src="img/jinjanglogo.png" alt="Jinjang Logo"></a>
				<button type="button" class="navbar-toggle tabMobile" data-toggle="collapse" data-target="#navToggle" id="tabMobile">
					<span class="icon-bar"></span>
        			<span class="icon-bar"></span>
        			<span class="icon-bar"></span>
				</button>
			</div>
			<?php 
			if(isset($_SESSION['id']) && $_SESSION['id'] > 0){
			?>
			<div class="collapse navbar-collapse" id="navToggle">
					<ul class="nav navbar-nav">
						<li <?php if (isset($is_home)){ ?>class="active"<?php } ?>><a href="index.php">Home</a></li>
						<li <?php if (isset($is_jobs)){ ?>class="active"<?php } ?>><a href="jobs.php">Jobs</a></li>


						<li <?php if (isset($is_aboutagn)){ ?>class="active"<?php }else{ ?><?php } ?>><a href="aboutagn.php">About AGN</a></li>
						<li <?php if (isset($is_ourwork)){ ?>class="active hidden-sm"<?php }else{ ?>class="hidden-sm"<?php } ?>><a href="ourwork.php">Our Work</a></li>

						<li <?php if (isset($is_howhelp)){ ?>class="active hidden-sm"<?php }else{ ?>class="hidden-sm"<?php } ?>><a href="howhelp.php">How Help</a></li>
						<li <?php if (isset($is_pastevents)){ ?>class="active hidden-sm"<?php }else{ ?>class="hidden-sm"<?php } ?>><a href="pastevents.php">Past Events</a></li>


					</ul>
					<ul class="navbar-right">
						<li><a href="myJobs.php" class="btn btn-success">
							<?php if ($user['type']=='jobFinder'){ ?>Jobs History<?php } else { ?>My Jobs<?php } ?></a></li>
						<li><a href="#" class="btn btn-primary btn-user"><i class="fa fa-user-circle-o"></i> <?php echo $_SESSION['username'] ?> <i class="fa fa-caret-down"></i></a>
						<?php 
						//check if user is member or jobprovider and show proper menu
						if($_SESSION['id']>0 && $user['type'] == "jobFinder"){
							echo"<ul>
								<li><a href=\"jobs.php?status=available\">Available Jobs</a></li>
								<li><a href=\"modifyJF.php\">Edit Account Info</a></li>
								<li><a href=\"logOut.php\">Logout</a></li>

							</ul></li>
						</ul>";
						} else if($_SESSION['id'] > 0 && $user['type'] == "jobProvider"){
							echo "<ul>
								<li><a href=\"newJob.php\">Add New Jobs</a></li>
								<li><a href=\"modifyJP.php\">Edit Account Info</a></li>
								<li><a href=\"logOut.php\">Logout</a></li>

							</ul></li>
						</ul>";
						}
					?>
				</div>	
			<?php } else { ?>
			<div class="collapse navbar-collapse" id="navToggle">
				<ul class="nav navbar-nav">
						<li <?php if (isset($is_home)){ ?>class="active"<?php } ?>><a href="index.php">Home</a></li>
						<li <?php if (isset($is_jobs)){ ?>class="active"<?php } ?>><a href="jobs.php">Jobs</a></li>

						<li <?php if (isset($is_aboutagn)){ ?>class="active"<?php }else{ ?><?php } ?>><a href="aboutagn.php">About AGN</a></li>
						<li <?php if (isset($is_ourwork)){ ?>class="active hidden-sm"<?php }else{ ?>class="hidden-sm"<?php } ?>><a href="ourwork.php">Our Work</a></li>

						<li <?php if (isset($is_howhelp)){ ?>class="active hidden-sm"<?php }else{ ?>class="hidden-sm"<?php } ?>><a href="howhelp.php">How Help</a></li>
						<li <?php if (isset($is_pastevents)){ ?>class="active hidden-sm"<?php }else{ ?>class="hidden-sm"<?php } ?>><a href="pastevents.php">Past Events</a></li>


				</ul>
				<ul class="navbar-right">
					<li><a href="LogIn.php" class="btn btn-trans">Log In</a></li>
					<li><a href="SignUp.php" class="btn btn-success">Sign Up</a></li>
				</ul>
			</div>	
			<?php } ?>
	</nav>
</header>

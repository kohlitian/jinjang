<footer class="container-fluid">
		<div class="footer">
			<div class="container marginTB">
				<div class="row">
					<div class="col-xs-7 col-sm-offset-1"  style="line-height: 17pt;">
						Jinjang Project, For a better life
						<small class="hidden-xs"><br><?php

						//count statistics of users and jobs and postings and print them
						if ($connect){ echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from member;"))[0]; ?> members, <?php echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from jobproviders;"))[0]; ?> job providers, <?php echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from jobads;"))[0]; ?> job ads <?php } ?></small>
					</div>
					<div class="col-sm-4 col-xs-5">
						<p>Copyright © 2017 Jinjang Project<br/>
						<small>Rights Reserved<span class="hidden-xs hidden-sm"> for A.Nikdel & L.T Koh</span></small></p>
					</div>
				</div>
			</div>	
		</div>	
	</footer>

<!-- Put scripts at end of the page, so they don't slow down loading time -->
<script type="text/javascript" src = "js/jquery.js"></script>
<script type="text/javascript" src = "js/bootbox.min.js"></script>
<script type="text/javascript" src = "js/bootstrap.min.js"></script>
<?php 
//if rating is needed, include rating plugin javascript codes
if (isset($need_rating)){ ?>
<script type="text/javascript" src = "js/star-rating.min.js"></script>
<?php } 
//if jinjang.js is allowed, included it
if (!isset($no_jinjang_js)) { ?>
<script type="text/javascript" src = "js/jinjang.js"></script>
<?php } ?>

<?php 

//if there is any message to show to user, popup it
if ($passThruMessage!=''){ ?>
<script>

	window.setTimeout( function(){
	bootbox.alert( '<?php echo addslashes($passThruMessage); ?>' );
	},200);
</script>
<?php }

//cleanup database connections and close them.
if (isset($connect)&&$connect)
	mysqli_close($connect);
?>
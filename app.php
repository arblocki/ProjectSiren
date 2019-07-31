<?php 

	require 'vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from $_SESSION
	session_start();
	$stmt = $pdo->query('SELECT * FROM users WHERE username = '.$_SESSION['id']);
	//unset($_SESSION['id']);
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// Get user playlists
	$playlists = getPlaylists($api, 20, 0);
	//print("<pre>".print_r($playlists, true)."</pre>");

?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Project Siren</title>

	<!-- CDN links for Bootstrap, JQuery, and Popper -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  <!-- jQuery -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>  <!-- Popper -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  <!-- Bootstrap JS -->

	<!-- Custom styling -->
	<link href="css/styles.css" rel="stylesheet">

</head>
<body>

	<!-- Header -->
  	<header class="header py-5 mb-5">
    	<div class="container h-100">
      		<div class="row h-100 align-items-left">
        		<div class="col-lg-12 pl-50">
		  			<h1 class="display-4 mt-5 mb-2">Project Siren</h1>
		  			<p class="lead mb-5">Discover new music.</p>
        		</div>
      		</div>
    	</div>
  	</header>

  	<!-- Page Content -->
  	<div class="container primary">

    	<div class="row">
	
	  		<!-- Left pane -->
      		<div class="col-md-3 mb-5">
        		<div class="card h-100">
          			<div class="card-body">
		    			<!-- Playlists category -->
						<div class="playlists-button">
			    			<a data-toggle="collapse" data-target="#collapse1"><h5>Playlists</h5></a>
		    			</div>
		    			<div id="collapse1" class="collapse">
		  	  				<ul class="list-group">
			    			<li class="list-group-item">Trip</li>
			    			<li class="list-group-item">You already know</li>
			    			<li class="list-group-item">July 2019</li>
			  				</ul>
		    			</div>
			
			<!-- Top Artists -->
			
			<!-- Top Songs -->
			
			<!-- Recently Played Songs -->
			
		  			</div>
        		</div>
      		</div>
	  		<!-- Left -->
	  
	  		<!-- Center pane -->
      		<div class="col-md-6 mb-5">
        	<div class="card h-100">
          		<div class="card-body">
            		<h5 class="card-title" style="margin-bottom: 10px;">Current Selection</h5>
            			<table>
			  			<col width="5%">
			  			<col width="55%">
			  			<col width="35%">
			  			<col width="5%">
			  
			  			<thead>
							<th></th>
							<th>Song</th>
							<th>Artist</th>
							<th>Favorite?</th>
			  			</thead>
			  			<tbody>
							<tr>
					  			<td><div class="form-check">
									<label class="form-check-label" style="color: #FFFFFF;">
						  			<input type="checkbox" class="form-check-input" value="">.
									</label>
					  			</div></td>
					  			<td>Guru</td>
					  			<td>Coast Modern</td>
					  			<td><div class="form-check">
									<label class="form-check-label" style="color: #FFFFFF;">
						  				<input type="checkbox" class="form-check-input" value="">.
									</label>
					  			</div></td>
							</tr>
			  			</tbody>
						</table>
          			</div>
        		</div>
      		</div>
	  		<!-- Center -->
	  
	  		<!-- Right pane -->
      		<div class="col-md-3 mb-5">
        		<div class="card h-100">
          			<div class="card-body">
            			<h4 class="card-title">Tune your Playlist!</h4>
						<h5>Audio Features</h5>
						<div class="feature-sliders">
              				<label for="acoustic">Acousticness</label><br>
			  				<input type="range" class="slide w-100" id="acoustic"><br>
			  
			  				<label for="dance">Danceability</label><br>
			  				<input type="range" class="slide w-100" id="dance">
						</div>
          			</div>
        		</div>
      		</div>
	  		<!-- Right -->
	  
    	</div>
    	<!-- /.row -->

  	</div>
  	<!-- /.container -->

  	<!-- Footer -->
  	<footer class="py-5 primary-neutral">
    	<div class="container">
    		<p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
    	</div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


	<!-- Original Page code -->
	<!--
	<div class="select">
		<div class="list">
			<?php // Print playlist names 
				$index //= 0;
				//foreach ($playlists as $pl) {
					//echo "<a href='#items' class='li' id='".$index."'>".$pl->name."</a><br>";
					//++$index;
				//}
			?>
		</div>
	</div>

	<div class="view">
		<div id="items">

		</div>
	</div>

	<div class="stats">

	</div> -->

	<script>
		
		/*$(document).ready(function() {
			var jsPlaylists = <?php //echo json_encode($playlists); ?>;

			// When list item is clicked, update viewer 
			$('.li').click(function(event) {
				var index = this.id;
				var pl = jsPlaylists[index];
				window.console && console.log('Printing playlist '.index); 
				$('#items').empty();
				$('#items').append(
					JSON.stringify(pl, null, 2)
				);
			});
		
		});*/

	</script>
</body>

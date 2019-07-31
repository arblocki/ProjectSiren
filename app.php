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
	<meta name="author" content="Andrew Blocki">

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
					<div class="playlists-button">
					    <a data-toggle="collapse" data-target="#collapse2"><h5>Top Artists</h5></a>
					</div>
					<div id="collapse2" class="collapse">
						<ul class="list-group">
						<li class="list-group-item">Tyler, The Creator</li>
						<li class="list-group-item">BROCKHAMPTON</li>
						<li class="list-group-item">MF DOOM</li>
						</ul>
					</div>

					<!-- Top Songs -->
					<div class="playlists-button">
					    <a data-toggle="collapse" data-target="#collapse3"><h5>Top Songs</h5></a>
					</div>
					<div id="collapse3" class="collapse">
						<ul class="list-group">
						<li class="list-group-item">NEW MAGIC WAND</li>
						<li class="list-group-item">STUPID</li>
						<li class="list-group-item">BLEACH</li>
						<li class="list-group-item">Crime Pays</li>
						<li class="list-group-item">Meat Grinder</li>
						</ul>
					</div>
			
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

							<label for="energy">Energy</label><br>
							<input type="range" class="slide w-100" id="energy">

							<label for="instrument">Instrumentalness</label><br>
							<input type="range" class="slide w-100" id="instrument">

							<label for="liveness">Liveness</label><br>
							<input type="range" class="slide w-100" id="liveness">

							<label for="loudness">Loudness</label><br>
							<input type="range" class="slide w-100" id="loudness">

							<label for="speech">Speechiness</label><br>
							<input type="range" class="slide w-100" id="speech">

							<label for="tempo">Tempo</label><br>
							<input type="range" class="slide w-100" id="tempo">

							<label for="valence">Valence</label><br>
							<input type="range" class="slide w-100" id="valence">
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
    		<p class="m-0 text-center text-white">Copyright &copy; Project Siren 2019</p>
    	</div>
      <!-- /.container -->
    </footer>
	
	<script type="text/javascript">
		<!-- AJAX for loading playlists and other songs lists -->
		
		<!-- Get track data for a given playlist -->
		function getTracks(playlistID) {
			if ($.active > 0) { 
				xmlhttp.abort();
			}
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					handleResponse(this.responseTest);
				}
			};
			xmlhttp.open("GET", "analyzePlaylist.php?id=" + playlistID, true); // Returns json encoded object
			xmlhttp.send();
		}

		function handleResponse(jsonObject) {
			
		}
		
		<!-- jQuery for audio feature sliders, key songs, etc. -->
		$(document).ready(function(){
			
			<!-- Check marks for audio feature calculation -->
			$('.audioFeat').click(function(){
				if($(this).prop("checked") == true){
					alert("Checkbox is checked.");
					// Add this song's audio features to the averages
				} else {
					alert("Checkbox is unchecked.");
					// Take out this song's audio features from the averages
				}
			});
		});
	</script>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

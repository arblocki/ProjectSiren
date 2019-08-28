<?php 

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	session_start();

	// Login to API 
	require_once "apiLogin.php";
	
	// API READY
	
	// Triggered when user clicks one of the buttons on this page  
	if (isset($_POST['action'])) { 
		// Create playlist if the user clicked "Add to my Spotify!"
		if ($_POST['action'] == 'add') {
			// Generate the description string 
			$description = getDescription($api); // from func.php

			// Create playlist 
			$newPlaylist = $api->createPlaylist(['name' => $_POST['title'] ]);
			$id = $newPlaylist->id;

			// Add description 
			$api->updatePlaylist($id, [
				'description' => 'This Siren Playlist was generated at ProjectSiren.me with the '.
								'following key tracks and audio features: '.$description
			]);

			// Add tracks to it 
			$api->addPlaylistTracks($id, $_SESSION['recommend']);

			// Add playlist record to database 
			insertPlaylist($pdo);

			// Save playlist ID for success flash message and redirect to app 
			$_SESSION['success'] = $id;
			header("Location: app.php");
			return;
		} else {
			// Redirect to app 
			header("Location: app.php");
			return;
		}
	}

	// Generate comma seperated list of track IDs 
	$IDs = [ $_SESSION['id0'] ];
	for ($i = 1; $i < $_SESSION['numIDs']; ++$i) {
		array_push($IDs, $_SESSION['id'.$i]);
	}

	// Get recommendations 
	$recommendations = $api->getRecommendations([
		'limit' => 50,
		'seed_tracks' => $IDs,
		'target_acousticness' => $_SESSION['acoustic'],
		'target_danceability' => $_SESSION['dance'],
		'target_energy' => $_SESSION['energy'],
		'target_instrumentalness' => $_SESSION['instrument'],
		'target_tempo' => $_SESSION['tempo'],
		'target_valence' => $_SESSION['valence']
	]);

	// Push array of song IDs to SESSION 
	$_SESSION['recommend'] = array();
	foreach ($recommendations->tracks as $track) {
		array_push($_SESSION['recommend'], $track->id);
	}

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
<link href="../css/styles.css" rel="stylesheet">
<link href="../css/recommendations.css" rel="stylesheet">

<style>

td {
	margin-top: 10px;
	margin-bottom: 10px;
}

</style>

</head>
<body>

<!-- Header -->
<header class="header py-5 mb-5">
<div class="container">
		<div class="row align-items-left">
		<div class="col-lg-12 pl-50">
  			<h1 class="display-4 mt-5 mb-2">Project Siren</h1>
  			<p class="lead mb-5">Discover new music.</p>
		</div>
		</div>
</div>
</header>

<!-- Page Content -->
<div class="container primary">

	<form id="hiddenForm" method="POST">
		<?php  
			echo '<input type="text" id="playlistTitle" name="title" value="Siren Playlist based on ';
				
			// Generate playlist title based on number of key tracks 
			// EXAMPLE: 
			// 	1 key track: 	...based on {title} by {artist}
			// 	2 tracks: 	...based on {title} by {artist} and {title} by {artist}
			// 	3 or more: 	...based on {title} by {artist}, {title} by {artist}, and more...
			echo $api->getTrack( $_SESSION['id0'] )->name;
			if ( $_SESSION['numIDs'] == 2 ) {
				echo ' and '.$api->getTrack( $_SESSION['id1'] )->name;
			} else if ( $_SESSION['numIDs'] > 1 ) {
				echo ', '.$api->getTrack( $_SESSION['id1'] )->name.', and more...';
			}

			echo '">';
		?>
		<input id="action" type="text" name="action">
	</form>

	<div class="row">
		<div class="col-12">
			<table>
				<col width="70%">
				<col width="30%">
				
				<thead>
					<th>Song</th>
					<th>Artist</th>
				</thead>
				<tbody>
				<?php
					// Display recommendation tracks in table 
					foreach ($recommendations->tracks as $track) {
						echo '<tr><td>'.$track->name.'</td><td>';
						if (count($track->artists) > 1) {
							echo $track->artists[0]->name; 
							for ($i = 1; $i < count($track->artists); ++$i) {
								echo ', '.$track->artists[$i]->name;
							}
						} else {
							echo $track->artists[0]->name;
						}
						echo '</td></tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-0 col-md-3"></div>

		<div class="col-xs-12 col-md-3">
			<a class="btn btn-lg" onclick="addPlaylist()">Add this to my Spotify!</a>
		</div>
		<div class="col-xs-12 col-md-3">
			<a class="btn btn-lg" onclick="startOver()">Start Over.</a>
		</div>
	
		<div class="col-xs-0 col-md-3"></div>

	</div>

</div>

<!-- Footer -->
<footer class="py-5 secondary">
<div class="container">
	<p class="m-0 text-center text-white">Copyright &copy; Project Siren 2019</p>
</div>
<!-- /.container -->
</footer>

<script type="text/javascript">
	
	// FUNCTION: Set hidden form action field to "add" and then submit form
	// Triggered when add playlist button is clicked
	function addPlaylist() {
		$('#action').attr('value', 'add');
		$('#hiddenForm').submit();
	}

	// FUNCTION: Set hidden form action field to "restart" and then submit form
	// Triggered when start over button is clicked
	function startOver() {
		$('#action').attr('value', 'restart');
		$('#hiddenForm').submit();
	}

</script>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

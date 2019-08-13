<?php 

	require 'vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from $_SESSION
	session_start();
	$stmt = $pdo->query('SELECT * FROM users WHERE username = '.$_SESSION['id']);
	
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// Generate comma seperated list of track IDs 
	$IDs = [ $_SESSION['id0'] ];
	for ($i = 1; $i < $_SESSION['numIDs']; ++$i) {
		array_push($IDs, $_SESSION['id'.$i]);
	}

	//print("<pre>".print_r($IDs, true)."</pre>");

	// Get recommendations 
	$recommendations = $api->getRecommendations([
		'limit' => 50,
		'seed_tracks' => $IDs,
		'target_acousticness' => $_SESSION['acoustic'],
		'target_danceability' => $_SESSION['dance'],
		'target_energy' => $_SESSION['energy'],
		'target_instrumentalness' => $_SESSION['instrument'],
		'target_liveness' => $_SESSION['liveness'],
		'target_loudness' => $_SESSION['loudness'],
		'target_speechiness' => $_SESSION['speech'],
		'target_tempo' => $_SESSION['tempo'],
		'target_valence' => $_SESSION['valence']
	]);

	//print("<pre>".print_r($recommendations, true)."</pre>");

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

	<div class="row">
		<div class="col-xs-12">
			<table>
				<col width="55%">
				<col width="35%">
				<col width="10%">
				
				<thead>
					<th>Song</th>
					<th>Artist</th>
					<th>Remove Song</th>
				</thead>
				<tbody>
				<?php
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
						echo '</td><td><img src="images/redX.png" style="width: 2rem;"></td></tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>

</div>
<?php 

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from $_SESSION
	$stmt = $pdo->query('SELECT * FROM users WHERE user_id = \''.$_SESSION['id'].'\'');
	
	$row = $stmt->fetch();

	$session->refreshAccessToken(/*$_SESSION['refresh']*/$row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);
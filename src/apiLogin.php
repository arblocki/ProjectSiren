<?php 

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from $_SESSION
	$stmt = $pdo->query('SELECT * FROM users WHERE user_id = \''.$_SESSION['id'].'\'');
	$row = $stmt->fetch();

	// Get access token and set it in API instance
	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();
	$api->setAccessToken($accessToken);

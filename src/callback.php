<?php 

	require '../vendor/autoload.php';
	require_once 'pdo.php';
	require_once "session.php";

	// Request a access token using the code from Spotify
	$session->requestAccessToken($_GET['code']);

	$accessToken = $session->getAccessToken();
	$refreshToken = $session->getRefreshToken();

	$api = new SpotifyWebAPI\SpotifyWebAPI();
	$api->setAccessToken($accessToken);

	$me = $api->me();
	
	// Query the user table for an existing record of this user 
	$userQuery = $pdo->query('SELECT * FROM users WHERE user_id = \''.$me->id.'\'');
	
	if ( !$userQuery->fetch() ) {
		// If new user, insert new record into database 
		$stmt = $pdo->prepare('INSERT INTO users (user_id, name, email, access, refresh) 
			VALUES ( :uid, :nme, :eml, :acc, :ref )');

		$stmt->execute(array(
			':uid' => $me->id,
			':nme' => $me->display_name,
			':eml' => $me->email,
			':acc' => $accessToken,
			':ref' => $refreshToken)
		);
	} else {
		// If existing user, update current record in database 
		$stmt = $pdo->prepare('UPDATE users SET name = :nme, email = :eml, 
			access = :acc, refresh = :ref WHERE user_id = \''.$me->id.'\'');

		$stmt->execute(array(
			':nme' => $me->display_name,
			':eml' => $me->email,
			':acc' => $accessToken,
			':ref' => $refreshToken)
		);
	}

	// Store login time in login table 
	$stmt = $pdo->prepare('INSERT INTO login (user_id, login_time) 
		VALUES ( :uid, :ltime )');
	$stmt->execute(array(
	  ':uid' => $me->id,
	  ':ltime' => gmdate('Y-m-d H:i:s'))
	);

	// Store the id in SESSION and redirect to the app
	session_start();
	$_SESSION['id'] = $me->id;
	header('Location: http://projectsiren.us-east-2.elasticbeanstalk.com/src/app.php');
	die();
	

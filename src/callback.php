<?php 

	require '../vendor/autoload.php';
	require_once 'pdo.php';
	require_once "session.php";

	// Request a access token using the code from Spotify
	$session->requestAccessToken($_GET['code']);

	$accessToken = $session->getAccessToken();
	$refreshToken = $session->getRefreshToken();

	//error_log('accessToken: '.$accessToken);
	//error_log('reffreshToken: '.$refreshToken);

	$api = new SpotifyWebAPI\SpotifyWebAPI();
	$api->setAccessToken($accessToken);

	$me = $api->me();

	// Store the access and refresh tokens somewhere. In a database for example.

	$userQuery = $pdo->query('SELECT * FROM users WHERE user_id = '.$me->id);

	if ( !$userQuery->fetch() ) {
		$stmt = $pdo->prepare('INSERT INTO users (user_id, access, refresh) 
			VALUES ( :uid, :acc, :ref )');

		$stmt->execute(array(
		  ':uid' => $me->id,
		  ':acc' => $accessToken,
		  ':ref' => $refreshToken)
		);
	} else {
		$stmt = $pdo->prepare('UPDATE users SET access = :acc, 
			refresh = :ref WHERE user_id = '.$me->id);

		$stmt->execute(array(
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
	header('Location: http://localhost/ProjectSiren/src/app.php');
	die();
	
<?php 

	require 'vendor/autoload.php';
	require_once 'pdo.php';
	require_once "session.php";

	// Request a access token using the code from Spotify
	$session->requestAccessToken($_GET['code']);

	$accessToken = $session->getAccessToken();
	$refreshToken = $session->getRefreshToken();

	error_log('accessToken: '.$accessToken);
	error_log('refreshToken: '.$refreshToken);

	// Store the access and refresh tokens somewhere. In a database for example.

	$stmt = $pdo->prepare('INSERT INTO users
	  (username, access, refresh) VALUES ( :user, :acc, :ref )');

	$stmt->execute(array(
	  ':user' => 'test',
	  ':acc' => $accessToken,
	  ':ref' => $refreshToken)
	);

	// Send the user along and fetch some data!
	header('Location: http://localhost/ProjectSiren/app.php');
	die();
	
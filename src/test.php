<?php 

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from somewhere. A database for example.
	session_start();
	$stmt = $pdo->query('SELECT * FROM users WHERE user_id = '.$_SESSION['id']);
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// Test out API methods to your heart's desire 

	/*$array = array( '7lW2HbFRh65PMz82J7mPnb' );
	$audioFeatures = $api->getAudioFeatures('7lW2HbFRh65PMz82J7mPnb');
	print("<pre>".print_r($audioFeatures, true)."</pre>");*/

	//$test = $api->getPlaylist('1vdulGmOo1satkc8G01qrJ');
	//$analysis = analyzePlaylist($api, $test);

	print("<pre>".print_r($api->me(), true)."</pre>");
	
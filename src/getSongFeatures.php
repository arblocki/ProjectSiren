<?php

	header('Content-Type: application/json');

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	session_start();
	$stmt = $pdo->query('SELECT * FROM users WHERE user_id = '.$_SESSION['id']);
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// API READY 

	$audioFeatures = $api->getAudioFeatures($_REQUEST['id'])->audio_features[0];
	
	$audioFeatureResponse = array($audioFeatures->acousticness, 
								$audioFeatures->danceability, 
								$audioFeatures->energy, 
								$audioFeatures->instrumentalness, 
								$audioFeatures->tempo, 
								$audioFeatures->valence
							);

	echo json_encode($audioFeatureResponse);
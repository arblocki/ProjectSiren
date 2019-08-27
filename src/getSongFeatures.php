<?php

	header('Content-Type: application/json');

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	session_start();
	
	require_once "apiLogin.php";

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
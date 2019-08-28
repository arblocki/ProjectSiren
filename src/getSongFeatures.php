<?php

	// FUNCTION: Return specific audio features that we need for a specific song
	// Called for every song in a playlist or song selection in app.php 

	header('Content-Type: application/json');

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	session_start();
	
	// Login to API
	require_once "apiLogin.php";

	// API READY 

	// Get audio features for the requested track 
	$audioFeatures = $api->getAudioFeatures($_REQUEST['id'])->audio_features[0];	
	// Extract the specific needed features and return that array
	$audioFeatureResponse = array($audioFeatures->acousticness, 
								$audioFeatures->danceability, 
								$audioFeatures->energy, 
								$audioFeatures->instrumentalness, 
								$audioFeatures->tempo, 
								$audioFeatures->valence
							);

	echo json_encode($audioFeatureResponse);

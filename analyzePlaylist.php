<?php

	header('Content-Type: application/json');

	require 'vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	session_start();
	$stmt = $pdo->query('SELECT * FROM users WHERE username = '.$_SESSION['id']);
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// API READY 

	$playlistTrackInfo = $api->getPlaylistTracks($_REQUEST['id']);

	$playlistTracks = $playlistTrackInfo->items;

	// Initialize array of associative arrays
	//		Each internal array will have strictly the data that we need
	
	$audioFeatureTotals = array(0, 0, 0, 0, 0, 0);
	
	foreach ($playlistTracks as $track) {
		if ($track->is_local) {
			continue;
		}

		$currentTrack = $track->track;

		$audioFeatures = $api->getAudioFeatures($currentTrack->id)->audio_features[0];

		$audioFeatureTotals[0] += $audioFeatures->acousticness;
		$audioFeatureTotals[1] += $audioFeatures->danceability;
		$audioFeatureTotals[2] += $audioFeatures->energy;
		$audioFeatureTotals[3] += $audioFeatures->instrumentalness;
		$audioFeatureTotals[4] += $audioFeatures->tempo;
		$audioFeatureTotals[5] += $audioFeatures->valence;
	}

	echo json_encode($response);
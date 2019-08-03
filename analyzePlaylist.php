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

	$numTracks = $playlistTrackInfo->total;
	$playlistTracks = $playlistTrackInfo->items;

	// Initialize array of associative arrays
	//		Each internal array will have strictly the data that we need
	$playlistData = array();
	foreach ($playlistTracks as $track) {
		if ($track->is_local) {
			continue;
		}


		$currentTrack = $track->track;

		$audioFeatures = $api->getAudioFeatures($currentTrack->id)->audio_features[0];

		$playlistData[] = array('track' => $currentTrack->name,
								'trackID' => $currentTrack->id,
								'artist' => $currentTrack->artists[0]->name,
								'artistID' => $currentTrack->artists[0]->id,
								'popularity' => $currentTrack->popularity,
								// Audio features
								'dance' => $audioFeatures->danceability,
								'energy' => $audioFeatures->energy,
								'loudness' => $audioFeatures->loudness,
								'speech' => $audioFeatures->speechiness,
								'acoustic' => $audioFeatures->acousticness,
								'instrument' => $audioFeatures->instrumentalness,
								'liveness' => $audioFeatures->liveness,
								'valence' => $audioFeatures->valence,
								'tempo' => $audioFeatures->tempo
		);
	}

	echo json_encode($playlistData);
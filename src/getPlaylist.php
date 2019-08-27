<?php

	header('Content-Type: application/json');

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	session_start();
	
	require_once "apiLogin.php";

	// API READY 

	$playlistTrackInfo = $api->getPlaylistTracks($_REQUEST['id']);

	$numTracks = $playlistTrackInfo->total;
	$playlistTracks = $playlistTrackInfo->items;

	// Initialize array of associative arrays
	//		Each internal array will have strictly the data that we need
	$trackData = array();

	foreach ($playlistTracks as $track) {
		if ($track->is_local) {
			continue;
		}

		$currentTrack = $track->track;
		$trackData[] = array('track' => $currentTrack->name,
								'trackID' => $currentTrack->id,
								'artist' => $currentTrack->artists[0]->name,
								//'artistID' => $currentTrack->artists[0]->id,
								//'album' => $currentTrack->album->name,
								//'albumID' => $currentTrack->album->id,
								'image' => $currentTrack->album->images[1]->url
		);
	}

	echo json_encode($trackData);
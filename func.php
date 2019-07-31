<?php 

	// Get user playlists
	// Returns array with playlist objects 
	function getPlaylists($api, $limit, $offset) {

		$playlists = $api->getMyPlaylists([
			'limit' => $limit,
			'offset' => $offset 
		]);

		return $playlists->items;
	}

	// Get relevant track info for all tracks in a playlist
	function analyzePlaylist($api, $playlistId) {
		$playlistTrackInfo = $api->getPlaylistTracks($playlistId);

		$numTracks = $playlistTrackInfo->total;
		$playlistTracks = $playlistTrackInfo->items;

		// Initialize array of associative arrays
		//		Each internal array will have strictly the data that we need
		$playlistData = array();
		foreach ($playlistTracks as $track) {
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

		return $playlistData;
	
	}
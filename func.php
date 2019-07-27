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
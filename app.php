<?php 

	require 'vendor/autoload.php';
	require_once "pdo.php";

	$api = new SpotifyWebAPI\SpotifyWebAPI();
	print_r($_COOKIE);
	// Fetch the saved access token from somewhere. A database for example.
	$stmt = $pdo->query('SELECT * FROM users WHERE username = \'test\'');
	$row = $stmt->fetch();
	$api->setAccessToken($row['access']);

	// It's now possible to request data about the currently authenticated user
	
	print_r(
	    $api->me()
	);

	// Getting Spotify catalog data is of course also possible
	$song = $api->getTrack('7EjyzZcbLxW7PaaLua9Ksb');
	print_r(
	    $song['name']
	);
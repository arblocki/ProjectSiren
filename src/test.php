<?php 

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	session_start();
	
	// Login to API
	require_once "apiLogin.php";

	// Test out API methods to your heart's desire 

	/*$array = array( '7lW2HbFRh65PMz82J7mPnb' );
	$audioFeatures = $api->getAudioFeatures('7lW2HbFRh65PMz82J7mPnb');
	print("<pre>".print_r($audioFeatures, true)."</pre>");*/

	//$test = $api->getPlaylist('1vdulGmOo1satkc8G01qrJ');
	//$analysis = analyzePlaylist($api, $test);

	print("<pre>".print_r($api->me(), true)."</pre>");
	

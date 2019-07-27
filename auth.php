<?php 

	require_once 'vendor/autoload.php';
	require_once "session.php";

	error_log('session created');

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	if (isset($_GET['code'])) {
	    $session->requestAccessToken($_GET['code']);
	    $api->setAccessToken($session->getAccessToken());

	    print_r($api->me());
	} else {
	    $options = [
	        'scope' => [
	            'user-top-read',
	            'user-read-email',
	            'user-read-recently-played',
	            'user-follow-read',
	            'playlist-read-collaborative',
	            'playlist-read-private',
	            'playlist-modify-public',
	            'playlist-modify-private'
	        ],
	    ];

	    header( 'Location: ' . $session->getAuthorizeUrl($options) );
	    die();
	}

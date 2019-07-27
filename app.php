<?php 

	require 'vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from somewhere. A database for example.
	$stmt = $pdo->query('SELECT * FROM users WHERE username = \'test\'');
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// It's now possible to request data about the currently authenticated user
	
	echo( $api->me()->display_name . '<br><br>');

	// Get user playlists
	$playlists = getPlaylists($api, 20, 0);
	//print("<pre>".print_r($playlists, true)."</pre>");

?>
<head>
	<title>Project Siren</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

	<div class="select">
		<div class="list">
			<?php // Print playlist names 
				$index = 0;
				foreach ($playlists as $pl) {
					echo "<a href='#items' class='li' id='".$index."'>".$pl->name."</a><br>";
					++$index;
				}
			?>
		</div>
	</div>

	<div class="view">
		<div id="items">

		</div>
	</div>

	<div class="stats">

	</div>

	<script>
		
		$(document).ready(function() {
			var jsPlaylists = <?php echo json_encode($playlists); ?>;

			// When list item is clicked, update viewer 
			$('.li').click(function(event) {
				var index = this.id;
				var pl = jsPlaylists[index];
				window.console && console.log('Printing playlist '.index); 
				$('#items').empty();
				$('#items').append(
					JSON.stringify(pl, null, 2)
				);
			});
		
		});

	</script>
</body>

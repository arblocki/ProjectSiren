<?php 

	require 'vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	// Check if form has been submitted to get recommendations 
	session_start();
	if (isset($_POST['songID0'])) {
		$_SESSION['id0'] = $_POST['songID0'];
		$_SESSION['numIDs'] = 1;
		for ($i = 1; $i < 5; ++$i) {
			if (isset($_POST['songID'.$i]) && $_POST['songID'.$i] != '') {
				$_SESSION['id'.$i] = $_POST['songID'.$i];
				++$_SESSION['numIDs'];
			}
		}

		$_SESSION['acoustic'] = ($_POST['acoustic'] / 100);
		$_SESSION['dance'] = ($_POST['dance'] / 100);
		$_SESSION['energy'] = ($_POST['energy'] / 100);
		$_SESSION['instrument'] = ($_POST['instrument'] / 100);
		$_SESSION['liveness'] = ($_POST['liveness'] / 100);
		$_SESSION['loudness'] = $_POST['loudness'];
		$_SESSION['speech'] = ($_POST['speech'] / 100);
		$_SESSION['tempo'] = $_POST['tempo'];
		$_SESSION['valence'] = ($_POST['valence'] / 100);

		header("Location: recommendations.php");
		return;
	}

	$api = new SpotifyWebAPI\SpotifyWebAPI();

	// Fetch the saved access token from $_SESSION
	$stmt = $pdo->query('SELECT * FROM users WHERE username = '.$_SESSION['id']);
	
	$row = $stmt->fetch();

	$session->refreshAccessToken($row['refresh']);
	$accessToken = $session->getAccessToken();

	$api->setAccessToken($accessToken);

	// Get user playlists
	$playlists = getPlaylists($api, 50, 0);
	$numPlaylists = count($playlists);
	//print("<pre>".print_r($playlists, true)."</pre>");

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="Andrew Blocki">

<title>Project Siren</title>

<!-- CDN links for Bootstrap, JQuery, and Popper -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  <!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>  <!-- Popper -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  <!-- Bootstrap JS -->

<!-- Custom styling -->
<link href="css/styles.css" rel="stylesheet">

</head>
<body>

<!-- Header -->
<header class="header py-5 mb-5">
<div class="container h-100">
		<div class="row h-100 align-items-left">
		<div class="col-lg-12 pl-50">
  			<h1 class="display-4 mt-5 mb-2">Project Siren</h1>
  			<p class="lead mb-5">Discover new music.</p>
		</div>
		</div>
</div>
</header>

<!-- Page Content -->
<div class="container primary">

<div class="row">

		<!-- Left pane -->
		<div class="col-lg-3 mb-5">
		<div class="card h-100">
  			<div class="card-body" id>
    			<!-- Playlists category -->
				<div class="playlists-button">
	    			<a data-toggle="collapse" data-target="#collapse1"><h5>Playlists</h5></a>
    			</div>
    			<div id="collapse1" class="collapse">
  	  				<ul class="list-group">
	    			<!--<a onclick="getTracks('7HJcgdQgJAOGel6j7o0Mwo')"><li class="list-group-item">Trip</li></a>-->
	    			<?php 
	    				for ($i = 0; $i < $numPlaylists; ++$i) {
	    					echo '<a onclick="getTracks(\''.$playlists[$i]->id.'\')"><li class="list-group-item">'.
	    							$playlists[$i]->name.'</li></a>';
	    				}
	    			?>
	  				</ul>
    			</div>
	
				<!-- Top Artists -->
				<div class="playlists-button">
				    <a data-toggle="collapse" data-target="#collapse2"><h5>Top Artists</h5></a>
				</div>
				<div id="collapse2" class="collapse">
					<ul class="list-group">
					<li class="list-group-item">Tyler, The Creator</li>
					<li class="list-group-item">BROCKHAMPTON</li>
					<li class="list-group-item">MF DOOM</li>
					</ul>
				</div>

				<!-- Top Songs -->
				<div class="playlists-button">
				    <a data-toggle="collapse" data-target="#collapse3"><h5>Top Songs</h5></a>
				</div>
				<div id="collapse3" class="collapse">
					<ul class="list-group">
					<li class="list-group-item">NEW MAGIC WAND</li>
					<li class="list-group-item">STUPID</li>
					<li class="list-group-item">BLEACH</li>
					<li class="list-group-item">Crime Pays</li>
					<li class="list-group-item">Meat Grinder</li>
					</ul>
				</div>
		
				<!-- Recently Played Songs -->
	
  			</div>
		</div>
		</div>
		<!-- Left -->

		<!-- Center pane -->
		<div class="col-lg-6 mb-5">
    	<div class="card h-100">
    		<div id="loading-overlay"></div>
      		<div class="card-body" id="center-card-body">
        		<h5 class="card-title" style="margin-bottom: 10px;">Current Selection</h5>
    			<table>
	  			<col width="5%">
	  			<col width="55%">
	  			<col width="35%">
	  			<col width="5%">
	  
	  			<thead>
					<th></th>
					<th>Song</th>
					<th>Artist</th>
					<th style="text-align: center;">Key Track</th>
	  			</thead>
	  			<tbody>
	  				
	  			</tbody>
				</table>
  			</div>
		</div>
		</div>
		<!-- Center -->

		<!-- Right pane -->
		<div class="col-lg-3 mb-5">
		<div class="card h-100">
  			<div class="card-body">
    			<h4 class="card-title">Tune your Playlist!</h4>
    			<!-- Siren Songs section -->
		        <div class="key-tracks">
		          	<h5>Key Tracks</h5>
		          	<div class="row icons">
		            	<div class="track">
		            		<img class="track-icon" id="track-icon0" src="images/placeholder.jpg" data-filled="0">
		            		<div class="overlay">
		            			<img class="icon-x" id="icon0" src="images/redX.png">
		            		</div>
		            	</div>
		            	<div class="track">
		            		<img class="track-icon" id="track-icon1" src="images/placeholder.jpg" data-filled="0">
		            		<div class="overlay">
		            			<img class="icon-x" id="icon1" src="images/redX.png">
		            		</div>
		            	</div>
		            	<div class="track">
		            		<img class="track-icon" id="track-icon2" src="images/placeholder.jpg" data-filled="0">
		            		<div class="overlay">
		            			<img class="icon-x" id="icon2" src="images/redX.png">
		            		</div>
		            	</div>
		            	<div class="track">
		            		<img class="track-icon" id="track-icon3" src="images/placeholder.jpg" data-filled="0">
		            		<div class="overlay">
		            			<img class="icon-x" id="icon3" src="images/redX.png">
		            		</div>
		            	</div>
		            	<div class="track">
		            		<img class="track-icon" id="track-icon4" src="images/placeholder.jpg" data-filled="0">
		            		<div class="overlay">
		            			<img class="icon-x" id="icon4" src="images/redX.png">
		            		</div>
		            	</div>
		          	</div>
		          	<div class="detail">
		            	<div class="detail-blurb">
		              		<p>Add Key Tracks by clicking the star icons.</.p>
		            	</div>
		            	<div class="track-detail">
		              		<strong><p id="track-title">Song Title</p></strong>
		              		<p id="key-artist">Artist</p>
		              		<p id="key-album">Album</p>
		            	</div>
		          	</div>
		        </div>
        
        		<!-- Audio Features section -->
				<h5>Audio Features</h5>
				<div class="feature-sliders">
      				<label for="acoustic">Acousticness</label>
      				<p class="feature-value" id="acoustic-value">50</p>
	  				<input type="range" class="slide w-100" id="acoustic" min="0" max="100" step="0.1"
	  				oninput="showVal(this.value, 'acoustic')" onchange="showVal(this.value, 'acoustic')">
	  
	  				<label for="dance">Danceability</label>
	  				<p class="feature-value" id="dance-value">50</p>
	  				<input type="range" class="slide w-100" id="dance" min="0" max="100" step="0.1"
	  				oninput="showVal(this.value, 'dance')" onchange="showVal(this.value, 'dance')">

					<label for="energy">Energy</label>
					<p class="feature-value" id="energy-value">50</p>
					<input type="range" class="slide w-100" id="energy" min="0" max="100" step="0.1"
					oninput="showVal(this.value, 'energy')" onchange="showVal(this.value, 'energy')">

					<label for="instrument">Instrumentalness</label>
					<p class="feature-value" id="instrument-value">50</p>
					<input type="range" class="slide w-100" id="instrument" min="0" max="100" step="0.01"
					oninput="showVal(this.value, 'instrument')" onchange="showVal(this.value, 'instrument')">

					<label for="liveness">Liveness</label>
					<p class="feature-value" id="liveness-value">50</p>
					<input type="range" class="slide w-100" id="liveness" min="0" max="100" step="0.1"
					oninput="showVal(this.value, 'liveness')" onchange="showVal(this.value, 'liveness')">

					<label for="loudness">Loudness</label>
					<p class="feature-value" id="loudness-value">-20</p>
					<input type="range" class="slide w-100" id="loudness" min="-40" max="0" step="0.1"
					oninput="showVal(this.value, 'loudness')" onchange="showVal(this.value, 'loudness')">

					<label for="speech">Speechiness</label>
					<p class="feature-value" id="speech-value">50</p>
					<input type="range" class="slide w-100" id="speech" min="0" max="100" step="0.01"
					oninput="showVal(this.value, 'speech')" onchange="showVal(this.value, 'speech')">

					<label for="tempo">Tempo</label>
					<p class="feature-value" id="tempo-value">135</p>
					<input type="range" class="slide w-100" id="tempo" min="50" max="220" step="1"
					oninput="showVal(this.value, 'tempo')" onchange="showVal(this.value, 'tempo')">

					<label for="valence">Valence</label>
					<p class="feature-value" id="valence-value">50</p>
					<input type="range" class="slide w-100" id="valence" min="0" max="100" step="0.1"
					oninput="showVal(this.value, 'valence')" onchange="showVal(this.value, 'valence')">
				</div>

				<!-- Generate Playlist button -->
				<h5>Generate New Playlist!</h5>
				<button onclick="generatePlaylist()">Generate</button>

				<!-- Hidden form used for playlist generation -->
				<form id="hidden-form" method="POST">
					<!-- song IDs -->
					<input type="hidden" id="songID0" name="songID0" value="">
					<input type="hidden" id="songID1" name="songID1" value="">
					<input type="hidden" id="songID2" name="songID2" value="">
					<input type="hidden" id="songID3" name="songID3" value="">
					<input type="hidden" id="songID4" name="songID4" value="">
					<!-- Audio features -->
					<input type="hidden" id="form-acoustic" name="acoustic" value="">
					<input type="hidden" id="form-dance" name="dance" value="">
					<input type="hidden" id="form-energy" name="energy" value="">
					<input type="hidden" id="form-instrument" name="instrument" value="">
					<input type="hidden" id="form-liveness" name="liveness" value="">
					<input type="hidden" id="form-loudness" name="loudness" value="">
					<input type="hidden" id="form-speech" name="speech" value="">
					<input type="hidden" id="form-tempo" name="tempo" value="">
					<input type="hidden" id="form-valence" name="valence" value="">
				</form>
  			</div>
		</div>
		</div>
		<!-- Right -->

</div>
<!-- /.row -->

</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-5 primary-neutral">
<div class="container">
	<p class="m-0 text-center text-white">Copyright &copy; Project Siren 2019</p>
</div>
<!-- /.container -->
</footer>

<script type="text/javascript">
	
	var audioFeatureTotals = [0, 0, 0, 0, 0, 0, 0, 0, 0];
	var numSongs = 1;
	var json = [];
	var numKeyTracks = 0;
	var keyTracks = new Array();	
	// TODO: Make associative array with key-value pairs: (trackID, xmlhttp)
	// 		If user unchecks a star and the request for the icon is still active, it will abort it

	// AJAX for loading playlists and other songs lists
	
	// Get track data for a given playlist
	function getTracks(playlistID) {
		/*if ($.active > 0) { 
			xmlhttp.abort();
		}*/
		$('#loading-overlay').css("display", "block");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				json = JSON.parse(this.responseText);
				handleResponse(json);
				updateFeatures(json);
			}
		};
		xmlhttp.open("GET", "analyzePlaylist.php?id=" + playlistID, true); // Returns json encoded object 
		xmlhttp.send();
	}

	function handleResponse(json) {
		$('tbody').empty();
		for (var i = 0; i < json.length; i++) {
		    var track = json[i];

		    $('tbody').append(
		    	'<tr id="'+i+'"><td><div class="form-check"> \
					<label class="form-check-label" style="color: #FFFFFF;"> \
					<input type="checkbox" class="form-check-input" value="" checked> \
					&nbsp;</label></div></td> \
				  	<td>'+track.track+'</td><td>'+track.artist+'</td> \
				  	<td><div class="star_container"> \
              		<img class="star" id="star'+i+'" src="images/star_empty.png" onclick="toggleStar('+i+ 
              		')"></div></td></tr>' 
		    );
		}
		$('#loading-overlay').css("display", "none");
		if ($(window).width() < 992 ) {
			$('.collapse').collapse('hide');
		}
	}

	function updateFeatures(json) {
		audioFeatureTotals = [0, 0, 0, 0, 0, 0, 0, 0, 0];
		var numSongs = json.length;
		for (var i = 0; i < json.length; i++) {
		    var track = json[i];

		    audioFeatureTotals[0] += track.acoustic;
		    audioFeatureTotals[1] += track.dance;
		    audioFeatureTotals[2] += track.energy;
		    audioFeatureTotals[3] += track.instrument;
		    audioFeatureTotals[4] += track.liveness;
		    audioFeatureTotals[5] += track.loudness;
		    audioFeatureTotals[6] += track.speech;
		    audioFeatureTotals[7] += track.tempo;
		    audioFeatureTotals[8] += track.valence;
		}

		// TODO: MAYBE REVERSE THIS PART SO THAT SLIDERS GET SET FIRST  ??? 

		// Set numerical values to their new values
		$('#acoustic-value').html( +((audioFeatureTotals[0] / numSongs) * 100).toFixed(6) );
		$('#dance-value').html( +((audioFeatureTotals[1] / numSongs) * 100).toFixed(6) );
		$('#energy-value').html( +((audioFeatureTotals[2] / numSongs) * 100).toFixed(6) );
		$('#instrument-value').html( +((audioFeatureTotals[3] / numSongs) * 100).toFixed(6) );
		$('#liveness-value').html( +((audioFeatureTotals[4] / numSongs) * 100).toFixed(6) );
		$('#loudness-value').html( +(audioFeatureTotals[5] / numSongs).toFixed(6) );
		$('#speech-value').html( +((audioFeatureTotals[6] / numSongs) * 100).toFixed(6) );
		$('#tempo-value').html( +(audioFeatureTotals[7] / numSongs).toFixed(6) );
		$('#valence-value').html( +((audioFeatureTotals[8] / numSongs) * 100).toFixed(6) );
		
		// Set sliders to their new values
		$('#acoustic').attr('value', $('#acoustic-value').html());
		$('#dance').attr('value', $('#dance-value').html());
		$('#energy').attr('value', $('#energy-value').html());
		$('#instrument').attr('value', $('#instrument-value').html());
		$('#liveness').attr('value', $('#liveness-value').html());
		$('#loudness').attr('value', $('#loudness-value').html());
		$('#speech').attr('value', $('#speech-value').html());
		$('#tempo').attr('value', $('#tempo-value').html());
		$('#valence').attr('value', $('#valence-value').html());
	}

	function showVal(newVal, featureName) {
		var idName = '#' + featureName + '-value';
		// DEBUG: window.console && console.log('updating '+idName+' to '+newVal);
		$(idName).html(newVal);
	}
	
	// jQuery for audio feature sliders, key songs, etc.
	$(document).ready(function(){

		// Check marks for audio feature calculation
		$('.audioFeat').click(function(){
			if($(this).prop("checked") == true){
				alert("Checkbox is checked.");
				// Add this song's audio features to the averages
			} else {
				alert("Checkbox is unchecked.");
				// Take out this song's audio features from the averages
			}
		});

		$('.track').mouseenter(function() {
			var filled = $(this).find('.track-icon').attr('data-filled');
			if (filled == '1') {
				$(this).find('.track-icon').css('opacity', 0.6);
				$(this).find('.overlay').css('opacity', 0.9);

				var index = $(this).find('.track-icon').attr('id').substring(10);
	    		$('.detail-blurb').css('display', 'none');
	    		$('.track-detail').css('display', 'block');
	    		$('#track-title').html(keyTracks[index].track);
	    		$('#key-artist').html(keyTracks[index].artist);
	    		$('#key-album').html(keyTracks[index].album);
	    	}
    	});
    	$('.track').mouseleave(function() {
    		$(this).find('.track-icon').css('opacity', 1);
			$(this).find('.overlay').css('opacity', 0);

      		$('.detail-blurb').css('display', 'block');
      		$('.track-detail').css('display', 'none');
    	});

    	$('.icon-x').click(function() {
    		var index = $(this).attr('id').substring(4);
    		var ID = keyTracks[index].trackID;
    		console.log('deleting index '+index+', songID is '+ID);
    		deleteKeyTrack(ID);
    		checkForStar(ID);
    	});
	});

	function toggleStar(songIndex) {
    	if (numKeyTracks == 5 && $('#star'+songIndex).attr("src") == 'images/star_empty.png') {
			alert('Maximum 5 key tracks can be selected.');
		} else {
			if ($('#star'+songIndex).attr("src") == 'images/star_empty.png') {
				$('#star'+songIndex).attr("src", "images/star_filled.png");
				++numKeyTracks;
				window.console && console.log('key tracks: '+numKeyTracks);
				// Call function to add song to key tracks 
				var track = json[songIndex];
				addKeyTrack(track, songIndex);
			} else {
				$('#star'+songIndex).attr("src", "images/star_empty.png");
				window.console && console.log('key tracks: '+numKeyTracks-1);
				// Call function to delete song from key tracks
				var track = json[songIndex];
				//console.log('track: '+);
				deleteKeyTrack(track.trackID);
			}
		}
  	}

  	function addKeyTrack(track) {
		// TODO: Make trimmed track object with id, name, artist, album 
		window.console && console.log(track);
		keyTracks.push(track); // TODO: change push from id to object 
		// Fetch track art - Local storage method
		var imageLink = track.image;
		$( '#track-icon'+(numKeyTracks-1)).attr('src', imageLink);
		$( '#track-icon'+(numKeyTracks-1)).attr('data-filled', '1');
	}


	
	function deleteKeyTrack(trackID) { 
		// Find index within keyTracks and erase it 
		var index = findKeyTrackIndex(trackID);
		if (index != -1) {
			//console.log('keyTracks: '+keyTracks);
			//console.log('searching for '+trackID);
			//window.console && console.log('Deleting key track at index '+index);
			--numKeyTracks;
			keyTracks.splice(index, 1);
			// Move icons on the right of the deleted index to their new position 
			var right = index + 1;
			while (right <= numKeyTracks) {
				$( '#track-icon'+ index ).attr('src', keyTracks[index].image );
				//$( '#track-icon'+ index ).attr('data-JSONIndex', $( '#track-icon'+ right ).attr('data-JSONIndex') );
				++index;
				++right;
			}
			$( '#track-icon'+ index ).attr('src', 'images/placeholder.jpg');
			$( '#track-icon'+ index ).attr('data-filled', '0');
		} else {
			console.log('trackID not found!');
		}
	}

	function findKeyTrackIndex(trackID) {
		for (var i = 0; i < keyTracks.length; ++i) {
			if (keyTracks[i].trackID == trackID) {
				return i;
			}
		}
		return -1;
	}

	function checkForStar(trackID) {
		for (var i = 0; i < json.length; ++i) {
			if (json[i].trackID == trackID && $('#star'+i).attr('src') == "images/star_filled.png") {
				$('#star'+i).attr('src', 'images/star_empty.png');
			}
		}
	}

	function generatePlaylist() {
		if (keyTracks.length == 0) {
			alert('At least one key track must be selected to generate a new playlist.');
		} else {
			// Set form values to interactive values
			$('#songID0').attr('value', keyTracks[0].trackID);
			for (var i = 1; i < keyTracks.length; ++i) {
				$('#songID'+i).attr('value', keyTracks[i].trackID);
			}

			$('#form-acoustic').attr('value', $('#acoustic-value').html() );
			$('#form-dance').attr('value', $('#dance-value').html() );
			$('#form-energy').attr('value', $('#energy-value').html() );
			$('#form-instrument').attr('value', $('#instrument-value').html() );
			$('#form-liveness').attr('value', $('#liveness-value').html() );
			$('#form-loudness').attr('value', $('#loudness-value').html() );
			$('#form-speech').attr('value', $('#speech-value').html() );
			$('#form-tempo').attr('value', $('#tempo-value').html() );
			$('#form-valence').attr('value', $('#valence-value').html() );
			// Submit
			$('#hidden-form').submit();
		}
	}

</script>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

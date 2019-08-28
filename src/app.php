<?php 

	require '../vendor/autoload.php';
	require_once "pdo.php";
	require_once "session.php";

	include 'func.php';

	session_start();

	// Check if recommendation form has been submitted (POST/Redirect/GET pattern)
	if (isset($_POST['songID0'])) {
		// 	Take POST variables and set them to SESSION array 
		// Set first ID and any others that are present
		$_SESSION['id0'] = $_POST['songID0'];
		$_SESSION['numIDs'] = 1;
		for ($i = 1; $i < 5; ++$i) {
			if (isset($_POST['songID'.$i]) && $_POST['songID'.$i] != '') {
				$_SESSION['id'.$i] = $_POST['songID'.$i];
				++$_SESSION['numIDs'];
			}
		}
		// Convert from percentage to float for API request to Recommendations 
		$_SESSION['acoustic'] = ($_POST['acoustic'] / 100);
		$_SESSION['dance'] = ($_POST['dance'] / 100);
		$_SESSION['energy'] = ($_POST['energy'] / 100);
		$_SESSION['instrument'] = ($_POST['instrument'] / 100);
		$_SESSION['tempo'] = $_POST['tempo'];
		$_SESSION['valence'] = ($_POST['valence'] / 100);
	
		// Redirect to recommendations page 
		header("Location: recommendations.php");
		return;
	}

	// Unset any previous IDs 
	for ($i = 0; $i < 5; ++$i) {
		if (isset( $_SESSION['id'.$i] )) {
			unset( $_SESSION['id'.$i] );
		}
	}

	// Login to API
	require_once "apiLogin.php";

	// Get user playlists
	$playlists = getPlaylists($api, 50, 0);
	$numPlaylists = count($playlists);

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
<link href="../css/styles.css" rel="stylesheet">

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

<!-- Navbar for mobile playlist tuning -->
<!-- NEW -->
<nav class="navbar navbar-light bg-light" id="mainnav">
	<div class="container nav-head">	
		<a class="navbar-brand nav-scroll" href="#" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			Tune Your Playlist
		</a>
		<button class="navbar-toggler nav-scroll" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="navbarNavDropdown">
		<div class="container playlist-tuner">
			<div class="row">
				<div class="col-lg-6 key-tracks">
					<h5>Key Tracks</h5>
					<div class="key-tracks">
						<div class="row track">
							<div class="col-3 icon-wrapper">
			            		<img class="track-icon" id="track-icon0" src="../images/placeholder.jpg" data-filled="0">
			            	</div>
							<div class="col-9 track-detail">
								<div class="track-text" id="track-text0">
									<strong class="track-title detail">Select a key track below.</strong>
									<p class="track-artist detail"></p>
								</div>
								<div class="redX" id="redX0">
									<img class="icon-x" id="icon0" src="../images/redX.png">
								</div>
							</div>
						</div>
						<div class="row track">
							<div class="col-3 icon-wrapper">
			            		<img class="track-icon" id="track-icon1" src="../images/placeholder.jpg" data-filled="0">
			            	</div>
							<div class="col-9 track-detail">
								<div class="track-text" id="track-text1">
									<strong class="track-title detail">Select a key track below.</strong>
									<p class="track-artist detail"></p>
								</div>
								<div class="redX" id="redX1">
									<img class="icon-x" id="icon1" src="../images/redX.png">
								</div>
							</div>
						</div>
						<div class="row track">
							<div class="col-3 icon-wrapper">
			            		<img class="track-icon" id="track-icon2" src="../images/placeholder.jpg" data-filled="0">
			            	</div>
							<div class="col-9 track-detail">
								<div class="track-text" id="track-text2">
									<strong class="track-title detail">Select a key track below.</strong>
									<p class="track-artist detail"></p>
								</div>
								<div class="redX" id="redX2">
									<img class="icon-x" id="icon2" src="../images/redX.png">
								</div>
							</div>
						</div>
						<div class="row track">
							<div class="col-3 icon-wrapper">
			            		<img class="track-icon" id="track-icon3" src="../images/placeholder.jpg" data-filled="0">
			            	</div>
							<div class="col-9 track-detail">
								<div class="track-text" id="track-text3">
									<strong class="track-title detail">Select a key track below.</strong>
									<p class="track-artist detail"></p>
								</div>
								<div class="redX" id="redX3">
									<img class="icon-x" id="icon3" src="../images/redX.png">
								</div>
							</div>
						</div>
						<div class="row track">
							<div class="col-3 icon-wrapper">
			            		<img class="track-icon" id="track-icon4" src="../images/placeholder.jpg" data-filled="0">
			            	</div>
							<div class="col-9 track-detail">
								<div class="track-text" id="track-text4">
									<strong class="track-title detail">Select a key track below.</strong>
									<p class="track-artist detail"></p>
								</div>
								<div class="redX" id="redX4">
									<img class="icon-x" id="icon4" src="../images/redX.png">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 mb-5">
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
						<input type="hidden" id="form-tempo" name="tempo" value="">
						<input type="hidden" id="form-valence" name="valence" value="">
					</form>
				</div>
			</div>
		</div>
	</div>
</nav>
<!-- Navbar for mobile playlist tuning -->

</header>

<!-- Page Content -->
<div class="container primary content">

<?php
// Flash success mesage if playlist was just created 
if ( isset($_SESSION['success']) ) {
	echo '<div class="alert alert-success" role="alert"> 
	  	Your playlist was successfully added to your Spotify! 
	  	<a href="https://open.spotify.com/playlist/'.$_SESSION['success'].'" 
	  		target="_blank" class="alert-link">Check it out!</a>
		</div>';
	unset($_SESSION['success']);
}
?>

<div class="row content-inside">

		<!-- Selector (left) -->
		<div class="col-lg-4 mb-5">
		<div class="card h-100">
  			<div class="card-body" id>
    			<!-- Playlists category -->
				<div class="playlists-button">
	    			<a id="playlists" data-toggle="collapse" data-target="#collapse1"><h5>Playlists</h5></a>
    			</div>
    			<div id="collapse1" class="collapse">
  	  				<ul class="list-group">
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
				    <a data-toggle="collapse" data-target="#collapse2"><h5>Top Artists -- Coming Soon!</h5></a>
				</div>
				<div id="collapse2" class="collapse">
					<!--<ul class="list-group">
						<li class="list-group-item">Tyler, The Creator</li>
						<li class="list-group-item">BROCKHAMPTON</li>
						<li class="list-group-item">MF DOOM</li>
					</ul>-->
				</div>

				<!-- Top Songs -->
				<div class="playlists-button">
				    <a data-toggle="collapse" data-target="#collapse3"><h5>Top Songs -- Coming Soon!</h5></a>
				</div>
				<div id="collapse3" class="collapse">
					<!--<ul class="list-group">
						<li class="list-group-item">NEW MAGIC WAND</li>
						<li class="list-group-item">STUPID</li>
						<li class="list-group-item">BLEACH</li>
						<li class="list-group-item">Crime Pays</li>
						<li class="list-group-item">Meat Grinder</li>
					</ul>-->
				</div>
		
				<!-- Recently Played Songs -->
	
  			</div>
		</div>
		</div>
		<!-- Selector (left) -->

		<!-- Viewer (right) -->
		<div class="col-lg-8 mb-5">
    	<div class="card h-100">
    		<div id="loading-overlay"></div>
      		<div class="card-body" id="center-card-body">
        		<h5 class="card-title" style="margin-bottom: 10px;">Current Selection</h5>
    			<table>
	  			<col width="60%">
	  			<col width="35%">
	  			<col width="5%">
	  
	  			<thead>
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
		<!-- Viewer (right) -->

</div>
<!-- /.row -->

</div>
<!-- /.container -->

</body>

<!-- Footer -->
<footer class="py-5 secondary">
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

	// AJAX for loading playlists and other songs lists
	
	// FUNCTION: Get list of tracks for a given playlist in JSON format 
	// Triggered when a playlist is clicked 
	function getTracks(playlistID) {
		/*if ($.active > 0) { 
			xmlhttp.abort();
		}*/
		$('#loading-overlay').css("display", "block");
		// Send request to get tracks 
		var trackRequest = new XMLHttpRequest();
		trackRequest.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				try {
					json = JSON.parse(this.responseText);
					handleResponse(json);
					updateFeatures(json);
				} catch (SyntaxError) {
					$('tbody').empty();
					$('tbody').append(
				    	'<tr><td></td><td style="color: red;">Playlist request timed out</td><td></td><td></td></tr>'
				    );
				    $('#loading-overlay').css("display", "none");
				    if ($(window).width() < 992 ) {
						$('.collapse').collapse('hide');
					}
				}
			}
		};
		trackRequest.open("GET", "getPlaylist.php?id=" + playlistID, true); // Returns json encoded object 
		trackRequest.send();
	}

	// FUNCTION: Take JSON-formatted track list and display it in the right column table 
	// Triggered in getTracks when response is received
	function handleResponse(json) {
		$('tbody').empty();
		numSongs = json.length;
		for (var i = 0; i < numSongs; i++) {
		    var track = json[i];

		    $('tbody').append(
		    	'<tr id="'+i+'"><td>'+track.track+'</td><td>'+track.artist+'</td> \
				  	<td><div class="star_container"> \
              		<img class="star" id="star'+i+'" src="../images/star_empty.png" onclick="toggleStar('+i+ 
              		')"></div></td></tr>' 
		    );
		}
		$('#loading-overlay').css("display", "none");
		if ($(window).width() < 992 ) {
			$('.collapse').collapse('hide');
		}
	}

	// FUNCTION: Take JSON-formatted track list, get audio features for each song, and add to running averages  
	// Triggered in getTracks after response is handled 
	function updateFeatures(json) {
		audioFeatureTotals = [0, 0, 0, 0, 0, 0];

		var completed = 0;
		// Send request for each song and add the features to the running totals
		for (var i = 0; i < numSongs; ++i) {
			var audioRequest = new XMLHttpRequest();
			audioRequest.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					++completed;
					audioFeatures = JSON.parse(this.responseText);
					audioFeatureTotals[0] += audioFeatures[0];
					audioFeatureTotals[1] += audioFeatures[1];
					audioFeatureTotals[2] += audioFeatures[2];
					audioFeatureTotals[3] += audioFeatures[3];
					audioFeatureTotals[4] += audioFeatures[4];
					audioFeatureTotals[5] += audioFeatures[5];

					// Set numerical values to their new values
					$('#acoustic-value').html( +((audioFeatureTotals[0] / completed) * 100).toFixed(6) );
					$('#dance-value').html( +((audioFeatureTotals[1] / completed) * 100).toFixed(6) );
					$('#energy-value').html( +((audioFeatureTotals[2] / completed) * 100).toFixed(6) );
					$('#instrument-value').html( +((audioFeatureTotals[3] / completed) * 100).toFixed(6) );
					$('#tempo-value').html( +(audioFeatureTotals[4] / completed).toFixed(6) );
					$('#valence-value').html( +((audioFeatureTotals[5] / completed) * 100).toFixed(6) );
					
					// Set sliders to their new values
					$('#acoustic').attr('value', $('#acoustic-value').html());
					$('#dance').attr('value', $('#dance-value').html());
					$('#energy').attr('value', $('#energy-value').html());
					$('#instrument').attr('value', $('#instrument-value').html());
					$('#tempo').attr('value', $('#tempo-value').html());
					$('#valence').attr('value', $('#valence-value').html());
				}
			};
			audioRequest.open("GET", "getSongFeatures.php?id=" + json[i].trackID, true); 
			audioRequest.send();
		}
	}

	// FUNCTION: Update text to show slider value for audio features 
	// Triggered when sliders are moved 
	function showVal(newVal, featureName) {
		var idName = '#' + featureName + '-value';
		// DEBUG: window.console && console.log('updating '+idName+' to '+newVal);
		$(idName).html(newVal);
	}
	
	// When document is ready: 
	$(document).ready(function(){
		
		// Expand playlist when page loads 
		setTimeout( function() {
			$('#playlists').click();
		}, 1000);

		// Add responsive top margin to red X's 
		$(window).resize(function() {
			if ( $(window).width() < 375 ) {
				$('.redX').css('margin-top', '3%');
			} else if ( $(window).width() < 475 ) {
				$('.redX').css('margin-top', '2%');
			} else if ( $(window).width() < 575 ) {
				$('.redX').css('margin-top', '1%');
			} else {
				$('.redX').css('margin-top', '0');
			}
		});

		// If navbar is collapsed and user scrolls past it, it is fixed to top of screen 
		var navpos = $('#mainnav').offset();
		$(window).bind('scroll', function() {
			if ($(window).scrollTop() > navpos.top && !$("#navbarNavDropdown").is(":visible") ) {
				$('#mainnav').addClass('fixed-top');
			} else {
				$('#mainnav').removeClass('fixed-top');
			}
		});
		
		// When navbar is collapsed and then clicked, it opens and the page automatically scrolls up to it (mainly for mobile use)
		$(".nav-scroll").click(function() {
			if ( !$("#navbarNavDropdown").is(":visible") )
				$('html,body').animate({
					scrollTop: $("#navbarNavDropdown").offset().top},
					'slow');
		});
	
		// When the red X on a key track is pressed, delete the track in the list and uncheck the star (if one exists on the page)
    		$('.icon-x').click(function() {
    			var index = $(this).attr('id').substring(4);
    			var ID = keyTracks[index].trackID;
    			deleteKeyTrack(ID);
    			checkForStar(ID);
    		});
	});

	// FUNCTION: Change the star's appearance and add the respective track to key tracks list
	// Triggered when a star is clicked 
	function toggleStar(songIndex) {
		// Check if there are already 5 key tracks 
    		if (numKeyTracks == 5 && $('#star'+songIndex).attr("src") == '../images/star_empty.png') {
			alert('Maximum 5 key tracks can be selected.');
		} else {
			// Toggle view of star (checked or unchecked) and add/delete key track 
			if ($('#star'+songIndex).attr("src") == '../images/star_empty.png') {
				$('#star'+songIndex).attr("src", "../images/star_filled.png");
				++numKeyTracks;
				// Call function to add song to key tracks 
				var track = json[songIndex];
				addKeyTrack(track, songIndex);
			} else {
				$('#star'+songIndex).attr("src", "../images/star_empty.png");
				// Call function to delete song from key tracks
				var track = json[songIndex];
				//console.log('track: '+);
				deleteKeyTrack(track.trackID);
			}
		}
  	}

	// FUNCTION: Add key track to key tracks list 
	// Triggered in multiple functions that manipulate key tracks list 
  	function addKeyTrack(track) {
		keyTracks.push(track);
		// Fetch track art link 
		var imageLink = track.image;
		$( '#track-icon'+(numKeyTracks-1) ).attr('src', imageLink);
		$( '#track-icon'+(numKeyTracks-1) ).attr('data-filled', '1');
		$( '#track-text'+(numKeyTracks-1) ).find('.track-title').html(track.track);
		$( '#track-text'+(numKeyTracks-1) ).find('.track-artist').html(track.artist);
	}

	// FUNCTION: Delete key track from key tracks list 
	// Triggered in multiple functions that manipulate key tracks list 
	function deleteKeyTrack(trackID) { 
		// Find index within keyTracks and erase it 
		var index = findKeyTrackIndex(trackID);
		if (index != -1) {
			--numKeyTracks;
			keyTracks.splice(index, 1);
			// Shift all icons to the right of the deleted index left by 1 
			var right = index + 1;
			while (right <= numKeyTracks) {
				$( '#track-icon'+ index ).attr('src', keyTracks[index].image );
				$( '#track-text'+ index ).find('.track-title').html( keyTracks[index].track );
				$( '#track-text'+ index ).find('.track-artist').html( keyTracks[index].artist );
				//$( '#track-icon'+ index ).attr('data-JSONIndex', $( '#track-icon'+ right ).attr('data-JSONIndex') );
				++index;
				++right;
			}
			// Change last entry to placeholder 
			$( '#track-icon'+ index ).attr('src', '../images/placeholder.jpg');
			$( '#track-icon'+ index ).attr('data-filled', '0');
			$( '#track-text'+ index ).find('.track-title').html( 'Select a key track below.' );
			$( '#track-text'+ index ).find('.track-artist').html('');
		} else {
			console.log('trackID not found!');
		}
	}

	// FUNCTION: Take track ID, search the key tracks list, and return the index of the track if it exists (-1 if it doesn't) 
	// Triggered in deleteKeyTrack() 
	function findKeyTrackIndex(trackID) {
		for (var i = 0; i < keyTracks.length; ++i) {
			if (keyTracks[i].trackID == trackID) {
				return i;
			}
		}
		return -1;
	}

	// FUNCTION: Checks if a track is listed in the right column and is checked. Uncheck it if so 
	// Triggered when a key track is deleted with the red X 
	function checkForStar(trackID) {
		for (var i = 0; i < json.length; ++i) {
			if (json[i].trackID == trackID && $('#star'+i).attr('src') == "../images/star_filled.png") {
				$('#star'+i).attr('src', '../images/star_empty.png');
			}
		}
	}

	// FUNCTION: Fill hidden form with song IDs and audio features, then submit the form
	// Triggered when the Generate button is pressed 
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


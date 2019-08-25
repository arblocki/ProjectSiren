<?php 

	// Get user playlists
	// Returns array with playlist objects 
	function getPlaylists ($api, $limit, $offset) {
		// Call the api method with the given parameters 
		$playlists = $api->getMyPlaylists([
			'limit' => $limit,
			'offset' => $offset 
		]);

		return $playlists->items;
	}


	// Generates playlist description from key tracks and audio features 
	function getDescription ($api) {

		// Add first key track 
		$description = $api->getTrack( $_SESSION['id0'] )->name.' by '.$api->getTrack( $_SESSION['id0'] )->artists[0]->name;

		// Add any additional key tracks to description 
		for ($i = 1; $i < $_SESSION['numIDs']; ++$i) {
			$track = $api->getTrack( $_SESSION['id'.$i] );
			$description = $description.', '.$track->name.' by '.
							$track->artists[0]->name;
		}

		// Add audio features to description
		$description = $description.', Acousticness: '.( floatval($_SESSION['acoustic']) * 100).'%';
		$description = $description.', Danceability: '.( floatval($_SESSION['dance']) * 100).'%';
		$description = $description.', Energy: '.( floatval($_SESSION['energy']) * 100).'%';
		$description = $description.', Instrumentalness: '.( floatval($_SESSION['instrument']) * 100).'%';
		$description = $description.', Tempo: '.( $_SESSION['tempo']).'BPM';
		$description = $description.', Valence: '.( floatval($_SESSION['valence']) * 100).'%';

		return $description;
	}

	// Inserts a record of the generated playlist into the playlist table
	function insertPlaylist ($pdo) {
		switch ( $_SESSION['numIDs'] ) {
			case 1:
				$stmt = $pdo->prepare('INSERT INTO playlist (user_id, creation_time, keyID0, acoustic, 
					dance, energy, instrument, tempo, valence) 
					VALUES ( :uid, :plTime, :id0, :acou, :dnce, :nrg, :inst, :tmpo, :val )');
				$stmt->execute(array(
					':uid' => $_SESSION['id'],
					':plTime' => gmdate('Y-m-d H:i:s'),
					':id0' => $_SESSION['id0'],
					':acou' => $_SESSION['acoustic'],
					':dnce' => $_SESSION['dance'],
					':nrg' => $_SESSION['energy'],
					':inst' => $_SESSION['instrument'],
					':tmpo' => $_SESSION['tempo'],
					':val' => $_SESSION['valence'])
				);
				break;
			case 2: 
				$stmt = $pdo->prepare('INSERT INTO playlist (user_id, creation_time, keyID0, keyID1, 
					acoustic, dance, energy, instrument, tempo, valence) 
					VALUES ( :uid, :plTime, :id0, :id1, :acou, :dnce, :nrg, :inst, :tmpo, :val )');
				$stmt->execute(array(
					':uid' => $_SESSION['id'],
					':plTime' => gmdate('Y-m-d H:i:s'),
					':id0' => $_SESSION['id0'],
					':id1' => $_SESSION['id1'],
					':acou' => $_SESSION['acoustic'],
					':dnce' => $_SESSION['dance'],
					':nrg' => $_SESSION['energy'],
					':inst' => $_SESSION['instrument'],
					':tmpo' => $_SESSION['tempo'],
					':val' => $_SESSION['valence'])
				);
				break;
			case 3: 
				$stmt = $pdo->prepare('INSERT INTO playlist (user_id, creation_time, keyID0, keyID1, 
					keyID2, acoustic, dance, energy, instrument, tempo, valence) 
					VALUES ( :uid, :plTime, :id0, :id1, :id2, :acou, :dnce, :nrg, :inst, :tmpo, :val )');
				$stmt->execute(array(
					':uid' => $_SESSION['id'],
					':plTime' => gmdate('Y-m-d H:i:s'),
					':id0' => $_SESSION['id0'],
					':id1' => $_SESSION['id1'],
					':id2' => $_SESSION['id2'],
					':acou' => $_SESSION['acoustic'],
					':dnce' => $_SESSION['dance'],
					':nrg' => $_SESSION['energy'],
					':inst' => $_SESSION['instrument'],
					':tmpo' => $_SESSION['tempo'],
					':val' => $_SESSION['valence'])
				);
				break;
			case 4: 
				$stmt = $pdo->prepare('INSERT INTO playlist (user_id, creation_time, keyID0, keyID1, 
					keyID2, keyID3, acoustic, dance, energy, instrument, tempo, valence) 
					VALUES ( :uid, :plTime, :id0, :id1, :id2, :id3, :acou, :dnce, :nrg, :inst, :tmpo, :val )');
				$stmt->execute(array(
					':uid' => $_SESSION['id'],
					':plTime' => gmdate('Y-m-d H:i:s'),
					':id0' => $_SESSION['id0'],
					':id1' => $_SESSION['id1'],
					':id2' => $_SESSION['id2'],
					':id3' => $_SESSION['id3'],
					':acou' => $_SESSION['acoustic'],
					':dnce' => $_SESSION['dance'],
					':nrg' => $_SESSION['energy'],
					':inst' => $_SESSION['instrument'],
					':tmpo' => $_SESSION['tempo'],
					':val' => $_SESSION['valence'])
				);
				break;
			case 5: 
				$stmt = $pdo->prepare('INSERT INTO playlist (user_id, creation_time, keyID0, keyID1, 
					keyID2, keyID3, keyID4, acoustic, dance, energy, instrument, tempo, valence) 
					VALUES ( :uid, :plTime, :id0, :id1, :id2, :id3, :id4, :acou, :dnce, :nrg, :inst, :tmpo, :val )');
				$stmt->execute(array(
					':uid' => $_SESSION['id'],
					':plTime' => gmdate('Y-m-d H:i:s'),
					':id0' => $_SESSION['id0'],
					':id1' => $_SESSION['id1'],
					':id2' => $_SESSION['id2'],
					':id3' => $_SESSION['id3'],
					':id4' => $_SESSION['id4'],
					':acou' => $_SESSION['acoustic'],
					':dnce' => $_SESSION['dance'],
					':nrg' => $_SESSION['energy'],
					':inst' => $_SESSION['instrument'],
					':tmpo' => $_SESSION['tempo'],
					':val' => $_SESSION['valence'])
				);
				break;
		}
	}
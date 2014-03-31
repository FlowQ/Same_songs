<?php
	include_once('config/config_dev.php');
	$id = "b45b1aa10f1ac2941910a7f0d10f8e28";
	$offset = 0;
	$limit = 5;
	$track_id = 45719017;
	$tour = 1;

	if(isset($_GET['url']) && $_GET['url'] != '')
		$url = $_GET['url'];
	else {
		echo "shit 1";
		exit();
	}

	$req = json_decode(file_get_contents("http://api.soundcloud.com/resolve.json?url=" . $url . "&client_id=" . APP_ID));

	if(!isset($req->kind) || ($req->kind != "track")) {
		echo "This is not a playlist";
		exit();
	}

	echo "here";
	$track_id = $req->id;

	$result = array();
	while($offset < 200) {
		$url = "http://api.soundcloud.com/tracks/" . $track_id . "/playlists?app_version=feefe0ab&client_id=". APP_ID . "&limit=" . $limit . "&linked_partitioning=1&offset=". $offset;

		$page_xml = file_get_contents($url);
		$xml = simplexml_load_string($page_xml);
		$json = json_encode($xml);
		$playlists_array = json_decode($json);


		$variable = $playlists_array->playlist;
		foreach ($variable as $key => $value) {
			if($value->{'track-count'} == 1) {
				$result[] = $value->tracks->track->id;
				//viz($value->tracks->track->id);
			} else { 
				foreach ($value->tracks->track as $tr) {
					$result[] = $tr->id;
					//viz($tr->id);
				}
			}
			echo "<p>Playlist ".$tour.'</p>';
			$tour++;
		}
		echo "<p>While ". $offset .'</p>';
		$offset += $limit;
	}

	$counted = array_count_values($result);
	asort($counted);
	$rest = array_slice($counted, 1, 10, true);

	$final_list = array();
	foreach ($rest as $key => $value) {
		$final_list[] = $key;
	}

	echo json_encode($final_list);

	function viz($array) {
		echo "<pre>VIZ";
		var_export($array);
		echo "/VIZ</pre>";
	}

?>
<?php
	include_once('config/config_dev.php');
	$id = "b45b1aa10f1ac2941910a7f0d10f8e28";
	$offset = 0;
	$limit = 2;
	$track_id = 45719017;
	$tour = 1;

	$stop = false;
	while(!$stop) {
		$url = "http://api.soundcloud.com/tracks/" . $track_id . "/playlists?app_version=feefe0ab&client_id=". APP_ID . "&limit=" . $limit . "&linked_partitioning=1&offset=". $offset;

		$page_xml = file_get_contents($url);
		
		$xml = simplexml_load_string($page_xml);
		$json = json_encode($xml);
		$playlists_array = json_decode($json);
		$result = array();

		$variable = $playlists_array->playlist;
		foreach ($variable as $value) {

			foreach ($value->tracks->track as $tr) {
				$result[] = $tr->id;
			}
		}

		echo "<p>XXX tour = " . $tour++ . "</p>";
		$offset += $limit;
		//$stop = true;
	}

	$counted = array_count_values($result);
	asort($counted);
	print_r($counted);


	function viz($array) {
		echo "<pre>VIZ";
		var_export($array);
		echo "/VIZ</pre>";
	}

?>
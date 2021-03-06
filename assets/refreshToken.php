<?php

	require "../../../config/glancrConfig.php";

	$client_id = getConfigValue("spotify_client_id");
	$client_secret = getConfigValue("spotify_client_secret");
	$refresh_token = getConfigValue("spotify_refresh_token");

	$url = "https://accounts.spotify.com/api/token";

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url	,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "client_id=" . $client_id . "&client_secret=". $client_secret. "&grant_type=refresh_token&refresh_token=" . $refresh_token,
		CURLOPT_HTTPHEADER => array( "content-type: application/x-www-form-urlencoded" )
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$json = json_decode($response, true);

	$access_token = $json["access_token"];
	$expires_in = $json["expires_in"];
	$expires_at = time() + $expires_in;

	header("Content-Type: application/json");

	if (strlen($access_token) > 1 && $json["expires_in"] != null){
		setConfigValue("spotify_access_token", $access_token);
		setConfigValue("spotify_expires_at", $expires_at);
		echo '{ "success": true }';
	} else {
		echo $response;
	}

?>

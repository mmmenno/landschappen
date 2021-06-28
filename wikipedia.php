<?php

//print_r($gebied);

$wpname = str_replace("https://nl.wikipedia.org/wiki/", "", $gebied['wikipedia']);

$url = "https://nl.wikipedia.org/w/api.php?action=query&prop=extracts&exchars=700&explaintext&titles=" . $wpname . "&format=json";

$wpjson = file_get_contents($url);
$wpdata = json_decode($wpjson,true);

//print_r($wpname);

$wptext = "Geen Nederlandstalig Wikipedia artikel gevonden";
if(isset($wpdata['query']['pages'])){
foreach ($wpdata['query']['pages'] as $key => $value) {
	$wptext = $value['extract'];
}
}

$wptext = preg_replace("/===([^=]+)===/", "<h4>$1</h4>", $wptext);
$wptext = preg_replace("/==([^=]+)==/", "<h3>$1</h3>", $wptext);

$wptext .= '<p class="wikipedialink"><a href="' . $gebied['wikipedia'] . '">naar het Wikipedia artikel</a></p>';

?>
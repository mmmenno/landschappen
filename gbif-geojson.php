<?



$bounds = json_decode($_GET['bounds'],true);
$qid = $_GET['qid'];


//print_r($bounds);


$url = "https://api.gbif.org/v1/occurrence/search?has_coordinate=true&limit=9&";
$url .= "has_geospatial_issue=false&media_type=StillImage&geometry=";
$url .= "POLYGON((" . $bounds["_southWest"]['lng'] . "%20" . $bounds["_southWest"]['lat'] . ",";
$url .= $bounds["_northEast"]['lng'] . "%20" . $bounds["_southWest"]['lat'] . ",";
$url .= $bounds["_northEast"]['lng'] . "%20" . $bounds["_northEast"]['lat'] . ",";
$url .= $bounds["_southWest"]['lng'] . "%20" . $bounds["_northEast"]['lat'] . ",";
$url .= $bounds["_southWest"]['lng'] . "%20" . $bounds["_southWest"]['lat'] . "))";

$json = file_get_contents($url);

$data = json_decode($json,true);

//print_r($data['results'][0]);
//print_r($data['results'][1]);

//die;

$colprops = array("total_results"=>$data['count'], "offset"=>$data['offset'], "limit"=>$data['limit']);

$fc = array("type"=>"FeatureCollection", "properties"=>$colprops, "features"=>array());


foreach ($data['results'] as $key => $value) {
	$obs = array("type"=>"Feature");
	$obs['geometry'] = array(
		"type" => "Point",
		"coordinates" => array($value['decimalLongitude'],$value['decimalLatitude'])
	);
	$props = array(
		"datum" => date("d-m-Y", strtotime($value['eventDate'])),
		"foto" => $value['media'][0]['identifier'],
		"fotoattr" => $value['media'][0]['rightsHolder'] . ", " . $value['media'][0]['license'],
		"obsid" => $value['references'],
		"taxonname" => $value['acceptedScientificName'],
		"taxonwp" => $value['references']
	);
	$obs['properties'] = $props;
	$fc['features'][] = $obs;
}

$geojson = json_encode($fc);

file_put_contents(__DIR__ . "/data/" . $qid . '.geojson', $geojson);

header('Content-Type: application/json');
echo $geojson;


die;


?>
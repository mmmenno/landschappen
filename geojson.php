<?




$lat = 52.2565;
$lon = 5.1472;
$radius = 1;
$qid = "no-qid-provided";

if(isset($_GET['lat'])){
	$lat = $_GET['lat'];
}
if(isset($_GET['lon'])){
	$lon = $_GET['lon'];
}
if(isset($_GET['radius'])){
	$radius = $_GET['radius'];
}
if(isset($_GET['qid'])){
	$qid = $_GET['qid'];
}


if(file_exists(__DIR__ . "/data/" . $qid . ".geojson") || isset($_GET['uncache'])){
	$geojson = file_get_contents(__DIR__ . "/data/" . $qid . ".geojson");
	header('Content-Type: application/json');
	echo $geojson;
	die;
}

$url = "https://api.inaturalist.org/v1/observations?photos=true&lat=" . $lat . "&lng=" . $lon . "&radius=" . $radius . "&per_page=27&order=desc&order_by=created_at";

$json = file_get_contents($url);

$data = json_decode($json,true);

//print_r($data['results'][0]);

$colprops = array("total_results"=>$data['total_results'], "page"=>$data['page'], "per_page"=>$data['per_page']);

$fc = array("type"=>"FeatureCollection", "properties"=>$colprops, "features"=>array());


foreach ($data['results'] as $key => $value) {
	$obs = array("type"=>"Feature");
	//$obs['id'] = "http://resolver.clariah.org/hisgis/lp/geometry/" . $key;
	$obs['geometry'] = $value['geojson'];
	$props = array(
		"datum" => $value['observed_on'],
		"foto" => $value['photos'][0]['url'],
		"obsid" => $value['id']
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
<?php


$sparql = "
SELECT ?gebied ?gebiedLabel ?afb ?coords ?m2 ?klasse ?klasseLabel ?article ?werk ?werkLabel ?werkafb WHERE {
  VALUES ?gebied { wd:" . $qid . " }
  ?gebied wdt:P625 ?coords .  
  ?gebied wdt:P31 ?klasse .
  OPTIONAL{
    ?gebied p:P2046 ?opp .
    ?opp psn:P2046 ?oppnorm .
    ?oppnorm wikibase:quantityAmount ?m2 .
  }
  OPTIONAL{
    ?gebied wdt:P18 ?afb .
  }
  OPTIONAL{
    ?werk wdt:P180 ?gebied .
    ?werk wdt:P18 ?werkafb .
  }
  OPTIONAL{
    ?article schema:about ?gebied .
    ?article schema:isPartOf <https://nl.wikipedia.org/> .
  }
  SERVICE wikibase:label { bd:serviceParam wikibase:language \"nl,en\". }
}
";


$endpoint = 'https://query.wikidata.org/sparql';

$json = getSparqlResults($endpoint,$sparql);
$data = json_decode($json,true);

$gebied = array();
$gebied['werken'] = array();

foreach ($data['results']['bindings'] as $k => $v) {

	$gebied["wdid"] = $qid;
	$gebied["uri"] = $v['gebied']['value'];
	$gebied["label"] = $v['gebiedLabel']['value'];
	$gebied['wikipedia'] =$v['article']['value'];
	$gebied['coords'] = wkt2geojson(strtoupper($v['coords']['value']));

  if(strlen($v['m2']['value'])){
    $gebied['oppervlakte'] = $v['m2']['value']/1000000;
  }

	if(strlen($v['klasseLabel']['value'])){
		$gebied['iseen'][] = $v['klasseLabel']['value'];
	}
	
	$gebied['afb'] = $v['afb']['value'];

  if(strlen($v['werk']['value'])){
    $gebied['werken'][$v['werk']['value']] = array(
      "werk" => $v['werk']['value'],
      "werklabel" => $v['werkLabel']['value'],
      "werkafb" => $v['werkafb']['value']
    );
  }

}





?>


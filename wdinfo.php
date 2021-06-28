<?php


$sparql = "
SELECT ?gebied ?gebiedLabel ?afb ?coords ?m2 ?klasse ?klasseLabel ?article WHERE {
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

foreach ($data['results']['bindings'] as $k => $v) {

	$gebied["wdid"] = $qid;
	$gebied["uri"] = $v['gebied']['value'];
	$gebied["label"] = $v['gebiedLabel']['value'];
	$gebied['wikipedia'] =$v['article']['value'];
	$gebied['coords'] = wkt2geojson(strtoupper($v['coords']['value']));
	$gebied['oppervlakte'] = $v['m2']['value']/1000000;

	if(strlen($v['klasseLabel']['value'])){
		$gebied['iseen'][] = $v['klasseLabel']['value'];
	}
	
	$gebied['afb'] = $v['afb']['value'];

}





?>


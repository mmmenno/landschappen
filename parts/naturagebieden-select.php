<?php

include("../functions.php");

$sparql = "
SELECT ?i ?iLabel WHERE {
  ?i wdt:P31 wd:Q15069452 .
  ?i wdt:P17 wd:Q55 .
  SERVICE wikibase:label { bd:serviceParam wikibase:language \"nl,en\". }
} 
ORDER BY ASC(?iLabel)
";


$endpoint = 'https://query.wikidata.org/sparql';

$json = getSparqlResults($endpoint,$sparql);
$data = json_decode($json,true);


echo "<option>kies een Natura-2000 gebied ...</option>";

foreach ($data['results']['bindings'] as $k => $v) {

	echo '<option value="' . str_replace("http://www.wikidata.org/entity/", "", $v['i']['value']) . '">' . $v['iLabel']['value'] . '</option>';
	echo "\n";

}



?>



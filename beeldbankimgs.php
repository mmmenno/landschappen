<?php


$bbimgs1 = array();
$bbimgs2 = array();
$i = 1;

if (($handle = fopen("beeldbankimgs.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    	if($data[0] == $qid){
    		$i++;
    		if($i%2 == 0){
        		$bbimgs1[] = $data;
    		}else{
        		$bbimgs2[] = $data;
    		}
    	}
    }
    fclose($handle);
}

?>
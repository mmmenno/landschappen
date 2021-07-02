<?php

if(!isset($_GET['qid'])){
  $qid = "Q2138052";
}else{
  $qid = $_GET['qid'];
}

include("functions.php");
include("wdinfo.php");
include("wikipedia.php");
include("beeldbankimgs.php");

$lat = $gebied['coords']['coordinates'][1];
$lon = $gebied['coords']['coordinates'][0];
if(!isset($gebied['oppervlakte'])){
  $zoomlevel = 14;
  $radius = 1;
}elseif($gebied['oppervlakte'] < 0.5){
  $zoomlevel = 17;
}elseif($gebied['oppervlakte'] < 1){
  $zoomlevel = 16;
}elseif($gebied['oppervlakte'] < 3){
  $zoomlevel = 15;
}elseif($gebied['oppervlakte'] < 10){
  $zoomlevel = 14;
}elseif($gebied['oppervlakte'] < 50){
  $zoomlevel = 13;
}elseif($gebied['oppervlakte'] < 100){
  $zoomlevel = 12;
}else{
  $zoomlevel = 11;
}
if(!isset($radius)){
  $radius = sqrt($gebied['oppervlakte'])/2;
}
/*
$geojsonurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$geojsonurl .= "://" . $_SERVER['HTTP_HOST'] . "/plekken/";
$geojsonurl .= "geojson.php?lat=" . $lat . "&lon=" . $lon . "&radius=" . $radius . "&qid=" . $qid;

echo $geojsonurl;
//die;

$json = file_get_contents($geojsonurl);
$jsondata = json_decode($json,true);
print_r($jsondata);
die;
*/

?><!DOCTYPE html>
<html>
<head>
  
<title>Het Landschap - <?= $gebied['label'] ?></title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

<!-- Leaflet -->
<!--
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet-src.js"></script>
-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>

  <!-- Proj4 and Proj4Leaflet -->
  <script src="https://unpkg.com/proj4@2.5.0/dist/proj4-src.js"></script>
  <script src="https://unpkg.com/proj4leaflet@1.0.1"></script>


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link rel="stylesheet" href="assets/css/styles.css" />
  
    </script>
    
</head>
<body class="abt-locations">

<div class="container-fluid">
  <div class="row">
    <div class="col-md">
      
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">

      <h1><?= $gebied['label'] ?></h1>

      <?= $wptext ?>

        <div id="wdimg">
          <?php 
          if(strlen($gebied['afb'])){
            echo '<img src="' . $gebied['afb'] . '?width=500" />';
          }
          ?>
      </div>

      <div id="works">
        <?php foreach ($gebied['werken']  as $k => $work) { ?>
          <a title="naar wikidata item" href="<?= $work['werk'] ?>" target="_blank"><img src="<?= $work['werkafb'] ?>?width=500"></a>
          <p class="onderschrift"><?= $work['werklabel'] ?></p>
        <?php } ?>
      </div>

    </div>
    <div class="col-md-4">
      
        
        <div id="map" style="height: 400px; margin-bottom: 24px; width: 98%;"></div>

        <div id="maptt" style="height: 400px; margin-bottom: 24px; width: 98%;"></div>

      
        <div class="bbimgs">
          <?php foreach ($bbimgs1 as $key => $bbimg) { ?>
            <a title="bekijk bij de instelling" href="<?= $bbimg[1] ?>" target="_blank"><img src="<?= $bbimg[2] ?>"></a>
          <?php } ?>
        </div>
      

    </div>
    <div class="col-md-4">

      <div id="fotos"></div>
      <div id="foto"></div>
      <div id="fotobeschrijving"></div>
        
      <div class="bbimgs">
          <?php foreach ($bbimgs2 as $key => $bbimg) { ?>
            <a title="bekijk bij de instelling" href="<?= $bbimg[1] ?>"><img src="<?= $bbimg[2] ?>"></a>
          <?php } ?>
        </div>
        
      
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    createMap();
    createTopoTijdReisMap();
    refreshMap();
  });

  function createMap(){
    center = [<?= $lat ?>, <?= $lon ?>];
    zoomlevel = <?= $zoomlevel ?>;

    map = L.map('map', {
          center: center,
          zoom: zoomlevel,
          minZoom: 1,
          maxZoom: 20,
          scrollWheelZoom: true,
          zoomControl: false
      });

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
  }

  function createTopoTijdReisMap(){
    <?php $year = 1910; ?>
    year = <?= $year ?>

    var RD = new L.Proj.CRS(
        'EPSG:28992',
        '+proj=sterea +lat_0=52.15616055555555 +lon_0=5.38763888888889 +k=0.9999079 +x_0=155000 +y_0=463000 +ellps=bessel +units=m +towgs84=565.2369,50.0087,465.658,-0.406857330322398,0.350732676542563,-1.8703473836068,4.0812 +no_defs', {
        origin: [-3.05155E7,3.1112399999999993E7],
        resolutions: [3251.206502413005,1625.6032512065026,812.8016256032513,406.40081280162565,203.20040640081282,101.60020320040641, 50.800101600203206,25.400050800101603,12.700025400050801,6.350012700025401,3.1750063500127004,1.5875031750063502,0.7937515875031751,0.39687579375158755,0.19843789687579377,0.09921894843789689,0.04960947421894844]
    });

    var topotijdreislayer = L.tileLayer('https://tiles.arcgis.com/tiles/nSZVuSZjHpEZZbRo/arcgis/rest/services/Historische_tijdreis_<?= $year ?>/MapServer/WMTS/tile/1.0.0/Historische_tijdreis_<?= $year ?>/default/default028mm/{z}/{y}/{x}',
    { WMTS: false, attribution: 'Kadaster (TopoTijdReis <?= $year ?>)' });

    maptt = L.map('maptt', {
        crs: RD,
        scrollWheelZoom: true,
        zoomControl: false,
        minZoom: 1,
        maxZoom: 11,
        layers: [topotijdreislayer]
    });
    L.control.zoom({
        position: 'bottomright'
    }).addTo(maptt);

    // we set the bounds/extent/zoom in refreshMap to be the same as the bounds of the lat lon map

    //map view still gets set with Latitude/Longitude,
    //BUT the zoomlevel is now different (it uses the resolutions defined in our projection tileset above)
    //maptt.setView(center, 11);
    // OR use RD coordinates (28992), and reproject it to LatLon (4326)
    //maptt.setView(RD.projection.unproject(center), 10);
  }

  function refreshMap(){

    // make sure the bounds of the maptt is (around) the same as the map (latlon) bounds
    maptt.fitBounds(map.getBounds());

    // attach the moveend and zoomend events so the maptt follows the map (NOT vice versa)
    map.on('moveend', function() {
      maptt.panTo(map.getCenter());
    });
    map.on('zoomend', function() {
      maptt.fitBounds(map.getBounds());
    });


    $.ajax({
      type: 'GET',
      url: 'geojson.php',
      data: { lat: <?= $lat ?>, lon: <?= $lon ?>, radius: <?= $radius ?>, qid: "<?= $qid ?>" },
      dataType: 'json',
      success: function(jsonData) {
        if (typeof sightings !== 'undefined') {
          map.removeLayer(sightings);
        }

        sightings = L.geoJson(null, {
          pointToLayer: function (feature, latlng) {                    
              return new L.CircleMarker(latlng, {
                  color: "#FC2211",
                  radius:8,
                  weight: 2,
                  opacity: 0.8,
                  fillOpacity: 0.3
              });
          },
          style: function(feature) {
            return {
                color: getColor(feature.properties),
                clickable: true
            };
          },
          onEachFeature: function(feature, layer) {
            showImages(feature);
            layer.on({
                click: whenClicked
              });
            }
        }).addTo(map);

        sightings.addData(jsonData).bringToFront();

        if(sightings.getLayers().length == 0){
          $('#fotobeschrijving').css("margin-bottom","0");
        }
        //map.fitBounds(sightings.getBounds());
      },
      error: function() {
          console.log('Error loading data');
      }

    });

    
  }

  function getColor(props) {

    if (typeof props['bend'] == 'undefined' || props['bend'] == null) {
      return '#950305';
    }
    return '#738AB7';
  }

  function whenClicked() {

    console.log('clicked');
  }

  function showImages(feature){
    //console.log(feature);
    var props = feature['properties'];
    var src = props['foto'];
    var photoid = props['obsid'];
    //console.log(src);
    var photo = $('<img>',{id: photoid, src: src});
    photo.click(function(){
      $('#foto').html('');
      var src = $(this).attr('src');
      src = src.replace('square','medium');
      var bigphoto = $('<img>',{src: src});
      $('#foto').append(bigphoto);

      var phototxt = '';
      if(props['taxonwp']!=null){
        phototxt = '<a href="' + props['taxonwp'] + '">' + props['taxonname'] + '</a> - ';
      }
      phototxt += props['fotoattr'];
      console.log(phototxt)
      $('#fotobeschrijving').html(phototxt);
    });
    $('#fotos').append(photo);
  }



</script>



</body>
</html>

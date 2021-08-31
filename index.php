<!DOCTYPE html>
<html>
<head>
  
<title>Landschappen</title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  
  <link rel="stylesheet" href="assets/css/styles.css" />

  
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-md">
      <h1>Weergaven van het Nederlandse landschap</h1>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <p>Op Wikidata worden veel Nederlandse landschappen beschreven: zo zijn er 129 Natura 2000-gebieden, 1400+ 'natuurlandschappen' en 500+ parken te vinden. In zo'n beschrijving zijn meestal coördinaten opgenomen en vaak ook een oppervlakte, zodat je een kaart op de juiste plek in kunt zoomen. Vaak is er ook een afbeelding (P18) aan gelinkt.</p>
    </div>
    <div class="col-md-3">
      <p>Zowel op Wikidata als op Wikimedia Commons is het mogelijk (meer) afbeeldingen met een landschap te verbinden middels de 'beeldt af' (P180) property. Veel is dit nu nog niet gedaan, maar in de afgelopen jaren is een geweldige hoeveelheid afbeeldingen op Commons geplaatst en het leggen van zo'n verbinding is een fluitje van een cent.</p>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-4">

      
      <form id="natuurlandschappen" action="plek.php">
        <select name="qid">
          


        </select>
      </form>

    </div>
    <div class="col-md-4">

      
      <form id="naturagebieden" action="plek.php">
        <select name="qid">
          


        </select>
      </form>

    </div>
    <div class="col-md-4">

      
      <form id="parken" action="plek.php">
        <select name="qid">
          


        </select>
      </form>

    </div>
  </div>

  <div class="row">
    <div class="col-md-3">

      <h3><a href="plek.php?qid=Q2235193">Hekslootpolder</a></h3>

      <h3><a href="plek.php?qid=Q2292178">Naardermeer</a></h3>

      <h3><a href="plek.php?qid=Q9900">De Beemster</a></h3>

      <h3><a href="plek.php?qid=Q2052566">Dekkersduin</a></h3>

    </div>
    <div class="col-md-3">
      
        
      <h3><a href="plek.php?qid=Q2370412">Spanderswoud</a></h3>

      <h3><a href="plek.php?qid=Q2138052">Franse Kamp</a></h3>

      <h3><a href="plek.php?qid=Q2663130">Haagse Bos</a></h3>
        

    </div>
    <div class="col-md-3">

      
      <h3><a href="plek.php?qid=Q3183833">Eilandspolder</a></h3>

      <h3><a href="plek.php?qid=Q5590948">Wolfhezerheide</a></h3>

      <h3><a href="plek.php?qid=Q13742779">Kennemerduinen</a></h3>
        
      
    </div>
    <div class="col-md-3">

      
      <h3><a href="plek.php?qid=Q1123665">Kebun Raya Bogor</a></h3>

      <h3><a href="plek.php?qid=Q2923688">De Beer</a></h3>

      <h3><a href="plek.php?qid=Q2278595">Elswout</a></h3>
        
      
    </div>
  </div>
</div>

<script type="text/javascript">
  
  $("#natuurlandschappen select").load('parts/natuurlandschappen-select.php');
  $("#natuurlandschappen select").change(function() {
    $("#natuurlandschappen").submit();
  });

  $("#naturagebieden select").load('parts/naturagebieden-select.php');
  $("#naturagebieden select").change(function() {
    $("#naturagebieden").submit();
  });

  $("#parken select").load('parts/parken-select.php');
  $("#parken select").change(function() {
    $("#parken").submit();
  });

</script>

</body>
</html>

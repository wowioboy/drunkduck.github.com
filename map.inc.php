
<div id="map" style="width: 640px; height: 480px"></div>
<script type="text/javascript">
//<![CDATA[

// Create map
var map = new GMap(document.getElementById("map"));

// Add Controls
map.addControl(new GMapTypeControl());
map.addControl(new GLargeMapControl());
map.addControl(new GSmallZoomControl());

// Initial View
map.centerAndZoom(new GPoint(-59.0625, 57.70414723434193), 15);

// Listener for when moved.
GEvent.addListener(map, 'moveend', function() {

  var center = map.getCenterLatLng();
  var latLngStr = '(' + center.y + ', ' + center.x + ')';
  document.getElementById("message").innerHTML = latLngStr;
});

// Add 10 random markers in the map viewport using the default icon.
var bounds = map.getBoundsLatLng();
var width = bounds.maxX - bounds.minX;
var height = bounds.maxY - bounds.minY;

<?
$USER_DATA = array();
$PIN_DATA  = array();
$res = db_query("SELECT * FROM demographics");
while( $row = db_fetch_object($res) )
{
  $PIN_DATA[$row->zipcode] =  db_escape_string($row->zipcode);
}


$res = db_query("SELECT * FROM zipcode_data WHERE zipcode IN ('".implode("','", $PIN_DATA)."') ORDER BY RAND() LIMIT 200");
while( $row = db_fetch_object($res) )
{
  ?>
  var point = new GPoint(<?=$row->longitude?>,
              					 <?=$row->latitude?>);
  var marker = new GMarker(point);
  map.addOverlay(marker);
  <?
}
?>



//]]>
</script>
<div id='message'></div>
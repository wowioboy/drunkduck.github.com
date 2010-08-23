<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<h1 align="left">DuckCAM</h1>

<script type="text/javascript">
 var filename = 'duckcam.jpg';

 function refreshCam()
 {
   $('campic').src = filename + "?r=" + Math.random();
 }

 setInterval('refreshCam()', 15000);
</script>

<div class="gameContent">
  <img id="campic" src="duckcam.jpg?r=<?=dice(1,999999)?>">
  <br>
  <a href="<?=$_SERVER['PHP_SELF']?>" onClick="refreshCam(); return false;">Refresh</a> the page every so often for a more recent image.<br>The image will also automatically refresh every 15 seconds.

  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>

</div>
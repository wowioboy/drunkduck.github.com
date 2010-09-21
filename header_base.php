<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL ^ E_NOTICE);

require_once('includes/db.class.php');

$db = new DB();
if ($showFeatured) {
 ob_start(); 
$query = "select c.comic_name as title 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'
order by feature_id desc 
limit 24";
$featured = $db->fetchCol($query);
?>
<div class="span-96" style="display:block;height:10px;"></div>
<div class="span-96 ">
    <div style="float:left;">
      <button id="prev_button">prev</button>
    </div>
    <div id="slideshow" style="float:left;">
      <?php foreach ($featured as $i => $comic) : ?>
        <?php 
          $i++;
          $path = '/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
        ?>
        <?php if ($i % 8 == 1) : ?>
          <div <?php echo ($i != 1) ? 'style="display:none;"' : ''; ?>>
        <?php endif; ?>  
        <img src="<?php echo $path; ?>" width="100" />
        <?php if ($i % 8 == 0) : ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div style="float:left;">
      <button id="next_button">next</button>
    </div>
</div>    
<?php
$output = ob_get_contents();
ob_end_clean();
}
?>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Drunk Duck</title>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet'>
    <link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/gutterless.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">    
    <!--[if IE]><link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

    <link rel="stylesheet" href="css/custom.css" type="text/css" media="screen, projection, print">
<link href='/css/layout.css' rel='stylesheet'>
<link href='/css/global.css' rel='stylesheet'>
<!--[if lt IE 8]>
  <link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection">
<![endif]-->
<script src="/js/jquery/jquery-1.4.2.min.js"></script>
<script src="/js/jquery/cycle/jquery.cycle.all.js"></script>
<script type="text/javascript">
$.ready(function(){
    $('#slideshow').cycle({ 
        fx:      'scrollHorz', 
        timeout: 0 
    });
    $('#next_button').click(function(){
        $('#slideshow').cycle('next');
    });
    $('#prev_button').click(function(){
        $('#slideshow').cycle('prev');
    });
    
});
</script>
<style type="text/css">
#slideshow img {
  float:right;
  clear:none;
  padding: 0 2px 0 2px;
}
</style>
</head>
<body>
<div id="backdrop"></div>

<div class="container">
    <div class="span-96 green rounded">
        <?php echo $output; ?>
        <div class="span-23" style="height:160px">
            
                <div style="width:268px;height:187px;background-image:url('drunkduck-logo.png');position:relative;left:-40px;top:-20px"></div>
            
        </div>
        <div class="span-73 border-1 rounded green" style="position:relative;height:110px;display:block;">
            <div class="rounded" style="position:absolute;top:5px;right:-7px;border:10px solid rgb(174,230,1);background-color:#FFF;height:90px;width:728px;">banner</div>
        </div>

        <?php require('navi_v2.php'); ?>
    </div>
    
    <div class="span-96">&nbsp;</div>
    
    <div id="main-content" class="main-content span-64 canary">
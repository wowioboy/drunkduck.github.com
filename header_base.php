<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once('includes/db.class.php');

$db = new DB();
$query = "select c.comic_name as title 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'
order by feature_id desc 
limit 24";
$featured = $db->fetchCol($query);

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
$(document).ready(function(){
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
    $('#slideshow').css('width') = '900px';
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
        <div class="span-23" style="height:160px">
            
                <div style="width:268px;height:187px;background-image:url('DD-logo-for-main.png');position:relative;left:-40px;top:-20px"></div>
            
        </div>
        <div class="span-73 border-1 rounded green" style="position:relative;height:110px;display:block;">
            <div class="rounded" style="position:absolute;top:5px;right:-7px;border:10px solid rgb(174,230,1);background-color:#FFF;height:90px;width:728px;">banner</div>
        </div>

        <?php require('navi_v2.php'); ?>
    </div>
    
    <div class="span-96">&nbsp;</div>
    
    <div id="main-content" class="main-content span-64 canary">
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
<html>
<head>
<title>Drunk Duck</title>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet'>
<link href='css/global.css' rel='stylesheet'>
<link href='css/layout.css' rel='stylesheet'>
<link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">
<!--[if lt IE 8]>
  <link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection">
<![endif]-->
<script src="js/jquery/jquery-1.4.2.min.js"></script>
<script src="js/jquery/cycle/jquery.cycle.all.js"></script>
</head>
<body>
<div class="container showgrid">
  <div class="span-24 rounded green">
    <div style="display:inline-block;">
      <button id="prev_button">prev</button>
    </div>
    <div id="slideshow" style="display:inline-block;width:900px;">
      <?php foreach ($featured as $i => $comic) : ?>
        <?php 
          $i++;
          $path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
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
    <div style="display:inline-block;">
      <button id="next_button">next</button>
    </div>
    <div id="topBar" class="table fill">
      <div class="cell bottom" style="width:200px;">
        <input type="text" id="search" />
      </div>
      <div class="cell center bottom">
        <a href="#browse">browse</a>
      </div>
      <div class="cell center bottom">
        <a href="#create">create</a>
      </div>
      <div class="cell center bottom">
        <a href="#news">news</a>
      </div>
      <div class="cell center bottom">
        <a href="#tutorials">tutorials</a>
      </div>
      <div class="cell center bottom">
        <a href="#videos">videos</a>
      </div>
      <div class="cell center bottom">
        <a href="#forums">forums</a>
      </div>
      <div class="cell center bottom">
        <a href="#store">store</a>
      </div>
    </div>
  </div>
  <hr class="space" />
  <div class="span-24">
    <div id="main-content" class="span-18 canary main-content">
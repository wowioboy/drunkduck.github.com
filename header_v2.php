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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Drunk Duck</title>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet' />
<link href='/css/global.css' rel='stylesheet' />
<link href='/css/layout.css' rel='stylesheet' />
<script src="/js/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="/js/jquery/cycle/jquery.cycle.all.js" type="text/javascript"></script>
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
<div id="main" class="centered">
  <div id="header" class="rounded green">
    <div style="display:inline-block;">
      <button id="prev_button">prev</button>
    </div>
    <div id="slideshow" style="display:inline-block;width:900px;">
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
  <div class="table fill">
    <div id="content-left" class="cell pad top">
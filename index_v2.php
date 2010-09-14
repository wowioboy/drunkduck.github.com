<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once('includes/db.class.php');

$db = new DB();
$query = "select c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by visits desc 
limit 10";
$topTen = $db->fetchAll($query);
$query = "select c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by last_update desc 
limit 10";
$latestUpdates = $db->fetchAll($query);
$query = "select c.comic_name as title 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'
order by feature_id desc 
limit 24";
$featured = $db->fetchCol($query);
$query = "select c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
left join comic_pages p 
on p.comic_id = c.comic_id 
right join page_likes l 
on l.page_id = p.page_id 
where l.date between now() - interval 1 week and now()
group by title 
order by count(1) desc
limit 10";
$mostLiked = $db->fetchAll($query);
$query = "select b.title, u.username as author, b.body, from_unixtime(b.timestamp_date) as created_on
from admin_blog b 
left join users u 
on u.user_id = b.user_id 
order by created_on desc 
limit 2";
$news = $db->fetchAll($query);

?>
<html>
<head>
<title>Drunk Duck</title>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet'>
<script src="js/jquery/jquery-1.4.2.min.js"></script>
<script src="js/jquery/cycle/jquery.cycle.all.js"></script>
<style>
* {
	padding:0;
	margin:0;
}
body {
	font-family:Helvetica;
}
h1 {
	font-family:'Yanone Kaffeesatz';
	font-weight:bold;
	font-size:32px;
}
h2 {
	font-family:'Yanone Kaffeesatz';
	font-weight:bold;
	font-size:30px;
	color:rgb(69,180,185);
}
h4 {
	font-family:helvetica;
	font-size:12pt;
	color:rgb(0,133,118);
}
a {
	text-decoration:none;
	color: #000;
}
#main {
  width:960px;
}
#header {
	padding:10px;
	margin-bottom:10px;
}
.rounded {
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
/* TABLE STUFF */
.table {
	display:table;
}
.cell { 
	display:table-cell;
}

/* SPACING */
.fill {
	width:100%;
}
.pad {
	padding:10px;
}
.pad-5 {
	padding:5px;
}
.centered {
	position:relative;
	margin-left:auto;
	margin-right:auto;
}

/* ALIGNMENT */
.left {
	text-align:left;
}
.right {
	text-align:right;
}
.center {
	text-align:center;
}
.bottom {
	vertical-align:bottom;
}
.top {
	vertical-align:top;
}
.middle {
	vertical-align:middle;
}

/* PANEL STUFF */
.panel-header {
	-webkit-border-top-left-radius: 10px;
	-webkit-border-top-right-radius: 10px;
	-moz-border-radius-topleft: 10px;
	-moz-border-radius-topright: 10px;
	border-top-left-radius: 10px;	
	border-top-right-radius: 10px;
	padding:5px;
	font-family:helvetica;
	font-weight:bold;
	font-size:10pt;
	width:200px;
}

.panel-header-right {
	-webkit-border-top-left-radius: 10px;
	-webkit-border-top-right-radius: 10px;
	-moz-border-radius-topleft: 10px;
	-moz-border-radius-topright: 10px;
	border-top-left-radius: 10px;	
	border-top-right-radius: 10px;
	padding:5px;
	font-family:helvetica;
	font-weight:bold;
	font-size:10pt;
	width:200px;
	margin-left:auto;
}

.panel-body {
	-webkit-border-radius: 10px;
	-webkit-border-top-left-radius: 0px;
	-moz-border-radius: 10px;
	-moz-border-radius-topleft: 0px;
	border-radius: 10px;
	border-top-left-radius: 0px;	
	padding:10px;
}

.panel-body-right {
	-webkit-border-radius: 10px;
	-webkit-border-top-right-radius: 0px;
	-moz-border-radius: 10px;
	-moz-border-radius-topright: 0px;
	border-radius: 10px;
	border-top-right-radius: 0px;
	padding:10px;
}

.yellow {
	background-color:rgb(255,230,102);
}
.green {
	background-color:rgb(174,230,1);
}

.push-top {
	margin-top:10px;
}

.push-bottom { 
	margin-bottom:10px;
}

/* homepage related */
#topBar a {
	text-decoration:none;
	color:rgb(0,133,118);
	font-family:'Yanone Kaffeesatz';
	font-weight:bold;
	font-size:24px;
}
#content-left {
	background-color:rgb(255,240,170);
	width:610px;
	height:500px;
	-webkit-border-top-left-radius: 10px;
	-webkit-border-top-right-radius: 0px;
	-webkit-border-bottom-right-radius: 10px;
	-webkit-border-bottom-left-radius: 10px;
	-moz-border-radius-topleft: 10px;
	-moz-border-radius-topright: 0px;
	-moz-border-radius-bottomright: 10px;
	-moz-border-radius-bottomleft: 10px;
	border-top-left-radius: 10px;	
	border-top-right-radius: 0px;
	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
}
#loginbox {
	background-color:rgb(255,240,170);
	-webkit-border-top-left-radius: 0px;
	-webkit-border-top-right-radius: 10px;
	-webkit-border-bottom-right-radius: 10px;
	-webkit-border-bottom-left-radius: 0px;
	-moz-border-radius-topleft: 0px;
	-moz-border-radius-topright: 10px;
	-moz-border-radius-bottomright: 10px;
	-moz-border-radius-bottomleft: 0px;
	border-top-left-radius: 0px;
	border-top-right-radius: 10px;
	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 0px;
}
.drop-list {
	background-color:#fff;
	padding:5px;
	font-family:helvetica;
	font-weight:bold;
	color:rgb(69,180,185);
}
.post {
	padding:10px;
}
#slideshow img {
	float:right;
	clear:none;
	padding: 0 2px 0 2px;
}
</style>
</head>
<body>
<script>
$(document).ready(function(){
  $('.top-ten-image').live('mouseenter', function(){
  	var title = $(this).attr('title');
		var description = $(this).attr('description');
		var author = $(this).attr('author'); 
		var html = title + ' by ' + author + '<br />' + description;
		$('#top-ten-description').html(html).slideDown();
	});
	$('#top-ten-holder').mouseleave(function(){
		$('#top-ten-description').slideUp();
	});
	$('.most-liked-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = title + ' by ' + author + '<br />' + description;
    $('#most-liked-description').html(html).slideDown();
  });
  $('#most-liked-holder').mouseleave(function(){
    $('#most-liked-description').slideUp();
  });
  $('.latest-update-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = title + ' by ' + author + '<br />' + description;
    $('#latest-update-description').html(html).slideDown();
  });
  $('#latest-update-holder').mouseleave(function(){
    $('#latest-update-description').slideUp();
  });
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
<div id="main" class="centered">
  <div id="header" class="rounded green">
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
  <div class="table fill">
    <div id="content-left" class="cell pad top">
      <div>
        <div class="panel-header green">&raquo; Top Ten</div>
        <div class="panel-body green">
          <div id="top-ten-holder">
            <?php foreach ((array) $topTen as $comic) : ?>
              <?php 
      	        $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
              ?>
              <img class="top-ten-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" />
            <?php endforeach; ?>
          </div>
          <div id="top-ten-description" class="rounded pad-5" style="background-color:#fff;display:none;">asdfasdkfjasodfj</div>   
        </div>
      </div>
      <div class="push-top">
        <div class="panel-header green">&raquo; Most Liked of The Week</div>
        <div class="panel-body green">
          <div id="most-liked-holder">
            <?php foreach ((array) $mostLiked as $comic) : ?>
              <?php 
                $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
              ?>
              <img class="most-liked-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" />
            <?php endforeach; ?>
          </div>
          <div id="most-liked-description" class="rounded pad-5" style="background-color:#fff;display:none;">asdfasdkfjasodfj</div>
        </div>
      </div>
      <div class="push-top">
        <div class="panel-header green">&raquo; Latest Updates</div>
        <div class="panel-body green">
          <div id="latest-update-holder">
            <?php foreach ((array) $latestUpdates as $comic) : ?>
              <?php 
                $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
              ?>
              <img class="latest-update-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" />
            <?php endforeach; ?>
          </div>
          <div id="latest-update-description" class="rounded pad-5" style="background-color:#fff;display:none;">asdfasdkfjasodfj</div>
        </div>
      </div>
      <div class="push-top table fill">
        <div class="cell top" style="width:200px;padding-right:10px;">
          <div class="center">
        spotlight
          </div>
          <div class="rounded" style="height:400px;border:2px solid rgb(174,230,1);">
          </div>
        </div>
        <div class="cell top">
          <?php foreach ($news as $entry) : ?>
          
          <div class="post yellow push-bottom">
            <h1><?php echo $entry['title']; ?></h1>
            <h4>posted by <?php echo $entry['author']; ?></h4>
            <p><?php echo $entry['body']; ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="cell top">
      <div id="loginbox" class="pad">
        <div>
          <div class="panel-header-right yellow center">
            <a href="">log out</a> | <a href="">help</a>
          </div>
          <div class="panel-body-right yellow">
            <div class="table fill">
             <div class="cell">sdjfsjdfklsjd</div>
             <div class="cell">
             <h2>Hi, Yourname</h2>
             </div>
            </div>
            <div class="drop-list rounded push-top">
            &raquo; my favorites
            </div>
            <div class="drop-list rounded push-top">
             &raquo; my webcomics
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
  <div class="push-top rounded yellow" style="height:100px;">
  </div>
</div>
</body>
</html>
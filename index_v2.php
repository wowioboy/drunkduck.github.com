<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once('includes/db.class.php');

$db = new DB();
$latestUpdates = $db->fetchCol("select * from latest_updates");
$mostLiked = $db->fetchCol("select * from most_liked");
$topTen = $db->fetchCol("select * from top_ten");
$featured = $db->fetchCol("select * from featured limit 8");
?>
<html>
<head>
<title>Drunk Duck</title>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet'>
<!--<link rel="stylesheet" href="css/global.css" />-->
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
</style>
</head>
<body>
<div id="main" class="centered">
  <div id="header" class="rounded green">
    <div>
    <?php foreach ($featured as $comic) : ?>
      <?php 
      $path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
      ?>
      <img src="<?php echo $path; ?>" />
     <?php endforeach; ?>
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
          <div>
            <?php foreach ($topTen as $comic) : ?>
              <?php 
      	        $path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
              ?>
              <img src="<?php echo $path; ?>" width="54" />
            <?php endforeach; ?>
          </div>
          <div class="rounded pad-5" style="background-color:#fff;">asdfasdkfjasodfj</div>   
        </div>
      </div>
      <div class="push-top">
        <div class="panel-header green">&raquo; Most Liked of The Week</div>
        <div class="panel-body green">
        <?php foreach ($mostLiked as $comic) : ?>
          <?php 
      $path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
      ?>
      <img src="<?php echo $path; ?>" width="54" />
        <?php endforeach; ?>
        </div>
      </div>
      <div class="push-top">
        <div class="panel-header green">&raquo; Latest Updates</div>
        <div class="panel-body green">
        <?php foreach ($latestUpdates as $comic) : ?>
          <?php 
      $path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
      ?>
      <img src="<?php echo $path; ?>" width="54" />
        <?php endforeach; ?>
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
          <div class="post yellow">
            <h1>blog stuff 1</h1>
            <h4>posted by Author Name</h4>
          </div>
          <div class="post yellow push-top">
            <h1>blog stuff 2</h1>
            <h4>posted by Author Name</h4>
          </div>
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

ini_set('display_errors', 1); 
error_reporting(0);
unset($GLOBALS['loginError']);


// added to assure relative-absolute path translation works on most levels.
if (!defined('WWW_ROOT')) define('WWW_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(WWW_ROOT. '/includes/global.inc.php');
require_once('includes/db.class.php');
require_once('bbcode.php'); 


/*echo '<pre>'; var_dump($USER);*/

$db = new DB();
?>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Drunk Duck</title>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet' type="text/css">
<link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/gutterless.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">    
    <!--[if IE]><link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

    <link rel="stylesheet" href="css/custom.css" type="text/css" media="screen, projection, print">
    <link href="/css/jquery/start/jquery-ui-1.8.5.custom.css" type="text/css" rel="stylesheet" />
    <link href="/css/jquery/ui.selectmenu.css" type="text/css" rel="stylesheet" />
<link href='/css/layout.css' rel='stylesheet' type="text/css">
<link href='/css/global.css' rel='stylesheet' type="text/css">
<!--[if lt IE 8]>
  <link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection">
<![endif]-->
<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery/ui.selectmenu.js"></script>
<script type="text/javascript" src="/js/jquery/cycle/jquery.cycle.all.js"></script>
</head>
<body>
<div id="backdrop"></div>

<div class="container">
    <div class="span-96 green rounded">
        <?php if ($showFeatured) : ?>
        <?php require_once('featured_slideshow.php'); ?>
        <?php endif; ?>
        <div class="span-23" style="height:160px">
            
                <div style="width:268px;height:187px;background-image:url('/media/images/drunkduck-logo.png');position:relative;left:-40px;top:-20px;z-index:5;"></div>
            
        </div>
        <div class="span-73 border-1 rounded green" style="position:relative;height:110px;display:block;">
            <div class="rounded" style="position:absolute;top:5px;right:-7px;border:10px solid rgb(174,230,1);background-color:#FFF;height:90px;width:728px;">banner</div>
        </div>

        <?php require('navi_v2.php'); ?>
    </div>
    
    <div class="span-96">&nbsp;</div>
    
    <div id="main-content" class="main-content span-64 canary">
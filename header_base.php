<?php ob_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
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
<!-- BLUEPRINT -->
<link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="/css/blueprint/gutterless.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">    
<!--[if IE]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
<style>
body {    
    background-color:rgb(9,153,68);
    background-image:url('/media/images/bg-gradient-color3.jpg');
    background-repeat: repeat-x;
    background-position:center top;
    }
</style>
<!-- JQUERY -->
<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>

<!-- JQUERY UI (JQUERY) -->
<link href="/css/jquery/start/jquery-ui-1.8.5.custom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.5.custom.min.js"></script>

<!-- CYCLE (JQUERY) -->
<script type="text/javascript" src="/js/jquery/cycle/jquery.cycle.all.js"></script>

<!-- SELECT MENU (JQUERY, JQUERY UI) -->
<link href="/css/jquery/ui.selectmenu.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/js/jquery/ui.selectmenu.js"></script>

<!-- FANCY BOX (JQUERY) --> 
<link type="text/css" rel="stylesheet" href="/js/jquery/fancybox/jquery.fancybox-1.3.1.css" />
<script type="text/javascript" src="/js/jquery/fancybox/jquery.fancybox-1.3.1.pack.js"></script>

<!-- JQUERY FORM (JQUERY) -->
<script type="text/javascript" src="/js/jquery/form.js"></script>

<!-- GOOGLE FONT -->
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet' type="text/css">

<link rel="stylesheet" href="/css/custom.css" type="text/css" media="screen, projection, print">
<link href='/css/layout.css' rel='stylesheet' type="text/css">
<link href='/css/global.css' rel='stylesheet' type="text/css">
</head>
<body>
<div id="backdrop"></div>

<div class="container">
    <div class="span-96 green rounded" style="position:relative;padding-top:10px">
        <?php 
            if ($showFeatured) require_once('featured_slideshow.php'); 
        ?>
        <div class="span-23" style="height:150px">
            <a href="/">
                <div style="width:268px;height:187px;background-image:url('/media/images/drunkduck-logo.png');position:relative;left:-40px;top:-28px;z-index:5;"></div>
            </a>
            
        </div>
        <div class="span-73 border-1 rounded green" style="position:relative;height:100px;display:block;">
            <div class="rounded" style="position:absolute;top:0px;right:-7px;border:10px solid rgb(174,230,1);background-color:#FFF;height:90px;width:728px;">
            <?php include(WWW_ROOT.'/ads/ad_includes/comic_template/728x90_et.html'); ?>
            </div>
        </div>
        <div class="span-73" id="menu">
        <?php require('navi_v2.php'); ?>
        </div>
    </div>
    
    <div class="span-96" style="height:10px;"></div>
    
    <div id="main-content" class="main-content span-64 canary">
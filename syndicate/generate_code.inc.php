<?
include('syndication_func.inc.php');
?>
<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<h1 align="left">Syndication Code</h1>

<div class="gameContent">

<div align="center">Copy/Paste the following code into your website, and strips will start appearing there!</div>

<?

$key = create_client( $_POST['email'], $_POST['password'], $_POST['comic_id'], $_POST['url'] );

$url = 'http://syndicate.drunkduck.com/syndicate.js?key='.$key;

$embed = '<script type="text/javascript" src="http://syndicate.drunkduck.com/syndicate.js?key='.$key.'"> </script>';
?>

<textarea style="width:500px;height:200px;"><?=htmlentities($embed, ENT_QUOTES)?></textarea>

</div>
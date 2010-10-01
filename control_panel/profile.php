<?php require_once('../header_base.php'); ?>
<?php require_once('../includes/user_maintenance/trophies/trophy_data.inc.php'); ?>

<?php
$USER = new stdClass();
$USER->user_id = '19085';
$USER->username = 'pscomics';
$USER->signed_up = '2009-12-31';
$USER->about_self = 'hey this is about me. whats up everybody?';
$USER->trophy_string = '1,2,3,4,9,13,17,18,29,30,500,501';

$trophies = explode(',', $USER->trophy_string);

$joined = new DateTime($USER->signed_up);

$query = "select comic_name as title 
          from comics 
          where user_id = '{$USER->user_id}'";
$comics = $db->fetchCol($query);
$query = "select c.comic_name as title 
          from comics c 
          inner join comic_favs f 
          on f.comic_id = c.comic_id
          where f.user_id = '{$USER->user_id}' 
          and f.recommend = '1'";
$recommended = $db->fetchCol($query);
$query = "select comic_name as title 
          from comics 
          where secondary_author = '{$USER->user_id}'";
$assisted = $db->fetchCol($query);

$query = "select url, name, description 
          from publisher_links 
          where user_id = '{$USER->user_id}'";
$pubLinks = $db->fetchAll($query);
?>
<script type="text/javascript">
$(document).ready(function(){
});
</script>
<h2>Public Profile</h2>
<img src="http://images.drunkduck.com/process/user_<?php echo $USER->user_id; ?>.gif" />
<h2><?php echo $USER->username; ?></h2>
<h4>member since <?php echo $joined->format('F j, Y'); ?></h4>

<div style="height:10px;"></div>

<div>
PUBLISHER LINKS
<br />
<?php foreach ((array) $pubLinks as $link) : ?>
<a href="<?php echo $link['url']; ?>"><?php echo $link['name']; ?></a>
<br />
<?php echo $link['description']; ?>
<br />
<br />
<?php endforeach; ?>
</div>

<div style="height:10px;"></div>

<div>
TROPHIES
<br />
<?php foreach ((array) $trophies as $trophy) : ?>
<?php 
$path = "http://images.drunkduck.com/trophies/small/$trophy.png";
?>
<a title="<?php echo $TROPHIES[$trophy]['name']; ?>" href="http://www.drunkduck.com/trophies.php"><img src="<?php echo $path; ?>" /></a>
<?php endforeach; ?>
</div>

<div style="height:10px;"></div>

<div>HIGH SCORES</div>
<div>FORUM TOPICS</div>

<div style="height:10px;"></div>

<div>
COMICS CREATED
<br />
<?php foreach ((array) $comics as $comic) : ?>
<?php 
$path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
?>
<a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic); ?>"><img src="<?php echo $path; ?>" title="<?php echo $comic; ?>" width="105" height="131"/></a>
<?php endforeach; ?>
</div>

<div style="height:10px;"></div>

<div>
COMICS ASSISTED
<?php foreach ((array) $assisted as $comic) : ?>
<?php 
$path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
?>
<a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic); ?>"><img src="<?php echo $path; ?>" title="<?php echo $comic; ?>" width="105" height="131"/></a>
<?php endforeach; ?>
</div>

<div style="height:10px;"></div>

<div>
FRIENDS
<br />
</div>

<div style="height:10px;"></div>

<div>
COMICS RECOMMENDED
<br />
<?php foreach ((array) $recommended as $comic) : ?>
<?php 
$path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
?>
<a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic); ?>"><img src="<?php echo $path; ?>" title="<?php echo $comic; ?>" width="105" height="131"/></a>
<?php endforeach; ?>
</div>

<!-- <div style="height:10px;"></div>

<div>VIDEOS</div>-->

<div style="height:10px;"></div>

<div>COMMENTS LEFT</div>

<?php require_once('../footer_base.php'); ?>
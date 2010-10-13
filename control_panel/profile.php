<?php require_once('../header_base.php'); ?>
<?php require_once('../includes/user_maintenance/trophies/trophy_data.inc.php'); ?>

<?php
if (!$username = $_REQUEST['username']) {
      if (!$USER) {
        header('Location: /login.php');
        die('please log in to use this page!');
      }
  $username = $USER->username; 
}


  
$query = "select user_id, username, from_unixtime(signed_up) as signed_up, trophy_string, about_self as about, avatar_ext
          from users where username = '$username'";
$user = DB::getInstance()->fetchRow($query);

if ($user['user_id'] == $USER->user_id) {
  $profileOwner = true;
}

if ($comment = $_REQUEST['comment']) {
  $time = time();
  $query = "insert into profile_comments (user_id, poster_id, comment, approved, posted) values ('{$user['user_id']}', '{$USER->user_id}', '$comment', '1', '$time')";
  $db->query($query);
} 

$trophies = explode(',', $user['trophy_string']);

$joined = new DateTime($user['signed_up']);

$query = "select comic_id as id, comic_name as title 
          from comics 
          where user_id = '{$user['user_id']}'";
$comics = $db->fetchAll($query);
$query = "select c.comic_id as id, c.comic_name as title 
          from comics c 
          inner join comic_favs f 
          on f.comic_id = c.comic_id
          where f.user_id = '{$user['user_id']}' 
          and f.recommend = '1'";
$recommended = $db->fetchAll($query);
$query = "select comic_id as id, comic_name as title 
          from comics 
          where secondary_author = '{$user['user_id']}'";
$assisted = $db->fetchAll($query);

$query = "select url, name, description 
          from publisher_links 
          where user_id = '{$user['user_id']}'";
$pubLinks = $db->fetchAll($query);

$query = "select u.user_id, u.username, avatar_ext
          from users u 
          inner join friends f 
          on f.friend_id = u.user_id 
          where f.user_id = '{$user['user_id']}'";  
$friends = $db->fetchAll($query);

$query = "select u.username, concat('http://images.drunkduck.com/process/user_', u.user_id, '.', u.avatar_ext) as avatar, c.comment, from_unixtime(c.posted) as created_at
          from profile_comments c 
          inner join users u 
          on u.user_id = c.poster_id
          where c.user_id = '{$user['user_id']}' 
          and c.approved = '1' 
          order by posted desc";
$comments = DB::getInstance()->fetchAll($query);


$communityDb = new DB(array('scheme' => 'drunkduck_community'));
$query = "select topic_name as name, topic_id as tid, category_id as cid
          from community_topics 
          where user_id = '{$user['user_id']}'
          order by date_created desc";
$topics = $communityDb->fetchAll($query);

$gamesDb = new DB(array('scheme' => 'drunkduck_games'));
$query = "select g.game_id as id, g.title, h.highscore as score 
          from game_info g 
          inner join user_highscores h 
          on h.game_id = g.game_id 
          where h.username = '{$user['username']}' 
          order by h.highscore desc";
$scores = $gamesDb->fetchAll($query);
?>
<?php if ($profileOwner) : ?>
<script type="text/javascript">
$(document).ready(function(){
  $('#boomgong').htmlarea();
  $('#edit-profile-about-form').ajaxForm({
    success: function() {
      alert('profile saved!');
    }
  });
});
</script>
<style>
.jHtmlArea {
  background-color:#fff;
}
</style>
<div class="rounded canary span-63 box-1 pull-1" style="clear:both;">
    <div class="span-63 dark-green rounded header">
    <img src="/media/images/control-panel.png" />
    </div>
</div>

<div class="span-64 box-1 header-menu">
<a class="teal rounded button" href="/control_panel/account.php">account</a>
<a class="teal rounded button" href="/control_panel/quacks.php">quacks</a>
<a class="teal rounded button" href="/control_panel/favorites.php">favorites</a>
</div>
<?php endif; ?>

<div class="box-2" style="padding-top:160px">
    <div class="box-2 yellow rounded" >
<div class="drunk" style="font-size:3em;">Public Profile</div>

<div class="span-16">
    <img src="http://drunkduck.com/gfx/avatars/avatar_<?php echo $user['user_id']; ?>.<?php echo $user['avatar_ext']; ?>" />
    <div class="drunk">
        <div><?php echo $user['username']; ?></div>
        <span style="font-size:0.8em;">member since <?php echo @$joined->format('F j, Y'); ?></span>
    </div>
</div>
<div class="span-40">
<div class="box-1">
<?php if ($profileOwner) : ?>
<form id="edit-profile-about-form" method="post" action="/ajax/control_panel/change-about.php">
<input type="hidden" name="user_id" value="<?php echo $USER->user_id; ?>" />
<div>
<textarea id="boomgong" name="about_self" style="background-color:#fff;"><?php echo bbcode2html($user['about']); ?></textarea>
</div>
<br />
<input class="teal rounded button" type="submit" value="save" />
</form>
<?php else: ?>
<?php echo bbcode2html($user['about']); ?>
<?php endif; ?>
</div>
</div>    

<div style="height:20px;"></div>

<div>
<div class="drunk">PUBLISHER LINKS</div>
<?php foreach ((array) $pubLinks as $link) : ?>
<a href="<?php echo $link['url']; ?>"><?php echo $link['name']; ?></a>
<br />
<?php echo $link['description']; ?>
<br />
<br />
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">TROPHIES</div>
<?php foreach ((array) $trophies as $trophy) : ?>
<?php 
$path = "http://images.drunkduck.com/trophies/small/$trophy.png";
?>
<a title="<?php echo $TROPHIES[$trophy]['name']; ?>" href="/trophies.php"><img src="<?php echo $path; ?>" /></a>
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">HIGH SCORES</div>
<?php foreach ((array) $scores as $score) : ?>
<div style="display:inline-block;">
<a title="<?php echo $score['title']; ?>" href="/games/play/<?php echo str_replace(' ', '_', $score['title']); ?>.php">
<img src="http://images.drunkduck.com/games/thumbnails/game_<?php echo $score['id']; ?>_tn_med.gif" width="125" height="85" />
</a>
<br />
<?php echo $score['score']; ?>
</div>
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">FORUM TOPICS</div>
<?php foreach ((array) $topics as $topic) : ?>
<a href="http://www.drunkduck.com/community/view_topic.php?cid=<?php echo $topic['cid']; ?>&tid=<?php echo $topic['tid']; ?>"><?php echo $topic['name']; ?></a>
<br />
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">COMICS CREATED</div>
<?php foreach ((array) $comics as $comic) : ?>
<?php 
$path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
?>
<a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img src="<?php echo $path; ?>" title="<?php echo $comic['title']; ?>" /></a>
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">COMICS ASSISTED</div>
<?php foreach ((array) $assisted as $comic) : ?>
<?php 
$path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
?>
<a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img src="<?php echo $path; ?>" title="<?php echo $comic['title']; ?>" /></a>
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">FRIENDS</div>
<?php foreach ((array) $friends as $friend) : ?>
<a href="http://user.drunkduck.com/<?php echo $friend['username']; ?>" title="<?php echo $friend['username']; ?>"><img src="http://images.drunkduck.com/process/user_<?php echo $friend['user_id']; ?>.<?php echo $friend['avatar_ext']; ?>" width="50" height="50" /></a>
<?php endforeach; ?>
</div>

<div style="height:20px;"></div>

<div>
<div class="drunk">COMICS RECOMMENDED</div>
<?php foreach ((array) $recommended as $comic) : ?>
<?php 
$path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
?>
<a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img src="<?php echo $path; ?>" title="<?php echo $comic['title']; ?>" /></a>
<?php endforeach; ?>
</div>

<!-- <div style="height:10px;"></div>

<div>VIDEOS</div>-->

<div style="height:20px;"></div>

<form id="leaveCommentForm" method="post">
<div class="drunk">LEAVE A COMMENT</div>
<textarea style="display:block;height:100px" name="comment" class="span-56"></textarea>
<div style="text-align:right;">
    <input type="submit" value="say it!" class="rounded button" />
</div>
</form>

<div style="height:20px;"></div>

<div>
COMMENTS LEFT
<br />
<?php foreach ((array) $comments as $comment) : ?>
<a href="http://user.drunkduck.com/<?php echo $comment['username']; ?>">
  <img src="<?php echo $comment['avatar']; ?>" width="50" height="50" />
  <?php echo $comment['username']; ?>
</a>
<br />
<?php echo $comment['comment']; ?>
<br />
<br />
<?php endforeach; ?>
</div>
    </div>
</div>
<?php require_once('../footer_base.php'); ?>
<?php require_once('../header_base.php'); ?>
<?php require_once('../bbcode.php'); ?>
<?php require_once('../includes/user_maintenance/trophies/trophy_data.inc.php'); ?>

<?php
if (!$username = $_GET['username']) {
  if (!$username = $USER->username) {
    header('Location: /login.php');
    die('please log in to use this page!');
  }
}
if (strtolower($username) == strtolower($USER->username)) {
  $owner = true;  
}

if ($edit = $_GET['edit']) {
  if (!$owner && !($USER->flags & 8)) {
    $edit = false;
  }
} else if ($owner || ($USER->flags & 8)) {
  $editable = true;
}

if ($_POST['add_video']) {
  $query = "insert into pool_movies 
            (user_id, url, width, height, title, description, popularity, movie_type) 
            values 
            ('{$USER->user_id}', '{$_POST['url']}', '425', '350', '{$_POST['title']}', '{$_POST['description']}', '1', 'video')";
   DB::getInstance()->query($query);
}
  
$query = "select user_id, username, from_unixtime(signed_up) as signed_up, trophy_string, about_self as about, avatar_ext
          from users where username = '$username'";
$user = DB::getInstance()->fetchRow($query);

if (!$user) {
  die('This user does not exist!');
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

$query = "select url, title, description 
          from pool_movies 
          where user_id = '{$user['user_id']}' 
          order by id desc 
          limit 10";
$videos = $db->fetchAll($query);

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
          where f.user_id = '{$user['user_id']}' 
          and order_id > 0";  
$friends = $db->fetchAll($query);

$query = "select u.username, u.avatar_ext, u.user_id, c.comment, from_unixtime(c.posted) as created_at
          from profile_comments c 
          inner join users u 
          on u.user_id = c.poster_id
          where c.user_id = '{$user['user_id']}' 
          and c.approved = '1' 
          order by posted desc 
          limit 10";
$comments = DB::getInstance()->fetchAll($query);


$communityDb = new DB(array('scheme' => 'drunkduck_community'));
$query = "select t.topic_name as name, t.topic_id as tid, t.category_id as cid, c.flags
          from community_topics t 
          inner join community_categories c 
          on t.category_id = c.category_id
          where user_id = '{$user['user_id']}'
          order by date_created desc 
          limit 10";
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
<script type="text/javascript">
$(document).ready(function(){
<?php if ($edit) : ?>
  $('#boomgong').htmlarea();
  $('#edit-profile-about-form').ajaxForm({
    success: function() {
      alert('profile saved!');
    }
  });
<?php endif; ?>
});
</script>
<?php if ($edit) : ?>
<style type="text/css">
.jHtmlArea {
  background-color:#fff;
}
</style>
<?php endif; ?>
<?php if ($owner) : ?>
<div class="rounded canary span-63 box-1 pull-1" style="height:100px;clear:both;">
  <div class="span-63 dark-green rounded header">
    <img src="/media/images/control-panel.png" />
  </div>
  <div class="span-61 box-1 header-menu">
    <a class="button nav" href="/control_panel/profile.php">public profile</a>
    <a class="button nav" href="/control_panel/account.php">account</a>
    <a class="button nav" href="/control_panel/favorites.php">favorites</a>
    <a class="button nav" href="/control_panel/quacks.php">personal quacks</a>
  </div>
</div>
<?php endif; ?>
<div class="box-2" style="clear:both;">
    <div class="box-2 yellow rounded" >
    <div class="drunk" style="font-size:3em;">Public Profile</div>

<div class="span-16">
    <img src="http://drunkduck.com/gfx/avatars/avatar_<?php echo $user['user_id']; ?>.<?php echo $user['avatar_ext']; ?>" />
<?php if ($editable) : ?>
    <br />
<a class="button" href="?username=<?php echo $username; ?>&edit=1" target="_blank">EDIT</a>
<?php endif; ?>
    <div class="drunk">
        <div><?php echo $user['username']; ?></div>
        <span style="font-size:0.8em;">member since <?php echo @$joined->format('F j, Y'); ?></span>
    </div>
</div>
<div class="span-40">
<div class="box-1">
<?php if ($edit) : ?>
<form id="edit-profile-about-form" method="post" action="/ajax/control_panel/change-about.php">
<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>" />
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

<div style="height:20px;clear:both;"></div>

<?php if ($pubLinks) : ?>
<div style="clear:both;">
<div><span class="drunk">PUBLISHER LINKS</span></div>
<?php foreach ((array) $pubLinks as $link) : ?>
<a href="<?php echo $link['url']; ?>"><?php echo $link['name']; ?></a>
<br />
<?php echo $link['description']; ?>
<br />
<br />
<?php endforeach; ?>
</div>
<div style="height:20px;"></div>
<?php endif; ?>

<?php if ($trophies) : ?>
<div style="clear:both;">
<div class="drunk">TROPHIES</div>
<?php foreach ((array) $trophies as $trophy) : ?>
<?php 
$path = "http://images.drunkduck.com/trophies/small/$trophy.png";
?>
<a title="<?php echo $TROPHIES[$trophy]['name']; ?>" href="/trophies.php"><img src="<?php echo $path; ?>" /></a>
<?php endforeach; ?>
</div>
<div style="height:20px;"></div>
<?php endif; ?>

<?php if ($scores) : ?>
<div style="clear:both;">
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
<?php endif; ?>

<?php if ($topics) : ?>
<div style="clear:both;">
<div class="drunk">FORUM TOPICS</div>
<?php foreach ((array) $topics as $topic) : ?>
<?php # mod only topic is 1 and admin only is 2 ?>
 <?php if (!($topic['flags'] & 1) && !($topic['flags'] & 2)) : ?>
<a href="/community/view_topic.php?cid=<?php echo $topic['cid']; ?>&tid=<?php echo $topic['tid']; ?>"><?php echo $topic['name']; ?></a>
<br />
<?php endif; ?>
<?php endforeach; ?>
</div>
<div style="height:20px;"></div>
<?php endif; ?>

<?php if ($comics) : ?>
<div style="clear:both;">
<div>
 <span class="drunk">COMICS CREATED</span>&nbsp;
 <?php if ($edit) : ?>
 <a href="/account/overview/" target="_blank" class="button">manage comics</a>
 <?php endif; ?>
 </div>
<?php foreach ((array) $comics as $comic) : ?>
<?php 
if ($USER->age < 18 && $comic['rating'] == 'A') {
            $path = "http://images.drunkduck.com/gfx/process/preset/censored_thumb.jpg";              
            } else {
            $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
            }
?>
<a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img src="<?php echo $path; ?>" title="<?php echo $comic['title']; ?>" /></a>
<?php endforeach; ?>
</div>
<div style="height:20px;"></div>
<?php endif; ?>

<?php if ($assisted) : ?>
<div style="clear:both;">
<div class="drunk">COMICS ASSISTED</div>
<?php foreach ((array) $assisted as $comic) : ?>
<?php 
if ($USER->age < 18 && $comic['rating'] == 'A') {
            $path = "http://images.drunkduck.com/gfx/process/preset/censored_thumb.jpg";              
            } else {
            $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
            }
?>
<a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img src="<?php echo $path; ?>" title="<?php echo $comic['title']; ?>" /></a>
<?php endforeach; ?>
</div>
<div style="height:20px;"></div>
<?php endif; ?>

<?php if ($friends) : ?>
  <div style="clear:both;">
  <div>
    <span class="drunk">FRIENDS</span>
    <a class="button" href="http://user.drunkduck.com/friends.php?u=<?php echo $username; ?>" target="_blank">see all friends</a>
    <?php if ($edit) : ?>
    <a class="button" href="http://user.drunkduck.com/edit_top_friends.php" target="_blank">manage friends</a>
    <?php endif; ?>
  </div>
    <div id="friends_holder">
      <?php foreach ((array) $friends as $i => $friend) : ?>
        <a href="/control_panel/profile.php?username=<?php echo $friend['username']; ?>" title="<?php echo $friend['username']; ?>"><img src="http://images.drunkduck.com/process/user_<?php echo $friend['user_id']; ?>.<?php echo $friend['avatar_ext']; ?>" width="50" height="50" /></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div style="height:20px;"></div>
<?php endif; ?>

<?php if ($recommended) : ?>
<div style="clear:both;">
<div>
<span class="drunk">COMICS RECOMMENDED</span>
<?php if ($edit) : ?>
<a class="button" target="_blank" href="http://user.drunkduck.com/edit_recommended_comics.php">manage recommends</a>
<?php endif; ?>
</div>
<?php foreach ((array) $recommended as $comic) : ?>
<?php 
if ($USER->age < 18 && $comic['rating'] == 'A') {
            $path = "http://images.drunkduck.com/gfx/process/preset/censored_thumb.jpg";              
            } else {
            $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
            }
?>
<a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img src="<?php echo $path; ?>" title="<?php echo $comic['title']; ?>" /></a>
<?php endforeach; ?>
</div>
<div style="height:20px;"></div>
<?php endif; ?>

  <div style="clear:both;">
  <div>
    <span class="drunk">VIDEOS</span>
    <a class="button" href="http://user.drunkduck.com/see_all_videos.php?u=<?php echo $username; ?>" target="_blank">see all</a>
    <?php if ($edit) : ?>
    <a class="button" href="javascript:" onclick="jQuery('#add-video-form').slideDown();">add video</a>
    <a class="button" href="http://user.drunkduck.com/see_all_videos.php" target="_blank">manage videos</a>
    <?php endif; ?>
  </div>
  <div id="add-video-form" style="display:none;">
    <form method="post">
    title:
    <br />
    <input class="quack-input rounded span-55" type="text" name="title" />
    <br />
    <br />
    url:
    <br />
    <input class="quack-input rounded span-55" type="text" name="url" />
    <br />
    <br />
    description:
    <br />
    <textarea class="quack-input rounded span-55" name="description">
    </textarea>
    <br />
    <br />
    <input class="button" type="submit" value="save" name="add_video" /> <input type="button" class="button" onclick="jQuery('#add-video-form').slideUp();" value="cancel" />
    </form>
  </div>
  <?php if ($videos) : ?>
    <div id="videos_holder">
      <?php foreach ((array) $videos as $video) : ?>
        <div>
        <a href="<?php echo $video['url']; ?>"><?php echo $video['title']; ?></a>
        <br />
        <?php echo $video['description']; ?>
        </div>
        <div style="height:5px;"></div>
      <?php endforeach; ?>
    </div>
<?php endif; ?>
  </div>
  <div style="height:20px;"></div>

<form id="leaveCommentForm" method="post">
<div class="drunk">LEAVE A COMMENT</div>
<textarea style="display:block;height:100px" name="comment" class="span-56"></textarea>
<div style="text-align:right;">
    <input type="submit" value="say it!" class="rounded button" />
</div>
</form>

<?php if ($comments) : ?>
<div style="height:20px;"></div>
<div>
<span class="drunk">COMMENTS LEFT</span> <a class="button" href="http://user.drunkduck.com/read_all_comments.php?u=<?php echo $username; ?>">see all</a>
<br />
<?php foreach ((array) $comments as $comment) : ?>
<a href="/control_panel/profile.php?username=<?php echo $comment['username']; ?>">
  <img src="<?php echo "http://images.drunkduck.com/process/user_{$comment['user_id']}.{$comment['avatar_ext']}"; ?>" width="50" height="50" />
  <?php echo $comment['username']; ?>
</a>
<br />
<?php echo bbcode2html($comment['comment']); ?>
<br />
<br />
<?php endforeach; ?>
</div>
<?php endif; ?>

    </div>
</div>
<?php require_once('../footer_base.php'); ?>
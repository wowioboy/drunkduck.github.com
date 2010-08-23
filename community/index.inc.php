<?
include('community_header.inc.php');


if ( $USER->username == 'Volte6' )
{
  // create_category("Advertising Discussion", "Talk with other members about ad buys you've made. Where works? Where failed? Details, details, details!");


  // UPDATE community_categories SET grouping='2', order_id='1' WHERE category_id='3301';


  /*
  if ( $id = create_category('Evidence Locker', 'A place for Mods and Admins to stick stuff for later reference.') )
  {
    db_query("UPDATE community_categories SET flags=(flags | ".FORUM_FLAG_MOD_ONLY.") WHERE category_id='$id'");
  }
  */

  // create_category('Interviews', 'DrunkDuck User Interviews!');
  /*
  db_query("DELETE FROM community_categories");
  db_query("DELETE FROM community_posts");
  db_query("DELETE FROM community_topics");
  db_query("DELETE FROM community_sessions");

  migrate_category(31);
  migrate_category( 3);
  migrate_category( 6);

  migrate_category(16);
  migrate_category(14);
  migrate_category( 9);
  migrate_category(28);
  migrate_category(17);
  migrate_category(62);
  migrate_category(70);
  migrate_category( 5);
  migrate_category( 1);
  migrate_category( 8);
  migrate_category(73);
  migrate_category(19);
  migrate_category(42);
  migrate_category( 4);
  migrate_category(59);
  migrate_category(18);
  migrate_category(13);
  migrate_category(12);
  migrate_category(72);
  migrate_category(22);
  migrate_category(77);
  migrate_category(83);
  migrate_category( 7);
  migrate_category(79);
  */

  // migrate_category(62);
  // db_query("UPDATE community_categories SET flags=(flags | ".FORUM_FLAG_MOD_ONLY.") WHERE category_id=''");
}


/*
create_category("General Discussion", "Step in and chat. Vent. Rant. Talk about anything you want that doesn\'t belong in the other forums. You know you want to.");
create_category("Main Page/Newspost Discussion", "Discuss the news posts from DD's main page here! (Anything else will be deleted!)");
create_category("Ideas and suggestions for the Duck!", "Quack about it here!");
create_category("Extermination Station", "AUGH I found a BUG!");
create_category("Hey Everyone Look What I Did!", "This would be the place to plug/advertise your comics, projects, etc. (Just put the word [PLUG] in your subject line) You can also get your comic critiqued, if you so desire! (Just put the word [CRIT] in your subject line) Read the GUIDELINES STICKY for more info!");
create_category("Comic Discussion (Print & Web!)", "Talk about print/web comics here! This is not the place to plug your own comics though. Arrrrr!");
create_category("Comic Review", "Want to review comics? Got a comic you want reviewed? This is the forum to be in! DO NOT MAKE YOUR OWN REVIEW THREAD! Read the forum rules you guys!");
create_category("Networking & Community projects", "Artists looking for writers. Writers looking for artists. Singles looking for singles. Cat looking for catnip. Community projects like jams and fusions can also be found here so hook up!");
create_category("Fightsplosion!", "Biff bang POW! The one and only DrunkDuck fighting tournament. Come in and watch or bash people up! PUNCHTASTIC!");
create_category("Tips and Tricks", "Want to discuss some comic techniques? Or maybe you just want some tips and advice? This is the forum for it all!");
create_category("Forum Games", "Forum Games go here because sometimes they are just too annoying NOT to have their own sub-forum.");
create_category("Top Drawer v.2.0.35 BETA", "Dumb Posts, irrelevant threads, and other types of duck droppings we don't want cluttering up the other forums can be found here. WARNING: May contain distasteful and offensive remarks and images. Enter at your own risk. Admins and Mods will delete anything they wish. Have a nice day.");
create_category("Animation, books, TV, movies, music, and movies oh my!", "Pretty self explainatory. :P It's entertainment!");
*/

if ( $USER->flags & FLAG_IS_ADMIN )
{
  ?>
  <script language="JavaScript">
  function editDesc( cat_id, descNow )
  {
    var descForm = '';
    descForm += '<form action="process_desc.php" method="POST">';
    descForm += '<textarea name="new_desc" rows="5" style="width:100%;">'+descNow+'</textarea>';
    descForm += '<div align="center"><input type="submit" value="Submit!"></div>';
    descForm += '<?=passables_hidden_field()?>';
    descForm += '<input type="hidden" name="cat_id" value="'+cat_id+'">';
    descForm += '</form>';
    $('desc_'+cat_id).innerHTML = descForm;
  }
  </script>
  <?
}
?>

<div class="community_div">

  <div align="left" class="community_general_container">
    &raquo; View Categories
  </div>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">
    <tr>
      <td align="left" class='community_hdr'>
        Category Name/Description
      </td>
      <td width="100" align="center" class='community_hdr'>
        Post Count
      </td>
      <td width="100" align="center" class='community_hdr'>
        Last Post
      </td>
    </tr>
  <?
  $res = db_query("SELECT * FROM community_categories WHERE comic_id='".(int)$_GET['comic_id']."' ORDER BY order_id ASC");
  while( $row = db_fetch_object($res) )
  {
    $POSTS[] = $row;
    if ( $row->last_post_user != 0 ) {
      $USERS[$row->last_post_user] = $row->last_post_user;
    }
  }
  db_free_result($res);


  $res = db_query("SELECT user_id, username FROM users WHERE user_id IN('".implode("','", $USERS)."')");
  while( $row = db_fetch_object($res) ) {
    $USERS[$row->user_id] = $row->username;
  }
  db_free_result($res);

  $totalPosts = 0;
  $ct = 0;
  foreach($POSTS as $post)
  {
    if ( !($post->flags&FORUM_FLAG_ADMIN_ONLY) || ($USER->flags & FLAG_IS_ADMIN) )
    {
      if ( !($post->flags&FORUM_FLAG_MOD_ONLY) || ($USER->flags & FLAG_IS_MOD) || ($USER->flags & FLAG_IS_ADMIN) )
      {
        $totalPosts += $post->post_ct;
        if ( $post->last_topic_id == 0 )
        {
          $res = db_query("SELECT * FROM community_topics WHERE category_id='".$post->category_id."' ORDER BY last_post_date DESC LIMIT 1");
          $row = db_fetch_object($res);
          $post->last_topic_id = $row->topic_id;
          $post->last_post_id = $row->last_post_id;
          db_query("UPDATE community_categories SET last_topic_id='".$post->last_topic_id."', last_post_id='".$post->last_post_id."' WHERE category_id='".$post->category_id."'");
        }
        $JUMP_LINK = "<br><A HREF='view_topic.php?cid=".$post->category_id."&tid=".$post->last_topic_id."&pid=".$post->last_post_id."#".$post->last_post_id."'><img src='".IMAGE_HOST."/community_gfx/icon_jump.gif' border='0' title='Jump to latest!' alt='Jump to latest!'></A>";

        $bg = '';
        if ( ++$ct%2==0 ) {
          $bg = 'bgcolor="#001D37"';
        }

        if ($post->flags&FORUM_FLAG_ADMIN_ONLY)
        {
          $linkClr = 'style="color:#8888ff;"';
        }
        else if ($post->flags&FORUM_FLAG_MOD_ONLY)
        {
          $linkClr = 'style="color:#bb0000;"';
        }


        if ( $post->comic_id == 0 )
        {

          if ( $post->grouping != $lastGrouping )
          {
            ?>
            <tr>
              <td align="left" class="community_thrd" colspan="3" style="font-weight:bold;font-size:13px;background:#ffffff;">
                <?=$GROUPINGS[$post->grouping]?>
              </td>
            </tr>
            <?
            $lastGrouping = $post->grouping;
          }

        }

        ?>
        <tr>
          <td align="left" class="community_thrd" <?=$bg?>>
            <h3><a <?=$linkClr?> href="view_category.php?cid=<?=$post->category_id?>&<?=passables_query_string( array('cid') )?>"><?=htmlentities($post->category_name, ENT_QUOTES)?></a></h3>
            <p id="desc_<?=$post->category_id?>"><?=nl2br( community_bb_code( htmlentities($post->category_desc, ENT_QUOTES) ) )?></p>
            <?
            if ( $USER->flags & FLAG_IS_ADMIN )
            {
              $TMP = trim($post->category_desc);
              $TMP = str_replace('\\', '\\\\', $TMP);
              $TMP = str_replace('\'', '\\\'', $TMP);
              $TMP = htmlentities($TMP, ENT_QUOTES);
              $TMP = str_replace("\n", '\n', $TMP);
              $TMP = str_replace("\r", '\r', $TMP);
              ?><a href="#" onClick="JavaScript:editDesc('<?=$post->category_id?>', '<?=$TMP?>');return false;" style="font-size:9px;font-weight:normal;">&raquo;Edit</a><?
            }
            ?>
          </td>
          <td width="100" align="center" class="community_thrd" <?=$bg?>>
            <?=number_format($post->post_ct)?>
          </td>
          <td width="100" align="center" class="community_thrd_last" <?=$bg?>><?=( ($post->last_post_date==0)?"Never":date($POST_DATE_FORMAT, $post->last_post_date).' by '.username( $USERS[$post->last_post_user] ))?><?=$JUMP_LINK?></td>
        </tr>
        <?
      }
    }
  }
  ?>
    <tr>
      <td align="left" class="community_thrd">
        &nbsp;
      </td>
      <td width="100" align="center" class="community_thrd">
        <?=number_format($totalPosts)?>
      </td>
      <td width="100" align="center" class="community_thrd">&nbsp;</td>
    </tr>
  </table>

  <?
  if ( true ) {
    cached_include(WWW_ROOT.'/community/modules/users_online_cloud.inc.php', 60);
  }
  else if ( $USER->flags & FLAG_IS_ADMIN ) {
    cached_include(WWW_ROOT.'/community/modules/users_online.inc.php', 60, 'admin');
  }
  else {
    cached_include(WWW_ROOT.'/community/modules/users_online.inc.php', 60);
  }
  ?>

</div>

<?
include('community_footer.inc.php');
?>
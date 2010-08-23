<?php

  function forumTitle($user_id)
  {
    switch( $user_id )
    {
      case 2489:
        return '<i><b>Detective</b></i><br>';
      break;
      case 3212:
        return '<i><b>1st post in WTH</b></i><br>';
      break;
      case 154:
        return '<i><b>Ducktective</b></i><br>';
      break;
      case 3116:
        return '<i><b>Need Hugz!</b></i><br>';
      break;
      default:
        return '';
      break;
    }
  }

  class CommunitySessionEX
  {
    var $session_start;
    var $viewed_topics;
    var $viewed_categories;

    function CommunitySessionEX()
    {
      $this->session_start      = mktime( 0, 0, 0, date("m"), date("d"), date("Y") );
      $this->viewed_topics      = Array();
      $this->viewed_categories  = Array();
    }

    function viewTopic($catID, $topicID)
    {
      unset( $this->viewed_topics[$catID][$topicID] );
    }

    function addUnviewed( $catID, $topicID, $postTime )
    {
      $this->viewed_topics[$catID][$topicID] = $postTime;
    }

    function cleanData()
    {
      $cutoff = mktime( 0, 0, 0, date("m"), date("d")-4, date("Y") ); // 4 days ago

      if ( $this->session_start < $cutoff )
      {
        foreach($this->viewed_topics as $catID=>$topicArr)
        {
          foreach($this->viewed_topics[$catID] as $topicID=>$timestamp)
          {
            if ( $timestamp < $cutoff ) {
              unset( $this->viewed_topics[$catID][$topicID] );
            }
          }
        }
        $this->session_start = $cutoff;
      }
    }
  }

  function updatePostCt($user_id)
  {
    $pRes = db_query("SELECT COUNT(*) as forum_post_ct FROM community_posts WHERE user_id='".(int)$user_id."'");
    $pRow = db_fetch_object($pRes);
    db_free_result($pRes);
    db_query("UPDATE users SET forum_post_ct='".$pRow->forum_post_ct."' WHERE user_id='".(int)$user_id."'");
    return $pRow->forum_post_ct;
  }

  function updateStats( $category_id )
  {
    $res = db_query("SELECT SUM(post_ct) AS total_posts FROM community_topics WHERE category_id='".(int)$category_id."'");
    $row = db_fetch_object($res);
    db_query("UPDATE community_categories SET post_ct='".(int)$row->total_posts."' WHERE category_id='".(int)$category_id."'");
    db_free_result($res);


    $res = db_query("SELECT topic_id, last_post_id, last_post_date, last_user_id FROM community_topics WHERE category_id='".(int)$category_id."' ORDER BY last_post_date DESC LIMIT 1");
    if ( $row = db_fetch_object($res) ) {
      db_query("UPDATE community_categories SET last_topic_id='".$row->topic_id."', last_post_id='".$row->last_post_id."', last_post_user='".$row->last_user_id."', last_post_date='".$row->last_post_date."' WHERE category_id='".(int)$category_id."'");
    }
  }

  function migrate_category($phpBB_forum_id)
  {
    echo "MIGRATING ".$phpBB_forum_id."\n";

    $USER_CACHE = array();
    $ANONY_NAMES = array();
    $phpBBForumRes = db_query("SELECT * FROM forum_forums WHERE forum_id='".$phpBB_forum_id."'");
    while( $forumRow = db_fetch_object($phpBBForumRes) )
    {
      $forum_id = $forumRow->forum_id;
      $name     = $forumRow->forum_name;
      $desc     = $forumRow->forum_desc;

      $CAT_ID = create_category($forumRow->forum_name, $forumRow->forum_desc);




      $postRes = db_query("SELECT * FROM forum_posts WHERE forum_id='".$forumRow->forum_id."' ORDER BY topic_id ASC, post_id ASC");
      while( $postRow = db_fetch_object($postRes) )
      {
        if ( $postRow->topic_id != $last_topic ) {
          $last_topic = $postRow->topic_id;
          $ct = 0;
          $new_forum_tid = 0;
        }

        $bodyRes = db_query("SELECT * FROM forum_posts_text WHERE post_id='".$postRow->post_id."' ORDER BY post_id ASC");
        $bodyRow = db_fetch_object($bodyRes);
        db_free_result($bodyRes);

        if ( !$USER_CACHE[$postRow->poster_id] )
        {
          $userRes = db_query("SELECT username FROM forum_users WHERE user_id='".$postRow->poster_id."'");
          $row = db_fetch_object($userRes);
          db_free_result($res);

          $userRes = db_query("SELECT user_id FROM users WHERE username='".db_escape_string($row->username)."'");
          if ( $row2 = db_fetch_object($userRes) ) {
            $USER_CACHE[$postRow->poster_id] = $row2->user_id;
          }
          else {
            $USER_CACHE[$postRow->poster_id] = 0;
            $ANONY_NAMES[$postRow->posted_id] = $row->username;
          }
        }

        $BODY = $bodyRow->post_text;
        $BODY = preg_replace("(:[a-f0-9]{10})", "", $BODY);
        $BODY = str_replace('\r\n', "\r\n", $BODY);

        if ( !$USER_CACHE[$postRow->poster_id] ) {
          $BODY .= "\n\n[size=4][b].: ".$ANONY_NAMES[$postRow->posted_id]." :.[/b][/size]";
        }

        $GLOBALS['migration_time_override'] = $postRow->post_time;

        if ( $ct == 0 )
        {
          $new_forum_tid = create_topic($CAT_ID, ( $bodyRow->post_subject ), $USER_CACHE[$postRow->poster_id], ($BODY));

          $res = db_query("SELECT * FROM forum_topics WHERE topic_id='".$postRow->topic_id."'");
          $topicRow = db_fetch_object($res);

          db_query("UPDATE community_topics SET flags='".FORUM_MIGRATED_POST."', sticky='".( ($topicRow->topic_type)?"1":"0")."' WHERE topic_id='".$new_forum_tid."'");
        }
        else
        {
          create_post($new_forum_tid, $USER_CACHE[$postRow->poster_id], ($BODY));
        }

        ++$ct;
      }
    }
  }

  function create_category($name, $description, $comic_id=0)
  {
    if ( strlen($name) < 3 ) {
      echo "<DIV ALIGN='CENTER' STYLE='color:red;'>Category Name too short.</DIV>";
      return 0;
    }

    // By searching the entire name by ascii value we can precisely limit characters.
    for($i=0; $i<strlen($name); $i++)
    {
      $ord = ord($name{$i});
      if ( ($ord < 32) || ($ord > 126) ) {
        echo "<DIV ALIGN='CENTER' STYLE='color:red;'>The character '".$nane{$i}."' is not allowed.</DIV>";
        return 0;
      }
    }

    if ( strlen($description) > 1000 ) {
        echo "<DIV ALIGN='CENTER' STYLE='color:red;'>The description was too long.</DIV>";
        return 0;
    }

    $res = db_query("SELECT order_id FROM community_categories WHERE comic_id='".(int)$comic_id."' ORDER BY order_id DESC LIMIT 1");
    $row = db_fetch_object($res);
    db_free_result($res);
    $order_id = ((int)$row->order_id) + 1;

    db_query("INSERT INTO community_categories (comic_id, category_name, category_desc, order_id) VALUES ('".(int)$comic_id."', '".db_escape_string($name)."', '".db_escape_string($description)."', '".$order_id."')");
    return db_get_insert_id();
  }



  function create_topic($parent_category, $name, $user_id, $message_body)
  {
    $res = db_query("SELECT COUNT(*) as total_cats FROM community_categories WHERE category_id='".(int)$parent_category."'");
    $row = db_fetch_object($res);
    db_free_result($res);

    if ( $row->total_cats < 1 ) {
      echo "<DIV ALIGN='CENTER' STYLE='color:red;'>There was a problem with your request.</DIV>";
      return 0;
    }

    // By searching the entire name by ascii value we can precisely limit characters.
    for($i=0; $i<strlen($name); $i++)
    {
      $ord = ord($name{$i});
      if ( ($ord < 32) || ($ord > 126) ) {
        echo "<DIV ALIGN='CENTER' STYLE='color:red;'>The character '".$nane{$i}."' is not allowed in your topic name.</DIV>";
        return 0;
      }
    }

    if ( strlen(trim($name)) < 1 ) {
      echo "<DIV ALIGN='CENTER' STYLE='color:red;'>You cannot have an empty topic title.</DIV>";
      return 0;
    }

    $res = db_query("SELECT * FROM community_topics WHERE category_id='".(int)$parent_category."' AND user_id='".(int)$user_id."' AND date_created>'".(time()-30)."'");
    if ( $row = db_fetch_object($res) ) {
      echo "<DIV ALIGN='CENTER' STYLE='color:red;'>You are posting topics too frequently. Wait 30 seconds before posting again.</DIV>";
      return 0;
    }

    $T = time();
    if ( $GLOBALS['migration_time_override'] ) $T = $GLOBALS['migration_time_override'];

    db_query("INSERT INTO community_topics (category_id, topic_name, user_id, date_created) VALUES ('".(int)$parent_category."', '".db_escape_string($name)."', '".(int)$user_id."', '".$T."')");
    $topic_id = db_get_insert_id();
    db_query("UPDATE community_categories SET last_post_user='".(int)$user_id."' WHERE category_id='".(int)$parent_category."'");

    create_post($topic_id, $user_id, $message_body);

    return $topic_id;
  }



  function create_post($topic_id, $user_id, $message_body)
  {
    if ( strlen($message_body) > 20000 ) {
      echo "<DIV ALIGN='CENTER' STYLE='color:red;'>Your post was waaaaaaaaaaaaaaaaaay too long!</DIV>";
      return 0;
    }

    $res = db_query("SELECT * FROM community_topics WHERE topic_id='".(int)$topic_id."'");
    if ( !$row = db_fetch_object($res) ) {
      echo "<DIV ALIGN='CENTER' STYLE='color:red;'>There was an error!</DIV>";
      return 0;
    }
    db_free_result($res);

    if ( $row->flags & FORUM_FLAG_LOCKED ) {
      ?><div align="center">This forum thread is LOCKED</div><?
      return $row->last_post_id;
    }

    $T = time();
    if ( $GLOBALS['migration_time_override'] ) $T = $GLOBALS['migration_time_override'];

    db_query("INSERT INTO community_posts (topic_id, user_id, post_body, date_created) VALUES ('".(int)$topic_id."', '".(int)$user_id."', '".db_escape_string($message_body)."', '".$T."')");
    $post_id = db_get_insert_id();
    db_query("UPDATE community_topics SET last_post_date='".$T."', last_user_id='".(int)$user_id."', last_post_id='".$post_id."', post_ct=post_ct+1 WHERE topic_id='".(int)$topic_id."'");
    db_query("UPDATE community_categories SET last_post_date='".$T."', post_ct=post_ct+1, last_post_user='".(int)$user_id."', last_topic_id='".$topic_id."', last_post_id='".$post_id."' WHERE category_id='".$row->category_id."'");

    return $post_id;
  }

  function delete_post( $post_id )
  {
    /*
    ORDER OF CLEANUP:
    GET Post Row
    GET Topic Row
    GET Category Row

    DELETE Post Row

    UPDATE or DELETE Topic Row

    UPDATE Category Row
    */

    $res = db_query("SELECT * FROM community_posts WHERE post_id='".(int)$post_id."'");
    if ( $POST_ROW = db_fetch_object($res) )
    {
      db_free_result($res);

      $res = db_query("SELECT * FROM community_topics WHERE topic_id='".$POST_ROW->topic_id."'");
      if ( $TOPIC_ROW = db_fetch_object($res) )
      {
        db_free_result($res);

        $res = db_query("SELECT * FROM community_categories WHERE category_id='".$TOPIC_ROW->category_id."'");
        if ( $CAT_ROW = db_fetch_object($res) )
        {
          db_free_result($res);

          db_query("DELETE FROM community_posts WHERE post_id='".$POST_ROW->post_id."'");
          if ( db_rows_affected() > 0 )
          {
            $res = db_query("SELECT COUNT(*) as total_posts FROM community_posts WHERE topic_id='".$TOPIC_ROW->topic_id."'");
            $TOTAL_ROW = db_fetch_object($res);
            db_free_result($res);

            // Get rid of the shell of a topic.
            if ( $TOTAL_ROW->total_posts < 1 ) {
              db_query("DELETE FROM community_topics WHERE topic_id='".$TOPIC_ROW->topic_id."'");
            }
            else {
              $res = db_query("SELECT * FROM community_posts WHERE topic_id='".$TOPIC_ROW->topic_id."' ORDER BY post_id DESC LIMIT 1");
              $LAST_POST_ROW = db_fetch_object($res);
              db_free_result($res);

              db_query("UPDATE community_topics SET post_ct='".$TOTAL_ROW->total_posts."', last_user_id='".$LAST_POST_ROW->user_id."', last_post_date='".$LAST_POST_ROW->date_created."', last_post_id='".$LAST_POST_ROW->post_id."' WHERE topic_id='".$TOPIC_ROW->topic_id."'");
            }

            // Lets get fresh category info.
            $res = db_query("SELECT * FROM community_topics WHERE category_id='".$CAT_ROW->category_id."' ORDER BY last_post_date DESC LIMIT 1");
            $NEWEST_TOPIC_ROW = db_fetch_object($res);
            db_free_result($res);

            $res = db_query("SELECT SUM(post_ct) as total_posts FROM community_topics WHERE category_id='".$CAT_ROW->category_id."'");
            $TOTAL_ROW = db_fetch_object($res);
            db_free_result($res);


            $res = db_query("SELECT * FROM community_topics WHERE category_id='".$CAT_ROW->category_id."' ORDER BY topic_id DESC");
            $LAST_TOPIC_ROW = db_fetch_object($res);
            db_free_result($res);

            db_query("UPDATE community_categories SET post_ct='".$TOTAL_ROW->total_posts."', last_post_date='".(int)$NEWEST_TOPIC_ROW->last_post_date."', last_post_user='".(int)$NEWEST_TOPIC_ROW->last_user_id."', last_topic_id='".$LAST_TOPIC_ROW->topic_id."', last_post_id='".$LAST_TOPIC_ROW->last_post_id."' WHERE category_id='".$CAT_ROW->category_id."'");
          }
        }
      }
    }
  }

  function community_bb_code( $string, $forceImageSizes=false )
  {
    global $EMOTES;

    foreach($EMOTES as $txt=>$img)
    {
      $string = str_replace(htmlentities($txt, ENT_QUOTES), '[img]'.IMAGE_HOST.'/community_gfx/emotes/'.$img.'"[/img]', $string);
    }

    $rand = rand(1000, 999999);

    $search = array(
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',

                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',

                    '`\[b\](.*?)\[\/b\]`msi',
                    '`\[i\](.*?)\[\/i\]`msi',
                    '`\[u\](.*?)\[\/u\]`msi',
                    '`\[img\](.*?)\[\/img\]`msi',
                    '`\[img width=(.*?) height=(.*?)\](.*?)\[\/img\]`msi',
                    '`\[img height=(.*?) width=(.*?)\](.*?)\[\/img\]`msi',
                    '`\[url=(.*?)\](.*?)\[\/url\]`msi',
                    '`\[url\](.*?)\[\/url\]`msi',
                    '`\[code\](.*?)\[\/code\]`msi',
                    '`\[color=(.*?)\](.*?)\[\/color\]`msi',
                    '`\[size=(.*?)\](.*?)\[\/size\]`msi',

                    '`\[swf width=(.*?) height=(.*?)\](.*?)\[\/swf\]`msi',
                    '`\[swf height=(.*?) width=(.*?)\](.*?)\[\/swf\]`msi',

                    '`\[spoiler\](.*?)\[\/spoiler\]`msi',
                    '`\[spoiler=(.*?)\](.*?)\[\/spoiler\]`msi',

                    '`'.htmlentities('<object .*"(http:\/\/[a-zA-Z_0-9\/\.\?=\-&]*)".*\/object>').'`mi',
                    '`'.htmlentities('<embed .*"(http:\/\/[a-zA-Z_0-9\/\.\?=\-&]*)".*\/embed>').'`mi'
                    );
    $replace = array(
                    '<div align="center"><div align="left" class="community_quote"> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>QUOTE:</b> <br> \\1 </div></div>',

                    '<div align="center"><div align="left" class="community_quote"> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" class="community_quote"> <b>\\1 Said:</b> <br> \\2 </div></div>',

                    '<b>\\1</b>',
                    '<i>\\1</i>',
                    '<u>\\1</u>',
                    '<img src="\\1" border="0" style="max-width:'.( $forceImageSizes ? $forceImageSizes[0] : '600' ).'px;">',
                    '<img src="\\3" height="\\2" width="\\1">',
                    '<img src="\\3" height="\\1" width="\\2">',
                    '<a href="\\1" target="_blank">\\2</a>',
                    '<a href="\\1" target="_blank">\\1</a>',
                    '<code>\\1</code>',
                    '<font color="\\1">\\2</font>',
                    '<font style="font-size:\\1px;">\\2</font>',

                    '<embed src="\\3" width="\\1" height="\\2">',
                    '<embed src="\\3" width="\\2" height="\\1">',

                    '<div style="color:#000000;background:#000000;" onMouseOut="this.style.background=\'#000000\';" onMouseOver="this.style.background=\'\';"><div align="center" style="color:#ff0000"><b>WARNING: *CONTAINS SPOILER*</b> - <i>Mouse over black box to read.</i></div>\\1</div>',
                    '<div style="color:#000000;background:#000000;" onMouseOut="this.style.background=\'#000000\';" onMouseOver="this.style.background=\'\';"><div align="center" style="color:#ffffff">\\1</div><div align="center" style="color:#ff0000"><b>WARNING: *CONTAINS SPOILER*</b> - <i>Mouse over black box to read.</i></div>\\2</div>',

                    '<embed src="\\1" width="'.( $forceImageSizes ? $forceImageSizes[0] : '425' ).'" height="'.( $forceImageSizes ? $forceImageSizes[1] : '350' ).'" type="application/x-shockwave-flash" wmode="transparent">',
                    '<embed src="\\1" width="'.( $forceImageSizes ? $forceImageSizes[0] : '425' ).'" height="'.( $forceImageSizes ? $forceImageSizes[1] : '350' ).'" type="application/x-shockwave-flash" wmode="transparent">'
                    );
     $string = preg_replace($search, $replace, $string);
     return $string;
  }


  function passables_query_string( $exlusionArray = null )
  {
    global $PASSABLES;

    $ct = 0;
    $ret = '';
    foreach($PASSABLES as $key=>$value)
    {
      if ( $key != 'pid' )
      {
        if ( !isset($exlusionArray) || !in_array($key, $exlusionArray) )
        {
          if ( $ct != 0 ) {
            $ret .= '&';
          }
          $ret .= $key.'='.rawurlencode($value);
          ++$ct;
        }
      }
    }
    return $ret;
  }

  function passables_hidden_field( $exlusionArray = null )
  {
    global $PASSABLES;
    $ret = '';
    foreach($PASSABLES as $key=>$value)
    {
      if ( !isset($exlusionArray) || !in_array($key, $exlusionArray) ) {
        $ret .= '<input type="hidden" name="'.$key.'" value="'.rawurlencode($value).'">';
      }
    }
    return $ret;
  }
?>
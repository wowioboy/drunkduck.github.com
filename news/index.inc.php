<div align="left" class="header_title">
  News
</div>
<link href="news.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

	<table width="724" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%" align="center" valign="top">
		      <div class="newscolumn">
            <div class="rack_header" style="width:100%;">
              Previously Featured Comics
            </div>
		        <div class="col_content">
            <?
              $FEATURED_ARR = array();
              $res = db_query("SELECT * FROM featured_comics WHERE approved='1' AND ymd_date_live<='".date("Ymd")."' AND ymd_date_live>'0' ORDER BY ymd_date_live DESC LIMIT 5, 8");
              while($row = db_fetch_object($res))
              {
                $FEATURED_ARR[(int)$row->comic_id] = $row;
              }

              $COMIC_DATA = array();
              $res = db_query("SELECT * FROM comics WHERE comic_id IN ('".implode("','", array_keys($FEATURED_ARR))."')");
              while($row = db_fetch_object($res) )
              {
                $res2 = db_query("SELECT page_id FROM comic_pages WHERE comic_id='".$row->comic_id."' ORDER BY order_id ASC LIMIT 1");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);

                $row->first_page_id = $row2->page_id;

                $COMIC_DATA[$row->comic_id] = $row;
              }
              db_free_result($res);

              foreach($FEATURED_ARR as $id=>$row)
              {
                $url         = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_DATA[$id]->comic_name.'/');

                $description = htmlentities( $row->description, ENT_QUOTES );
                $description = nl2br($description);
                $description = str_replace("\n", '',  $description);
                $description = str_replace("\r", '',    $description);

                ?>
            		  <table class="feature">
            		    <tr>
            		      <td>
            		        <h4><a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/featured_comic_gfx/<?=$row->feature_id?>.gif" width="80" height="110" border="2" align="left" class="feature_thumb" /><?=$COMIC_DATA[$id]->comic_name?></a></h4>
                        <p><?=$description?></p>
            		      </td>
            		    </tr>
            		    <tr>
            		      <td>
            		        <p class="feature_nav">
            		          <img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/readit.gif" alt="read it" width="54" height="16" />
            		          <a href="<?=$url?>?p=<?=$COMIC_DATA[$id]->first_page_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/first.gif" alt="first" width="44" height="16" border="0" /></a>
            		          <a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/last.gif" alt="last" width="52" height="16" border="0" /></a>
            		        </p>
            		      </td>
            		    </tr>
            		  </table>
                <?
              }
            ?>
      		  <div class="archive">
              <?
              $dateSelections = array();
              for($i=2006; $i<=date("Y"); $i++) {
                if ( $i == $_GET['year'] ) {
                  $dateSelections[] = $i;
                }
                else {
                  $dateSelections[] = '<a href="feature_archive.php?year='.$i.'">'.$i.'</a>';
                }
              }
              ?>
              <h4>Past Features:</h4>
      		    <p><?=implode(" | ", $dateSelections)?></p>

            </div>

    		    <p></p>
            <div align="center">
              <? include(WWW_ROOT.'/ads/ad_includes/main_template/300x250b.html'); ?>
            </div>

    		  </div>
    		</div>
		  </td>
        <td width="50%" align="center" valign="top">

		<div class="newscolumn">

      <div class="rack_header" style="width:100%;">
        News
      </div>

		  <div class="col_content">
            <?
            $DTE = (int)$_GET['d'];
            if ( !$DTE ) {
              $DTE = YMD;
            }


              $res      = db_query("SELECT ymd_date FROM admin_blog WHERE ymd_date<='".$DTE."' ORDER BY ymd_date DESC LIMIT 1");
              $row      = db_fetch_object($res);
              $BLOG_YMD = $row->ymd_date;
              db_free_result($res);

              $USERS = array();
              $POSTS = array();

              $res = db_query("SELECT * FROM admin_blog WHERE ymd_date='".$BLOG_YMD."' ORDER BY timestamp_date DESC");
              while($row = db_fetch_object($res))
              {
                $POSTS[]                   = $row;
                $USERS[$row->user_id]      = $row->user_id;
                if ( $row->edit_user_id > 0 ) {
                  $USERS[$row->edit_user_id] = $row->edit_user_id;
                }
              }
              db_free_result($res);


              $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id IN ('".implode("','", $USERS)."')");
              while($row = db_fetch_object($res)) {
                $USERS[$row->user_id] = $row;
              }
              db_free_result($res);

              foreach($POSTS as $post)
              {
                ?>
                <h4><?=BBCode($post->title)?></h4>
                <p>
                <?


                $U = &$USERS[$post->user_id];

                if ( $U->avatar_ext )
                {
                  if ( $U->avatar_ext == 'swf' ) {

                    $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$U->user_id.'.swf');
                    echo get_flash_movie(IMAGE_HOST.'/avatars/avatar_'.$U->user_id.'.swf', $INFO[0], $INFO[1]);
                  }
                  else {
                    echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$U->user_id.".".$U->avatar_ext."' ALIGN='LEFT'>";
                  }
                }
                else {
                  echo "<IMG SRC='".IMAGE_HOST."/site_gfx/anonymous.jpg' ALIGN='LEFT'>";
                }
                ?>
                <?=strtoupper(date("F d, Y", $post->timestamp_date))?> - <?=date("g:ia", $post->timestamp_date)?>
                <BR>
                <BR>
                <?=BBCode(nl2br($post->body))?>
                <BR><BR>
                <A HREF='/community/view_category.php?cid=227'>Discuss this news post in the forum.</A>
                <BR><BR>
                <B>
                  <i>This message was posted by <?=(($U->user_id==1)?"The Administrator of DrunkDuck.com":username($U->username))?></i>
                </B>
                <?
                if ( $post->edit_timestamp )
                {
                  ?>
                  <br>
                  <i>...and edited by <?=(($USERS[$post->edit_user_id]->user_id==1)?"The Administrator of DrunkDuck.com":username($USERS[$post->edit_user_id]->username))?> on <?=strtoupper(date("F d, Y", $post->edit_timestamp))?> - <?=date("g:ia", $post->edit_timestamp)?></i>
                  <?
                }
              }
            ?>

          <div style="border:1px solid #bbb;">
            <h4>Past News Stories:</h4>
            <ul>
            <?
            $res = db_query("SELECT * FROM admin_blog ORDER BY blog_id DESC LIMIT 3");
            while( $row = db_fetch_object($res) ) {
              ?><li><a href="news_archive.php?story=<?=$row->blog_id?>"><?=$row->title?></a></li><?
            }
            ?>
            </ul>
            <?
            $dateSelections = array();
            for($i=2006; $i<=date("Y"); $i++) {
              if ( $i == $_GET['year'] ) {
                $dateSelections[] = $i;
              }
              else {
                $dateSelections[] = '<a href="news_archive.php?year='.$i.'">'.$i.'</a>';
              }
            }
            ?>
            <p><?=implode(" | ", $dateSelections)?></p>
          </div>
    		</div>
      </div>

		    </td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
          <div class="widecolumn">
            <img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/widecol_faq.png" width="760" height="29" />
            <div class="widecol_content">
        		  <ol>
        		    <?
        		    $res = db_query("SELECT * FROM faq ORDER BY id ASC");
        		    while( $row = db_fetch_object($res) )
        		    {
        		      ?><li><a href="faq.php?id=<?=$row->id?>"><?=$row->question?></a></li><?
        		    }
                ?>
              </ol>
  		      </div>
  		      <img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/widecol_bot.png" width="760" height="19" />
  		    </div>
		    </td>
      </tr>
    </table>
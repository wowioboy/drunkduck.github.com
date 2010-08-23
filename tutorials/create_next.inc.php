<div align="left" class="header_title">
  Send Tutorial
</div>
<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
include('tutorial_data.inc.php');
?>
<link href="tutorials.css" rel="stylesheet" type="text/css" />

<div id="tutorial_body">

<?
function is_valid_tutorial_file( $filename )
{
  $ext      = getFileExt($filename);

  $allowed  = array('png', 'jpg', 'gif', 'jpeg');
  if ( in_array($ext, $allowed) ) {
    return true;
  }
  return false;
}

if ( isset($_POST['body']) && isset($_POST['title']) && isset($_POST['desc']) )
{
  $_POST['body']  = trim($_POST['body']);
  $_POST['title'] = trim($_POST['title']);
  $_POST['desc']  = trim($_POST['desc']);

  if ( strlen($_POST['title']) < 5 ) {
    ?>
      <div align="center">
        Your title was too short. Please click the <a href="javascript:history.back();" id="tutorial">back button</a> on your browser and update it.
      </div>
    </div>
    <?
    return;
  }


  if ( strlen($_POST['desc']) < 5 ) {
    ?>
      <div align="center">
        Your description was too short. Please click the <a href="javascript:history.back();" id="tutorial">back button</a> on your browser and update it.
      </div>
    </div>
    <?
    return;
  }

  if ( strlen($_POST['body']) < 100 ) {
    ?>
      <div align="center">
        The body of your tutorial must be at least 100 characters long. Please click the <a href="javascript:history.back();" id="tutorial">back button</a> on your browser and update it.
      </div>
    </div>
    <?
    return;
  }


  if ( isset($_POST['edit_id']) )
  {
    if ( $USER->flags & FLAG_IS_ADMIN ) {
      db_query("UPDATE tutorials SET title='".db_escape_string($_POST['title'])."', description='".db_escape_string($_POST['desc'])."', body='".db_escape_string($_POST['body'])."', timestamp_edited='".time()."', finalized='0' WHERE tutorial_id='".(int)$_POST['edit_id']."'");
    }
    else {
      db_query("UPDATE tutorials SET title='".db_escape_string($_POST['title'])."', description='".db_escape_string($_POST['desc'])."', body='".db_escape_string($_POST['body'])."', timestamp_edited='".time()."', finalized='0' WHERE tutorial_id='".(int)$_POST['edit_id']."' AND user_id='".$USER->user_id."'");
    }

    if ( db_rows_affected() > 0 )
    {
      $ARTICLE_ID = (int)$_POST['edit_id'];

      delete_tags( $ARTICLE_ID );

      $TAGS_ARR    = explode(',', $_POST['tags']);
      foreach($TAGS_ARR as $id=>$tag)
      {
        $tag = trim($tag);

        if ( strlen($tag)<2 || preg_match('/([^a-zA-Z0-9_ \-])+/', $tag) ) {
          // NOOP
          echo "BAD TAG:" . $tag."<BR>";
        }
        else
        {
          db_query("INSERT INTO tutorial_tags (tutorial_id, tag) VALUES ('".$ARTICLE_ID."', '".db_escape_string($tag)."')");
          db_query("UPDATE tutorial_tags_used SET counter=counter+1 WHERE tag='".db_escape_string($tag)."'");
          if ( db_rows_affected() < 1 ) {
            db_query("INSERT INTO tutorial_tags_used (tag, counter) VALUES ('".db_escape_string($tag)."', '1')");
          }
        }
      }
    }

  }
  else
  {
    $res        = db_query("INSERT INTO tutorials (user_id, username, timestamp, title, description, body, vote_count, vote_total, vote_avg, finalized) VALUES ('".$USER->user_id."', '".db_escape_string($USER->username)."', '".time()."', '".db_escape_string($_POST['title'])."', '".db_escape_string($_POST['desc'])."', '".db_escape_string($_POST['body'])."', '0', '0', '0', '0')");
    $ARTICLE_ID = db_get_insert_id();

    $TAGS_ARR    = explode(',', $_POST['tags']);
    foreach($TAGS_ARR as $id=>$tag)
    {
      $tag = trim($tag);

      if ( strlen($tag)<2 || preg_match('/([^a-zA-Z0-9_ \-])+/', $tag) ) {
        // NOOP
        echo "BAD TAG:" . $tag."<BR>";
      }
      else
      {
        db_query("INSERT INTO tutorial_tags (tutorial_id, tag) VALUES ('".$ARTICLE_ID."', '".db_escape_string($tag)."')");
        db_query("UPDATE tutorial_tags_used SET counter=counter+1 WHERE tag='".db_escape_string($tag)."'");
        if ( db_rows_affected() < 1 ) {
          db_query("INSERT INTO tutorial_tags_used (tag, counter) VALUES ('".db_escape_string($tag)."', '1')");
        }
      }
    }
  }
}
else if ( isset($_POST['article_id']) && (is_uploaded_file($_FILES['upload_file']['tmp_name']) || (count($_POST['delete_img']) > 0) ) )
{
  $ARTICLE_ID = (int)$_POST['article_id'];
  if ( $USER->flags & FLAG_IS_ADMIN ) {
    $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".$ARTICLE_ID."'");
  }
  else {
    $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".$ARTICLE_ID."' AND user_id='".$USER->user_id."'");
  }

  if ( !$row = db_fetch_object($res) ) die;


  if ( count($_POST['delete_img']) > 0 )
  {
    foreach($_POST['delete_img'] as $id) {
      delete_tutorial_image( $id );
    }
  }

  if ( is_uploaded_file($_FILES['upload_file']['tmp_name']) )
  {
    if ( is_valid_tutorial_file( $_FILES['upload_file']['name'] ) )
    {
      $ext        = getFileExt($_FILES['upload_file']['name']);
      $TMP_FILE   = WWW_ROOT.'/gfx/tutorials/content/_cache/'.$ARTICLE_ID.'_tmp.'.$ext;
      copy( $_FILES['upload_file']['tmp_name'], $TMP_FILE );

      $res = db_query("SELECT order_id FROM tutorial_images WHERE tutorial_id='".$ARTICLE_ID."' ORDER BY order_id DESC LIMIT 1");
      $ORDER_ID = 0;
      if ( $row = db_fetch_object($res) ) {
        $ORDER_ID = (int)$row->order_id;

      }
      $ORDER_ID++;

      db_query("INSERT INTO tutorial_images (tutorial_id, title, file_ext, order_id) VALUES ('".$ARTICLE_ID."', '', '".db_escape_string($ext)."', '".$ORDER_ID."')");
      $IMAGE_ID = db_get_insert_id();
      $ARTICLE_ID_2 = str_pad( $ARTICLE_ID, 2, '0', STR_PAD_LEFT);

      $NEW_FOLDER   = WWW_ROOT.'/gfx/tutorials/content/'.(int)($ARTICLE_ID_2{0}).'/'.(int)($ARTICLE_ID_2{1});


      copy( $TMP_FILE, $NEW_FOLDER.'/'.$ARTICLE_ID.'_'.$IMAGE_ID.'.'.$ext );
      thumb( $TMP_FILE, $NEW_FOLDER.'/'.$ARTICLE_ID.'_'.$IMAGE_ID.'_thumb.'.$ext, 250, 100, true );
      unlink($TMP_FILE);
    }
}

  // must be adding
}
else if ( isset($_POST['article_id']) )
{
  header("Location: view.php?id=".$_POST['article_id']);
}

if ( !$ARTICLE_ID ) {
  ?>ERROR<?
  return;
}

/*
CREATE TABLE tutorial_images
(
  image_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tutorial_id INT(11) NOT NULL,
  title VARCHAR(255) NOT NULL,
  file_ext VARCHAR(4) NOT NULL,
  order_id INT(11) NOT NULL,
  INDEX(order_id),
  FULLTEXT(title)
);
*/


?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="left" width="160" valign="bottom" bgcolor="#007610">
        <img src="<?=IMAGE_HOST?>/tutorials/view_tutorials_hdr.gif"><br>
        <img src="<?=IMAGE_HOST?>/tutorials/btn_the_art_institute_big.gif"><br>
        <img src="<?=IMAGE_HOST?>/tutorials/btn_view_search.gif"><br>
        <img src="<?=IMAGE_HOST?>/tutorials/btn_view_create.gif">

        <script language='JavaScript' type='text/javascript' src='http://ads.platinumstudios.net/adx.js'></script>
        <script language='JavaScript' type='text/javascript'>
        <!--
           if (!document.phpAds_used) document.phpAds_used = ',';
           phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);

           document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
           document.write ("http://ads.platinumstudios.net/adjs.php?n=" + phpAds_random);
           document.write ("&amp;what=zone:63");
           document.write ("&amp;exclude=" + document.phpAds_used);
           if (document.referrer)
              document.write ("&amp;referer=" + escape(document.referrer));
           document.write ("'><" + "/script>");
        //-->
        </script><noscript><a href='http://ads.platinumstudios.net/adclick.php?n=a1fa26c8' target='_blank'><img src='http://ads.platinumstudios.net/adview.php?what=zone:63&amp;n=a1fa26c8' border='0' alt=''></a></noscript>

      </td>
      <td align="center" valign="top" width="100%">

        <div style="margin:10px;" id="tutorial_body">

          <div align="center" style="font-size:18px;font-weight:bold;">
            Adding a File
          </div>

          <p align="center">
            Leave blank if you are adding no more files.
            <br>
            <input type="file" name="upload_file">
          </p>

          <p align="center">
            <?
            $res = db_query("SELECT * FROM tutorial_images WHERE tutorial_id='".$ARTICLE_ID."' ORDER BY order_id ASC");
            while($row = db_fetch_object($res) )
            {
              $ARTICLE_ID_2 = str_pad( $row->tutorial_id, 2, '0', STR_PAD_LEFT);

              ?><div align="center" style="padding-bottom:10px;"><?
                ?><a href="http://images.drunkduck.com/tutorials/content/<?=(int)($ARTICLE_ID_2{0})?>/<?=(int)($ARTICLE_ID_2{1})?>/<?=$row->tutorial_id?>_<?=$row->image_id?>.<?=$row->file_ext?>" target="_blank"><img src="http://images.drunkduck.com/tutorials/content/<?=(int)($ARTICLE_ID_2{0})?>/<?=(int)($ARTICLE_ID_2{1})?>/<?=$row->tutorial_id?>_<?=$row->image_id?>_thumb.<?=$row->file_ext?>" border="0"></a><?
                ?><br><input type="checkbox" name="delete_img[]" value="<?=$row->image_id?>" id="delete_<?=$row->image_id?>"><label for="delete_<?=$row->image_id?>">Delete</label><?
              ?></div><?
            }
            ?>
          </p>


          <p align="center">
            <input type="submit" value="Continue">
          </p>

        </div>

      </td>
    </tr>
  </table>
  <input type="hidden" name="article_id" value="<?=$ARTICLE_ID?>">
</form>
</div>
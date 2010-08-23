<?
$dateSelection = array();
for($i=2006; $i<=date("Y"); $i++) {
  if ( $i == $_GET['year'] ) {
    $dateSelections[] = $i;
  }
  else {
    $dateSelections[] = '<a href="'.$_SERVER['PHP_SELF'].'?year='.$i.'">'.$i.'</a>';
  }
}
?>
<link href="news.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

<div id="newspage" style="color:black;text-align:left;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100%" colspan="2" valign="top"><h1 class="style1">News</h1>
  		  <div id="contentpage">
<?
if ( isset($_GET['year']) )
{
  $Y = (int)$_GET['year'];
  $start = $Y.'0101';
  $end   = $Y.'1231';
  ?>
  <h2>News Archive</h2>
  <p class="byline"><?=implode(" | ", $dateSelections)?></p>
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <?
      $res = db_query("SELECT * FROM admin_blog WHERE ymd_date>='".$start."' AND ymd_date<='".$end."' ORDER BY blog_id DESC");
      while( $row = db_fetch_object($res) )
      {
        ?>
        <tr>
          <td><?=date("m-d-Y", $row->timestamp_date)?></td>
          <td width="598">
            <a href="<?=$_SERVER['PHP_SELF']?>?story=<?=$row->blog_id?>"><?=bbcode($row->title)?></a>
          </td>
        </tr>
        <?
      }
    ?>
  </table>
  <?
}
else if ( $_GET['story'] )
{
  $res = db_query("SELECT * FROM admin_blog WHERE blog_id='".(int)$_GET['story']."'");
  $post = db_fetch_object($res);
  db_free_result($res);

  $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id='".$post->user_id."'");
  $uRow = db_fetch_object($res);
  db_free_result($res);


  ?>
  <h2><?=$row->title?></h2>
  <p class="byline"><a href="JavaScript:history.back();">Back</a></p>
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td align="left">
        <?
        $U = &$uRow;

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
        <B>
          <i>This message was posted by <?=(($U->user_id==1)?"The Administrator of DrunkDuck.com":username($U->username))?></i>
        </B>
      </td>
    </tr>
  </table>
  <?
}
else
{
  ?>
  <h2>News Archive</h2>
  <p class="byline"><?=implode(" | ", $dateSelections)?></p>
  <?
}
?>
  		    <p>&nbsp;</p>
		    </div>
      </td>
    </tr>
  </table>
</div>
<?
include('header_edit_comic.inc.php');


if ( isset($_POST['header_style']) )
{
  db_query("UPDATE comics SET comic_caps_id='".(int)$_POST['header_style']."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  $COMIC_ROW->comic_caps_id = (int)$_POST['header_style'];
}

if ( isset($_GET['use']) )
{
  if ( $_GET['use'] == 'html' ) {
    db_query("UPDATE comics SET template='0' WHERE comic_id='".$COMIC_ROW->comic_id."'");
    $COMIC_ROW->template = 0;
  }
  else {
    header("Location: http://".DOMAIN."/account/comic/edit_homepage_template_premade.php?cid=".$COMIC_ROW->comic_id);
  }
}
?>

<div class="pagecontent">


<TABLE width="100%" BORDER='0' CELLPADDING='0' CELLSPACING='0'>
  <TR>
    <TD VALIGN='TOP'>

      <table border='0' cellpadding='0' cellspacing='0' width='100%'>
        <tr>
          <td colspan="3" align='LEFT' valign="top"><h3>Comic Page Layout:</h3></td>
        </tr>
        <tr>
          <td colspan="3" align='LEFT' valign="top" class="community_hdr">Comic  Design/Layout</td>
        </tr>
        <tr>
          <td colspan="3" align='LEFT' valign="top" class="community_thrd">
            Pick a method of designing the look of your comic pages.
            (You can change your method and your design at any time!)
          </td>
        </tr>

<?
  if ( $COMIC_ROW->flags & FLAG_USE_HOMEPAGE )
  {
    ?>
        <tr>
          <td width="25%" align='center' valign="middle" class="community_thrd">
            <p><strong>Comic Home Page: </strong></p>
            <p><em>Currently Using:</em> <?=( ($COMIC_ROW->template==0)?"<a href='".$_SERVER['PHP_SELF']."?cid=".$COMIC_ROW->comic_id."&use=template'>Raw HTML</a>":"<a href='".$_SERVER['PHP_SELF']."?cid=".$COMIC_ROW->comic_id."&use=html'>Template</a>" )?></p>
          </td>
          <td height="30" align='LEFT' valign="bottom" class="community_thrd">
            <div align="center" class="style1">
              <strong>1. Raw HTML </strong>
            </div>
            <p align="center">
              <a href="edit_homepage_template_html.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_html.gif" width="100" height="24" border="0"></a>
            </p>
            <p>
              <span class="helpnote">This is for advanced users only. Here we allow you full control over all the HTML of your comic. CSS and Javascript are allowed if you know how. </span>
            </p>
          </td>
          <td height="30" align='LEFT' valign="bottom" class="community_thrd">
            <div align="center" class="style1">
              <strong>2. Choose Template </strong>
            </div>
            <p align="center">
              <a href="edit_homepage_template_premade.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_temp.gif" width="100" height="24" border="0"></a>
            </p>
            <p>
              <span class="helpnote">This is the simplest way to change the design of your comic pages. Just choose from one of the pre-made templates. </span>
            </p>
          </td>
        </tr>
    <?
  }
?>
        <tr>
          <td width="25%" align='center' valign="middle" class="community_thrd">
            <p><strong>Comic Page: </strong></p>
            <p><em>Currently Using:</em> <?=( ($COMIC_ROW->template==0)?"<a href='".$_SERVER['PHP_SELF']."?cid=".$COMIC_ROW->comic_id."&use=template'>Raw HTML</a>":"<a href='".$_SERVER['PHP_SELF']."?cid=".$COMIC_ROW->comic_id."&use=html'>Template</a>" )?></p>
          </td>
          <td height="30" align='LEFT' valign="bottom" class="community_thrd">
            <div align="center" class="style1">
              <strong>1. Raw HTML </strong>
            </div>
            <p align="center">
              <a href="edit_comicpage_template_html.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_html.gif" width="100" height="24" border="0"></a>
            </p>
            <p>
              <span class="helpnote">This is for advanced users only. Here we allow you full control over all the HTML of your comic. CSS and Javascript are allowed if you know how. </span>
            </p>
          </td>
          <td height="30" align='LEFT' valign="bottom" class="community_thrd">
            <div align="center" class="style1">
              <strong>2. Choose Template </strong>
            </div>
            <p align="center">
              <a href="edit_homepage_template_premade.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_temp.gif" width="100" height="24" border="0"></a>
            </p>
            <p>
              <span class="helpnote">This is the simplest way to change the design of your comic pages. Just choose from one of the pre-made templates. </span>
            </p>
          </td>
        </tr>


      </table>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
		  <tr>
        <td colspan="4" align='LEFT' valign="top" class="community_hdr">Comic Header Design </td>
      </tr>
      <form action="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>" method="POST">
      <tr>
        <td align='LEFT' valign="top" class="community_thrd"><p>Select Header Design:<br>
          <span class="helpnote">Selecting a header design allows you to change the DrunkDuck page header that appears above all hosted comics.</span></p>
          <p>&nbsp;</p>
          <p align="center">Currently Selected:</p>
          <p align="center"><strong><?=$HEADER_STYLES[$COMIC_ROW->comic_caps_id]?></strong></p>
          <p align="center"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/previews/tb_<?=$COMIC_ROW->comic_caps_id?>.gif"></p>
        </td>
        <td align='LEFT' valign="top" class="community_thrd">
        <?
        $i=0;
        foreach($HEADER_STYLES as $id=>$name)
        {
          if ( ($id == 5) || ($id == 10) ) {
            ?></td><td align='LEFT' valign="top" class="community_thrd"><?
          }
          ?><p align="center">
              <input type="radio" name="header_style" value="<?=$id?>" <?=( ($COMIC_ROW->comic_caps_id==$id)?"CHECKED":"" )?>> <?=$name?>
              <br>
              <img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/previews/tb_<?=$id?>.gif">
            </p><?
          ++$i;
        }
        ?>
      </tr>
      <tr>
        <td colspan="4" align='LEFT' valign="top" class="community_thrd"><div align="right"><input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_hdr.gif" width="100" height="24" border="0"></div></td>
      </tr>
      </form>
  	</table>

</TD>
 </TR>
</TABLE>

</div>

<?
include('footer_edit_comic.inc.php');
?>
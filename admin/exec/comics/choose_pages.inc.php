<?
$res = db_query("SELECT * FROM comics WHERE comic_name='".db_escape_string( $_GET['comic_name'] )."'");
if ( !$COMIC_ROW = db_fetch_object($res) ) return;
?>

<form action="get_pdf.php" method="POST">

  <div align="center" style="width:400px;">
    PDF Title:<br>
    <input type="text" name="pdf_title">
    <br>
    <br>
    Every image on new page?<br>
    <input type="checkbox" name="new_page_every_image" value="1">
  </div>


  <table border="0" cellpadding="0" cellspacing="0" width="600">
    <tr>
      <td>Page #</td>
      <td>Title</td>
      <td>Include?</td>
    </tr>

    <?
    $p = 0;
    $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY order_id ASC");
    while($PAGE_ROW = db_fetch_object($res) )
    {
      if ( $PAGE_ROW->is_chapter )
      {
        ?>
        <tr>
          <td colspan="3" bgcolor="#000000" style="color:#ffffff;font-weight:bold;">NEW CHAPTER</td>
        </tr>
        <?
      }

      ?>
      <tr>
        <td><?=(++$p)?></td>
        <td><a href="http://<?=COMIC_DOMAIN?>/<?=comicNameToFolder($COMIC_ROW->comic_name)?>/pages/<?=md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id)?>.<?=$PAGE_ROW->file_ext?>" target="_blank"><?=$PAGE_ROW->page_title?></a></td>
        <td><input type="checkbox" name="pages[]" value="<?=$PAGE_ROW->page_id?>"></td>
      </tr>
      <?
    }
    ?>

  </table>
  <input type="hidden" name="comic_id" value="<?=$COMIC_ROW->comic_id?>">

  <div align="center">
    <input type="submit" value="Create!" style="width:200px;">
  </div>

</form>
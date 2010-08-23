<?
include('header_edit_comic.inc.php');
?>
  <script language="JavaScript">
  function toggleChapter( page_id )
  {
    var checkBoxName = 'is_chapter_'+String(page_id);

    if ( $(checkBoxName).checked ) {
      ajaxCall('/xmlhttp/makeChapter.php?cid=<?=$CID?>&pid='+page_id+'&isChap=1', onToggleChapter);
    }
    else {
      ajaxCall('/xmlhttp/makeChapter.php?cid=<?=$CID?>&pid='+page_id+'&isChap=0', onToggleChapter);
    }
  }

  function onToggleChapter( resp )
  {
    var checkBoxName = 'is_chapter_'+String(resp);
    if ( $(checkBoxName).checked ) {
      $('page_'+resp+'_a').style.background = '#6785AC';
      $('page_'+resp+'_b').style.background = '#6785AC';
      $('page_'+resp+'_c').style.background = '#6785AC';
      $('page_'+resp+'_d').style.background = '#6785AC';
      $('page_'+resp+'_e').style.background = '#6785AC';
      $('page_'+resp+'_f').style.background = '#6785AC';
    }
    else {
      $('page_'+resp+'_a').style.background = '#CCCCCC';
      $('page_'+resp+'_b').style.background = '#CCCCCC';
      $('page_'+resp+'_c').style.background = '#CCCCCC';
      $('page_'+resp+'_d').style.background = '#CCCCCC';
      $('page_'+resp+'_e').style.background = '#CCCCCC';
      $('page_'+resp+'_f').style.background = '#CCCCCC';
    }
  }
  </script>
  <DIV CLASS='container' ALIGN='LEFT'>


  <TABLE WIDTH='100%' BORDER='0' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD COLSPAN='6' ALIGN='CENTER' valign="bottom"><h3 align="left"><span style="float:left;">Comic Pages:</span><span style="float:right;"><a href="add_page.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/addpage.gif" width="100" height="24" border="0" align="baseline"></a></span></h3></TD>
    </TR>

          <TR>
        <TD ALIGN='CENTER' COLSPAN='3'></TD>
        <TD width="11%" ALIGN='CENTER'></TD>
        <TD ALIGN='CENTER' COLSPAN='2'></TD>
      </TR>
      <TR>
        <TD width="5%" ALIGN='CENTER' class="community_hdr"><div align="left">Page</div></TD>
        <TD width="6%" ALIGN='CENTER' class="community_hdr">Chapter</TD>
        <TD width="45%" ALIGN='CENTER' class="community_hdr"><div align="left">Title</div></TD>
        <TD ALIGN='CENTER' class="community_hdr"><div align="left">Date Live</div></TD>
        <TD width="8%" ALIGN='CENTER' class="community_hdr"><div align="left">Avg. Score</div></TD>
        <TD width="25%" ALIGN='CENTER' class="community_hdr"><div align="left">Action</div></TD>
      </TR>

      <?
      $P        = (int)$_GET['p'];
      $PER_PAGE = 20;
      $START    = $P*$PER_PAGE;
      $TOTAL    = $COMIC_ROW->total_pages;

      if ( $P > 0 ) {
        $PREV_PAGE = "<A HREF='".$_SERVER['PHP_SELF']."?cid=".$CID."&p=".($P-1)."'>Previous Page</A>";
      }
      if ( ($P*$PER_PAGE+$PER_PAGE) < $TOTAL ) {
        $NEXT_PAGE = "<A HREF='".$_SERVER['PHP_SELF']."?cid=".$CID."&p=".($P+1)."'>Next Page</A>";
      }

      $res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$CID."'");
      $row = db_fetch_object($res);
      db_free_result($res);
      $TOTAL_PAGES = $row->total_pages;


      ?>
      <TR>
        <TD COLSPAN='3' ALIGN='LEFT'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$PREV_PAGE?></TD>
        <TD COLSPAN='3' ALIGN='RIGHT'><?=$NEXT_PAGE?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
      </TR>
      <?


      $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id DESC LIMIT ".$START.",".$PER_PAGE);
      $ROW_CT      = db_num_rows($res);
      $START_POINT = $TOTAL_PAGES - $START;

      $COUNT = 0;
      $BAD = false;
      while ( $row = db_fetch_object($res) )
      {
        if ( $row->order_id != ($START_POINT-$COUNT) ) {
          $BAD = true;
          //break;
        }


        $COLOR = ( ($row->is_chapter)?'bgcolor="#6785AC"':'');

        ?>

        <TR>
          <TD id='page_<?=$row->page_id?>_a' ALIGN='LEFT' class="community_thrd" <?=$COLOR?>>
            <?=$row->order_id?>
          </TD>
          <TD id='page_<?=$row->page_id?>_b' ALIGN='center' class="community_thrd" <?=$COLOR?>>
            <input type="checkbox" id="is_chapter_<?=$row->page_id?>" value="<?=$row->page_id?>" <?=( ($row->is_chapter)?'CHECKED':'')?> onClick="toggleChapter(<?=$row->page_id?>);">
          </TD>
          <TD id='page_<?=$row->page_id?>_c' ALIGN='LEFT' class="community_thrd" <?=$COLOR?>>
            <A HREF='http://<?=DOMAIN?>/<?=comicNameToFolder($COMIC_ROW->comic_name)?>/?p=<?=$row->page_id?>'><?=$row->page_title?></A>
          </TD>
          <TD id='page_<?=$row->page_id?>_d' ALIGN='CENTER' class="community_thrd" <?=$COLOR?>><?=date("m-d-Y", $row->post_date)?></TD>
          <TD id='page_<?=$row->page_id?>_e' ALIGN='CENTER' class="community_thrd" <?=$COLOR?>><?=$row->page_score?></TD>
          <TD id='page_<?=$row->page_id?>_f' ALIGN='CENTER' class="community_thrd" <?=$COLOR?>>
            <div align="left">
              <?
              if ( $row->order_id != $TOTAL ) {
                ?><A HREF='move_page.php?dir=1&cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$row->page_id?>&p=<?=$P?>' TITLE='Move this page up!'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_up.gif" TITLE='Move this page up!' width="13" height="16" border="0"></A><?
              }
              ?> <?
              if ( $row->order_id > 1 ) {
                ?><A HREF='move_page.php?dir=-1&cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$row->page_id?>&p=<?=$P?>' TITLE='Move this page down!'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_down.gif" TITLE='Move this page down!' width="13" height="16" border="0"></A><?
              }
              ?>
              <A HREF='http://<?=DOMAIN?>/account/comic/edit_page.php?cid=<?=$CID?>&pid=<?=$row->page_id?>'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a>
              <A HREF='http://<?=DOMAIN?>/<?=comicNameToFolder($COMIC_ROW->comic_name)?>/pages/<?=md5($row->comic_id.$row->page_id)?>.<?=$row->file_ext?>' TARGET='_BLANK'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_view.gif" TITLE='View the image for this page!' width="41" height="16" border="0"></a>
              <a href="http://<?=DOMAIN?>/account/comic/send_page_edit.php?cid=<?=$CID?>&pid=<?=$row->page_id?>&delete=1"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_del.gif" TITLE='Delete this page!' width="15" height="16" border="0"></a>
            </div>
          </TD>
        </TR>

        <?

        $COUNT++;
      }

      ?>

      <TR>
        <TD colspan="6" ALIGN='LEFT' class="helpnote"><p>CHAPTERS: Setting a page as a chapter sets it apart in your comic's drop-down comic navigation menu. Chapters make it easy to navigate multi-page stories. The titles of your chapters appear in the optional Chapter List on your comic's home page.</p>
          <p> DESCRIPTION: This is the title of the page. It appears in the drop-down menu on your comic and homepages. </p>
          <p>DATE LIVE: This is the date a page went live or is scheduled to go live. Other users can only see pages that have gone live.</p>
          <p>AVERAGE SCORE: This is the average score commenters have given your page. The scoring system is from 1-5.</p>
          <p>ACTIONS:</p>
          <ul>
            <li> Up/Down Arrows: The up/down arrows allow you to move a page in the list. This only changes the order of this list and the drop-down menu. It does not change the date a page will go live.</li>
            <li>Edit: This allows you to edit an uploaded page. You can change the title, author's note, or the actual graphic of a page or delete the page outright. You cannot edit a live date.</li>
            <li>View: This button will show you the uploaded graphic for a page by itself.</li>
            <li>Delete: This button will delete a page.</li>
          </ul>
        </TD>
      </TR>
          </TABLE>
</DIV>

<?
include('footer_edit_comic.inc.php');
?>
<?
$OMO = "onMouseOver=\"this.style.background='#FFCCCC';\" onMouseOut=\"this.style.background='';\";";
?>
<DIV ID='sideNav'>

  <DIV STYLE='width:200px;padding-left:15px;' ALIGN='LEFT'>
    <B>User Management</B>
  </DIV>

  <A HREF='http://<?=DOMAIN?>/admin/user_view.php' <?=$OMO?> style="display:block;padding-left:5px;">View Users</A>
  <A HREF='http://<?=DOMAIN?>/admin/comic_list.php' <?=$OMO?> style="display:block;padding-left:5px;">View Comics</A>
  <A HREF='http://<?=DOMAIN?>/admin/ip_ban.php' <?=$OMO?> style="display:block;padding-left:5px;">IP Ban</A>

  <br><br>

  <DIV STYLE='width:200px;padding-left:15px;' ALIGN='LEFT'>
    <B>Content</B>
  </DIV>

  <A HREF='http://<?=DOMAIN?>/admin/blog_post.php?a=list' <?=$OMO?> style="display:block;padding-left:5px;">Post News</A>
  <A HREF='http://<?=DOMAIN?>/admin/featured_comics.php?a=list' <?=$OMO?> style="display:block;padding-left:5px;">Featured Comics</A>
  <A HREF='http://<?=DOMAIN?>/admin/content/faq.php' <?=$OMO?> style="display:block;padding-left:5px;">F.A.Q.</A>
  <A HREF='http://<?=DOMAIN?>/admin/games/' <?=$OMO?> style="display:block;padding-left:5px;">Games</A>

  <br><br>

  <DIV STYLE='width:200px;padding-left:15px;' ALIGN='LEFT'>
    <B>Moderation</B>
  </DIV>

  <A HREF='http://<?=DOMAIN?>/admin/reported_comments.php' <?=$OMO?> style="display:block;padding-left:5px;">Reported Comments</A>

  <br><br>

  <?
  if ( $USER->flags & FLAG_IS_EXEC )
  {
    ?>
    <DIV STYLE='width:200px;padding-left:15px;' ALIGN='LEFT'>
      <B>Statistics</B>
    </DIV>

    <A HREF='http://<?=DOMAIN?>/admin/exec/' <?=$OMO?> style="display:block;padding-left:5px;">General Summary</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_pageviews_filtered.php' <?=$OMO?> style="display:block;padding-left:5px;">Pageviews</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_uniques_filtered.php' <?=$OMO?> style="display:block;padding-left:5px;">Uniques</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_users.php' <?=$OMO?> style="display:block;padding-left:5px;">Signups</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_ages.php' <?=$OMO?> style="display:block;padding-left:5px;">Age Breakdown</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_geo.php' <?=$OMO?> style="display:block;padding-left:5px;">Geo Breakdown</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/comic_stats.php' <?=$OMO?> style="display:block;padding-left:5px;">Comic Page Views</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_game_plays.php' <?=$OMO?> style="display:block;padding-left:5px;">Game Plays/Launches</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_created_comics.php' <?=$OMO?> style="display:block;padding-left:5px;">Comics Created</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_created_pages.php' <?=$OMO?> style="display:block;padding-left:5px;">Pages Created</A>
    <br>
    <br>
    <A HREF='http://<?=DOMAIN?>/admin/exec/clickstream/' <?=$OMO?> style="display:block;padding-left:5px;">ClickStream</A>
    <A HREF='http://<?=DOMAIN?>/admin/exec/exec_page_visits_summary.php' <?=$OMO?> style="display:block;padding-left:5px;">Page Visit Breakdown</A>

    <br><br>

    <DIV STYLE='width:200px;padding-left:15px;' ALIGN='LEFT'>
      <B>Nontest</B>
    </DIV>

    <A HREF='http://<?=DOMAIN?>/admin/nontest/' <?=$OMO?> style="display:block;padding-left:5px;">Rate Entries</A>
    <?
  }
  ?>
</DIV>
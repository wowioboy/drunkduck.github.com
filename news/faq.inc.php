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
		      $res = db_query("SELECT * FROM faq WHERE id='".(int)$_GET['id']."'");
		      $row = db_fetch_object($res);
		      db_free_result($res);
		      ?>
  		    <h2><?=$row->question?></h2>
          <?=bbcode($row->answer)?>
  		    <p>&nbsp; </p>
  		    <p>&nbsp; </p>
  		    <p>&nbsp; </p>
  		    <!--
  		    <h5>Other F.A.Q.s:</h5>
  		    <ol>
  		      <li><a href="#">F.A.Q. #2: What is my purpose?</a></li>
  		      <li><a href="#">F.A.Q. #3: Do you have any tea?</a></li>
		      </ol>
		      -->
		    </div>
	    </td>
    </tr>
  </table>
</div>
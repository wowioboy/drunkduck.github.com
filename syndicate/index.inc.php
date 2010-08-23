<?
include('syndication_func.inc.php');




?>
<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<h1 align="left">Syndication</h1>
<div class="gameContent">

  <div align="center">
    So you want to syndicate a Web Comic.<br>
    Just fill out the form below, and we will provide you with your own syndication code to paste into your website.
  </div>

  <p>&nbsp;</p>
  <p>&nbsp;</p>

  <form action="generate_code.php" method="post">

  <table border="0" cellpadding="15" cellspacing="0" width="600">

    <tr>
      <td align="right" width="200">
        <b>EMail Address:</b>
      </td>
      <td align="left">
        <input type="text" name="email" style="width:100%;">
      </td>
    </tr>

    <tr>
      <td align="right" width="200">
        <b>Choose a password:</b>
      </td>
      <td align="left">
        <input type="password" name="password" style="width:100%;">
      </td>
    </tr>

    <tr>
      <td align="right" width="200">
        <b>URL this strip will appear on:</b>
      </td>
      <td align="left">
        <input type="text" name="url" style="width:100%;">
      </td>
    </tr>

    <tr>
      <td colspan="2" align="left">
        <b>Strip to Syndicate:</b>
      </td>
    </tr>
    <tr>
      <td align="left" colspan="2">
        <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <?
        $STRIP_ARR = array(5505, 5941);

        $res = db_query("SELECT * FROM comics WHERE comic_id IN ('".implode("','", $STRIP_ARR)."')");
        while( $row = db_fetch_object($res) )
        {
          ?>
          <tr>
            <td align="center" valign="top" style="border-bottom:1px solid black;">
              <img src="<?=thumb_processor($row)?>" border="0" style="margin:5px;">
              <br>
              <input type="radio" name="comic_id" value="<?=$row->comic_id?>">
            </td>
            <td align="left" valign="top" style="border-bottom:1px solid black;">
              <?=$row->description?>
            </td>
          </tr>
          <?
        }
        ?>
        </table>
      </td>
    </tr>

  </table>

  <input type="submit" value="Generate Code">

  </form>

  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>

</div>
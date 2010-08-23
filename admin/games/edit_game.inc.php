<?
include('games_admin_header.inc.php');

$res = db_query("SELECT * FROM game_info WHERE game_id='".(int)$_GET['gid']."'");
if ( !$GAME_ROW = db_fetch_object($res) ) {
  return;
}

?>
<DIV STYLE='WIDTH:500px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Editing Game #<?=$GAME_ROW->game_id?><BR>"<?=$GAME_ROW->title?>"</DIV>
<?
echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='10' WIDTH='500'>";

echo "<FORM ACTION='submit_game_edit.php' METHOD='POST'>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Title</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='title' STYLE='width:100%;' MAXLENGTH='255' VALUE='".htmlentities($GAME_ROW->title, ENT_QUOTES)."'></TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%' VALIGN='TOP'><B>Description</B></TD>
        <TD ALIGN='LEFT'><TEXTAREA NAME='description' STYLE='width:100%;' ROWS='5'>".htmlentities($GAME_ROW->description, ENT_QUOTES)."</TEXTAREA></TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Difficulty</B></TD>
        <TD ALIGN='LEFT'>
          <SELECT NAME='difficulty' STYLE='width:85px;'>
            <OPTION VALUE='easy' ".(($GAME_ROW->difficulty=='easy')?"SELECTED":"").">Easy</OPTION>
            <OPTION VALUE='med' ".(($GAME_ROW->difficulty=='med')?"SELECTED":"").">Medium</OPTION>
            <OPTION VALUE='hard' ".(($GAME_ROW->difficulty=='hard')?"SELECTED":"").">Hard</OPTION>
          </SELECT>
        </TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Trophy Score:</B></TD>
        <TD ALIGN='LEFT'>
          <input type='text' name='score_to_beat' value='".$GAME_ROW->score_to_beat."' STYLE='width:100%;'>
        </TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Score Trophy ID:</B></TD>
        <TD ALIGN='LEFT'>
          <input type='text' name='score_trophy' value='".$GAME_ROW->trophy_beat_score."' STYLE='width:100%;'>
        </TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Weekly Winner Trophy ID:</B></TD>
        <TD ALIGN='LEFT'>
          <input type='text' name='weekly_winner_trophy' value='".$GAME_ROW->trophy_weekly_winner."' STYLE='width:100%;'>
        </TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER' STYLE='font-size:18px;'>
          <B>PHP Games:</B>
        </TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>PHP Page URL</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='php_game_url' VALUE='".$GAME_ROW->php_game_url."' STYLE='width:100%;'></TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER' STYLE='font-size:18px;'>
          <B>Flash Games:</B>
        </TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Filename</B></TD>
        <TD ALIGN='LEFT'>game_".$GAME_ROW->game_id."_v".$GAME_ROW->game_version.".swf</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>FPS</B></TD>
        <TD ALIGN='LEFT'>
          <SELECT NAME='fps' STYLE='width:85px;'>
            <OPTION VALUE='18' ".(($GAME_ROW->fps==18)?"SELECTED":"").">18</OPTION>
            <OPTION VALUE='24' ".(($GAME_ROW->fps==24)?"SELECTED":"").">24</OPTION>
            <OPTION VALUE='33' ".(($GAME_ROW->fps==33)?"SELECTED":"").">33</OPTION>
            <OPTION VALUE='99' ".(($GAME_ROW->fps==99)?"SELECTED":"").">99</OPTION>
          </SELECT>
        </TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Version</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='version' SIZE='10' VALUE='".$GAME_ROW->game_version."'></TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Width</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='width' SIZE='10' VALUE='".$GAME_ROW->width."'>px</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Height</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='height' SIZE='10' VALUE='".$GAME_ROW->height."'>px</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>1 point =</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='pts_to_tt' VALUE='".($GAME_ROW->pts_to_tokens)."' SIZE='10'>tt</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Live</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='CHECKBOX' NAME='is_live' ".(($GAME_ROW->is_live)?"CHECKED":"")."></TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Delete?</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='CHECKBOX' NAME='del' VALUE='".$GAME_ROW->game_id."'></TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='CENTER' COLSPAN='2'><INPUT TYPE='SUBMIT' VALUE='Submit Change!'></TD>
      </TR>
      <INPUT TYPE='HIDDEN' NAME='gid' VALUE='".$GAME_ROW->game_id."'>
      </FORM>";

echo "</TABLE>";
?>
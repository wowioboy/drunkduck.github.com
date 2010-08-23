<?
include('games_admin_header.inc.php');

?>
<DIV STYLE='WIDTH:500px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Add Game</DIV>
<?
echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='10' WIDTH='500'>";

echo "<FORM ACTION='submit_new_game.php' METHOD='POST'>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Title</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='title' STYLE='width:100%;' MAXLENGTH='255'></TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%' VALIGN='TOP'><B>Description</B></TD>
        <TD ALIGN='LEFT'><TEXTAREA NAME='description' STYLE='width:100%;' ROWS='5'></TEXTAREA></TD>
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
          <input type='text' name='score_to_beat' value='1000' STYLE='width:100%;'>
        </TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Score Trophy ID:</B></TD>
        <TD ALIGN='LEFT'>
          <input type='text' name='score_trophy' value='0' STYLE='width:100%;'>
        </TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Weekly Winner Trophy ID:</B></TD>
        <TD ALIGN='LEFT'>
          <input type='text' name='weekly_winner_trophy' value='0' STYLE='width:100%;'>
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
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='php_game_url' STYLE='width:100%;'></TD>
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
        <TD ALIGN='RIGHT' WIDTH='40%'><B>FPS</B></TD>
        <TD ALIGN='LEFT'>
          <SELECT NAME='fps' STYLE='width:85px;'>
            <OPTION VALUE='18'>18</OPTION>
            <OPTION VALUE='24'>24</OPTION>
            <OPTION VALUE='33' SELECTED>33</OPTION>
            <OPTION VALUE='99'>99</OPTION>
          </SELECT>
        </TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Version</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='version' SIZE='10' VALUE='1'></TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Width</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='width' SIZE='10' VALUE='600'>px</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Height</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='height' SIZE='10' VALUE='400'>px</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>1 point =</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='pts_to_tt' VALUE='1' SIZE='10'>tt</TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='40%'><B>Live</B></TD>
        <TD ALIGN='LEFT'><INPUT TYPE='CHECKBOX' NAME='is_live'></TD>
      </TR>

      <TR>
        <TD COLSPAN='2' ALIGN='CENTER'>&nbsp;</TD>
      </TR>

      <TR>
        <TD ALIGN='CENTER' COLSPAN='2'><INPUT TYPE='SUBMIT' VALUE='Submit Change!'></TD>
      </TR>
      <INPUT TYPE='HIDDEN' NAME='new' VALUE='1'>
      </FORM>";

echo "</TABLE>";
?>
<?
if ( $USER->age < 2 )
{
  if ( isset($_POST['born_month']) )
  {
    $DOB_TIMESTAMP = mktime(1, 1, 1, $_POST['born_month'], $_POST['born_day'], $_POST['born_year']);
    db_query("UPDATE demographics SET dob_timestamp='".$DOB_TIMESTAMP."' WHERE user_id='".$USER->user_id."'");
    if ( (int)timestampToYears($DOB_TIMESTAMP) > 12 )
    {
      $USER->flags = $USER->flags | FLAG_OVER_12;
    }

    db_query("UPDATE users SET age='".(int)timestampToYears($DOB_TIMESTAMP)."', flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
    header("Location: http://".DOMAIN);
    return;
  }
  ?>
  <br>
  <br>
  <br>
  Oops! Your birthday looks like it's wrong... you can't possibly be <?=$USER->age?> years old!
  <br>
  Please fill out the form below.
  <br>
  <br>
  <br>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <table border="0" cellpadding="5" cellspacing="0" width="300">
  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Birthday</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
        <SELECT NAME='born_month'>
          <?
          for ( $i=1; $i<13; $i++ ) {
            echo "<OPTION VALUE='".$i."'>".date("F", mktime(1,1,1,$i,1,1) )."</OPTION>";
          }
          ?>
        </SELECT><SELECT NAME='born_day'>
          <?
            for ($i=1; $i<32; $i++) {
              echo "<OPTION VALUE='".$i."'>".$i."</OPTION>";
            }
          ?>
        </SELECT><SELECT NAME='born_year'>
          <?
            for ($i=date("Y"); $i>=(date("Y")-100); $i--) {
              echo "<OPTION VALUE='".$i."'>".$i."</OPTION>";
            }
          ?>
        </SELECT>
    </TD>
  </TR>
  <tr>
    <td colspan="2" align="center"><input type="submit" value="Submit!"></td>
  </tr>
  </table>
  </form>

  <?
}
else
{
  header("Location: http://".DOMAIN);
}
?>
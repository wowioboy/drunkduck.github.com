<?
require_once('signup_data.inc.php');
require_once(PACKAGES.'/wordfilter_package/load.inc.php');

if ( $USER ) {
  echo "<DIV ALIGN='CENTER'>Oops! You are already logged in!</DIV>";
  return;
}
?>

<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='600'>
  <TR>
    <TD ALIGN='CENTER' VALIGN='MIDDLE' STYLE='BORDER-RIGHT:#000000 1px NONE;BORDER-TOP:#000000 1px NONE;BORDER-LEFT:#000000 1px NONE;BORDER-BOTTOM:#000000 1px NONE;'>
    <h1 style="color:#fff;"><b>Drunk Duck Registration Page</b></h1>
     <?php if (isset($_GET['restricted'])) : ?>
     <span style="color:#f00;font-size:16px;">This comic is rated "Adult." In order to view it, you must be registered, logged in, and over the age of 18.</span>
     <br />
     <?php endif; ?>
      <A HREF='signup_step_2.php?a=1'><IMG SRC='/images/under18.jpg' WIDTH='300' HEIGHT='300' alt="18 and under" BORDER='0'></A><A HREF='signup_step_2.php?a=2'><IMG SRC='/images/over18.jpg' WIDTH='300' HEIGHT='300' ALT='18 and over' BORDER='0'></A>
      
    </TD>
  </TR>
</TABLE>
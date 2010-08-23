<h1 align="left">Investor Information</h1>

<div class="content" style='color:white;padding-left:75px;padding-right:75px;' align='left'>

<?
  if ( !$_POST['email_addy'] )
  {
    ?>
      <h3>Investors</h3>
      Platinum Studios, Inc. intends to file a registration statement with the SEC to register certain shares of stock to allow for the resale of these shares.
      Once the registration has been declared effective, the Company plans to ask an NASD broker-dealer/market maker to file a Form 211 with the NASD so that its
      registered shares may be freely traded on the Over the Counter Bulletin Board market.

      <p>&nbsp;</p>

      To sign up for our Investor's newsletter, please enter your e-mail address below:

      <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <input type="text" name="email_addy" value=""><input type="submit" value="Send!">
      </form>
    <?
  }
  else
  {
    if ( strstr($_POST['email_addy'], '@') )
    {
      db_query("INSERT INTO investor_emails (email_addy) VALUES ('".db_escape_string($_POST['email_addy'])."')");
      ?>
      <div align="center">
        Thank you for your interest! Your email address has been inserted into our database for future reference.
      </div>
      <?
    }
    else
    {
      ?>
      <div align="center">
        There was a problem with your submssion. Please hit the back button on your browser and re-enter your email address.
      </div>
      <?
    }
  }
?>

</div>
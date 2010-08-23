<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<h1 align="left">Store</h1>
<div class="gameContent">

  <p>&nbsp;</p>

  <table border="0" cellpadding="10" cellspacing="0" width="100%">

    <tr>
      <td align="center" width="200">
        <img src="http://images2.drunkduck.com/process/user_1.gif" width="100" height="100"><br>
      </td>
      <td align="left" width="100%" valign="top">
        <div style="font-size:18px;font-weight:bold;">Platinum Status</div>
        <br>
        Platinum Status is a premium account membership that provides new features and access to DrunkDuck.
        <br>
        <div align="right">
          <table border="0" cellpadding="0" cellspacing="3" width="200">
            <tr>
              <td align="right">
                <strong>1 Month:</strong>
              </td>
              <td align="center">
                $5
              </td>
              <td align="center">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                  <input type="hidden" name="cmd" value="_s-xclick">
                  <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" style="border:0px;background:none;">
                  <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                  <input type="hidden" name="username" value="<?=$USER->username?>">
                  <input type="hidden" name="notify_url" value="http://<?=DOMAIN?>/store/api/paypal.php">
                  <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
                </form>
              </td>
            </tr>
            <tr>
              <td align="right">
                <strong>1 Year:</strong>
              </td>
              <td align="center">
                $30
              </td>
              <td align="center">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                  <input type="hidden" name="cmd" value="_s-xclick">
                  <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" style="border:0px;background:none;">
                  <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                  <input type="hidden" name="username" value="<?=$USER->username?>">
                  <input type="hidden" name="notify_url" value="http://<?=DOMAIN?>/store/api/paypal.php">
                  <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
                </form>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>

  </table>

  <p>&nbsp;</p>

</div>
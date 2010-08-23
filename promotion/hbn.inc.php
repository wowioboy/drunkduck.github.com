<h1 align="left">Locate Hero By Night</h1>

<div align="center" style="float:left;margin-left:50px;">
  <img src="<?=IMAGE_HOST?>/herobynight_thumb.jpg">
</div>

<div align="center" style="float:right;margin-right:50px;">
  <table cellpadding="0" cellspacing="7" border="0" align="center" width="300">
    <tr>
      <td colspan="2" align="center"><img src="<?=IMAGE_HOST?>/csls.gif"></td>
    </tr>
    <tr>
      <td colspan="2" align="center">Type in your Zip Code below:</td>
    </tr>
    <tr>
      <form method="POST" action="http://csls.diamondcomics.com/dc_csls.asp" target="CSLS" name="csls" onSubmit="return ValidateCSLS();">
      <td align="right" width="170"><input type="text" size="10" value="Zip Code" name="zip" style="width:90px;" onfocus="this.value='';">
      <td align="left" width="130"><input type="image" src="<?=IMAGE_HOST?>/csls_go.gif" value="Go"></td>
      </form>
    </tr>
  </table>
</div>
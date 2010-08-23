<table width="280" border="0" cellpadding="0" cellspacing="0" class="tellafriend">
  <tr>
    <td colspan="2" valign="top"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_hdr_tell-a-friend.png" width="280" height="25" class="headerimg" /></td>
  </tr>
  <tr>
    <td colspan="2" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
      <form id="form3" name="form3" method="post" action="http://<?=DOMAIN?>/tell_a_friend.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th valign="top" scope="col" class="tellafriend">
              <p><b>Don't be a quail! Share the Duck with a friend!</b></p>
              <p>
                <input name="name" type="text" value="Your Name" size="30" maxlength="100" onClick="this.value='';" />
                <input name="from_email" type="text" value="Your Address : email" size="30" maxlength="100" onClick="this.value='';" />
                <input name="to_email" type="text" value="Your Friend : email" size="30" maxlength="100" onClick="this.value='';" />
                <input type="submit" name="Submit" value="Send" />
              </p>
            </th>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
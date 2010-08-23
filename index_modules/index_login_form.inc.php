<FORM ACTION='http://<?=DOMAIN?>/index.php' METHOD='POST'>
  <div class="controlsbox">
    <img src="<?=IMAGE_HOST?>/site_gfx_new/DD_hdr_mytools.png" width="280" height="25" />
        <?
        if ( $_POST['un'] && $_POST['pw'] && !$USER ) {
          ?><div align='center' CLASS='microalert'>Invalid name/password</div><?
        }
        ?>
    <div class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);"><img src="<?=IMAGE_HOST?>/site_gfx_new/login_r1_c1.gif" width="60" height="20" />
      <INPUT TYPE='TEXT' NAME='un' VALUE='username' onFocus="this.value='';" STYLE='HEIGHT:20px;WIDTH:100px;'><br />
      <img src="<?=IMAGE_HOST?>/site_gfx_new/login_r2_c1.gif" width="60" height="20" />
      <INPUT TYPE='PASSWORD' NAME='pw' VALUE='password' onFocus="this.value='';" STYLE='HEIGHT:20px;WIDTH:100px;'>&nbsp;<INPUT TYPE='SUBMIT' VALUE='Login!' class="loginbutton"><br />
      <p><A HREF='http://<?=DOMAIN?>/forgot_password.php'>Forgot your password?</A> </p>
      <p>Not Registered? <A HREF='http://<?=DOMAIN?>/signup/'>Signup for a FREE Account!</A></p>
      <p>Registered users can comment on comics and create their own comics!</p>
    </div>
  </div>
</FORM>
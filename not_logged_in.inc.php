        <div class="rounded yellow span-14 right" style="float:right;padding:5px;text-transform:uppercase;font-family:helvetica;font-weight:bold;font-size:0.7em">
            
        </div>
        <div class="span-30 panel-body-right yellow">
          <div class="box-1">
              <?php
              if (isset($GLOBALS['loginError'])) {
                  echo '<h2 style="margin:0">login failed! ' . $GLOBALS['loginError'];
              } else {
                  ?><a href="/signup">
                    <img src="/media/images/join.jpg" />
                  </a>
                  <?php
              }
              ?></h2>
            
          </div>
        </div>
        <div class="span-28 box-1">
            <FORM ACTION='/' METHOD='POST'>
                <div class="span-11">
                    <div style="height:20px;line-height:20px;" class="drunk">or sign in here:</div>
                </div>
                <div class="span-17 left">
                    <INPUT class="span-17 rounded left teal" TYPE='TEXT' NAME='un' VALUE='username' onFocus="this.value='';" STYLE='font-weight:bold;padding:0 5px 0 5px;HEIGHT:20px;border:0px;text-align:left'>
                </div>
                
                <div class="span-28" style="height:10px;"></div>
                
                <div class="span-11">
                    <INPUT TYPE='SUBMIT' VALUE='sign in' class="button rounded left" style="font-size:12px;padding: 1px 15px 1px 15px;">
                </div>
                <div class="span-17">
                    <INPUT class="span-17 rounded left teal" TYPE='PASSWORD' NAME='pw' VALUE='password' onFocus="this.value='';" STYLE='font-weight:bold;padding:0 5px 0 5px;HEIGHT:20px;border:0px;'>
                </div>
                
                <div class="span-25 center" style="padding-top:10px;">                
                    <A HREF='/forgot_password.php'  class="span-25 button rounded left" style="display:block;margin:0;font-size:12px;padding: 1px 15px 1px 15px;">Forgot your username/password?</A>
                </div>
            </FORM>
        </div>






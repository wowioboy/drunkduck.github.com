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
        <div class="span-30" style="height:10px;"></div>
            <FORM ACTION='/' METHOD='POST'>
                <div class="span-12">
                    <div style="height:20px;font-size:14px;font-weight:bold;" class="teal">or sign-in here:</div>
                    <div class="span-12" style="height:10px;"></div>
                    <INPUT TYPE='SUBMIT' VALUE='sign in' class="button rounded">
                </div>
                
                <div class="span-15 ">
                <INPUT class="rounded" TYPE='TEXT' NAME='un' VALUE='username' onFocus="this.value='';" STYLE='padding:0 5px 0 5px;HEIGHT:20px;border:0px;'>
                <div class="span-12" style="height:10px;"></div>
                <INPUT class="rounded" TYPE='PASSWORD' NAME='pw' VALUE='password' onFocus="this.value='';" STYLE='padding:0 5px 0 5px;HEIGHT:20px;border:0px;'>
                </div>
                
                <div class="span-27 center" style="padding-top:10px;">                
                    <A HREF='/forgot_password.php'  class=" button rounded" style="width:100%;display:block;">Forgot your username/password?</A>
                </div>
            </FORM>







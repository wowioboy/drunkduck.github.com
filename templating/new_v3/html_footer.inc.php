                  </td>
                  <td width="300" height="100%" align="center" valign="top">

                    <div class="vert_spacer">&nbsp;</div>

                    <?
                    if ( !$USER )
                    {
                      ?>
                      <div style="border:1px solid #bbb;">
                        <img src="<?=IMAGE_HOST_SITE_GFX?>/mbrregister.gif" width="175" height="59" />
                        <br>Not Registered? <br /><a href="http://<?=DOMAIN?>/signup/">Signup for a FREE Account!</a><br>
                        <? /*<br><strong>Registered users can:</strong>
                        <br><strong> Comment on comics!</strong>
                        <br><strong>Create their own comics!</strong>
                        <br><strong>Vote in polls and contests!</strong>
                        <br><strong>Use the forums!</strong>*/?>
                        <a href="/signup/"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_register.gif" width="56" height="14" border="0"></a>
                      </div>
                      <?
                    }



                    if ( $USER )
                    {
                      ?>
                      <div style="height:97px;background:url(<?=IMAGE_HOST?>/site_gfx_new_v3/user_info_bg.gif);">
                        <div style="margin:10px;" align="left">

                          <div align="right">
                            <a href="http://<?=DOMAIN?>/?logout=<?=$USER->user_id?>">Log Out</a> | <a href="/community/view_category.php?cid=229">Help</a>
                          </div>

                          <div align="left">
                            <a href="http://<?=USER_DOMAIN?>/<?=$USER->username?>"><img src="<?=IMAGE_HOST?>/process/user_<?=$USER->user_id?>.<?=$USER->avatar_ext?>" height="50" width="50" border="0"></a>
                            Hi, <?=$USER->username?>
                          </div>

                          <div align="left">
                            <script language="JavaScript">
                              <?
                              if ( $USER->flags & FLAG_NEW_MAIL ) {
                                ?>alert('You have NEW mail!');<?
                                $USER->flags &= ~FLAG_NEW_MAIL;
                                db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
                              }
                              ?>
                            </script>
                            <a href="http://<?=DOMAIN?>/account/overview/">My Account</a> | <a href="http://<?=DOMAIN?>/community/message/inbox.php">Inbox (<?=$USER->pending_mail?>)</a>
                          </div>
                        </div>
                      </div>


                      <div class="module_header">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td align="center" valign="middle" width="33">
                              <a href="#" onClick="expandFavorites();return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif" style="padding-left:10px;" border="0" name="myFavorites"></a>
                            </td>
                            <td width="100%" class="side_expand_header">
                              &nbsp;&nbsp;
                              MyFavorites
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div id="favorites" style="display:none;" align="left">
                        No Content.
                      </div>


                      <div class="module_header">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td align="center" valign="middle" width="33">
                              <a href="#" onClick="expandMyComics();return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif" style="padding-left:10px;" border="0" name="myComics"></a>
                            </td>
                            <td width="100%" class="side_expand_header">
                              &nbsp;&nbsp;
                              MyComics
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div id="my_comics" style="display:none;" align="left">
                        No Content.
                      </div>
		      <?php
			}
		      ?>
		      <!-- News update section-->
                              <a href="http://twitter.com/DrunkDuck"><img src="<?=IMAGE_HOST?>/dd_twitter.png" border="0" name="DDonTwitter"></a>
                      <div class="module_header">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td align="center" valign="middle" width="33">
                              <a href="#" onClick="expandNewsUpdate();return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif" style="padding-left:10px;" border="0" name="myNewsUpdate"></a>
                            </td>
                            <td width="100%" class="side_expand_header">
                              &nbsp;&nbsp;
                              Latest News
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div id="my_news_update" style="display:none;" align="left">
                        No Content.
                      </div>
		      <!-- Open News update by default-->
		      <script language="javascript1.2">
		        expandNewsUpdate();
		      </script>

		    <!-- Disabled for now-->
		   <!--
                    <div class="module_header">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td align="center" valign="middle" width="33">
                            <a href="#" onClick="expandMyComicNew();return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif" style="padding-left:10px;" border="0" name="myComicsNew"></a>
                          </td>
                          <td width="100%" class="side_expand_header">
                            &nbsp;&nbsp;
                            MyComicNews
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div id="my_news" style="display:none;" align="left">
                      No Content.
                    </div>-->
<? /*

                    <div class="module_header">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td align="center" valign="middle" width="33">
                            <a href="#" onClick="expandDDCam();return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif" style="padding-left:10px;" border="0" name="DDCam"></a>
                          </td>
                          <td width="100%" class="side_expand_header">
                            &nbsp;&nbsp;
                            DD Cam (<font style="color:green;">Now Featuring: DDBook</font>)
                          </td>
                        </tr>
                      </table>
                    </div>
                    
                    <div id="ddcam" style="display:none;" align="left">
                      <embed src="http://player.stickam.com/stickamPlayer/174625835-2743920" type="application/x-shockwave-flash" width="300" height="300" scale="exactfit" allowScriptAccess="always" allowFullScreen="true"></embed>
                    </div>
*/?>
                    <div class="vert_spacer">&nbsp;</div>

                    <div style="width:300px;height:250px;border:1px solid black;background:#cccccc;">
<? /*
<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js">
</script>
<script type="text/javascript">
  GS_googleAddAdSenseService("ca-pub-0849196286564970");
  GS_googleEnableAllServices();
</script>
<!-- <script type="text/javascript">
  GA_googleAddSlot("ca-pub-0849196286564970", "300x250_DDMainEmbed");
</script> -->
<script type="text/javascript">
  GA_googleFetchAds();
</script>

<!-- 
<script type="text/javascript">
  GA_googleFillSlot("300x250_DDMainEmbed");
</script>
-->                 
<? /*
<!-- Beginning of Project Wonderful ad code: -->
<!-- Ad box ID: 47651 -->
<script type="text/javascript">
<!--
var pw_d=document;
pw_d.projectwonderful_adbox_id = "47651";
pw_d.projectwonderful_adbox_type = "7";
pw_d.projectwonderful_foreground_color = "";
pw_d.projectwonderful_background_color = "";
//-->
</script>
<script type="text/javascript" src="http://www.projectwonderful.com/ad_display.js"></script>
<noscript><map name="admap47651" id="admap47651"><area href="http://www.projectwonderful.com/out_nojs.php?r=0&amp;c=0&amp;id=47651&amp;type=7" shape="rect" coords="0,0,300,250" title="" alt="" target="_blank" /></map> */?> <? include(WWW_ROOT.'/ads/ad_includes/main_template/300x250.html'); ?>
 <? //include(WWW_ROOT.'/ads/ad_includes/main_template/300x250.html'); ?>

                    
                    </div>

                    <div class="vert_spacer">&nbsp;</div>

                   <!-- <a href="http://www.wowio.com/" target="_blank"><img src="http://www.wevolt.com/WEVOLT/ads/wowio_banner_300x250.jpg" border="0" /></a> -->
                   <a href="http://www.facebook.com/wowio?v=app_107611949261673"><img style="border:none;" src="/images/DD-Sherlock-rect.jpg" /></a>
<!--                   <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="300" height="250" id="utv592276" name="utv_n_481179">-->
<!--                     <param name="flashvars" value="beginPercent=0.0000&amp;endPercent=0.9869&amp;autoplay=false&locale=en_US" />-->
<!--                     <param name="allowfullscreen" value="true" />-->
<!--                     <param name="allowscriptaccess" value="always" />-->
<!--                     <param name="src" value="http://www.ustream.tv/flash/video/8753015" />-->
<!--                     <embed flashvars="beginPercent=0.0000&amp;endPercent=0.9869&amp;autoplay=false&locale=en_US" width="300" height="250" allowfullscreen="true" allowscriptaccess="always" id="utv592276" name="utv_n_481179" src="http://www.ustream.tv/flash/video/8753015" type="application/x-shockwave-flash" />-->
<!--                   </object>-->

                    <div class="vert_spacer">&nbsp;</div>
                  </td>
                </tr>

              </table>

              <div id="tipdiv" style="display:none;"></div>
              <!--END footer-->
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <div style="width:728px;margin-top:20px;" align="center" id="footer_div">
    <a href="http://<?=DOMAIN?>" style="font-size:14px;font-weight:bold;">home</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://<?=DOMAIN?>/search.php" style="font-size:14px;font-weight:bold;">search & browse</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://<?=DOMAIN?>/tutorials/" style="font-size:14px;font-weight:bold;">tutorials</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://<?=DOMAIN?>/games/" style="font-size:14px;font-weight:bold;">games</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://<?=DOMAIN?>/news/" style="font-size:14px;font-weight:bold;">news</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://<?=DOMAIN?>/community/" style="font-size:14px;font-weight:bold;">forums</a>

    <br><br>

    <a href="http://<?=DOMAIN?>/contact.php">About Us</a> | <a href="http://<?=DOMAIN?>/contact.php">Contact</a> | <a href="http://<?=DOMAIN?>/privacy.php">Privacy Policy</a> | Copyright 2010 WOWIO, Inc. All Rights Reserved

    <br><br>

    <? //include(WWW_ROOT.'/ads/ad_includes/main_template/728x90b.html'); ?>

  </div>

</div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-606793-4";
urchinTracker();
</script>
</body>
</html>

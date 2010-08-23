            </td>
            <td width="180" align="center" valign="top" id="user">
              <?include(WWW_ROOT.'/index_modules_v2/side_nav.inc.php');?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="middle" id="ftr">
              <!--footer-->
              <table>
                <tr>
                  <td align='center'>
                    <div id="navcontainer" align="center">
                      <ul id="navlist">
                        <li <?=( ($_SERVER['PHP_SELF']=='/index.php')?'id="active"':'')?>><a href="http://<?=DOMAIN?>/">home</a></li>
                        <li <?=( ($_SERVER['PHP_SELF']=='/search.php')?'id="active"':'')?>><a href="http://<?=DOMAIN?>/search.php">browse &amp; search</a></li>
                        <li <?=( ($_SERVER['PHP_SELF']=='/news.php')?'id="active"':'')?>><a href="http://<?=DOMAIN?>/news.php">news</a></li>
                        <li <?=( (strstr($_SERVER['PHP_SELF'],'/community/'))?'id="active"':'')?>><a href="http://<?=DOMAIN?>/community/">forums</a></li>
                        <li <?=( ($_SERVER['PHP_SELF']=='/store.php')?'id="active"':'')?>><a href="http://<?=DOMAIN?>/store.php">store</a></li>
                        <li <?=( (strstr($_SERVER['PHP_SELF'],'/account/overview/') && ($_SERVER['PHP_SELF']!='/account/overview/add_comic.php'))?'id="active"':'')?>><a href="http://<?=DOMAIN?>/account/overview/">my controls</a></li>
                        <li <?=( ($_SERVER['PHP_SELF']=='/account/overview/add_comic.php')?'id="active"':'')?>><a href="http://<?=DOMAIN?>/account/overview/add_comic.php">create a comic </a></li>
                        <li><a href="http://<?=DOMAIN?>/?logout=<?=rand(1,99999)?>">logout</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td align='center' valign='bottom'>
                    <div align="center" style="display:block;">
                      <p><a href="http://<?=DOMAIN?>/contact.php">About Us</a> | <a href="http://<?=DOMAIN?>/contact.php">Contact</a> | <a href="http://<?=DOMAIN?>/privacy.php">Privacy Policy</a> | Copyright &copy; 2010. WOWIO, Inc. All Rights Reserved.</p>
                  </div>
                </td>
              </tr>
            </table>
            <!--END footer-->
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-606793-4";
urchinTracker();
</script>
</body>
</html>
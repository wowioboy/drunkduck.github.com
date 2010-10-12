<?
include_once('../../../includes/global.inc.php');
ob_start();

$CAP_ID = 0;
if ( $COMIC_ROW ) {
  $CAP_ID = $COMIC_ROW->comic_caps_id;
}

?>
<script src="/__utm.js" type="text/javascript"></script>
<style type="text/css">
#dhtml_menu_div{
position:absolute;
border:1px solid black;
border-bottom-width: 0;
font:normal 12px Verdana;
line-height:18px;
z-index:100;
}

#dhtml_menu_div a{
width: 100%;
display: block;
text-indent: 3px;
border-bottom: 1px solid black;
padding: 1px 0;
text-decoration: none;
font-weight: bold;
}

#dhtml_menu_div a:hover{
  background-color: #2483c5;
}

#toolbar a:hover img {background:#FFFF00;}
#toolbar a:active img {background:#fff;}
#login {color:#ffffff; background:#06C; font-size:10px; font:Arial, Helvetica, sans-serif; padding:4px; line-height:1.5em;}
}
</style>


<?
if ( $USER )
{
  ?>
<script type="text/javascript">

/*Contents for menu 1*/
var menu1=new Array();

<?
$favCT = 0;
if ( $USER->flags&FLAG_FAVS_BY_DATE ) {
  $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_FAVS))."') ORDER BY last_update DESC");
}
else {
  $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_FAVS))."') ORDER BY comic_name ASC");
}
while ($row = db_fetch_object($res))
{
  $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
  $indicator = '';
  if  ( date("Ymd",$row->last_update) == YMD ) {
    $indicator = ' *';
  }
  ?>menu1[<?=($favCT++)?>] = '<a href="<?=$url?>" style="color:white;font-size:11px;"><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" align="left" border="0" /><?=$row->comic_name.$indicator?></a>';
  <?
}
db_free_result($res);
?>

var menuwidth         = '200px';    /* default menu width */
var menubgcolor       = '#6600cc';  /* menu bgcolor */
var disappeardelay    = 250;        /* menu disappear speed onMouseout (in miliseconds) */
var hidemenu_onclick  = "yes";      /* hide menu when user clicks within menu? */

/*No further editting needed*/

var ie4=document.all;
var ns6=document.getElementById&&!document.all;

if (ie4||ns6) {
  document.write('<div id="dhtml_menu_div" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>');
}

function getposOffset(what, offsettype){
  var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
  var parentEl=what.offsetParent;
  while (parentEl!=null){
    totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
    parentEl=parentEl.offsetParent;
  }
  return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
  if (ie4||ns6) {
    dhtml_menu_obj.style.left=dhtml_menu_obj.style.top="-500px";
  }
  if (menuwidth!=""){
    dhtml_menu_obj.widthobj=dhtml_menu_obj.style;
    dhtml_menu_obj.widthobj.width=menuwidth;
  }
  if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover") {
    obj.visibility=visible;
  }
  else if (e.type=="click") {
    obj.visibility=hidden;
  }
}

function iecompattest(){
  return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}

function clearbrowseredge(obj, whichedge)
{
  var edgeoffset=0;
  if (whichedge=="rightedge"){
    var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15;
    dhtml_menu_obj.contentmeasure=dhtml_menu_obj.offsetWidth;
    if (windowedge-dhtml_menu_obj.x < dhtml_menu_obj.contentmeasure) {
      edgeoffset=dhtml_menu_obj.contentmeasure-obj.offsetWidth;
    }
  }
  else{
    var topedge=ie4 && !window.opera? iecompattest().scrollTop : window.pageYOffset;
    var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18;
    dhtml_menu_obj.contentmeasure=dhtml_menu_obj.offsetHeight;
    if (windowedge-dhtml_menu_obj.y < dhtml_menu_obj.contentmeasure){ /*move up?*/
      edgeoffset=dhtml_menu_obj.contentmeasure+obj.offsetHeight;
      if ((dhtml_menu_obj.y-topedge)<dhtml_menu_obj.contentmeasure) { /*up no good either?*/
        edgeoffset=dhtml_menu_obj.y+obj.offsetHeight-topedge;
      }
    }
  }
  return edgeoffset;
}

function populatemenu(what){
  if (ie4||ns6) {
    dhtml_menu_obj.innerHTML=what.join("");
  }
}


function dropdownmenu(obj, e, menucontents, menuwidth){
  if (window.event) {
    event.cancelBubble=true;
  }
  else if (e.stopPropagation) {
    e.stopPropagation();
  }
  clearhidemenu();
  dhtml_menu_obj=document.getElementById? document.getElementById("dhtml_menu_div") : dhtml_menu_div;
  populatemenu(menucontents);

  if (ie4||ns6){
    showhide(dhtml_menu_obj.style, e, "visible", "hidden", menuwidth);
    dhtml_menu_obj.x=getposOffset(obj, "left");
    dhtml_menu_obj.y=getposOffset(obj, "top");
    dhtml_menu_obj.style.left=dhtml_menu_obj.x-clearbrowseredge(obj, "rightedge")+"px";
    dhtml_menu_obj.style.top=dhtml_menu_obj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px";
  }

  return clickreturnvalue();
}

function clickreturnvalue(){
  if (ie4||ns6) {
    return false;
  }
  else {
    return true;
  }
}

function contains_ns6(a, b) {
  while (b.parentNode) {
    if ((b = b.parentNode) == a) {
      return true;
    }
  }
  return false;
}

function dynamichide(e){
  if (ie4&&!dhtml_menu_obj.contains(e.toElement)) {
    delayhidemenu();
  }
  else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget)) {
    delayhidemenu();
  }
}

function hidemenu(e){
  if (typeof dhtml_menu_obj!="undefined"){
    if (ie4||ns6) {
      dhtml_menu_obj.style.visibility="hidden";
    }
  }
}

function delayhidemenu(){
  if (ie4||ns6) {
    delayhide=setTimeout("hidemenu()",disappeardelay);
  }
}

function clearhidemenu(){
  if (typeof delayhide!="undefined") {
    clearTimeout(delayhide);
  }
}

if (hidemenu_onclick=="yes") {
  document.onclick=hidemenu;
}

</script>
<?
}
?>
<SCRIPT LANGUAGE="JavaScript" SRC="/javascript/commonJS.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="/javascript/prototype-1.4.0_modified.js" TYPE="text/javascript"></SCRIPT>
<script language="JavaScript">
<?
if ( $COMIC_FAVS[$COMIC_ROW->comic_id] )
{
  ?>
  var bookmarkURL = 'http://<?=(DOMAIN.$_SERVER['PHP_SELF']."?p=".$COMIC_FAVS[$COMIC_ROW->comic_id]->bookmark_page_id )?>';
  <?
}
else
{
  ?>
  var bookmarkURL = '';
  <?
}
?>


function addBookmark()
{
  ajaxCall('/xmlhttp/addBookmark.php?cid=<?=$COMIC_ROW->comic_id?>&p=<?=$PAGE_ROW->page_id?>', onAddBookmark);
}

function onAddBookmark( resp )
{
  alert(resp);
  var bookmarkURL = 'http://<?=(DOMAIN.$_SERVER['PHP_SELF']."?p=".$PAGE_ROW->page_id )?>';
}

function goBookmark() {
  if ( bookmarkURL.length != 0 ) {
    window.open(bookmarkURL, "_top");
  }
}

function addFav() {
  if ( !confirm('Are you sure you want to add this comic as a favorite?') ) return;
  ajaxCall('/xmlhttp/addFav.php?fav=<?=$COMIC_ROW->comic_id?>&p=<?=$PAGE_ROW->page_id?>&r='+Math.floor(Math.random()*999999), onFavRes);
}
function onFavRes(resp) {
  if ( Number(resp) == 0 ) {
    alert('It is already a favorite!');
    return;
  }
  menu1.push(resp);
  alert('Favorite Added!');
}

var l_cid;
var l_pid;

function addTag(tagTxt, cid, pid)
{
  tagTxt = encodeURIComponent(tagTxt);
  l_cid = cid;
  l_pid = pid;
  cid    = encodeURIComponent(cid);
  pid    = encodeURIComponent(pid);
  ajaxCall('/xmlhttp/addTag.php?tag='+tagTxt+'&cid='+cid+'&pid='+pid+'&r='+Math.floor(Math.random()*999999), onTagRes);
}

function onTagRes(resp)
{
  getTags(l_cid, l_pid);
  if ( resp.length > 0 ) alert(resp);
}

function forceEmailNotify()
{
  ajaxCall('/xmlhttp/favEmailUpdate.php?cid=<?=$COMIC_ROW->comic_id?>&r='+Math.floor(Math.random()*999999), onEmailResp);
}
function onEmailResp(resp) {
  alert(resp);
}
</script><?

// compact it all to mostly unreadable.
$SO_FAR = ob_get_clean();
$SO_FAR = str_replace("\n", "", $SO_FAR);
while(strstr($SO_FAR, "  ")) $SO_FAR = str_replace("  ", " ", $SO_FAR);

// send it back to the output buffer...
ob_start();
echo $SO_FAR;

function capRO($imgPrefix) {
  global $COMIC_ROW;
  return "onMouseOver=\"this.src='".IMAGE_HOST."/site_gfx_new/comic_caps/".$COMIC_ROW->comic_caps_id."/".$imgPrefix."_f2.gif';\" onMouseOut=\"this.src='".IMAGE_HOST."/site_gfx_new/comic_caps/".$COMIC_ROW->comic_caps_id."/".$imgPrefix.".gif';\"";
}

?><div align="center"><?
if ( !$USER )
{
?>
<table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0" class="toolbar" style="padding: 0pt; margin-left: auto; margin-right: auto; margin-top: 0pt;">
  <form action="<?=$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" method="POST">
  <tr>
    <td colspan="4" align="center" background="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/bg_<?=$CAP_ID?>.png"><table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="173" height="60"><a href="http://www.drunkduck.com"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/left.png" width="179" height="60" border="0"></a></td>
          <td width="468" align="center" bgcolor="#CCCCCC">
</td>
          <td width="14" height="60" align="right"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/sponsor.png" width="14" height="60"></td>
          <td width="139" height="60" align="right" id="login"><div align="center"><strong>DrunkDuck users login:</strong><br>
            <div align="center"><input onFocus="this.value='';" name="un" style="border: 1px solid rgb(0, 0, 0); background-color: rgb(255, 204, 0); height: 16px; font-size: 9px; color: rgb(0, 0, 0); font-family: Arial,Helvetica,sans-serif;" value="username" size="10" type="text">&nbsp;<input onFocus="this.value='';" name="pw" style="border: 1px solid rgb(0, 0, 0); background-color: rgb(255, 204, 0); height: 16px; font-size: 9px; color: rgb(0, 0, 0); font-family: Arial,Helvetica,sans-serif;" value="password" size="10" type="password"></div>
            <input src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/0/small_go_button.png" type="image" align="middle" border="0" height="14" width="22"></div></td>
        </tr>
        <tr>
          <td colspan="4"><table width="800" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/left_bot_<?=$CAP_ID?>.png" width="297" height="40" border="0" usemap="#MainNav"></td>
                <td width="503" align="right" background="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/right_bot_<?=$CAP_ID?>.png"><a href="http://www.drunkduck.com/signup/"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/member_ad.gif" width="503" height="40" border="0"></a></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  </form>
</table>
<?
}
else
{
?>
<table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0" class="toolbar" style="padding: 0pt; margin-left: auto; margin-right: auto; margin-top: 0pt;">
  <tr>
    <td colspan="4" align="center" background="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/bg_<?=$CAP_ID?>.png">
      <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="173" height="60"><a href="http://www.drunkduck.com"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/left.png" width="179" height="60" border="0"></a></td>
          <td width="468" align="center" bgcolor="#CCCCCC">
</td>
          <td width="14" height="60" align="right"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/sponsor.png" width="14" height="60"></td>
          <td width="139" height="60" align="right"><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>&logout=1"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/logout.png" width="139" height="60" border="0"></a></td>
        </tr>
        <tr>
          <td colspan="4">
            <table width="800" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/left_bot_<?=$CAP_ID?>.png" width="297" height="40" border="0" usemap="#MainNav"></td>
                <td width="503" align="right" background="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/right_bot_<?=$CAP_ID?>.png">
                  <table width="100%" height="40" border="0" cellpadding="1" cellspacing="0" id="toolbar">
                    <tr>
                      <td width="17" align="center">&nbsp;</td>
                      <td align="center" valign="bottom"><a href="#"><a href="http://<?=DOMAIN?>/account/overview/"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_mc.gif" width="82" height="16" border="0" title="goto your account page"></a>&nbsp;<a href="#" onClick="return clickreturnvalue()" onMouseover="dropdownmenu(this, event, menu1, '200px')" onMouseout="delayhidemenu()"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_fave.gif" width="65" height="16" border="0"></a>&nbsp;<a href="http://<?=DOMAIN?>/community/message/inbox.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_pq.gif" width="47" height="16" border="0" title="goto your PQ center"></a></td>
                      <td width="106" align="center" valign="bottom"><?
                      if ( !$COMIC_FAVS[$COMIC_ROW->comic_id] ) {
                        ?><a href="JavaScript:var x=addFav();"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_addfave.gif" width="45" height="16" border="0" title="add this comic to your favorites list"></a><?
                      }
                      else {
                        ?><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_faved.gif" width="45" height="16" border="0" title="This is a favorite!"><?
                      }
                      ?><a href="#" onClick="forceEmailNotify();return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_faveemail.gif" width="19" height="16" border="0" title="get e-mail alerts for this comic"></a>&nbsp;<a href="http://<?=DOMAIN?>/rss/rss.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_rss.gif" width="30" height="16" border="0" title="get the RSS feed for this comic"></a></td>
                      <td width="90" align="center" valign="bottom"><a href="#" onClick="addBookmark();return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_bkmrk.gif" width="33" height="16" border="0" title="Set bookmark for this comic"></a>&nbsp;<a href="#" onClick="goBookmark();return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_bkmrkgo.gif" width="30" height="16" border="0" title="Go to your bookmark for this comic"></a></td>
                      <td width="34" align="center" valign="bottom"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$COMIC_ROW->rating_symbol?>_lg.gif" title="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" alt="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" width="32" height="32"></td>
                      <td width="20" align="center">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
}
?>
<map name="MainNav">
  <area shape="rect" coords="203,20,237,35" href="http://www.drunkduck.com/store.php">
  <area shape="rect" coords="150,20,194,35" href="http://www.drunkduck.com/community/">
  <area shape="rect" coords="14,20,97,37" href="http://www.drunkduck.com/search.php">
  <area shape="rect" coords="104,20,143,35" href="http://www.drunkduck.com/news/">
</map>
<map name="Login">
  <area shape="rect" coords="78,2,136,14" href="#">
</map>
</div>
<!--HDR-->





<?
return ob_get_clean();
?>
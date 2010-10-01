<?
include_once('../../../includes/global.inc.php');
ob_start();

$CAP_ID = 0;
if ( $COMIC_ROW ) {
  $CAP_ID = $COMIC_ROW->comic_caps_id;
}

?>

<!-- PUT THIS TAG IN THE head SECTION -->

<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js">
</script>
<script type="text/javascript">
  GS_googleAddAdSenseService("ca-pub-0849196286564970");
  GS_googleEnableAllServices();
</script>
<script type="text/javascript">
  GA_googleAddSlot("ca-pub-0849196286564970", "160x600DDMainSkyscraper");
  GA_googleAddSlot("ca-pub-0849196286564970", "300x250DDMainBox");
  GA_googleAddSlot("ca-pub-0849196286564970", "468x60DDComicPage");
  GA_googleAddSlot("ca-pub-0849196286564970", "728x90DDComicPageBottom");
  GA_googleAddSlot("ca-pub-0849196286564970", "728x90DDComicPageTop");
  GA_googleAddSlot("ca-pub-0849196286564970", "728x90DDMainTemplateBottom");
  GA_googleAddSlot("ca-pub-0849196286564970", "728x90DDMainTemplateTop");
</script>
<script type="text/javascript">
  GA_googleFetchAds();
</script>

<!-- BLUEPRINT -->
<link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="/css/blueprint/gutterless.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">    
<!--[if IE]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

<!-- JQUERY -->
<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>

<!-- JQUERY UI (JQUERY) -->
<link href="/css/jquery/start/jquery-ui-1.8.5.custom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.5.custom.min.js"></script>

<!-- CYCLE (JQUERY) -->
<script type="text/javascript" src="/js/jquery/cycle/jquery.cycle.all.js"></script>

<!-- SELECT MENU (JQUERY, JQUERY UI) -->
<link href="/css/jquery/ui.selectmenu.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/js/jquery/ui.selectmenu.js"></script>

<!-- FANCY BOX (JQUERY) --> 
<link type="text/css" rel="stylesheet" href="/js/jquery/fancybox/jquery.fancybox-1.3.1.css" />
<script type="text/javascript" src="/js/jquery/fancybox/jquery.fancybox-1.3.1.pack.js"></script>

<!-- JQUERY FORM (JQUERY) -->
<script type="text/javascript" src="/js/jquery/form.js"></script>

<!-- GOOGLE FONT -->
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet' type="text/css">

<link rel="stylesheet" href="/css/custom.css" type="text/css" media="screen, projection, print">
<link href='/css/layout.css' rel='stylesheet' type="text/css">
<link href='/css/global.css' rel='stylesheet' type="text/css">

<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/commonJS.js" TYPE="text/javascript"></SCRIPT>

<script language="javascript" type="text/javascript">
    $j = jQuery.noConflict();
</script>
<!-- <SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/prototype-1.4.0_modified.js" TYPE="text/javascript"></SCRIPT> -->
<!-- END OF TAG FOR head SECTION -->

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




<style>
table, th, td, tr {
    width:auto;
    }
th, td {
padding:0;
}

body {
    margin:0;
    padding:0;
    }

#menu {
    position:static;
    clear:both;
    }
#menu a {
    float:right;
    margin-bottom:20px;
    clear:both;
    padding: 0;
    width:160px;
    text-align:right;
    }
#advsearch {
    float:right;
    padding-bottom:30px;
    padding-right:5px;
    }
#advsearch a {
    margin:0;
    padding:2px;
    }
#myPanel {
    padding-left:10px;
    }         
</style>

<script>
jQuery(document).ready(function(){
    jQuery('#controlTab').click(function(){
      var node = $('#myPanel');
      if (node.css('display') == 'none') {
        node.slideDown();
      } else { 
        node.slideUp();
      }
    });
});
</script>

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


function okayComic() {
  ajaxCall('/xmlhttp/verifyComicRating.php?cid=<?=$COMIC_ROW->comic_id?>', onOkayComic);
}

function onOkayComic(resp) {
  alert('Verified!');
}

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


?>
<div style="height:100px;position:absolute;top:0;left:0;width:100%;z-index:-5000" class="green"></div>
<div class="container-main" style="min-width:960px;">
    <div class="span-16 green box-1" style="box-shadow: 0px 0 6px rgb(0,0,0);min-height:100%">
    <div>
        <img src="/media/images/drunkduck-logo.png" width="160" />
    </div>
    
    <div style="position:relative;width:160px;">
        <div id="menu"> 
            <?php require_once(WWW_ROOT . '/navi_v2.php'); ?>
            </div>     
        <a href="#" id="controlTab" style="font: bold 1.2em arial;float:right;color:yellow;width:160px;text-align:right">control panel</a>

        <div id="myPanel" style="display:none;margin-bottom:-170px;position:relative;left:160px;bottom:170px" class="span-30 green rounded">

            <script>
          jQuery(document).ready(function(){
            var user_id = '';

            function getWebcomics()
            {
              var object = {};
              object.user_id = user_id;
              object.sort = $('select.webcomics').val();
              $.getJSON('/ajax/webcomics.php', object, function(data) {
                  var html = '';
                  $.each(data, function(){
                    var date = this.updated_on;
                    var title = this.title;
                    html += '<a href="http://www.drunkduck.com/' + title.replace(/ /g, '_') + '">' + title + '</a>' + ' ' + '<a href="http://www.drunkduck.com/account/comic/?cid=' + this.comic_id + '">edit</a>' + ' ' + date + '<br />';
                  });
                  $('div.webcomics_display').html(html);
                });
            }
            
            function getFavorites() 
            {
              var object = {};
              object.user_id = user_id;
              object.sort = $('select.favorites').val();
              $.getJSON('/ajax/favorites.php', object, function(data) {
                  var html = '';
                  $.each(data, function(){
                    var date = this.updated_on;
                    var title = this.title;
                    html += '<a href="http://www.drunkduck.com/' + title.replace(/ /g, '_') + '">' + title + '</a>' + ' ' + date + '<br />'; 
                  });
                  $('div.favorites_display').html(html);
               });
            }

            $('a.favorties').click(function(){
              var favoritesDiv = $('div.favorites');
              if (favoritesDiv.css('display') == 'none') {
                $(this).html('collapse');
                getFavorites();
                  favoritesDiv.slideDown();
              } else { 
                $(this).html('expand');
                favoritesDiv.slideUp();
              }
            });

            $('a.webcomics').click(function(){
              var webcomicsDiv = $('div.webcomics');
              if (webcomicsDiv.css('display') == 'none') {
                $(this).html('collapse');
                  getWebcomics();
                  webcomicsDiv.slideDown();
              } else { 
                $(this).html('expand');
                webcomicsDiv.slideUp();
              }
            });
            
            $('select.favorites').change(function(){
              getFavorites();
            });
            
           $('select.webcomics').change(function(){
              getWebcomics();
            });
          });
          </script>
        <div class="panel-header " style="float:right;padding:5px;text-transform:uppercase;font-family:helvetica;font-weight:bold;font-size:0.7em">
            <a href="?logout=">log out</a> | <a href="">help</a>
        </div>

        <div class="span-30 panel-body-right ">
          <div class="box-1">
            <div class="span-8">
                <div style="width:65px;height:65px;background-color:#FFF">
                </div>
            </div>
            <div class="span-20" style="font-family:'Yanone Kaffeesatz';font-weight:bold;line-height:30px;margin-top:-5px;margin-bottom:5px;font-size:30px;color:rgb(69,180,185);">Hi, !</div>
            <div class="span-20" style="font-size:10px;">User Control Panel</div>
            <div class="span-20" style="font-size:10px;">Personal Quacks</div>
            <div style="clear:both;height:10px"></div>

          <div class="drop-list rounded ">
            my favorites  <a class="favorties" href="javascript:">expand</a>
            <div class="favorites" style="display:none;">
            <select class="favorites">
              <option value="">sort by</option>
              <option value="alpha">alphabetically</option>
              <option value="update">last update</option>
            </select>
              <div class="favorites_display">
                here are the favorites
              </div>
            </div>
          </div>
          <div style="display:block;height:10px;"></div>

          <div class="drop-list rounded ">
            my webcomics  <a class="webcomics" href="javascript:">expand</a>
            <div class="webcomics" style="display:none;">
            <select class="webcomics">
              <option value="">sort by</option>
              <option value="alpha">alphabetically</option>
              <option value="update">last update</option>
            </select>
              <div class="webcomics_display">
                here are the webcomics
              </div>
            </div>
          </div>
          </div>
        </div>
            </div>
       

    </div>
    <div class="span-16">
        ad
    </div>
    </div>
    <div class="green span-73" style="padding-bottom:10px;">
        <div style="height:90px;width:728px;background-color:white;"  class="span-73">
            <?
                if ($COMIC_ROW->rating_symbol == 'E' || $COMIC_ROW->rating_symbol == 'T') {
                    // ad include for safe-rated comics
                    include(WWW_ROOT.'/ads/ad_includes/comic_template/728x90_et.html');
                } else {
                    // ad include for adult/mature/unknown rated comics
                    include(WWW_ROOT.'/ads/ad_includes/comic_template/728x90.html');
                }
            ?>        
        </div>

    </div>

    

    <div id="mainContent" style="margin-left:180px;">
    <div style="height:100px;">


<?php if ( !$USER ) { ?>
<!-- wakka -->

<?php } else { ?>
        <div style="text-align:center">
                  <table width="100%" height="40" border="0" cellpadding="1" cellspacing="0" id="toolbar" style="background-color:#333;padding:10px" class="rounded">
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
                      <td width="34" align="center" valign="bottom"><?

                      if ( $USER->flags & FLAG_IS_ADMIN ) {
                        ?><a href="#" onClick="okayComic();return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$COMIC_ROW->rating_symbol?>_lg.gif" title="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" alt="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" width="32" height="32" border="0"></a><?
                      }
                      else {
                        ?><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$COMIC_ROW->rating_symbol?>_lg.gif" title="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" alt="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" width="32" height="32"><?
                      }

                      ?></td>
                      <td width="20" align="center">&nbsp;</td>
                    </tr>
                  </table>
            </div>
<? } ?>


</div>
<!--HDR-->


<?
return ob_get_clean();
?>

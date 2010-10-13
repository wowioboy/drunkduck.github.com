<?
include_once('../../../includes/global.inc.php');
ob_start();

$CAP_ID = 0;
if ( $COMIC_ROW ) {
  $CAP_ID = $COMIC_ROW->comic_caps_id;
}

?>

<!-- PUT THIS TAG IN THE head SECTION -->

<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:bold' rel='stylesheet' type="text/css">

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
<style>
    <style>
    body {
        background-color:rgb(9,153,68);
    background-image:url('/media/images/bg-gradient-color3.jpg');
    background-repeat: repeat-x;
    background-position:center top;
    background-attachment:fixed;
    }
</style>
<!-- END OF TAG FOR head SECTION -->
<script src="/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery.noConflict();
});
</script>

<script src="/__utm.js" type="text/javascript"></script>

<div style="-moz-box-shadow: 0px 1px 5px rgba(0,0,0,0.5);-webkit-box-shadow: 0px 1px 5px rgba(0,0,0,0.5);box-shadow: 0px 1px 5px rgba(0,0,0,0.5);width:100%;position:absolute;top:90px;height:30px;z-index:-2000;background-color:rgb(179,179,179);"></div>

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

<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/commonJS.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/prototype-1.4.0_modified.js" TYPE="text/javascript"></SCRIPT>
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


?><div align="center">


<div class="" style="width:908px;padding:0;margin:0;position:relative;padding-bottom:5px;">
    <div style="padding:0;margin:0;float:left;width:180px;padding-top:9px;">
        <a href="/">
            <img src="/media/images/DD-site-logo-for-comics-page.png" style="border:0;" />
        </a>
    </div>
    
    <style>

#menu {
    vertical-align:middle;
    height:20px;
    position:absolute;
    bottom:15px;
    right:0px;


}
#menu a {
  text-decoration:none;
  color:rgb(0,133,118);
  font-family:'Yanone Kaffeesatz';
  font-weight:bold;
  font-size:1.8em;
  line-height:12px;
  padding: 0 15px 0px 15px;
  
  vertical-align:middle;
  height:10px;
  float:left;
}
#dd-navigation {
    width:154px;
    height:20px;
}
#dd-navgation input{
outline:none;
border:0;
}
#menu {
    position:static;
    clear:both;
    }
#menu a {
    float:left;
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
    <div style="float:left;width:728px;padding:0;margin:0;">
    <?
        if ($COMIC_ROW->rating_symbol == 'E' || $COMIC_ROW->rating_symbol == 'T')
        {
            // ad include for safe-rated comics
            include(WWW_ROOT.'/ads/ad_includes/comic_template/728x90_et.html');
        }
        else
        {
            // ad include for adult/mature/unknown rated comics
            include(WWW_ROOT.'/ads/ad_includes/comic_template/728x90.html');
        }
    ?>
    </div>
    
    <div class="menu" style="width:720px;vertical-align:middle;height:30px;position:absolute;top:95px;right:0px;">
          
            <?php if (!$nosearch) : ?>
            <div style="float:left;display:inline;background-image:url('/media/images/search.png');width:154px;height:20px" class="span-16">
             <link type="text/css" rel="stylesheet" href="/css/search.css" />
              <script type="text/javascript" src="/js/search.js"></script>
                <form id="dd-navigation" action="/search.php" method="get" style="padding:0px;height:20px;border:0px;vertical-align:top;position:relative;left:-2px;">
                    <input type="text" id="searchTxt"  autocomplete="off" name="searchTxt" class="searchbox" style="position:absolute;top:0px;left:10px;margin:0;height:20px;border:0px;width:120px;outline:none;background:none;" />
                    <input type="image" style="background:none;outline:none;padding:0;border:0;position:absolute;top:0px;right:0px;" src="/media/images/search-placeholder.gif" class="searchbox_submit" />
                </form>
                <div id="search_results"></div>
            </div>

            <!-- Advanced Search Link--> 
            <!--<div id="advsearch" style="height:20px;padding-left:5px;line-height:5px;margin-top:-2px"> 
                <a href="/search.php" style="">Advanced Search</a> 
            </div>--> 
                <?php endif; ?>
            
            <!-- menu -->
            <a href="/search.php" style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;">comics</a>
            <a href="/account/overview/add_comic.php" style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;">create</a>
            <a href="/news_v2.php" style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;">news</a>
            <a href="/tutorials/"  style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;">tutorials</a>
            <!--<a href="#">videos</a>-->
            <a href="/community" style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;">forums</a>
            <a href="http://store.drunkduck.com"  style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;">store</a>
            
            <div style="display:inline-block;position:relative;">
            <a href="#"  style="padding: 0 15px 0 15px;text-decoration:none;color:white;font:bold 20px/15px 'Yanone Kaffeesatz';height:10px;float:left;" onclick="jQuery('#controlPanel').slideToggle();return false;">my control panel</a>
            <style>
                #controlPanel a, #controlPanel a:visited {
                    font-weight:bold;
                    color:#333;
                    text-decoration:none;
                    }
                #controlPanel a:hover {
                    color:#EEE;
                    }
                #controlPanel .table {
                    display:table;
                    width:100%;
                    }
                #controlPanel .cell {
                    display:table-cell;
                    }
                #controlPanel .row {
                    display:table-row;
                    }
                #controlPanel .drop-list {
                    font:bold 10px/10px helvetica;
                    border-radius: 10px;
                    background-color: white;
                    color: #333;
                    padding: 5px;
                    }
            </style>
            <div id="controlPanel" style="background-color:rgb(179,179,179);text-align:left;z-index:3000;position:absolute;display:none;top:20px;right:0;width:264px;padding:10px;border-radius: 0 0 10px 10px;">
                <?php require(WWW_ROOT . '/auth.php'); ?>
            </div>
            </div>
    </div>
</div>
<style>
.toolbar a, .toolbar a:visited, .toolbar a:visited {
    color: #333;
    text-decoration: none;
    font:bold 12px/20px Helvetica;
    border:0;
    background-color:none;
    }
.toolbar a:hover {
    color:#2483C5;
    background:none;
    }
.toolbar .menu-linkage {
    padding: 0 15px 0 15px;
}        
</style>

<?
if ( $USER )
{
?>


<div class="toolbar" style="clear:both;opacity:0.8;font:bold 12px/20px Helvetica;vertical-align:50%;color:#333;width:728px;background-color:#bbb;border-radius: 0 0 10px 10px;">
    
    <span class="menu-linkage">
<?php if ( !$COMIC_FAVS[$COMIC_ROW->comic_id] ) { ?>
    <a href="JavaScript:var x=addFav();">add to favorites</a>    
<?php } else { ?>
    <img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/tb_faved.gif" width="45" height="16" border="0" title="This is a favorite!">
<?php } ?>
    </span>
    
    <span class="menu-linkage">
    <a href="#" onClick="forceEmailNotify();return false;">
        email alerts
    </a>
    </span>
    
    <span class="menu-linkage">
    <a href="http://<?=DOMAIN?>/rss/rss.php?cid=<?=$COMIC_ROW->comic_id?>">rss</a>
    </span>
    
    <span class="menu-linkage">
    duckmark: 
    <a href="#" onClick="addBookmark();return false;">set</a>
    <a href="#" onClick="goBookmark();return false;">go</a>
    </span>
    
    <div class="menu-linkage" style="display:inline-block;position:relative;">
    <a href="#" onClick="jQuery('#shareComic').slideToggle(); return false;">share comic</a>
    <div id="shareComic" style="display:none;text-align:left;position:absolute;left:10px;font:bold 12px/20px Helvetica;color:#333;background-color:#bbb;padding:10px;border-radius: 0 0 10px 10px;">
    <ul style="list-style-type: none; margin: 0; -webkit-padding-start: 0px;">
        <li><a href="http://digg.com/submit?phase=2&url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Digg this comic!" title="Digg this comic!" >Digg</a></li>
        <li><a href="http://del.icio.us/post?url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Make this comic Del.icio.us!" title="Make this comic Del.icio.us!" >Del.icio.us</a></li>
        <li><a href="http://reddit.com/submit?url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Reddit this comic!" title="Reddit this comic!" />Reddit</a></li>
    </ul>    
    </div>
    </div>
    
    <div class="menu-linkage" style="display:inline-block;position:relative;">
    <a href="#" onClick="getTags('<?=$COMIC_ROW->comic_id?>', '<?=$PAGE_ROW->page_id?>');return false;">tag page</a>
    <div id="tagContent" style="display:none;text-align:left;position:absolute;right:0;font:bold 12px/20px Helvetica;color:#333;background-color:#bbb;padding:10px;border-radius: 0 0 10px 10px;">
        <form onSubmit="addTag(jQuery('#tagArea').val(), '<?=$COMIC_ROW->comic_id?>', <?=$PAGE_ROW->page_id?>);return false;">
        
        <textarea id="tagArea" style="height:150px" >
        </textarea>
        <input type="submit" name="taggit" value="save tags!" />
        </form>
    </div>
    </div>
    
    <?php if ( $USER->flags & FLAG_IS_ADMIN ) { ?>
        <a href="#" onClick="okayComic();return false;">
            <img src="/ratings/<?=$COMIC_ROW->rating_symbol?>.png" title="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" alt="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" border="0">
        </a>
    <? } else { ?>
        <img src="/ratings/<?=$COMIC_ROW->rating_symbol?>.png" title="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>" alt="<?=$RATINGS[$COMIC_ROW->rating_symbol]?>">
    <? } ?>

</div>





<?php } ?>

<div style="clear:both;height:1px;"></div>

<!--HDR-->

<?
return ob_get_clean();
?>

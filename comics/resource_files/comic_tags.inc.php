<?
ob_start();
?>
<style type="text/css">
#tag_div {
 position: absolute;
 color: ##001C70;
 background:white;
 border: 3px solid #ABCDEF;
 padding: 3px;
 font-family: Verdana;
 font-size: 11px;
 visibility: hidden;
 z-index: 100;
}
</style>
<script>
var tagForm = '<form onSubmit="addTag(this.tagTXT.value, \'<?=$COMIC_ROW->comic_id?>\', <?=$PAGE_ROW->page_id?>);this.tagTXT.value=\'\';return false;"><input id="tag" name="tagTXT" type="text" size="45"></form></div>';


var tags_recvd = false;
function getTags(cid, pid)
{
  cid    = encodeURIComponent(cid);
  pid    = encodeURIComponent(pid);
  ajaxCall('/xmlhttp/getTags.php?cid='+cid+'&pid='+pid+'&r='+Math.floor(Math.random()*999999), onGetTags);
  return false;
}

function onGetTags(resp)
{
  var html = '<div align="right"><a href="#" onClick="hideTags(); return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new/remove_button.gif" border="0"></a></div>' + resp + tagForm;
  jQuery('#tag_div').html();
  showTags();
  jQuery('#tagTXT').focus();
}

function hideTags() {
  jQuery('#tag_div').css('visibility', 'hidden');
}
function showTags() {
  jQuery('#tag_div').css('visibility', 'visible');
  var winW = 630;
  if (parseInt(navigator.appVersion)>3) {
   if (navigator.appName=="Netscape") {
    winW = window.innerWidth;
   }
   if (navigator.appName.indexOf("Microsoft")!=-1) {
    winW = document.body.offsetWidth;
   }
  }

  jQuery('#tag_div').css('width', 400);
  jQuery('#tag_div').css('left', ((winW/2)- 200) + "px");
  jQuery('#tag_div').css('visibility', 'visible');
  jQuery('#tagTXT').focus();
}
</script>

<div align="center" style="width:100%">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td style="background-color:cecfce;background-image:url(<?=IMAGE_HOST_SITE_GFX?>/tags/share_bg.gif);background-repeat:repeat-x;background-position:top left;"><img src="<?=IMAGE_HOST_SITE_GFX?>/tags/share_tools2.gif" width="405" height="32" border="0" usemap="#Share" />
        <div align="center">
          <table border="0" cellpadding="0" cellspacing="0" width="500">
            <tr>
              <td width="12" align="right"><img src="<?=IMAGE_HOST?>/sponsor_cap.gif"></td>
              <td align="center">
                <? include(WWW_ROOT.'/ads/ad_includes/comic_template/468x60.html'); ?>
              </td>
              <td width="12" align="left"><img src="<?=IMAGE_HOST?>/sponsor_cap.gif"></td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
  <map name="Share" id="Share">
    <area shape="rect" coords="303,0,405,30" href="#" alt="Tag this comic/View this comic's tags!" title="Tag this comic/View this comic's tags!" onClick="getTags('<?=$COMIC_ROW->comic_id?>', '<?=$PAGE_ROW->page_id?>');return false;" />
    <area shape="rect" coords="279,3,300,24" href="http://digg.com/submit?phase=2&url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Digg this comic!" title="Digg this comic!" />
    <area shape="rect" coords="258,3,279,24" href="http://del.icio.us/post?url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Make this comic Del.icio.us!" title="Make this comic Del.icio.us!" />
    <area shape="rect" coords="236,3,257,24" href="http://reddit.com/submit?url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Reddit this comic!" title="Reddit this comic!" />
    <area shape="rect" coords="215,3,236,24" href="http://ma.gnolia.com/bookmarklet/add?url=http://<?=DOMAIN.$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>" target="_blank" alt="Add this comic to Ma.gnolia!" title="Add this comic to Ma.gnolia!" />
    <area shape="rect" coords="0,0,210,30" href="#" target="_blank" alt="E-mail this comic to a friend!" title="E-mail this comic to a friend!" onClick="window.open('http://<?=DOMAIN?>/tell_a_friend.php?cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$PAGE_ROW->page_id?>', 'tell_a_friend', 'toolbar=0,menubar=0,directories=0,resizable=0,scrollbars=0,height=442,width=500');return false;"/>
  </map>
  <div name="tag_div" id="tag_div"></div>
</div>
<?

$ret = str_replace("\n", "", ob_get_clean());
return $ret;

?>
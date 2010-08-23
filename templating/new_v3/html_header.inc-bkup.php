<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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

<!-- END OF TAG FOR head SECTION -->

<meta name="description" content="Drunk Duck is the webcomics community that provides FREE hosting and memberships to people who love to read or write comic books, or comic strips.">
<meta name="keywords" content="The Webcomics Community, Webcomics Community, The Comics Community, Comics Community, Comics, Webcomics, Stories, Strips, Comic Strips, Comic Books, Funny, Interesting, Read, Art, Drawing, Photoshop">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="verify-v1" content="ZoMFM4DRqC3/0lXvShydioBgkCsnu6hXw3IAleL7eEc=" />
<META NAME="robots" CONTENT="index,follow">
<script src="/__utm.js" type="text/javascript"></script>
<title>DrunkDuck: The Webcomics Community - <?=$TITLE?></title>
<link href="http://www.drunkduck.com/gfx/site_gfx_new_v3/ddstyles.css?cache=<?=date("Ymd")?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=HTTP_JAVASCRIPT?>/modal/css/modal-message.css?cache=<?=date("Ymd")?>" type="text/css">
<script type="text/javascript" src="<?=HTTP_JAVASCRIPT?>/prototype-1.4.0_modified.js?cache=<?=date("Ymd")?>"></script>
<script type="text/javascript" src="<?=HTTP_JAVASCRIPT?>/commonJS.js?cache=<?=date("Ymd")?>"></script>
<script type="text/javascript" src="<?=HTTP_JAVASCRIPT?>/modal/js/ajax.js?cache=<?=date("Ymd")?>"></script>
<script type="text/javascript" src="<?=HTTP_JAVASCRIPT?>/modal/js/modal-message.js?cache=<?=date("Ymd")?>"></script>
<script type="text/javascript" src="<?=HTTP_JAVASCRIPT?>/modal/js/ajax-dynamic-content.js?cache=<?=date("Ymd")?>"></script>



</head>

<body onLoad="for(var cs=0; cs<commandStack.length;cs++){eval(commandStack[cs]);}">

<script type="text/javascript" src="http://www.platinumstudios.com/processing/ps_hat_top.js"></script>

<div align="center">
  <!--header-->
  <table border="0" cellpadding="0" cellspacing="0" width="1024">
    <tr>
      <td width="296" height="90"><a href="http://<?=DOMAIN?>"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/header_logo_top.gif" border="0"></a></td>
      <td width="100%" bgcolor="#8b9298">
        <? include(WWW_ROOT.'/ads/ad_includes/main_template/728x90.html'); ?>
      </td>
    </tr>
    <tr>
      <td width="296" height="21"><a href="http://<?=DOMAIN?>"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/header_logo_bottom.gif" border="0"></a></td>
      <td width="100%" height="21" background="<?=IMAGE_HOST?>/site_gfx_new_v3/header_nav_bg.gif" align="left">
        <table border="0" cellpadding="0" cellspacing="0" height="21">
          <tr>
            <form action="http://<?=DOMAIN?>/search.php" name="searchform" method="GET">
            <td align="center">
              <div style="width:100px;height:21px;background:url(http://images.drunkduck.com/site_gfx_new_v3/search_bg.gif);">
                <input name="searchTxt" type="text" value="Search!" onclick="this.value='';" style="border:0px;width:80px;height:18px;font-size:14px;text-align:center;"/>
              </div>
            </td>
            <input type="hidden" name="advanced" value="0">
            <?=( isset($SUBDOM_TO_CAT[SUBDOM]) ? '<input type="hidden" name="browsegenre[]" value="'.$SUBDOM_TO_CAT[SUBDOM].'">' : '' )?>
            </form>
            <td align="center">
              <div class="fake_nav_link" id="header_nav"><a href="http://<?=DOMAIN?>/search.php">Browse</a></div>
            </td>
            <td align="center">
              <div class="fake_link" id="header_nav"><a href="http://<?=DOMAIN?>/tutorials/">Tutorials</a></div>
            </td>
            <td align="center">
              <div class="fake_link" id="header_nav"><a href="http://<?=DOMAIN?>/games/">Games</a></div>
            </td>
            <td align="center">
              <div class="fake_link" id="header_nav"><a href="http://<?=DOMAIN?>/news/">News</a></div>
            </td>
            <td align="center">
              <div class="fake_link" id="header_nav"><a href="http://<?=DOMAIN?>/community/">Forums</a></div>
            </td>
            <td align="center" width="100%">
              <div class="fake_link" id="header_nav"><a href="http://www.wowio.com/users/searchresults.asp?nPublisherId=140"><?
              switch( dice(1, 10) )
              {
                case 1:
                  ?>Free&nbsp;Comic&nbsp;Books<?
                break;
                case 2:
                  ?>Knock&nbsp;Knock!<?
                break;
                case 3:
                  ?>Wowio<?
                break;
                case 4:
                  ?>5...4...3...2...1!<?
                break;
                case 5:
                  ?>Don't&nbsp;Click&nbsp;This!<?
                break;
                case 6:
                  ?>Steal&nbsp;this&nbsp;link!<?
                break;
                case 7:
                  ?>Seriously,&nbsp;eBooks.<?
                break;
                case 8:
                  ?><("<)<?
                break;
                case 9:
                  ?>(>")><?
                break;
                case 10:
                  ?><span style="color:red;">@</span><span style="color:green;">--'--,--</span><?
                break;
              }
              ?></a></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!--END header-->
  <table width="1024" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top">
        <!--main content-->

        <div class="vert_spacer" align="right"><?
          if ( !$USER )
          {
            ?>
            <form action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
              Username:
              <input name="un" value="username" onfocus="this.value='';" style="width: 100px;" type="text">
              Password:
              <input name="pw" value="password" onfocus="this.value='';" style="width: 100px;" type="password">
              &nbsp;
              <input type="submit" value="Login">
              <a href="http://<?=DOMAIN?>/forgot_password.php">Forgot your password?</a>
            </form>
            <?
          }
        ?>&nbsp;</div>

        <table border="0" cellpadding="0" cellspacing="0" width="1024" height="100%" style="width:1024px;overflow:hidden;">

          <tr>
            <td width="724" height="100%" align="center" valign="top">
              <script type="text/javascript">

                function fetch_content( contentType ) {
                  new Ajax.Request( '/xmlhttp/main_page/fetch_content.php', { method: 'post', parameters: 'request='+contentType, onComplete: onContent} );
                }

                function onContent(originalReq)
                {
                  resp = originalReq.responseText.split('[1]');
                  $(resp[0]).innerHTML = resp[1];
                }

                function expandFavorites(qty)
                {
                  if ( typeof(qty) == 'undefined' ) {
                    qty = '';
                  }

                  if($('favorites').style.display=='none')
                  {
                    $('favorites').innerHTML      = 'Loading...';
                    $('favorites').style.display  = '';
                    fetch_content('favorites[1]'+qty);
					document.myFavorites.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow.gif';
                  }
                  else {
				  	document.myFavorites.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif';
                    $('favorites').style.display  = 'none';
                  }
                }

                function expandMyComics()
                {
                  if($('my_comics').style.display=='none')
                  {
                    $('my_comics').innerHTML      = 'Loading...';
                    $('my_comics').style.display  = '';
                    fetch_content('my_comics');
					document.myComics.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow.gif';
                  }
                  else {
				  	document.myComics.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif';
                    $('my_comics').style.display  = 'none';
                  }
                }

		/* function expandNewsUpdate() to change the news update dynamically*/
                function expandNewsUpdate()
                {
                  if($('my_news_update').style.display=='none')
                  {
                    $('my_news_update').innerHTML      = 'Loading...';
                    $('my_news_update').style.display  = '';
                    fetch_content('my_news_update');
                                        document.myNewsUpdate.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow.gif';
                  }
                  else {
                                        document.myNewsUpdate.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif';
                    $('my_news_update').style.display  = 'none';
                  }
                }

                function expandMyComicNew()
                {
                  if($('my_news').style.display=='none')
                  {
                    $('my_news').innerHTML      = 'Loading...';
                    $('my_news').style.display  = '';
                    fetch_content('my_news');
					document.myComicsNew.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow.gif';
                  }
                  else {
				 	 document.myComicsNew.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif';
                    $('my_news').style.display  = 'none';
                  }
                }

                function expandDDCam()
                {
                  if($('ddcam').style.display=='none')
                  {
                    $('ddcam').style.display  = '';
                    $('ddcam').innerHTML      = '<embed src="http://player.stickam.com/stickamPlayer/174625835-2743920" type="application/x-shockwave-flash" width="300" height="300" scale="exactfit" allowScriptAccess="always" allowFullScreen="true"></embed>'+
                                                '<div align="center"><a href="http://www.drunkduck.com/community/view_topic.php?tid=39951&cid=226" target="_blank">Discuss in the forum</a></div>';
					document.DDCam.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow.gif';
                  }
                  else {
				  	document.DDCam.src='<?=IMAGE_HOST?>/site_gfx_new_v3/expand_arrow2.gif';
                    $('ddcam').style.display  = 'none';
                  }
                }

                function editFilter( rackname )
                {
                  if ( typeof(modalMenu) != 'undefined' ) {
                    modalMenu.close();
                  }

                  modalMenu = new DHTML_modalMessage(); // We only create one object of this class
                  modalMenu.setSource("/xmlhttp/main_page/modal_menus/modal_"+rackname+".php?rand="+Math.random(123123));
                  modalMenu.setSize(450, 325);
                  modalMenu.display();

                  // modalMenu.setHtmlContent( originalReq.responseText );
                  // modalMenu.close();
                }

                function saveFilter(rackname, frm)
                {
                  updateRack( rackname, Form.serialize(frm) )
                  modalMenu.close();
                }

                function updateRack(rackname, formData)
                {
                  new Ajax.Request( '/xmlhttp/main_page/racks/fetch_rack_'+rackname+'.php', { method: 'post', parameters: formData, onComplete: onUpdateRack} );
                }

                function onUpdateRack(originalReq)
                {
                  resp = originalReq.responseText.split('[1]');
                  $(resp[0]).innerHTML = resp[1];
                }

                var modalMenu;
              </script>

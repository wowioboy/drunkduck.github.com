<HTML>
<HEAD>
<title>DrunkDuck Admin - <?=$TITLE?></title>
<meta name="robots" content="All,INDEX,FOLLOW">
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=iso-8859-1'>
<link rel="stylesheet" type="text/css" href="<?=IMAGE_HOST?>/css/admin.css" />
<LINK REL="SHORTCUT ICON" HREF="http://<?=DOMAIN?>/favicon.ico">
</HEAD>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/commonJS.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/prototype-1.4.0_modified.js"></SCRIPT>

<!-- ext stuff added by Dan Kram -->
<link rel="stylesheet" href="../../js/ext/xtheme-blue.css" />
<script src="../../js/ext/ext-prototype-adapter.js"></script>
<script src="../../js/ext/ext-all.js"></script>

<BODY BGCOLOR='#FFFFFF' LEFTMARGIN='0' TOPMARGIN='0' MARGINWIDTH='0' MARGINHEIGHT='0' VALIGN='TOP' ALIGN='CENTER' onLoad="callOnLoadList()">
<script type="text/javascript">
  var navVisible = true;
  function toggleNavVisible()
  {
    if ( navVisible )
    {
      $('sideNav').style.display='none';
      $('navToggler').innerHTML = '&raquo; Navigation Show';
      navVisible = false;
    }
    else
    {
      $('sideNav').style.display='';
      $('navToggler').innerHTML = '&laquo; Navigation Hide';
      navVisible = true;
    }
    return navVisible;
  }
</script>
<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%' HEIGHT='100%'>
  <TR>
    <TD COLSPAN='2' ALIGN='LEFT' HEIGHT='20' BGCOLOR='#EFEFEF' STYLE='border-bottom:1px solid black;'>
      <DIV ALIGN='CENTER'>
        <B><A HREF='http://<?=DOMAIN?>'><?=str_replace("www.", "", DOMAIN)?></A> Administration</B>
      </DIV>
      <A ID='navToggler' HREF="JavaScript:var x=toggleNavVisible();">&laquo; Navigation Hide</A>
    </TD>
  </TR>
  <TR>
    <TD WIDTH='200' STYLE='border-right:1px solid black;' VALIGN='TOP' BGCOLOR='#EEEEFF'><?
      include_once(TEMPLATE.'/!admin/side_nav.inc.php');
    ?></TD>
    <TD WIDTH='100%' ALIGN='CENTER' VALIGN='TOP' BGCOLOR='#EEFFEE' ><P>&nbsp;</P>
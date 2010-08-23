<?
include('header_overview.inc.php');
include(WWW_ROOT.'/includes/calendar/calendar.class.php');


?>
<link href="http://<?=DOMAIN?>/includes/calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="http://<?=DOMAIN?>/includes/calendar/calendar.js" type="text/javascript"></script>
<?
$CALENDAR = new CalendarMonth();
$CALENDAR->editMode = true;
$CALENDAR->addContent( 9, 'Something');
echo $CALENDAR->render(700, 600);


?>
<?
$COMIC_ROW->comic_caps_id = 0;
$seed = rand(1, 999999);
$ret = '<style type="text/css">
.ftr_'.$seed.':link {
	font-weight:bold;
	color: #FFCC00;
	text-decoration: none;
}
.ftr_'.$seed.':visited {
	font-weight:bold;

	color: #FFCC00;
	text-decoration: none;
}
.ftr_'.$seed.':hover {
	font-weight:bold;

	color: #FFFF00;
	text-decoration: underline;
}
.ftr_'.$seed.':active {
	font-weight:bold;

	color: #000000;
	text-decoration: underline;
	background-color: #FFFFFF;
}
</style>
';

$ret .= '
<script type="text/javascript"> for(var cs=0; cs<commandStack.length;cs++){eval(commandStack[cs]);} </script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-606793-4";
urchinTracker();
</script>

</div>

<!--FTR-->';

return $ret;
?>

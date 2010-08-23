<?
include('../community_header.inc.php');
?>

<style>



div.autocomplete {
  position:absolute;
  width:250px;
  background-color:white;
  border:1px solid #6699ff;
  margin:0px;
  padding:0px;
}
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
}
div.autocomplete ul li.selected { background-color: #fffa8c;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  padding:2px;
  cursor:pointer;
  color:#000000;
}

div.autocomplete li span.informal {
  padding-left:10px;
  display:block;
  font-size:9px;
  color:#888;
}

div.autocomplete li span.informal span.informal_rt {
  padding-left:10px;
  display:block;
  font-size:9px;
  color:#888;
  text-align: right;
}
</style>

<script src="<?=HTTP_JAVASCRIPT?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>

<input type="text" id="autocomplete" name="group_name" style="width:250px;" value="" />
<div id="autocomplete_choices" class="autocomplete"></div>

<script type="text/javascript">
  new Ajax.Autocompleter("autocomplete", "autocomplete_choices", "/xmlhttp/community/find_forum.php", {paramName: "value", minChars: 3, afterUpdateElement: getSelectionId});

  function getSelectionId(text, li) {
    $('forum_direction').category_id.value = Number(li.id);
    $('forum_direction').submit();
  }
</script>





<form action="direct_to_forum.php" method="POST" id="forum_direction">
  <input type="hidden" name="category_id" value="0">
</form>

<?
include('../community_footer.inc.php');
?>
<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
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

<h1 align="left">Search</h1>
<div class="gameContent">

  <div style="height:50px;" align="right">
    <span id="indicator1" style="display: none"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/working.gif" alt="Working..." /></span>
  </div>

  <div style="width:80%;" align="left">
    <b>Body Search:</b>
    <div style="padding-left:25px;font-size:11px;">
      <i>Matches text that appears in the body of forum posts.</i>
    </div>
    <input id="text_search" type="text" name="textSearch" style="width:100%;">
  </div>

  <p>&nbsp;</p>

  <div style="width:80%;" align="left">
    <b>Search for a post by username:</b>
    <div style="padding-left:25px;font-size:11px;">
      <i>Matches posts made by a specific username.</i>
    </div>
    <input id="user_search" type="text" name="userSearch" style="width:100%;">
  </div>

  <p>&nbsp;</p>

  <div style="width:80%;" align="left">
    <b>Search for a category by name:</b>
    <div style="padding-left:25px;font-size:11px;">
      <i>Matches category names. Good for searching for comic forums.</i>
    </div>
    <input id="category_search" type="text" name="catSearch" style="width:100%;">
  </div>

  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

</div>

<div id="autocomplete_choices" class="autocomplete" align="left"></div>

<form action="direct_to_forum.php" method="POST" id="forum_direction">
  <input type="hidden" name="category_id" value="0">
  <input type="hidden" name="topic_id" value="0">
</form>

<script type="text/javascript">

  $('forum_direction').category_id.value = 0;
  $('forum_direction').topic_id.value = 0;

  new Ajax.Autocompleter("category_search", "autocomplete_choices", "/xmlhttp/community/find_forum.php", {paramName: "value", minChars: 3, afterUpdateElement: onCategorySelect, indicator: 'indicator1'});
  function onCategorySelect(text, li) {
    lockInputs();
    $('forum_direction').category_id.value = Number(li.id);
    $('forum_direction').submit();
  }

  new Ajax.Autocompleter("text_search", "autocomplete_choices", "/xmlhttp/community/search_text.php", {paramName: "value", minChars: 3, afterUpdateElement: onPostSelect, indicator: 'indicator1'});
  function onPostSelect(text, li) {
    lockInputs();
    $('forum_direction').topic_id.value = Number(li.id);
    $('forum_direction').submit();
  }

  new Ajax.Autocompleter("user_search", "autocomplete_choices", "/xmlhttp/community/search_user_posts.php", {paramName: "value", minChars: 3, afterUpdateElement: onPostSelect, indicator: 'indicator1'});

  function lockInputs()
  {
    $('text_search').value = 'Please Wait...';
    $('user_search').value = 'Please Wait...';
    $('category_search').value = 'Please Wait...';

    $('text_search').disabled = true;
    $('user_search').disabled = true;
    $('category_search').disabled = true;
  }

  function unlockInputs()
  {
    $('text_search').value = '';
    $('user_search').value = '';
    $('category_search').value = '';

    $('text_search').disabled = false;
    $('user_search').disabled = false;
    $('category_search').disabled = false;
  }

  unlockInputs();
</script>


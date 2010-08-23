<script src="<?=HTTP_JAVASCRIPT?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<style>
div.autocomplete {
text-align:left;
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


<FORM ACTION='choose_pages.php' METHOD='GET' onsubmit="if($('comic_name').length==0)return false;">
  <DIV CLASS='header' ALIGN='CENTER' style="width:300px;border:1px solid black;">
    Search for Comic
    <br>
    <INPUT TYPE="TEXT" NAME="comic_name" id="comic_name" style="width:300px;"> <INPUT TYPE='SUBMIT' VALUE='View!'>
    <div id="autocomplete_choices" class="autocomplete"></div>
  </DIV>
  <script type="text/javascript">
    new Ajax.Autocompleter("comic_name", "autocomplete_choices", "/xmlhttp/find_comic_for_admin.php", {paramName: "try", minChars: 3, afterUpdateElement: getSelectionId});

    function getSelectionId(text, li) {
      $('comic_name').value = li.id;
    }
  </script>
</FORM>
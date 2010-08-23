<script type="text/javascript">
  var linklinkFormRef = null;
  function serializeAndSubmitLink( frm )
  {
    linkFormRef = frm;
    var saveFrm = new Ajax.Request( '/xmlhttp/submit_link.php', { method: 'post', parameters: Form.serialize(frm), onComplete: onSubmittedLink} );
    Form.disable(frm);
  }

  function onSubmittedLink(originalReq) {
    Form.enable(linkFormRef);
    eval(originalReq.responseText);
  }

  function deleteLink(id) {
    new Ajax.Request( '/xmlhttp/delete_link.php', { method: 'post', parameters: 'id='+id, onComplete: onDeleteLink} );
  }

  function onDeleteLink(originalReq) {
    eval(originalReq.responseText);
  }
</script>

<div align="left" style="margin-bottom:5px;">
  <div style="font-size:16px;font-weight:bold;">
    <?=$viewRow->username?> Publisher Links:
  </div>

  <div align="left" id="pub_links">

  <?
  $res = db_query("SELECT * FROM publisher_links WHERE user_id='".$viewRow->user_id."'");
  while($row = db_fetch_object($res) )
  {
    ?>
    <div align="left" style="margin-bottom:5px;" id="link_<?=$row->id?>">
      <a href="<?=$row->url?>"><?=htmlentities($row->name, ENT_QUOTES)?></a>
      <?
      if ( $USER->user_id == $viewRow->user_id ) {
        ?><a href="#" onClick="if ( confirm('do you want to delete this link?') ) { deleteLink(<?=$row->id?>); };return false;"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/remove_button.gif" style="border:0px;"></a><?
      }
      ?>
      <br>
      <?=nl2br(htmlentities($row->description, ENT_QUOTES))?>
    </div>
    <?
  }
  ?>

  </div>
  <?
  if ( $viewRow->user_id == $USER->user_id )
  {
    ?>
    <form action="" method="POST" onSubmit="serializeAndSubmitLink(this);return false;">
    <div align="left" id="add_link_form" style="display:none;">
      <b>Add Link:</b><br>
      Name:<br><input type="text" name="name" style="width:100%;"><br>
      URL:<br><input type="text" name="url" style="width:100%;"><br>
      Description:<br><textarea name="description" rows="5" style="width:100%;"></textarea><br>
      <input type="submit" value="Add!">
    </div>
    </form>
    <div align="right">
      <a href="#" onClick="new Effect.BlindDown('add_link_form');this.style.display='none';return false;">Add Link</a>
    </div>
    <?
  }
  ?>
</div>
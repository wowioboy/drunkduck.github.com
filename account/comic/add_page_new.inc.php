<?php
include('header_edit_comic.inc.php');
if (!$CID = $_GET['cid']) {
	return;
}
?>
<script>
jQuery(document).ready(function() {
	var cid = '<?php echo $CID; ?>';
	jQuery("#uploadify").uploadify({
		uploader: '/swf/uploadify/uploadify.swf',
		cancelImg: '/images/uploadify/cancel.png',
		script: '/upload.php?cache=<?php echo date('YmdHis'); ?>',
		folder: 'files',
		multi: true,
		queueID: 'fileQueue',
		buttonText: 'Add Page',
		fileExt: '*.png;*.jpeg;*.jpg;*.bmp;*.gif',
		fileDesc: 'allowed file types: .png,.gif,.bmp,.jpg,.jpeg',
		sizeLimit: 2097152,
		queueSizeLimit: '10',
		onSelect: function(event, id, file) {
			var newuploader = jQuery('.uploadDetails:first').clone();
			newuploader.addClass(id);
			newuploader.find('legend').html('details for ' + file.name);
			jQuery('#uploadControls').before(newuploader);
			var dateEl = newuploader.find('.date');
			dateEl.val('<?php echo date('m/d/Y'); ?>');
			dateEl.datepicker({
				minDate: 0
			});
			newuploader.slideDown();
			jQuery('#startControls').show();
		},
		onCancel: function(event, id) {
			jQuery('fieldset.' + id).remove();
			if (jQuery('.uploadifyQueueItem').size() == 1) {
				jQuery('#startControls').hide();
			}
		},
		onComplete: function(event, id) {
			jQuery('fieldset.' + id).remove();
		},
		onError: function(event, id, file, error) {
			alert('there was an error with message: ' + error.info);
		},
		onAllComplete: function() {
			window.location = '/account/comic/manage_pages.php?cid=' + cid;
		},
		onClearQueue: function() {
			jQuery('#startControls').hide();
		}
	});
	jQuery('#startUploader').click(function(){
		jQuery('.uploadifyQueueItem').each(function(){
			var id = jQuery(this).attr('id');
			id = id.split('uploadify');
			id = id[1];
			var uploader = jQuery('fieldset.' + id);
			var title = uploader.find('.title').val();
			var notes = uploader.find('.notes').val();
			var date = uploader.find('.date').val();
			if (!title || !date) {
				jQuery('#uploadErrors').html('<span style="color:#f00;font-size:14px;">you are missing some info for your uploads.</span>');
				return;
			}
			jQuery('#uploadify').uploadifySettings('scriptData', {title:title, notes:notes, date:date, cid:cid});
			jQuery('#uploadify').uploadifyUpload(id);
		});
	});
	jQuery('#clearUploader').click(function(){
		jQuery('#uploadify').uploadifyClearQueue();
	});
});
</script>
<style>
a.controlBtn {
	color:#fff;
	padding:10px;
	background-color:#43f;
	text-align:center;
	width:88px;
	font-size:12px;
	display:inline-block;
	text-decoration:none;
}
#startControls {
	text-align:right;
	margin-top:10px;
	display:none;
}
</style>
If you're having problems with this new uploader, you can still use the old one: <a href="add_page_old.php?cid=<?php echo $CID; ?>">CLICK HERE</a>
<div id="uploadErrors"></div>
<div id="boomtown">
<ul>
<li>Size: 2mb or lower</li>
<li>File Types: .jpg .bmp .gif .png</li>
<!--<li>Dimensions: All pages greater than 1024px wide will be automatically resized. Aspect ratio of the image will be maintained.</li>-->
</ul>
<fieldset class="uploadDetails" style="display:none;">
  <legend>details for file:</legend>
  <table width="100%">
    <tr>
      <td>Post Date: </td>
      <td><input type="text" class="date" value="<?php echo date('m/d/Y'); ?>" /></td>
    </tr>
    <tr>
      <td>Title:</td>
      <td><input style="width:100%;" type="text" class="title" /></td>
    </tr>
    <tr>
      <td valign="top">Notes: </td>
      <td><textarea style="width:100%;height:150px;" class="notes"></textarea></td>
    </tr>
  </table>
</fieldset>
<table id="uploadControls" width="100%">
  <tr>
    <td style="vertical-align:bottom;">
      <input type="file" name="uploadify" id="uploadify" />
    </td>
    <td>
      <div id="fileQueue"></div>
      <div id="startControls">
      <a class="controlBtn" id="startUploader" href="javascript:">Upload Pages</a>
      <a class="controlBtn" id="clearUploader" href="javascript:">Clear Queue</a>
      </div>
    </td>
  </tr>
</table>
</div>





























<?
include('footer_edit_comic.inc.php');
?>
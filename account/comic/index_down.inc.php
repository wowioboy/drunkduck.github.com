<?php include('header_edit_comic.inc.php'); ?>

<table width="750">
  <tr>
    <td align="center" valign="top" id="main" width="750">

      <script type="text/javascript">
      var uploadCt  = 1;
      var uploadMax = 10;
      function addUpload(amt)
      {
        if ( !amt ) amt = 1;

        for (var x=0; x<amt; x++)
        {
          if ( uploadCt == uploadMax ) {
            alert("You've reached the maximum numbers of pages you can add at once!");
            return;
          }

          uploadCt++;
          $('uploadCount').value = uploadCt;
          $('uploadform_'+uploadCt).style.display = '';
        }
      }
      </script>

      <div class="pagecontent">
        <DIV CLASS='container' ALIGN='LEFT'>

  			Editing of comic details has been temporarily disabled. This functionality should be fully restored by Monday, 12/14/2009 @ 8:00 AM PST
  			<br/><br />
   			Sorry for the inconvenience.


      	</DIV>
    </div>

    </td>
  </tr>
</table>

<?php include('footer_edit_comic.inc.php'); ?>
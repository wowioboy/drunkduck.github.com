<?
include('header_edit_comic.inc.php');

if ( !isset($_GET['cid']) ) return;

$CID = (int)$_GET['cid'];
?>














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

          <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' width="100%">
              <TR>
                <TD colspan="2" ALIGN='LEFT'><h3><a href="manage_pages.php?cid=<?=$COMIC_ROW->comic_id?>">Comic Pages</a>: Add Pages <span class="helpnote">Pages can be GIFs, JPEGs, or PNGs</span> </h3></TD>
              </TR>
          </TABLE>

          <FORM ACTION='send_page.php' METHOD='POST' ENCTYPE='multipart/form-data'>
          <?
          for ($w=1; $w<11; $w++)
          {
            ?>
            <DIV ID='uploadform_<?=$w?>' <?=( ($w != 1)?"style='display:none;'":"")?>>

              <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%' style="background:url(images/num1.gif) no-repeat">
                <TR>
                  <TD colspan="2" ALIGN='LEFT' class="community_hdr">Page Details:</TD>
                </TR>
                <TR>
                  <TD WIDTH='20%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <B>Date Live:</B>
                  </TD>
                  <TD WIDTH='70%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <SELECT NAME='live_month_<?=$w?>'>
                      <OPTION VALUE='1'<?=((date('n')==1)? SELECTED:"")?>>January</OPTION>
                      <OPTION VALUE='2'<?=((date('n')==2)? SELECTED:"")?>>February</OPTION>
                      <OPTION VALUE='3'<?=((date('n')==3)? SELECTED:"")?>>March</OPTION>
                      <OPTION VALUE='4'<?=((date('n')==4)? SELECTED:"")?>>April</OPTION>
                      <OPTION VALUE='5'<?=((date('n')==5)? SELECTED:"")?>>May</OPTION>
                      <OPTION VALUE='6'<?=((date('n')==6)? SELECTED:"")?>>June</OPTION>
                      <OPTION VALUE='7'<?=((date('n')==7)? SELECTED:"")?>>July</OPTION>
                      <OPTION VALUE='8'<?=((date('n')==8)? SELECTED:"")?>>August</OPTION>
                      <OPTION VALUE='9'<?=((date('n')==9)? SELECTED:"")?>>September</OPTION>
                      <OPTION VALUE='10'<?=((date('n')==10)? SELECTED:"")?>>October</OPTION>
                      <OPTION VALUE='11'<?=((date('n')==11)? SELECTED:"")?>>November</OPTION>
                      <OPTION VALUE='12'<?=((date('n')==12)? SELECTED:"")?>>December</OPTION>
                    </SELECT>
                    <SELECT NAME='live_day_<?=$w?>'><?
                    for($i=1; $i<32; $i++) {
                      echo "<OPTION VALUE='".$i."'".(($i==date(j))?SELECTED:"").">".$i."</OPTION>";
                    }
                    ?></SELECT>
                    <SELECT NAME='live_year_<?=$w?>'><?
                    for($i=0; $i<2; $i++) {
                      echo "<OPTION VALUE='".(date(Y)+$i)."'".(((date(Y)+$i)==date(Y))?SELECTED:"").">".(date(Y)+$i)."</OPTION>";
                    }
                    ?></SELECT>
                    <span class="helpnote">Live date cannot be changed once uploaded! </span>
                  </TD>
                </TR>

                <TR>
                  <TD WIDTH='20%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <B>Page Title:</B>
                  </TD>
                  <TD WIDTH='70%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <INPUT TYPE='TEXT' NAME='pageTitle_<?=$w?>' STYLE='WIDTH:100%;'>
                  </TD>
                </TR>
                <TR>
                  <TD WIDTH='20%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <B>Authors Notes:</B>
                  </TD>
                  <TD WIDTH='70%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <TEXTAREA NAME='comicDescription_<?=$w?>' STYLE='WIDTH:100%;' rows='10'></TEXTAREA>
                  </TD>
                </TR>
                <TR>
                  <TD WIDTH='20%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <B>Page:</B>
                  </TD>
                  <TD WIDTH='70%' ALIGN='LEFT' valign="top" class="community_thrd">
                    <INPUT TYPE='FILE' NAME='file_<?=$w?>' STYLE='WIDTH:100%;'>
                  </TD>
                </TR>
              </TABLE>

            </DIV>
            <?
          }
          ?>
          <INPUT ID='uploadCount' TYPE='HIDDEN' NAME='uploads' VALUE='1'>
          <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>

          <DIV ALIGN='CENTER'><A HREF='JavaScript:addUpload();'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/pages_1.gif" width="100" height="24" border="0"></A> <A HREF='JavaScript:addUpload(5);'>&nbsp;<img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/pages_5.gif" width="100" height="24" border="0"></A> &nbsp;<A HREF='JavaScript:addUpload(10);'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/pages_10.gif" width="100" height="24" border="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</A>
            <INPUT TYPE='IMAGE' src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/pages_send.gif" VALUE='Send these Pages!'>
          </DIV>

        </FORM>
      </DIV>
    </div>

    </td>
  </tr>
</table>


















<?
include('footer_edit_comic.inc.php');
?>
<?
if ( !isset($_GET['cid']) ) return;

$CID = (int)$_GET['cid'];
?>
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

    var bgColor = '';
    var padding = '';
    if ( uploadCt%2 == 0 ) {
      bgColor = '#ECECEC';
      padding = 'PADDING-LEFT:50px;';
    }

    var divRef = document.getElementById('formArea');

    divRef.innerHTML +=
    //"<HR />"+
    "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%' BGCOLOR='"+bgColor+"' STYLE='"+padding+"'>"+
      "<TR>"+
        "<TD ALIGN='LEFT' WIDTH='30%'>"+
          "<B>Date Live:</B>"+
        "</TD>"+
        "<TD ALIGN='LEFT' WIDTH='70%'>"+
          "<SELECT NAME='live_month_"+uploadCt+"'>"+
            "<OPTION VALUE='1'<?=((date('n')==1)?" SELECTED":"")?>>January</OPTION>"+
            "<OPTION VALUE='2'<?=((date('n')==2)?" SELECTED":"")?>>February</OPTION>"+
            "<OPTION VALUE='3'<?=((date('n')==3)?" SELECTED":"")?>>March</OPTION>"+
            "<OPTION VALUE='4'<?=((date('n')==4)?" SELECTED":"")?>>April</OPTION>"+
            "<OPTION VALUE='5'<?=((date('n')==5)?" SELECTED":"")?>>May</OPTION>"+
            "<OPTION VALUE='6'<?=((date('n')==6)?" SELECTED":"")?>>June</OPTION>"+
            "<OPTION VALUE='7'<?=((date('n')==7)?" SELECTED":"")?>>July</OPTION>"+
            "<OPTION VALUE='8'<?=((date('n')==8)?" SELECTED":"")?>>August</OPTION>"+
            "<OPTION VALUE='9'<?=((date('n')==9)?" SELECTED":"")?>>September</OPTION>"+
            "<OPTION VALUE='10'<?=((date('n')==10)?" SELECTED":"")?>>October</OPTION>"+
            "<OPTION VALUE='11'<?=((date('n')==11)?" SELECTED":"")?>>November</OPTION>"+
            "<OPTION VALUE='12'<?=((date('n')==12)?" SELECTED":"")?>>December</OPTION>"+
          "</SELECT>"+
          "<SELECT NAME='live_day_"+uploadCt+"'><?
          for($i=1; $i<32; $i++) {
            echo "<OPTION VALUE='$i'".(($i==date("j"))?"SELECTED":"").">$i</OPTION>";
          }
          ?></SELECT>"+
          "<SELECT NAME='live_year_"+uploadCt+"'><?
          for($i=0; $i<2; $i++) {
            echo "<OPTION VALUE='".(date("Y")+$i)."'".(((date("Y")+$i)==date("Y"))?"SELECTED":"").">".(date("Y")+$i)."</OPTION>";
          }
          ?></SELECT>"+
        "</TD>"+
      "</TR>"+
      "<TR>"+
        "<TD ALIGN='LEFT' WIDTH='30%'>"+
          "<B>Page Title:</B>"+
        "</TD>"+
        "<TD ALIGN='LEFT' WIDTH='70%'>"+
          "<INPUT TYPE='TEXT' NAME='pageTitle_"+uploadCt+"' STYLE='WIDTH:100%;'>"+
        "</TD>"+
      "</TR>"+
      "<TR>"+
        "<TD ALIGN='LEFT' WIDTH='30%'>"+
          "<B>Authors Notes:</B>"+
        "</TD>"+
        "<TD ALIGN='LEFT' WIDTH='70%'>"+
          "<TEXTAREA NAME='comicDescription_"+uploadCt+"' STYLE='WIDTH:100%;' rows='10'></TEXTAREA>"+
        "</TD>"+
      "</TR>"+
      "<TR>"+
        "<TD ALIGN='LEFT' WIDTH='30%'>"+
          "<B>Page:</B>"+
        "</TD>"+
        "<TD ALIGN='LEFT' WIDTH='70%'>"+
          "<INPUT TYPE='FILE' NAME='file_"+uploadCt+"' STYLE='WIDTH:100%;'>"+
        "</TD>"+
      "</TR>"+
    "</TABLE>";
    document.getElementById('uploadCount').value = uploadCt;
  }
}
</script>

<SPAN CLASS='headline'>Add Page</SPAN>

<DIV STYLE='WIDTH:600px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Page Details:</DIV>

    <FORM ACTION='send_page.php' METHOD='POST' ENCTYPE='multipart/form-data'>

    <DIV ID='formArea'>
    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>

    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Date Live:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <SELECT NAME='live_month_1'>
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
        <SELECT NAME='live_day_1'><?
        for($i=1; $i<32; $i++) {
          echo "<OPTION VALUE='".$i."'".(($i==date(j))?SELECTED:"").">".$i."</OPTION>";
        }
        ?></SELECT>
        <SELECT NAME='live_year_1'><?
        for($i=0; $i<2; $i++) {
          echo "<OPTION VALUE='".(date(Y)+$i)."'".(((date(Y)+$i)==date(Y))?SELECTED:"").">".(date(Y)+$i)."</OPTION>";
        }
        ?></SELECT>
      </TD>
    </TR>

    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Page Title:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <INPUT TYPE='TEXT' NAME='pageTitle_1' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Authors Notes:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <TEXTAREA NAME='comicDescription_1' STYLE='WIDTH:100%;' rows='10'></TEXTAREA>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Page:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <INPUT TYPE='FILE' NAME='file_1' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    </TABLE>
    </DIV>

    <INPUT ID='uploadCount' TYPE='HIDDEN' NAME='uploads' VALUE='1'>
    <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>

    <DIV ALIGN='CENTER'>
      <INPUT TYPE='SUBMIT' VALUE='Send these Pages!'>
    </DIV>

    </FORM>
  <A HREF='JavaScript:addUpload();'>Add Page Form!</A> | <A HREF='JavaScript:addUpload(5);'>Add 5 Page Forms!</A> | <A HREF='JavaScript:addUpload(10);'>Add 10 Page Forms!</A>
</DIV>
<?
if ( $USER->username == 'Volte6' )
{
  include(WWW_ROOT.'/account/add_comic_v2.inc.php');
  return;
}
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics.</SPAN>";
    return;
  }
?>

<script type="text/javascript">
function formIsValid( frm )
{
  var badCharRegex = new RegExp("([^a-zA-Z0-9 ])");

  if ( frm.newComicName.value.length < 3 ) {
    alert('Your Comic Name must be at least 3 characters long. Please try again.');
    return false;
  }
  else if ( badCharRegex.test( frm.newComicName.value ) ) {
    alert('Your Comic Name contained bad characters. Please only use numbers, letters, and SPACES.');
    return false;
  }

  if ( !frm.newComicType[0].checked && !frm.newComicType[1].checked ) {
    alert('Please check one of the comic type options.');
    return false;
  }

  return true;
}

function updateCharCt(field, updateDiv, limit)
{
  if ( field.value.length >= limit ) {
    field.value = field.value.substring(0, limit);
    alert('You have reached the limit of the description size you are allowed.');
  }
  var div = document.getElementById(updateDiv);
  div.innerHTML = (limit-field.value.length)+" characters left.";
}

function updateTakenInfo(data)
{
  document.getElementById('takenInfo').innerHTML = data;
}
</script>

<SPAN CLASS='headline'>Add Comic</SPAN>

<DIV STYLE='WIDTH:400px;HEIGHT:400px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Comic Details:</DIV>

  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>
    <FORM ACTION='add_comic2.php' METHOD='POST' onSubmit='return formIsValid(this);'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Comic Name:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <DIV ID='takenInfo' CLASS='microalert'></DIV>
        <INPUT TYPE='TEXT' NAME='newComicName' STYLE='WIDTH:100%;' onKeyUp="ajaxCall('/xmlhttp/try_comic_name.php?try='+this.value, updateTakenInfo);">
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Description:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <TEXTAREA NAME='newComicDescription' STYLE='WIDTH:100%;HEIGHT:100;' onKeyUp="updateCharCt(this, 'charCt', 255);"></TEXTAREA>
        <DIV ID='charCt'>255 Characters Left</DIV>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Comic Style:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <?
        foreach($COMIC_STYLES as $key=>$value) {
          echo "<INPUT TYPE='RADIO' NAME='newComicType' VALUE='".$key."'>".$value."<BR>";
        }
        ?>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Category:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <SELECT NAME='newComicCat' STYLE='WIDTH:100%;'>
          <?=getKeyValueSelect($COMIC_CATS)?>
        </SELECT>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Subcategory:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <SELECT NAME='newComicSubCat' STYLE='WIDTH:100%;'>
          <?=getKeyValueSelect($COMIC_SUBCATS)?>
        </SELECT>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='CENTER' COLSPAN='2'>
        <BR>
        <INPUT TYPE='SUBMIT' VALUE='Add Comic!'>
      </TD>
    </TR>
    </FORM>
  </TABLE>
</DIV>
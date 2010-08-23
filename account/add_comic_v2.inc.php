<?
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
  if ( field.value.length >= 255 ) {
    field.value = field.value.substring(0, 255);
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

<h1 align="left">Create a Comic </h1>
<div class="pagecontent">





  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <FORM ACTION='add_comic2.php' METHOD='POST' onSubmit='return formIsValid(this);'>
    <tr>
      <td colspan="2" align="left" class="controltable_hdr"><h3>Add Comic</h3></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="controltable_subhdr">Comic Details:</td>
    </tr>
    <tr>
      <td width="125" align="left" class="controltable_thrd">
        Comic Name:
      </td>
      <td width="580" align="left" class="controltable_thrd">
        <div id="takenInfo" class="microalert"></div>
        <INPUT TYPE='TEXT' NAME='newComicName' STYLE='WIDTH:100%;' onKeyUp="ajaxCall('/xmlhttp/try_comic_name.php?try='+this.value, updateTakenInfo);">
      </td>
    </tr>

    <tr bgcolor="#001D37">
      <td width="125" align="left" class="controltable_thrd">
        Description:
      </td>
      <td width="580" align="left" class="controltable_thrd">
        <textarea name="newComicDescription" style="width: 580px; height: 100px;" onkeyup="updateCharCt(this, 'charCt', 255);"></textarea>
        <div id="charCt">255 Characters Left</div>
      </td>
    </tr>
    <tr>
      <td width="125" align="left" class="controltable_thrd">
        Comic Style:
      </td>
      <td width="580" align="left" class="controltable_thrd">
        <?
        foreach($COMIC_STYLES as $key=>$value) {
          ?><label for="type_<?=$key?>"><input id="type_<?=$key?>" type="radio" name="newComicType" value="<?=$key?>"><?=$value?></label><br><?
        }
        ?>
      </td>
    </tr>
    <tr bgcolor="#001D37">
      <td width="125" align="left" class="controltable_thrd">
        Category:
      </td>
      <td width="580" align="left" class="controltable_thrd">
        <select name='newComicCat' style="width: 200px;">
          <?=getKeyValueSelect($COMIC_CATS)?>
        </select>
      </td>
    </tr>
    <tr>
      <td width="125" align="left" class="controltable_thrd">
        Subcategory:
      </td>
      <td width="580" align="left" class="controltable_thrd">
        <select name='newComicSubCat' style="width: 200px;">
          <?=getKeyValueSelect($COMIC_SUBCATS)?>
        </select>
      </td>
    </tr>
    <tr bgcolor="#001D37">
      <td colspan="2" align="center" class="controltable_thrd">
        <br>
        <input value="Add Comic!" type="submit">
      </td>
    </tr>
    </form>
  </table>


</div>
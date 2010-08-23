<?
  include('header_overview.inc.php');

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
  var div = $(updateDiv);
  div.innerHTML = (limit-field.value.length)+" characters left.";
}

function updateTakenInfo(data)
{
  document.getElementById('takenInfo').innerHTML = data;
}
</script>

<table border='0' cellpadding='0' cellspacing='0' width='100%'>
  <FORM ACTION='add_comic2.php' METHOD='POST' onSubmit='return formIsValid(this);'>
      <tr>
        <td colspan="3" align='LEFT' valign="top"><h3>Create a New Comic :</h3></td>
        </tr>
      <tr>
        <td align='LEFT' valign="top" class="community_thrd"><strong>Comic Name:</strong><br>
          <span class="helpnote"><br>
          </span></td>
        <td colspan="2" align='LEFT' valign="top" class="community_thrd"><label>
          <div id="takenInfo" class="microalert"></div>
          <INPUT TYPE='TEXT' NAME='newComicName' size="60" onKeyUp="ajaxCall('/xmlhttp/try_comic_name.php?try='+this.value, updateTakenInfo);">
          </label></td>
        </tr>

      <tr>
        <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Short Description:<br>
          </b><span class="helpnote">This description is limited to 255 characters. </span></td>
        <td colspan="2" align='LEFT' class="community_thrd">
          <textarea name="newComicDescription" style='WIDTH:100%;HEIGHT:100;' onkeyup="updateCharCt(this, 'charCt', 255);"></textarea>
            <div>
              <div align="right" class="helpnote" id='charCt'>255 Characters Left</div>
            </div></td>
      </tr>
      <tr>
        <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Type of Comic:</b> </td>
        <td width="35%" align='LEFT' valign="top" class="community_thrd"><input type='RADIO' name='newComicType' value='0' >
          <strong>Comic Strip</strong><br>
          <span class="helpnote">A comic &quot;strip&quot; is typically 3-4 panels in a strip, like you see in the newspaper. Stories in this format generally don't continue past 1 or 2 strips, but there are exceptions. </span><br>          <br>        </td>
        <td width="35%" align='LEFT' valign="top" class="community_thrd"><input type='RADIO' name='newComicType' value='1' checked>
          <strong>Comic Story</strong><br>
<span class="helpnote">A comic story is longer and more involved than a strip. Stories span multiple pages before concluding (if ever). A typical print comic book runs between 24 and 48 pages. </span></td>
      </tr>
      <tr>
        <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Rating:<br>
          </b><span class="helpnote">Some comics aren't for everyone. What is tha appropriate audience for yours? </span></td>
        <td colspan="2" align='LEFT' class="community_thrd">
          <div id='ageGroup'>
            <?
            $ct = 0;
            foreach($RATINGS as $symbol=>$description)
            {
              ?><input type="radio" name="newRating" value="<?=$symbol?>" id="rating_<?=$symbol?>"><label for="rating_<?=$symbol?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0" title="<?=$description?>" alt="<?=$description?>"> <?=ucfirst( strtolower(array_shift(explode(".", $description))) )?></label><?
              if ( ++$ct<count($RATINGS) ) {
                ?> | <?
              }
            }
            ?>
          </div>
        </td>
      </tr>


      <tr>
        <td width='30%' align='left' valign="top" class="community_thrd"><b>Style:<br>
          </b><span class="helpnote">What genre does your comic fit best into? </span></td>
        <td colspan="2" align='LEFT' class="community_thrd">
          <select name='search_style' STYLE='WIDTH:100%;'>
            <?=getKeyValueSelect($COMIC_ART_STYLES, $COMIC_ROW->search_style)?>
          </select>
        </td>
      </tr>
      <tr>
        <td width='30%' align='left' valign="top" class="community_thrd"><b>Categories:<br>
          </b><span class="helpnote">When one is just not enough.</span></td>
        <td colspan="2" align='LEFT' class="community_thrd">
          <select name='search_category' STYLE='WIDTH:49%;'>
            <?=getKeyValueSelect($COMIC_CATEGORIES, $COMIC_ROW->search_category)?>
          </select>
          <select name='search_category_2' STYLE='WIDTH:49%;'>
            <?=getKeyValueSelect($COMIC_CATEGORIES, $COMIC_ROW->search_category_2)?>
          </select>
        </td>
      </tr>

      <tr>
        <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Tone:</b></td>
        <td colspan="2" align='LEFT' class="community_thrd">
          <select name='newComicSubCat' style="width:100%;">
            <?=getKeyValueSelect($COMIC_SUBCATS)?>
          </select>
        </td>
      </tr>

      <tr>
        <td colspan="3" align="left" valign="top">
          <div align="right">
            <input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/comic_create.gif" width="100" height="24" border="0">
          </div>
        </td>
      </tr>
      </form>
  	</table>
<?
include('footer_overview.inc.php');
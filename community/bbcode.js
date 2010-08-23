function injectBBCode( codeStart, codeEnd )
{
	var selLength = $(textareaName).textLength;
	var selStart = $(textareaName).selectionStart;
	var selEnd = $(textareaName).selectionEnd;
	
	if ( typeof(selLength) == 'undefined' )
	{
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			$(textareaName).value += '['+codeStart+']' + '['+codeEnd+']';
			$(textareaName).focus();
			return;
		}
		document.selection.createRange().text = '['+codeStart+']' + theSelection + '['+codeEnd+']';
		$(textareaName).focus();
		return;
	}
	
	
	if (selEnd == 1 || selEnd == 2) {
		selEnd = selLength;
	}
	
	var s1 = ($(textareaName).value).substring(0,selStart);
	var s2 = ($(textareaName).value).substring(selStart, selEnd)
	var s3 = ($(textareaName).value).substring(selEnd, selLength);
	$(textareaName).value = s1 + '['+codeStart+']' + s2 + '['+codeEnd+']' + s3;
}

if ( typeof(textareaName) != 'undefined' )
{
  document.write(' <input type="submit" onClick="injectBBCode(\'u\',         \'/u\');return false;"      value="Underline" class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'i\',         \'/i\');return false;"      value="Italics"   class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'b\',         \'/b\');return false;"      value="Bold"      class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'url\',       \'/url\');return false;"    value="Link"      class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'img\',       \'/img\');return false;"    value="Image"     class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'color=red\', \'/color\');return false;"  value="Color"     class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'size=2\',    \'/size\');return false;"   value="Size"      class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'code\',      \'/code\');return false;"   value="Code"      class="community_bbcode_btn">'+
                 ' <input type="submit" onClick="injectBBCode(\'quote=Someone\',     \'/quote\');return false;"  value="Quote"     class="community_bbcode_btn">');
}
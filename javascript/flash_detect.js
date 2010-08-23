// Flash Version Detector  v1.2.1
// documentation: http://www.dithered.com/javascript/flash_detect/index.html
// license: http://creativecommons.org/licenses/by/1.0/
// code by Chris Nott (chris[at]dithered[dot]com)
// with VBScript code from Alastair Hamilton (now somewhat modified)


function isDefined(property) {
  return (typeof property != 'undefined');
}

var flashVersion = 0;
function getFlashVersion() {
	var latestFlashVersion = 8;
   var agent = navigator.userAgent.toLowerCase(); 
	
   // NS3 needs flashVersion to be a local variable
   if (agent.indexOf("mozilla/3") != -1 && agent.indexOf("msie") == -1) {
      flashVersion = 0;
   }
   
	// NS3+, Opera3+, IE5+ Mac (support plugin array):  check for Flash plugin in plugin array
	if (navigator.plugins != null && navigator.plugins.length > 0) {
		var flashPlugin = navigator.plugins['Shockwave Flash'];
		if (typeof flashPlugin == 'object') { 
			for (var i = latestFlashVersion; i >= 3; i--) {
            if (flashPlugin.description.indexOf(i + '.') != -1) {
               flashVersion = i;
               break;
            }
         }
		}
	}

	// IE4+ Win32:  attempt to create an ActiveX object using VBScript
	else if (agent.indexOf("msie") != -1 && parseInt(navigator.appVersion) >= 4 && agent.indexOf("win")!=-1 && agent.indexOf("16bit")==-1) {
	   var doc = '<scr' + 'ipt language="VBScript"\> \n';
      doc += 'On Error Resume Next \n';
      doc += 'Dim obFlash \n';
      doc += 'For i = ' + latestFlashVersion + ' To 3 Step -1 \n';
      doc += '   Set obFlash = CreateObject("ShockwaveFlash.ShockwaveFlash." & i) \n';
      doc += '   If IsObject(obFlash) Then \n';
      doc += '      flashVersion = i \n';
      doc += '      Exit For \n';
      doc += '   End If \n';
      doc += 'Next \n';
      doc += '</scr' + 'ipt\> \n';
      document.write(doc);
   }
		
	// WebTV 2.5 supports flash 3
 	else if (agent.indexOf("webtv/2.5") != -1) flashVersion = 3;

	// older WebTV supports flash 2
	else if (agent.indexOf("webtv") != -1) flashVersion = 2;

	// Can't detect in all other cases
	else {
		flashVersion = flashVersion_DONTKNOW;
	}

	return flashVersion;
}

flashVersion_DONTKNOW = -1;
var commandStack = new Array();
var onLoadList = new Array();
var onLoadHash = new Array();

function registerOnLoad(func) {
  if ( typeof(onLoadHash[func.toString()]) == 'undefined' ) {
    onLoadList.push(func);
    onLoadHash[func.toString()] = 1;
  }
}

function callOnLoadList() {
  for(var i=0; i<onLoadList.length;i++) {
    onLoadList[i]();
  }
}

var linkTipLive = false;
var LinkTip = {

    show : function(id, linkRef, toolBoxType)
    {
      $('tipdiv').innerHTML = '';
      linkTipLive = true;

      var dim = Element.getDimensions(linkRef);
      //Position.clone(linkRef, $('tipdiv'), {"setLeft":true, "setTop":true, "setWidth":false, "setHeight":false, "offsetTop":dim.height, "offsetLeft":dim.width});
      Position.clone(linkRef, $('tipdiv'), {"setLeft":true, "setTop":true, "setWidth":false, "setHeight":false, "offsetTop":dim.height, "offsetLeft":0});
      var grabAjax = new Ajax.Request( '/xmlhttp/main_page/fetch_mouseover.php', { method: 'get', parameters: 'toolBoxType='+toolBoxType+'&mouseover='+id, onComplete: LinkTip.onData} );
    },

    hide : function()
    {
      $('tipdiv').style.display = 'none';
      linkTipLive = false;
    },

    onData : function(originalReq) {
      if ( !linkTipLive ) return;
      if ( originalReq.responseText == "0" ) {
        return;
      }

      $('tipdiv').innerHTML = originalReq.responseText;
      $('tipdiv').style.display = '';
    }

}

// onMouseOver="LinkTip.show(\'\\1\', this);" onMouseOut="LinkTip.hide();" onClick="return false;" style="cursor:auto;"

/*********************************************
*                                            *
*                                            *
*                                            *
*   New AJAX Managing Classes and Functions  *
*                                            *
*                                            *
*                                            *
*********************************************/
function ajaxCall(page, callback, forcerandom)
{
  var xmlhttp  = null;

  if ( (typeof(forcerandom) != 'undefined') && (forcerandom != false) )
  {
    if ( page.indexOf('?') != -1 ) {
      page += '&'+Math.floor(Math.random()*99999)
    }
    else {
      page += '?'+Math.floor(Math.random()*99999)
    }
  }

  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  }
  catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch (E) {
      xmlhttp = false;
    }
  }
  if ( !xmlhttp && typeof XMLHttpRequest != 'undefined' ) {
    xmlhttp = new XMLHttpRequest();
  }

  if ( !xmlhttp ) alert("Error: Please notify an administrator.");


  xmlhttp.onreadystatechange=function()
  {
    if ( xmlhttp.readyState == 4 )
    {
      if ( callback )
      {
        callback(xmlhttp.responseText);
      }
    }
  }

  xmlhttp.open("GET", page, true);

  xmlhttp.send(null);
}

/*********************************************
*                                            *
*                                            *
*                                            *
*  New Window Managing Classes and Functions *
*                                            *
*                                            *
*                                            *
*********************************************/



/*********************************************
*  Functions                                 *
*********************************************/

function validateYesNo( theVar ) {
  if ( !theVar ) return 'no';
  if ( theVar.toLowerCase() == 'no' ) return 'no';
  else if ( theVar == true ) return 'yes';
  else if ( theVar.toLowerCase() != 'true' ) return 'yes';
  else if ( theVar.toLowerCase() != 'yes' ) return 'no';
  return 'yes';
}

function validateInt( theVar ) {
  if ( !theVar ) return 0;
  return parseInt(theVar);
}

function openerCheck() {
  if ( !windowOpen( window.opener ) ) {
    window.close();
  }
}

function windowOpen(win) {
   if ( !win || win.closed ) {
      return false;
   }
   return true;
}

function WindowFormat(name, width, height, scrolling, scroll, scrollbars, resizable, toolbar, fullScreen)
{
  if ( !width )      width      = null;
  if ( !height )     height     = null;
  if ( !scrolling )  scrolling  = 'yes';
  if ( !scroll )     scroll     = 'yes';
  if ( !scrollbars ) scrollbars = 'yes';
  if ( !resizable )  resizable  = 'yes';
  if ( !toolbar )    toolbar    = 'yes';
  if ( !fullScreen ) fullScreen = 'no';
  if ( !name )       name       = width+height+scrolling+scroll+scrollbars+resizable+toolbar;

  this.setWidth( width );
  this.setHeight( height );
  this.setScrolling( scrolling );
  this.setScroll( scroll );
  this.setScrollBars( scrollbars );
  this.setResizable( resizable );
  this.setToolbar( toolbar );
  this.setName( name );
  this.setFS( fullScreen );
}

WindowFormat.prototype.setWidth     = function(amt)   { this.width      = validateInt(amt);     }
WindowFormat.prototype.setHeight    = function(amt)   { this.height     = validateInt(amt);     }
WindowFormat.prototype.setScrolling = function(yesNo) { this.scrolling  = validateYesNo(yesNo); }
WindowFormat.prototype.setScroll    = function(yesNo) { this.scroll     = validateYesNo(yesNo); }
WindowFormat.prototype.setScrollBars= function(yesNo) { this.scrollbars = validateYesNo(yesNo); }
WindowFormat.prototype.setResizable = function(yesNo) { this.resizable  = validateYesNo(yesNo); }
WindowFormat.prototype.setToolbar   = function(yesNo) { this.toolbar    = validateYesNo(yesNo); }
WindowFormat.prototype.setFS        = function(yesNo) { this.fullscreen = validateYesNo(yesNo); }
WindowFormat.prototype.setName      = function(nombre){ this.name       = nombre; }
WindowFormat.prototype.getFullString= function()
{
  return 'width='     +this.width     +','+
         'height='    +this.height    +','+
         'scrolling=' +this.scrolling +','+
         'scroll='    +this.scroll    +','+
         'scrollbars='+this.scrollbars+','+
         'resizable=' +this.resizable +','+
         'toolbar='   +this.toolbar+','+
         'fullscreen='+this.fullscreen;
}
WindowFormat.prototype.getDetails   = function()
{
  var retStr = "WindowFormat [Object]\n";
  retStr += " .name = "       + this.name       + "\n";
  retStr += " .width = "      + this.width      + "\n";
  retStr += " .height = "     + this.height     + "\n";
  retStr += " .scrolling = "  + this.scrolling  + "\n";
  retStr += " .scroll = "     + this.scroll     + "\n";
  retStr += " .scrollbars = " + this.scrollbars + "\n";
  retStr += " .resizable = "  + this.resizable  + "\n";
  retStr += " .toolbar = "    + this.toolbar    + "\n";
  retStr += " .fullscreen = " + this.fullscreen + "\n";
  return retStr;
}




/*********************************************
*  WindowManager Class                       *
*********************************************/

function WindowManager() {
  this.winArray = new Array();
}
WindowManager.prototype.getDetails   = function()
{
  var retStr = "WindowManager [Object]\n";
  retStr += " .winArray [Array]\n";
  for(var i in this.winArray ) {
    retStr += "  '"+this.winArray[i].name+"' [Window Object]\n";
  }
  return retStr;
}
WindowManager.prototype.cleanup = function() {
  for(var i in this.winArray ) {
    if ( !this.winArray[i] || this.winArray[i].closed ) {
      this.winArray[i] = null;
    }
  }
}
WindowManager.prototype.showBlockedWarn = function() {
  alert('Oops! An important window was blocked from showing. Could it be your popupblocker?');
}
WindowManager.prototype.openWin = function( location, wndFrmtObj )
{
  if ( !location )   return;
  if ( !wndFrmtObj ) wndFrmtObj = new WindowFormat();

  if (location.indexOf('?') == -1) location += '?width='+wndFrmtObj.width+'&height='+wndFrmtObj.height+'&fullscreen='+wndFrmtObj.fullscreen;
  else location += '&width='+wndFrmtObj.width+'&height='+wndFrmtObj.height+'&fullscreen='+wndFrmtObj.fullscreen;

  if ( this.winArray[wndFrmtObj.name] )
  {
    if ( !this.winArray[wndFrmtObj.name].closed && this.winArray[wndFrmtObj.name] )
    {
      if ( this.winArray[wndFrmtObj.name].location == location ) {
        this.winArray[wndFrmtObj.name].focus();
        return;
      }
      else {
        this.winArray[wndFrmtObj.name].close();
      }
    }
    else {
      this.winArray[wndFrmtObj.name] = null;
    }
  }

  this.winArray[wndFrmtObj.name] = window.open(location, wndFrmtObj.name, wndFrmtObj.getFullString());

  if ( !this.winArray[wndFrmtObj.name] ) {
    this.showBlockedWarn();
  }
}

// Create the window manager
var w          = new WindowManager();

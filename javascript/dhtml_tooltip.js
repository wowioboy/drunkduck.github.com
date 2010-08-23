var offsetxpoint = 20 //Customize x offset of tooltip
var offsetypoint = 0 //Customize y offset of tooltip
var thewidth     = 200;
var theheight    = 100;
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
var tippositioned = false;
var lastE;
var cachedReq = new Array();
var lastReq;
if (ie||ns6) {
  var tipobj;
}

function ajaxGetDescription(nm) {
  if ( cachedReq[nm] ) {    
    if ( !findTipObj() ) return;
    if (ns6||ie){
      if (typeof thewidth!="undefined") {
        tipobj.style.width=thewidth+"px"
        tipobj.style.height=theheight+"px"
      }
      //if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
      tipobj.innerHTML='<DIV ALIGN=CENTER>Loading Description...</DIV>';
      enabletip=true;
    }
    
    onDataBack(cachedReq[nm]);
  }
  else {
    lastReq = nm;
    ajaxCall('/xmlhttp/getComicDesc.php?try='+nm, onDataBack);
    
    if ( !findTipObj() ) return;
    if (ns6||ie){
      if (typeof thewidth!="undefined") {
        tipobj.style.width=thewidth+"px"
        tipobj.style.height=theheight+"px"
      }
      //if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
      tipobj.innerHTML='<DIV ALIGN=CENTER>Loading Description...</DIV>';
      enabletip=true;
    }
  }
}

function onDataBack( retStr )
{
  cachedReq[lastReq] = retStr;
  
  if ( enabletip ) {
    ddrivetip('<DIV ALIGN=\'center\'>'+retStr+'</DIV>', '#FF0000');
  }
}

function findTipObj() {
  if ( typeof tipobj == "undefined" ) {
    tipobj = document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : "";
  }
  
  if ( typeof tipobj == "undefined" ) return false;
  return true;
}

function ietruebody(){
  return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor){
  if ( !findTipObj() ) return;
  if (ns6||ie){
    if (typeof thewidth!="undefined") {
      tipobj.style.width=thewidth+"px"
      tipobj.style.height=theheight+"px"
    }
    //if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
    tipobj.innerHTML=thetext
    enabletip=true;
    
 //   positiontip();
    
    return false;
  }
}

function positiontip(e)
{ 
  if ( tippositioned ) return;
  if ( !findTipObj() ) return;
  
  if (enabletip){

    tippositioned = true;
    
    var curX=(ns6)?e.pageX : event.x+ietruebody().scrollLeft;
    var curY=(ns6)?e.pageY : event.y+ietruebody().scrollTop;
    //Find out how close the mouse is to the corner of the window
    var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
    var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20
    
    var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000
    
    //if the horizontal distance isn't enough to accomodate the width of the context menu
    if (rightedge<tipobj.offsetWidth)
    //move the horizontal position of the menu to the left by it's width
    tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
    else if (curX<leftedge)
    tipobj.style.left="5px"
    else
    //position the horizontal position of the menu where the mouse is positioned
    tipobj.style.left=curX+offsetxpoint+"px"
    
    //same concept with the vertical position
    if (bottomedge<tipobj.offsetHeight)
    tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
    else
    tipobj.style.top=curY+offsetypoint+"px"
    tipobj.style.visibility="visible"
  }
}

function hideddrivetip(){
  if ( !findTipObj() ) return;
  if (ns6||ie){
    tippositioned = false;
    enabletip=false
    tipobj.style.visibility="hidden"
    tipobj.style.left="-1000px"
    tipobj.style.backgroundColor=''
    tipobj.style.width=''
  }
}

document.onmousemove = positiontip;
class FLVRotator
{
	var videoArray		:	Array			= null;

	var flvTarget		:	Video			= null;
	var swfTarget		:	MovieClip		= null;
	var posterTarget	:	MovieClip		= null;
	
	var displayTime		:	Number			= 1000;
	
	var videoNow		:	Number			= -1;
	
	var netConn			:	NetConnection	= null;
	var netStream		:	NetStream		= null;
	
	var switchInterval	:	Number			= 0;
	
	function FLVRotator( flvContainer:Video, swfContainer:MovieClip, posterSWF:MovieClip )
	{
		this.videoArray		= new Array();
		this.flvTarget		= flvContainer;
		this.swfTarget		= swfContainer;
		this.posterTarget	= posterSWF;
		
		this.loadRootVars();
		this.beginRotation();
		
		Key.addListener(this);
	}
	
	function loadRootVars()
	{
		if ( (_level0.flvList == undefined) || (_level0.posterList == undefined) ) 
		{
			_level0.flvList		= 'assets/flv1.flv|300x128,assets/flv2.flv|300x100';
//			_level0.flvList		= 'assets/flv1.flv|586x250,assets/flv2.flv|300x100';
			_level0.posterList	= 'assets/poster1.gif,assets/poster2.gif';
			_level0.displayTime = 5000;
			_level0.startPoint  = 0;
		}
		
		this.displayTime = _level0.displayTime;
		
		/* All the following vars are throw away temp vars */
		var tmpFlvArr		= _level0.flvList.split(',');
		var tmpPosterArr	= _level0.posterList.split(',');
		
		for( var i=0; i<tmpFlvArr.length; i++ )
		{
			var video		= new Object();
		
			var pack		= tmpFlvArr[i].split('|');
			var sizes		= pack[1].split('x');
		
			video.url		= pack[0];
			video.width		= sizes[0];
			video.height	= sizes[1];
			video.posterURL	= tmpPosterArr[i];
			
			this.videoArray.push( video );
		}
	}
	
	function beginRotation()
	{
		this.nextVideo();
	}
	
	function nextVideo( dir:Number )
	{
		if ( dir == undefined ) dir = 1;
		
		this.flvTarget.clear();
		this.netStream.close();
		
		this.videoNow += dir;
		if ( this.videoNow > this.videoArray.length-1 ) {
			this.videoNow = 0;
		}
		else if ( this.videoNow < 0 ) {
			this.videoNow = this.videoArray.length-1;
		}
		
		this.playPoster();
	}
	
	function playPoster()
	{
		this.posterTarget.loadTarget.unloadMovie();
		this.posterTarget.loadTarget.loadMovie(this.videoArray[this.videoNow].posterURL);
		this.posterTarget.loadTarget._alpha = 100;
		this.posterTarget._visible	= true;
		this.posterTarget.parentObj	= this;
		this.posterTarget.onEnterFrame = undefined;
		this.posterTarget.onRelease = function() {
			this._alpha = 100;
			this.onEnterFrame = undefined;
			this.parentObj.playVideo();
		}
		
		this.switchInterval = setInterval(this, "fadePoster", this.displayTime);
	}
	
	function fadePoster()
	{
		clearInterval(this.switchInterval);		
		this.posterTarget.onEnterFrame = function() {
			this.loadTarget._alpha -= 2;
			if ( this.loadTarget._alpha <= 0 ) {
				this.parentObj.nextVideo(1);
			}
		}
	}
	
	function playVideo()
	{
		clearInterval(this.switchInterval);		
		
		this.posterTarget.stop();
		this.posterTarget._visible	= false;
		
		this.swfTarget.stop();
		this.swfTarget._visible		= false;

		this.flvTarget.clear();
		
		this.addFLVVideo( this.videoArray[this.videoNow] );
	}
	
	function addFLVVideo( videoObject:Object )
	{
		this.netConn = new NetConnection();
		this.netConn.connect( null );
		
		this.netStream.close();
		this.netStream				= new NetStream( this.netConn );
		this.netStream.parentObj	= this;
		
		this.netStream.onStatus	= function( info )
		{
			/*
			NetStream.Play.Start
			NetStream.Buffer.Empty
			NetStream.Buffer.Full
			NetStream.Buffer.Flush
			NetStream.Play.Stop
			*/
			if ( info.code == "NetStream.Play.Start" )
			{

			}
			else if ( info.code == "NetStream.Play.Stop" )
			{
				this.parentObj.netConn		= null;
				this.parentObj.netStream	= null;
				this.parentObj.flvTarget.clear();
				this.parentObj.nextVideo();
			}
		}
		
		this.flvTarget.clear();
		this.flvTarget.attachVideo( this.netStream );

		this.flvTarget._width	= videoObject.width;
		this.flvTarget._height	= videoObject.height;
		
		if ( this.flvTarget._height != Stage.height )
		{
			var extraSpace = Math.abs( Stage.height - this.flvTarget._height );
			this.flvTarget._y = extraSpace/2;
		}
		else {
			this.flvTarget._y = 0;
		}
		
		if ( this.flvTarget._width != Stage.width )
		{
			var extraSpace = Math.abs( Stage.width - this.flvTarget._width );
			this.flvTarget._x = -(extraSpace/2);
		}
		else {
			this.flvTarget._x = 0;
		}
		
		this.netStream.play( videoObject.url );
	}
	
	function onKeyDown()
	{
		if ( Key.isDown(Key.LEFT) ) {
			this.nextVideo(-1);
		}
		else if ( Key.isDown(Key.RIGHT) ) {
			this.nextVideo(1);
		}
	}
}
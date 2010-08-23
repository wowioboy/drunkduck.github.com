function getDetails( date )
{
  $('cal_details_content').innerHTML = '';

  new Ajax.Request( '/includes/calendar/xmlhttp/getDetails.php', { method: 'post', parameters: 'date='+date, onComplete: onDetails} );

}


function onDetails( originalReq )
{
  $('cal_details_content').innerHTML = originalReq.responseText;
  $('cal_calendar').style.display = 'none';
  $('cal_details').style.display  = '';
}

/*
function serializeAndSubmit( frm )
{
  formRef = frm;
  var saveFrm = new Ajax.Request( '/xmlhttp/submit_video.php', { method: 'post', parameters: Form.serialize(frm), onComplete: onSubmittedVideo} );
  Form.disable(frm);
  }

  function onSubmittedVideo(originalReq) {
  Form.enable(formRef);
  eval(originalReq.responseText);
  new Effect.BlindDown('submit_video_link');
  new Effect.BlindUp('submit_video');
}

*/
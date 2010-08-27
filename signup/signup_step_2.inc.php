<?
require_once('signup_data.inc.php');
require_once('signup_func.inc.php');
require_once(PACKAGES.'/wordfilter_package/load.inc.php');

if ( $USER ) {
  echo "<DIV ALIGN='CENTER'>Oops! You are already logged in!</DIV>";
  return;
}

if ( !isset($_GET['a']) || (($_GET['a'] != 1) && ($_GET['a'] != 2)) ) {
  header('Location: /signup/index.php');
}

?>

<script type="text/javascript">
function formIsValid( frm )
{
  var badCharRegex = new RegExp("([^a-zA-Z0-9_ ])");

  if ( frm.pw.value != frm.pw_verify.value ) {
    alert('Your passwords DID NOT MATCH. Please try again.');
    return false;
  }
  else if ( frm.pw.value.length < 6 ) {
    alert('Your password must be at least 6 characters long.');
    return false;
  }
  
  if ( frm.uname.value.length < 3 ) {
    alert('Your username must be at least 3 characters long. Please try again.');
    return false;
  }
  else if ( badCharRegex.test( frm.uname.value ) ) {
    alert('Your username contained bad characters. Please only use numbers, letters, underscores, and spaces.');
    return false;
  }
  
  if ( !frm.iAgree.checked ) {
    alert('Since you have not agreed to our TERMS OF SERVICE, you may not continue.');
    return false;
  }

  if ( !frm.gender[0].checked && !frm.gender[1].checked ) {
    alert('Please check one of the gender options.');
    return false;
  }
  
  return true;
}

function updateNameInfo(data)
{
  document.getElementById('nameInfo').innerHTML = data;
}

</script>


<FORM ACTION='signup_step_3.php' METHOD='POST' onSubmit="return formIsValid(this);">
<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='400'>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Username</B>
      <BR>
      <SPAN STYLE='FONT-SIZE:9px;'>Minimum 3 characters long, and may contain only LETTERS, NUMBERS, UNDERSCORES, and SPACES.</SPAN>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <DIV ID='nameInfo' CLASS='microalert'></DIV>
      <INPUT TYPE='TEXT' NAME='uname' STYLE='WIDTH:100%;' onKeyUp="ajaxCall('/xmlhttp/try_username.php?try='+this.value, updateNameInfo);">
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Password</B>
      <BR>
      <SPAN STYLE='FONT-SIZE:9px;'>Minimum 6 characters long.</SPAN>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='PASSWORD' NAME='pw' STYLE='WIDTH:100%;'>
    </TD>
  </TR>
  
  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Re-enter Password</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='PASSWORD' NAME='pw_verify' STYLE='WIDTH:100%;'>
    </TD>
  </TR>
  
  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Gender</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='RADIO' NAME='gender' VALUE='m'>Male
      <INPUT TYPE='RADIO' NAME='gender' VALUE='f'>Female
    </TD>
  </TR>
  
  <TR>
    <TD ALIGN='LEFT' COLSPAN='2'><?
    if ( PLATINUM_OWNED == 1 )
    {
      ?>
      <TEXTAREA STYLE='width:390px;height:300px;'>PRIVACY POLICY

Your privacy is important to us. This privacy policy explains our online information practices and how information is collected and used at this website ("Website"). Please note that our other affiliated websites may operate under their own privacy policies so you should review those privacy policies when visiting those websites. This privacy policy only applies to the personal information we collect on this Website and it does not apply to any other information Wowio, Inc collects through other means.

Personal Information We May Collect

This Website may collect personally-identifiable information and non-personally identifiable information about you. 

Personally-identifiable information you provide to us may include the following: your name and home address, your home phone number, your email address, your financial information, such as credit card numbers, your household income, your education and work experience, and other details of your personal life, such as your hobbies.

Non-personally identifiable information may include the type of browser you are using, your IP address, and your Internet service provider. 

This Website may also use cookies, which typically allow you to navigate between different web pages without losing the information you supplied at the start of any session. You may be able to accept or decline cookies depending on the capabilities and configuration of your browser. Most browsers automatically accept cookies and can be configured to decline cookies if you so desire. However, if you decline cookies, it may affect your ability to experience certain features of this Website. Wowio, Inc cannot and does not assume responsibility for any technical limitations or malfunction of your browser or for use of cookies by third parties that provide hosting or other services for this Website.

This Website typically uses such non-personally identifiable information to accumulate aggregate data on the Website, for tracking purposes or to provide you with additional products or services.

Use of Personal Information

This Website may use your personal information to complete transactions you request or to send you communications about your account, your submissions, or about new features on this Website. We may also use your personal information to send you promotional materials, including special offers and promotions.

Access to Personal Information

We may share your personal information with our affiliates or other companies in our corporate family. We may provide your personal information to other companies whom we may utilize in processing our online transactions or other services. We may be required by law to disclose your personal information, such as in response to a court order or subpoena or at a law enforcement agency's request, and we reserve the right to do so. We may share your personal information as part of a purchase or sale pursuant to appropriate confidentiality provisions, such as if Wowio, Inc merges with another company or sells a business division.

Opt-Out Choices

You may always choose to opt out of receiving promotional materials. If you wish to opt out of receiving such correspondence, please follow the opt-out instructions included in the applicable correspondence.

Updating or Correcting Your Personal Information

If you wish to review, update or correct any of your personal information in this Website's possession, please contact us using the contact information provided below.

Security Measures

Not all information provided while using this Website is necessarily secure against electronic interception or tampering. While this Website does incorporate secure software and encryption to protect certain sensitive information you may provide, as with any Internet transmission, there is always some element of risk in sending personal information. For that reason, Wowio, Inc cannot guarantee that all personal or other sensitive information will remain confidential, and we must disclaim any liability for the inadvertent or unknowing disclosure or use of such information.

Child Online Privacy

This Website is not generally intended for anyone under the age of 18. We do not knowingly collect personal information from children under the age of 13. If you are 13 or under, please do not submit your personal information on this Website. 

Links to Other Sites

This Website may contain links to other sites. We are not responsible for the privacy policies or practices, content, or any other aspect of these other sites, and you link to them at your own risk.

Changes to this Privacy Policy

We may change this Website's policies concerning the use of your personal information and other privacy policies from time to time. We will post any such changes on this Website. For that reason, we encourage you to periodically review this privacy policy to keep yourself informed of any changes.

How to Contact Us

We encourage you to share any comments or concerns you may have about this privacy policy or other aspects of this Website at the address given below. However, please note that by accessing this Website or submitting your personal information, you acknowledge and agree to be bound by this privacy statement, as amended or revised from time to time, in addition to all other terms of use posted at this Website.

For questions about this privacy policy or to contact us:

Wowio, Inc

3545 Motor Ave, 3rd Floor

Los Angeles, CA 90034

info@drunkduck.com

Last Modified: August 31, 2006 



TERMS OF USE

Legal Compliance

Users of this website ("Website") acknowledge and agree that they must observe all applicable state, local and federal laws and agree not to submit any material that is illegal, offensive or inappropriate in any way. This Website reserves the right in its sole discretion to remove any submitted materials or other communications that it deems not to be in compliance with the foregoing.

Links to Outside Sites

This Website assumes no responsibility for the materials provided on any site that is linked to this Website, regardless of whether or not it is an affiliated or third party site. Any entry to a linked site is made at your own risk.

Disclaimer

THIS WEBSITE AND ALL MATERIALS AND OTHER INFORMATION PROVIDED HEREIN ARE PROVIDED "AS IS" AND WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IF APPLICABLE LAW DOES NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, THEN THE ABOVE EXCLUSION MAY NOT APPLY.

THIS WEBSITE DOES NOT ASSUME RESPONSIBILITY OR LIABILITY FOR ANY ERRORS OR OMISSIONS PERTAINING TO THE MATERIALS OR OTHER INFORMATION PROVIDED IN THIS WEBSITE AND EXPRESSLY DISCLAIMS ALL LIABILITY REGARDING ACTIONS TAKEN OR NOT TAKEN BY USERS BASED ON THE MATERIALS AND OTHER INFORMATION PROVIDED IN THIS WEBSITE. THIS WEBSITE DOES NOT ASSUME ANY RESPONSIBILITY FOR COMPUTER VIRUSES OR OTHER HARMFUL COMPONENTS RESULTING FROM THE USE OF THIS WEBSITE OR LINKS FROM THIS WEBSITE. 

Limitation of Liability

UNDER NO CIRCUMSTANCES SHALL THIS WEBSITE BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTIAL, SPECIAL, CONSEQUENTIAL OR EXEMPLARY DAMAGES THAT MAY RESULT FROM THE USE OF OR INABILITY TO USE THIS WEBSITE OR MATERIALS THEREON, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. UNDER NO CIRCUMSTANCES SHALL OUR TOTAL LIABILITY TO YOU FOR ALL DAMAGES, LOSSES AND CAUSES OF ACTION, WHETHER IN CONTRACT, TORT OR OTHERWISE EXCEED THE SUM OF $100.00 FOR ACCESSING OR PARTICIPATING IN ANY ACTIVITY RELATED TO THIS WEBSITE.

General Provisions

This Website's terms of use are governed by and construed in accordance with California law, without giving effect to any principles of conflicts of law. You expressly consent to submit to the exclusive jurisdiction of the state or federal courts located in the County of Los Angeles, California. The provisions of these terms of use are severable, and if any one or more provision may be determined to be judicially unenforceable, in whole or in part, the remaining provisions shall nevertheless be binding and enforceable.

Changes to Terms of Use

We may change these terms of use from time to time. We will post any such changes on this Website. For that reason, we encourage you to periodically review these Terms of Use to keep yourself informed of any changes.

Last Modified: August 31, 2006 



DrunkDuck Etiquette 

DrunkDuck is a great place for hosting your comics. It is a wonderful community that will welcome you with open arms and treat you with respect. 

Great communities however do not spring from thin air. In order to foster a respectful, welcoming and creative community, we kindly ask that users do not engage in trollish behavior. This includes forum flames, disrespectful behavior to other members and flame baiting. Users who do engage in such behaviors will be suspended/removed and eventually banned. Should there be any personal disagreements or concerns between members, we ask that the users involved resolve the matter privately. 

DrunkDuck has no interest in wantonly oppressing or interfering with a user's artistic pursuits. While you are perfectly in your right to say what you want and exercise your freedom of speech, please bear in mind that DrunkDuck is privately owned and operated. In other words, it is not a public access site and the administrative team will modify or take down content if necessary. We reserve the right to remove or take down any art, comments, etc. we find inappropriate for any reason.

That said, you are pretty much able to do anything you want with your comic account as long as you follow the Terms of Use and these rules: 

Don't disrespect your comic host (DrunkDuck). Constructive criticisms are welcomed but don't bite the hand that feeds you. 

Don't disrespect your (DrunkDuck) community members. 

Violation of other artist?s copyrights is not acceptable. If you use artwork without permission of its creator, and the creator/copyright holder complains to the administrators, we will remove the offensive file. If you continue to do this, we will delete your account. 

If you find someone is using your artwork without permission and would like to see it stop, do not hesitate to contact an administrator for help. Do not begin a flame war with the offending user. 

Do not tamper with the DrunkDuck headers or footers on your page. We need those there for legal and administrative reasons. If you link off of DrunkDuck to your own page please have the courtesy to provide a link back to DrunkDuck. 

Understand that Administrators reserve the right to remove your account for the reasons above.



Copyright and Authorized Use

All materials contained in this website ("Website Materials") including comic strips, books, art, illustrations, images, or text are trademarks or copyrighted materials owned and controlled by Wowio, Inc or its subsidiaries, affiliated entities and/or third-party licensors and/or individual creators, and are protected by applicable trademark and/or copyright law. 

No Website Materials may be copied, modified, sold, leased, republished, posted, transmitted, distributed, or used in any way without obtaining Wowio, Inc's prior written consent.

No comic strips, books, art, illustrations, images, or text, may be copied, modified, sold, leased, republished, posted, transmitted, distributed, or used in any way without obtaining the copyright holder?s prior written consent.

All users are expressly prohibited from creating derivative works of or otherwise exploiting the Website Materials including comic strips, books, art, illustrations, images, or text in any manner whatsoever.

The name DrunkDuck, the DrunkDuck logo, images of The Duck and Quail, and related symbols are trademarks and service marks owned by Wowio, Inc., and are used with its permission. 

</TEXTAREA>
      <?
    }
?>
      <INPUT TYPE='CHECKBOX' NAME='iAgree' VALUE='yes'>
      <I>By checking this box you are agreeing to our terms.</I>
    </TD>
  </TR>
  
  <TR>
    <TD ALIGN='CENTER' COLSPAN='2'>
      <INPUT TYPE='SUBMIT' VALUE='Continue!'>
    </TD>
  </TR>
  
</TABLE>
<INPUT TYPE='HIDDEN' NAME='ageGroup' VALUE='<?=$_GET['a']?>'>
</FORM>
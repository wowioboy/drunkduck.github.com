<h1 align="left">DrunkDuck comic Rating System</h1>

<br><br>

<?
if ( isset($_GET['denied']) )
{
  ?>
  <script>
  window.location = '/signup/?restricted=1';
  </script>
  <div align="center" style="border:1px dashed white;padding:5px;width:500px;background:#000000">
    Oops! You tried to view a comic that had an inappropriate rating for your profile.
    <br>
    You tried to view:
    <br>
    <font style="font-size:24px"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$_GET['denied']?>_lg.gif"></font>
    <br>
    Please see the chart below for an explanation of ratings and their restrictions.
  </div>
  <br><br>
  <?
}
?>

<div align="left" style="width:500px;">
  The following system will be implemented for rating the content of comics on DrunkDuck. While it is creator driven, Admins will have the ability to override and lock down ratings. Ratings will be shown at the top of every comics' page.
</div>

<br><br>


<table border="1" cellpadding="2" cellspacing="0" width="500">
  <tr>
    <td align="center">
      Rating
    </td>
    <td align="center">
      Restrictions
    </td>
    <td align="center">
      Description
    </td>
  </tr>

  <tr bgcolor="#000000">
    <td align="center">
      <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_K_lg.gif">
    </td>
    <td align="center">
      None.
    </td>
    <td align="left">
      Suggested for Children. Content is not only deemed suitable for all ages, but recommended for children.
      This rating is subject to the discretion of DrunkDuck.com.
    </td>
  </tr>

  <tr bgcolor="#000000">
    <td align="center">
      <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_E_lg.gif">
    </td>
    <td align="center">
      None.
    </td>
    <td align="left">
      EXERYBODY. Open to everybody. Non-offensive material in text or imagery. Apropriate for children to adults.
    </td>
  </tr>

  <tr bgcolor="#000000">
    <td align="center">
      <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_T_lg.gif">
    </td>
    <td align="center">
      None.
    </td>
    <td align="left">
      Content is suitable for teens or older. Mild violence, slightly mature themes. No obscenities, graphic violence, sex, or nudity.
    </td>
  </tr>

  <tr bgcolor="#000000">
    <td align="center">
      <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_M_lg.gif">
    </td>
    <td align="center">
      Recommended for 13 and older.
    </td>
    <td align="left">
      Content contains mature themes that might not be suitable for children.
      May contain occasional obscenities, limited graphic violence, implied sex, mild nudity.
      No sexually explicit material, no graphic sexual acts, no Hentai, no unrelenting violence, or non-stop obscenities.
    </td>
  </tr>

  <tr bgcolor="#000000">
    <td align="center">
      <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_A_lg.gif">
    </td>
    <td align="center">
      18+ Logged In.
    </td>
    <td align="left">
      Content is sexually explicit, exceedingly violent, or an excess use of obscenities.
    </td>
  </tr>

</table>

<div align="left" style="width:500px;">

<br><br>

It is DrunkDuck's intention to comply with all Federal Internet child protection and Internet pornography laws.

<br><br>

While DrunkDuck does believe in free speech and gives tremendous leeway, there is a point where someone steps over the line.  We reserve the right to remove material we find created for no other reason than to be patently extremely offensive; hate mongering; unconscionable to the extreme; contains child pornography or explicit pedophilia; extreme racism or bigotry; or for some other reason exceeds our very liberal standards. You get the picture.

</div>
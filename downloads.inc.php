<DIV STYLE='WIDTH:800px;' CLASS='container'>
<FONT STYLE='font-size:20px;'><B><U>DrunkDuck Alerter v1.0b</U></B> (It's Free!)</FONT>
<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='10' WIDTH='100%'>
  <TR>
    <TD ALIGN='LEFT'>
      <A HREF='<?=$_SERVER['PHP_SELF']?>' onClick="alert('Please scroll down for the download links');return false;"><IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/DuckNotifier.jpg' ALIGN='LEFT' BORDER='0'></A>

        A little icon that resides in your Windows System Tray, and alerts you of things you want to know on DrunkDuck.com, such as:
        <br>
        <li>When your favorite comics have updated.</li>
        <li>When users have commented on your comic.</li>
        <li>How many users have made your comic a favorite.</li>

        <br><br><br>
        <div align='left'><B>Screen Shots</B></div>
        <DIV ALIGN='CENTER' STYLE='border:1px solid black;'>
          <br>
          <IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/alerter_sample_1.gif' STYLE='border:1px solid black;'>
          <br><br>
          <IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/alerter_sample_2.gif' STYLE='border:1px solid black;'>
        </DIV>
        <br><br>


        <div align='left'><B><U>System Requirements</U></B></div>

          <li><i>Windows Operating System</i></li>
          <li><i>The latest <A HREF='http://www.microsoft.com/downloads/details.aspx?FamilyID=0856EACB-4362-4B0D-8EDD-AAB15C5E04F5&displaylang=en'>Microsoft Windows .NET Framework</A></i> <FONT style='font-size:9px;'>(<i>Note: If you do not have the latest .NET framework... don't worry! It will offer to install for you!</i>)</FONT></li>
        <br><br>

      <?
        $res = db_query("SELECT SUM(counter) as total_dl FROM downloads");
        $row = db_fetch_object($res);
        db_free_result($res);
      ?>
      <div align='left'><B>Download</B> (downloaded <?=number_format($row->total_dl)?> times!)</div>
      <DIV ALIGN='CENTER' STYLE='border:1px solid black;'>
        <br>
        <A HREF='http://<?=DOMAIN?>/download_file.php?fid=1'>.Zip Install Files</A>
        <br>OR<br>
        <A HREF='http://<?=DOMAIN?>/download_file.php?fid=2'>Microsoft Installer</A>
        <br>
        <br>
      </DIV>
    </TD>
  </TR>
</TABLE>

</DIV>
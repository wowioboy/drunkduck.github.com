<SPAN CLASS='headline'>YOUR ACCOUNT IS TEMPORARILY FROZEN</SPAN>

<DIV STYLE='WIDTH:300px;HEIGHT:100px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Status:</DIV>
  
  <TABLE BORDER='0' WIDTH='100%' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='50%'>
        <B>Current Warning:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='50%'>
        <?=$USER->warning?>%
      </TD>
    </TR>
    
    <TR>
      <TD ALIGN='LEFT' WIDTH='50%'>
        <B>Days Remaining:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='50%'> 
        <?=number_format(101-$USER->warning)?>
      </TD>
    </TR>
  </TABLE>
  
</DIV>
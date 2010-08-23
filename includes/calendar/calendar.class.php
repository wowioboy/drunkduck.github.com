<?


class CalendarMonth
{
  var $year;
  var $month;
  var $dayNow;

  var $daysInMonth;
  var $weeksInMonth;
  var $firstDay;

  var $dayArray   = array();
  var $dayContent = array();
  var $dayMap     = array();

  var $editMode   = false;

  function CalendarMonth($year=false, $month=false, $dayNow=false)
  {
    if ( !$year ) $year = date("Y");
    if ( !$month ) $month = date("n");
    if ( !$day ) $day = date("j");

    $this->year   = $year;
    $this->month  = $month;
    $this->dayNow = $dayNow;

    // get number of days in month, eg 28
    $this->daysInMonth = date("t", mktime( 0, 0, 0, $this->month, 1, $this->year ) );

    // get first day of the month, eg 4
    $this->firstDay = date("w", mktime( 0, 0, 0, $this->month, 1, $this->year) );

    // calculate total spaces needed in array
    $tempDays = $this->firstDay + $this->daysInMonth;

    // calculate total rows needed
    $this->weeksInMonth = ceil( $tempDays/7 );

    $this->populateDays();
  }


  function populateDays()
  {
    $counter = 0;
    // Populate the array of days.
    for( $j=0; $j<$this->weeksInMonth; $j++ )
    {
        for( $i=0; $i<7; $i++ )
        {
            $counter++;
            $this->dayArray[$j][$i] = $counter - $this->firstDay;

            if ( ($this->dayArray[$j][$i] < 1) || ($this->dayArray[$j][$i] > $this->daysInMonth) )
            {
                $this->dayArray[$j][$i] = false;
            }

            $this->dayContent[$j][$i] = false;
            $this->dayMap[$counter - $this->firstDay] = array($j, $i);
        }
    }
  }


  function addContent( $day, $htmlContent )
  {
    $arr = $this->dayMap[$day];
    $this->dayContent[$arr[0]][$arr[1]] = $htmlContent;
  }


  function render( $width=400, $height=false )
  {
    $css_cell_height  = false;
    $css_cell_width   = false;

    if ( $height ) {
      $css_cell_height = floor($height / 7);
    }

    if ( $width ) {
      $css_cell_width = floor($width / 7);
    }

    ob_start();
    ?>
    <div id="cal_calendar">
    <table <?=( ($width) ? 'width="'.$width.'"' : '' )?> <?=( ($height) ? 'height="'.$height.'"' : '' )?> border="0" cellpadding="0" cellspacing="0" class="cal_table">
    	<tr>
    		<td colspan='7' class="cal_date" style="<?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>"><?= date('M', mktime( 0, 0, 0, $this->month, 1, $this->year) ).' '.$this->year; ?></td>
    	</tr>
    	<tr>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Sun</td>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Mon</td>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Tue</td>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Wed</td>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Thur</td>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Fri</td>
    		<td class="cal_dayofweek" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>">Sat</td>
    	</tr>

    <?php
    foreach ($this->dayArray as $weekIndex => $week )
    {
    	?><tr><?

    	for ( $dow=0; $dow<7; $dow++ )
    	{
    	  $YMD = $this->year . str_pad($this->month, 2, 0, STR_PAD_LEFT) . str_pad($week[$dow], 2, 0, STR_PAD_LEFT);
    		?><td class="cal_day" style="<?=( ($css_cell_width) ? 'width: '.$css_cell_width.'px;' : '' )?><?=( ($css_cell_height) ? 'height: '.$css_cell_height.'px;' : '' )?>"><?

    		  if ( $this->dayContent[$weekIndex][$dow] )
    		  {
            ?><a href="#" onClick="getDetails(<?=$YMD?>);return false;" class="cal_linkOn"><?=$week[$dow]?></a><?
    		  }
    		  else if ( $this->editMode )
    		  {
    		    ?><a href="#" onClick="getDetails(<?=$YMD?>);return false;" class="cal_linkOff"><?=$week[$dow]?></a><?
    		  }
    		  else
    		  {
    		    echo $week[$dow];
    		  }

        ?></td><?
    	}

    	?></tr><?
    }

    ?></table></div>
    <div id="cal_details" class="cal_details" style="display:none;<?=( ($width) ? 'width: '.$width.'px;' : '' )?><?=( ($height) ? 'height: '.$height.'px;' : '' )?>">
      <div id="cal_details_hdr" class="cal_details_hdr"><a href="#" <a href="#" onClick="$('cal_details').style.display='none';$('cal_calendar').style.display='';">X</a></div>
      <div id="cal_details_content" class="cal_details_content" style="<?=( ($width) ? 'width: '.$width.'px;' : '' )?><?=( ($height) ? 'height: '.($height-20).'px;' : '' )?>"></div>
    </div><?

    return ob_get_clean();
  }

}

?>
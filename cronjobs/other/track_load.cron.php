#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');


exec('ps aux', $feedback);

$REAL_FEEDBACK = array();
for ($i=0; $i<count($feedback); $i++)
{
  $line_array = preg_split('/ /', $feedback[$i], 0, PREG_SPLIT_NO_EMPTY);
  $REAL_FEEDBACK[$line_array[0]][] = $line_array;
  $USER_CT[$line_array[0]]++;
}



foreach($REAL_FEEDBACK as $user=>$data)
{
  if ($user != 'USER' && !strstr($data[0][count($data[0])-1], 'idle') )
  {
    $CPU=$MEM=$VSZ=$RSS=0;
    for($i=0; $i<count($data); $i++)
    {
      $piece = &$data[$i];

      if ( $piece[2] > $MOST_CPU[$user] ) {
        $MOST_CPU[$user]      = $piece[2];
        $now=10;
        $MOST_CPU_PROC[$user] = '';
        while($piece[$now]) {
          $MOST_CPU_PROC[$user] .= $piece[$now++].' ';
        }
      }

      if ( $piece[3] > $MOST_MEM[$user] ) {
        $MOST_MEM[$user]      = $piece[3];
        $now=10;
        $MOST_MEM_PROC[$user] = '';
        while($piece[$now]) {
          $MOST_MEM_PROC[$user] .= $piece[$now++].' ';
        }
      }

      $CPU += $piece[2];
      $MEM += $piece[3];
      $VSZ += $piece[4];
      $RSS += $piece[5];
    }
    $TOTAL_MEMORY += $MEM;
    $TOTAL_CPU    += $CPU;
    $TOTAL_VIR    += $VSZ;
    $TOTAL_RSS    += $RSS;
  }
}

db_query("INSERT INTO load_tracker ( ymd_date, hour, quarter, memory_load, cpu_load ) VALUES ( '".date("Ymd")."', '".date("H")."', '".floor(date("i")/15)."', '".$TOTAL_MEMORY."', '".$TOTAL_CPU."' )");
if ( db_rows_affected() < 1 )
{
  $res = db_query("SELECT * FROM load_tracker WHERE ymd_date='".date("Ymd")."' AND hour='".date("H")."' AND quarter='".floor(date("i")/15)."'");
  $row = db_fetch_object($res);
  if ( $row->memory_load < $TOTAL_MEMORY )
  {
    db_query("UPDATE load_tracker SET memory_load='".$TOTAL_MEMORY."' WHERE ymd_date='".date("Ymd")."' AND hour='".date("H")."' AND quarter='".floor(date("i")/15)."'");
  }
  if ( $row->cpu_load < $TOTAL_CPU )
  {
    db_query("UPDATE load_tracker SET cpu_load='".$TOTAL_CPU."' WHERE ymd_date='".date("Ymd")."' AND hour='".date("H")."' AND quarter='".floor(date("i")/15)."'");
  }
}

echo "Total CPU: "+$TOTAL_CPU."\n";
echo "Total MEM: "+$TOTAL_MEMORY."\n";
?>

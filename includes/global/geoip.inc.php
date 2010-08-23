<?php
function getALLfromIP($addr,$db)
{
  // this sprintf() wrapper is needed, because the PHP long is signed by default
  $ipnum = sprintf("%u", ip2long($addr));
  $query = "SELECT cc, cn FROM geoip_ip NATURAL JOIN geoip_cc WHERE ${ipnum} BETWEEN start AND end";
  $result = db_query($query);

  if((! $result) or db_num_rows($result) < 1)
  {
    return false;
  }
  return db_fetch_array($result);
}

function getCCfromIP($addr,$db) {
  $data = getALLfromIP($addr,$db);
  if($data) return $data['cc'];
  return false;
}

function getCOUNTRYfromIP($addr,$db) {
  $data = getALLfromIP($addr,$db);
  if($data) return $data['cn'];
  return false;
}

function getCCfromNAME($name,$db) {
  $addr = gethostbyname($name);
  return getCCfromIP($addr,$db);
}

function getCOUNTRYfromNAME($name,$db) {
  $addr = gethostbyname($name);
  return getCOUNTRYfromIP($addr,$db);
}
?>
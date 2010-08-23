<?php
// Database related functions.
// Wrap everything, for conversion if needed, and proper tracking/optimization


// Lets require the feedback functions here, since it's sorta part of the whole dealie.
if ( DEBUG_MODE == 1 ) {
  require_once(PACKAGES.'/database_package/global_db_feedback.inc.php');
}

/**
 * This function is called when a database connection is needed ( Should not be called manually ).
 * @param  String $host
 * @return Resource Handle
 */
function db_connect($host, $dbUser, $dbPass)
{
  if ( $GLOBALS['CONNECTIONS'][$host] )
  {
    //if ( DEBUG_MODE == 1 ) {
    //  $Q        = new QueryInfo('db_connect(\''.$host.'\', \''.str_pad('', strlen(DB_USER), '*').'\', \''.str_pad('', strlen(DB_PASS), '*').'\')');
    //  query_log( $Q );
    //}
    return $GLOBALS['CONNECTIONS'][$host];
  }
  else
  {

    $mtime  = microtime();
    $mtime  = explode(" ",$mtime);
    $mtime1 = $mtime[1] + $mtime[0];
    $ERR    = false;

    if ( !$GLOBALS['CONNECTIONS'][$host] = @mysql_connect($host, $dbUser, $dbPass) )
    {
      if ( DEBUG_MODE == 1 ) {
        $ERR =  mysql_error();
      }
    }

    $mtime  = microtime();
    $mtime  = explode(" ",$mtime);
    $mtime2 = $mtime[1] + $mtime[0];

    if ( DEBUG_MODE == 1 )
    {
      $Q        = new QueryInfo('db_connect(\''.$host.'\', \''.str_pad('', strlen(DB_USER), '*').'\', \''.str_pad('', strlen(DB_PASS), '*').'\')');
      $Q->time  = $mtime2 - $mtime1;
      if ( $ERR ) {
        $Q->error = $ERR;
      }
      query_log( $Q );
    }
  }
  return $GLOBALS['CONNECTIONS'][$host];
}

/**
 * This function is used internally to find an existing connection for a table.
 * @param  String $table
 * @return Resource Handle
 */
function db_get_connection_resource( $table )
{
  if ( !$GLOBALS['TABLE_TO_DB'][$table] )
  {
    $dbFind = db_find_table($table);

    if ( !$dbFind )
    {
      if ( DEBUG_MODE == 1 ) {
        $Q        = new QueryInfo('db_get_connection_resource($table=\''.$table.'\')');
        $Q->error = 'No host/database entries for table.';
        query_log( $Q );
        return false;
      }
    }
    else
    {
      $HOST  = &$dbFind->host;
      $DB    = &$dbFind->database;
      $UNAME = &$dbFind->user;
      $UPASS = &$dbFind->pass;
    }
  }
  else
  {
    $HOST  = &$GLOBALS['TABLE_TO_DB'][$table]->host;
    $DB    = &$GLOBALS['TABLE_TO_DB'][$table]->database;
    $UNAME = &$GLOBALS['TABLE_TO_DB'][$table]->user;
    $UPASS = &$GLOBALS['TABLE_TO_DB'][$table]->pass;
  }

  if ( !$CON_RES = $GLOBALS['CONNECTIONS'][$HOST] )
  {
    $CON_RES = db_connect($HOST, $UNAME, $UPASS);

    if ( count($GLOBALS['QUERIES_ON_CONNECT']) )
    {
      foreach( $GLOBALS['QUERIES_ON_CONNECT'] as $q )
      {
        if ( DEBUG_MODE == 1 )
        {
          $mtime  = microtime();
          $mtime  = explode(" ",$mtime);
          $mtime1 = $mtime[1] + $mtime[0];
          $ERR    = false;
        }

        mysql_query($q, $CON_RES);

        if ( DEBUG_MODE == 1 )
        {
          $Q        = new QueryInfo($q);
          $Q->time  = $mtime2 - $mtime1;
          query_log($Q);
        }
      }
    }

  }

  if ( $CON_RES && ($GLOBALS['HOST_TO_CURRENT_DB'][$HOST] != $DB) )
  {
    $mtime  = microtime();
    $mtime  = explode(" ",$mtime);
    $mtime1 = $mtime[1] + $mtime[0];
    $ERR    = false;

    if( !mysql_select_db($DB, $CON_RES) ) {
      if ( DEBUG_MODE == 1 ) {
        $ERR = mysql_error();
      }
    }
    else {
      $GLOBALS['HOST_TO_CURRENT_DB'][$HOST] = $DB;
    }

    $mtime  = microtime();
    $mtime  = explode(" ",$mtime);
    $mtime2 = $mtime[1] + $mtime[0];

    if ( DEBUG_MODE == 1 )
    {
      $Q        = new QueryInfo('db_get_connection_resource($table=\''.$table.'\')');
      $Q->time  = $mtime2 - $mtime1;

      if ($ERR) {
        $Q->error = $ERR;
      }

      query_log($Q);
    }
  }
  return $CON_RES;
}

/**
 * Closes all connections, or the connection for a specified host.
 * @param  [String $host]
 * @return Void
 */
function db_close($host=null)
{
  $Q = new QueryInfo("db_close('".$host."')");
  query_log($Q);
  if ( !$host ) {
    foreach( $GLOBALS['CONNECTIONS'] as $host=>$resource ) {
      @mysql_close( $resource );
    }
    return;
  }
  @mysql_close( $GLOBALS['CONNECTIONS'][$host] );
  return;
}

/**
 * Executed a query, and does all book keeping/resource management internally.
 * @param  String $sql
 * @return Resource Handle
 */
function db_query( $sql )
{
  // Timing to record what queries are taking too long.
  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime1 = $mtime[1] + $mtime[0];

  // Find the table name.
  $TABLE = db_table_name($sql);

  // Get/Make connection to appropriate DB
  if ( $CON_RES = db_get_connection_resource($TABLE) )
  {
    // DO THE QUERY
    $res = @mysql_query( $sql, $CON_RES );
    $GLOBALS['LAST_QUERY_RES'] = $CON_RES;
  }
  else {
    if ( DEBUG_MODE == 1 ) {
      $Q        = new QueryInfo($sql);
      $Q->error = 'No Connection Could be Made for table: '.$TABLE;
      query_log( $Q );
    }
    return false;
  }

  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime2 = $mtime[1] + $mtime[0];

  $GLOBALS['TOTAL_QUERIES']++;
  $GLOBALS['TOTAL_QUERY_TIME'] += ($mtime2-$mtime1);

  if ( DEBUG_MODE == 1 )
  {
    $Q = new QueryInfo($sql);
    $Q->time  = $mtime2 - $mtime1;
    if ( !$res )
    {
      $Q->error = mysql_error();
      query_log( $Q );
    }
    else
    {
      if ( $extra = mysql_info($CON_RES) ) {
        $Q->extraInfo = $extra;
      }
      else if ( db_get_query_type($sql) == 'select' )
      {
        $Q->extraInfo = "Rows Returned: ".db_num_rows($res);
        $Q->size      = db_data_size($res);
      }

      query_log( $Q );
    }
  }

  return $res;
}

/**
 * For debugging, loops through a result and gets the data size of it in bytes.
 * @param  Resource Handle $res
 * @return Integer
 */
function db_data_size(&$res) {
  $size = 0;
  while ($row = db_fetch_array($res))
  {
    foreach($row as $data) {
      if ( is_int($data) ) {
        $size += 4;
      }
      else {
        $size += strlen($data);
      }
    }
  }
  if ( $size ) {
    db_data_seek($res, 0);
  }
  return $size;
}

/**
 * Alias for mysql_data_seek.
 * @param  Resource Handle $res
 * @param  Integer $row
 * @return Void
 */
function db_data_seek($res, $row=0) {
  @mysql_data_seek($res, $row);
}

/**
 * rees up queried result sets.
 * @param  Resource Handle $res
 * @return Void
 */
function db_free_result( &$res ) {
  @mysql_free_result( $res );
}

/**
 * Performs an unbuffered query.
 * @param  String $sql
 * @return Void
 */
function db_unbuffered_query( $sql )
{
  // Timing to record what queries are taking too long.
  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime1 = $mtime[1] + $mtime[0];
  $ERR    = false;

  // Find the table name.
  $TABLE = db_table_name($sql);

  // Get/Make connection to appropriate DB
  if ( $CON_RES = db_get_connection_resource($TABLE) )
  {
    // DO THE QUERY
    mysql_unbuffered_query($sql, $CON_RES);
  }
  else {
    if ( DEBUG_MODE == 1 ) {
      $ERR = 'No Connection Could be Made for table: '.$TABLE;
    }
  }

  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime2 = $mtime[1] + $mtime[0];

  if ( DEBUG_MODE == 1 )
  {
    $Q                          = new QueryInfo($sql);
    $Q->time                    = $mtime2 - $mtime1;
    $Q->extraInfo               = 'UnBuffered';

    if ( $ERR ) {
      $Q->error     = $ERR;
    }

    query_log( $Q );
  }
}

/**
 * Alias for mysql_insert_id(), which defaults to the last query executed.
 * @return Integer
 */
function db_get_insert_id() {
  return @mysql_insert_id($GLOBALS['LAST_QUERY_RES']);
}

/**
 * TODO - Will open-query-close.
 * @param  String $sql
 * @return Resource Handle
 */
function db_oc_query( $sql )
{
  // Open connection, do query, close connection.
  // For when you plan to do a lot of processing in PHP,
  // But need to get a bit of data from the DB first.
  die("ERROR: db_oc_query() IS NOT TO BE USED AT THE MOMENT!");
  // Timing to record what queries are taking too long.
  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime1 = $mtime[1] + $mtime[0];

  db_connect();
  $res = @mysql_query( $sql );
  db_close();

  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime2 = $mtime[1] + $mtime[0];

  if ( DEBUG_MODE == 1 )
  {
    $Q        = new QueryInfo($sql);
    $Q->time  = $mtime2 - $mtime1;
    $Q->extraInfo = "Open->Close Query";

    if ( !$res ) {
      $Q->error = mysql_error();
    }
    query_log($Q);
  }

  return $res;
}

/**
 * Wrapper for mysql_fetch_object().
 * @param  Resource Handle $res
 * @return Object
 */
function db_fetch_object( &$res )
{
  return @mysql_fetch_object( $res );
}

/**
 * Wrapper for mysql_fetch_array().
 * @param  Resource Handle $res
 * @return Associative Array
 */
function db_fetch_array( &$res )
{
  return @mysql_fetch_array( $res );
}

/**
 * Wrapper for mysql_fetch_row().
 * @param  Resource Handle $res
 * @return Array
 */
function db_fetch_row( &$res )
{
  return @mysql_fetch_row( $res );
}

/**
 * Wrapper for mysql_affected_rows() - Defaults to last query made.
 * @return Integer
 */
function db_rows_affected()
{
  return @mysql_affected_rows($GLOBALS['LAST_QUERY_RES']);
}

/**
 * Wrapper for mysql_num_rows()
 * @param  Resource Handle $res
 * @return Integer
 */
function db_num_rows( &$res )
{
  return @mysql_num_rows( $res );
}


/**
 * Parses out and returns a table name from a query - For internal use.
 * @param  String $sql
 * @return String
 */
function db_table_name($sql)
{
  // Lets fix up any formatting to make it easily parsable.
  $sql = str_replace('(',            ' ',   $sql);  // Get rid of paren
  $sql = str_replace('(',            ' ',   $sql);  // Get rid of paren
  $sql = str_replace("\n",           ' ',   $sql);  // Replace new lines with spaces.
  $sql = str_replace(',',            ' , ', $sql);  // Format comma delimited stuff.
  $sql = ereg_replace(' \/\*.*\*\/', '',    $sql);  //
  $sql = trim(strtolower($sql));                    // Trim and tolower()
  $sql = preg_replace('/ +/',        ' ',   $sql);  // Eliminate double+ spacing.
  $arr = explode(' ', $sql );

  if ( in_array($arr[0], array('create', 'drop') ) ) die($arr[0] . ' should only be executed from the command line.');


  /*********
   * READ THIS IF YOU ARE HAVING TROUBLE EXECUTING A QUERY
   * This map tells us that if the first word is (KEY), then the (VALUE) precedes the table name.
   *********/
  $PRETEXT['select']   = 'from';
  $PRETEXT['insert']   = 'into';
  $PRETEXT['delete']   = 'from';
  $PRETEXT['update']   = 'update';
  $PRETEXT['truncate'] = 'table';
  $PRETEXT['show']     = 'from';
  $PRETEXT['alter']    = 'table';

  $PATTERN = '/'.$PRETEXT[$arr[0]].' `*([a-zA-Z0-9_]+)`*[ ]*/';
  preg_match($PATTERN, $sql, $match);

  if ( $match[1] == 'select' ) { // It's a nested select! Find the next one!
    array_shift($arr);
    $newSql    = implode(' ', $arr);
    $selPos    = strpos($newSql, 'select');
    $newSql    = substr( $newSql, $selPos ); // slice out the portion of the query that begins with the new 'select' keyword.
    $match[1]  = db_table_name($newSql);     // Recursively grab the new table name.
  }

  return $match[1];
}

/**
 * Returns the lowercase first word in the query, which should be the type of query ( INSERT, SELECT, etc )
 * @param  String $sql
 * @return String
 */
function db_get_query_type($sql) {
  $arr = explode( ' ', $sql );
  return strtolower(trim($arr[0]));
}

/**
 * Properly escapes strings for safe insertion into the database.
 * @param  String $string
 * @return String
 */
function db_escape_string($string)
{
  if ( get_magic_quotes_gpc() ) {
    $string = stripslashes($string);
  }
  return mysql_escape_string($string);
}

/**
 * Internally used to log query objects in debug mode.
 * @param  Object $QObj
 * @return Void
 */
function query_log($QObj) {
  global $QUERY_HISTORY;
  $QUERY_HISTORY[] = $QObj;
}

function db_find_table( $table )
{
  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime1 = $mtime[1] + $mtime[0];

  $found_DB = false;

  $databases = array_unique( $GLOBALS['TABLE_TO_DB'] );
  foreach($databases as $database)
  {
    $tmpLnk = db_connect($database->host, $database->user, $database->pass);
    $res = mysql_list_tables($database->database, $tmpLnk);
    while ($row = db_fetch_object($res))
    {
      $idx = "Tables_in_".$database->database;
      if ( $row->$idx == $table ) {
        $found_DB = $database;
        break;
      }
    }

    if ( $found_DB ) break;
  }

  $mtime  = microtime();
  $mtime  = explode(" ",$mtime);
  $mtime2 = $mtime[1] + $mtime[0];

  if ( DEBUG_MODE == 1 )
  {
    $Q        = new QueryInfo('db_find_table( \''.$table.'\' )');
    $Q->time  = $mtime2 - $mtime1;

    if ( !$found_DB ) {
      $Q->error = 'Could not located table: "'.$table.'" in any registered databases.';
      query_log( $Q );
    }
    else {
      $Q->error = 'Table: "'.$table.'" located in database: "'.$found_DB->database.'"';
      query_log( $Q );
    }
  }

  return $found_DB;
}

/*
  TEMPORARY IDEA I'M PLAYING WITH.
  I would like to cache queries without having to build a custom result resource!
*/
function db_cached_query($sql, $timeFrame=300)
{
  $hashName = QCACHE_FOLDER.'/'.md5($sql.$timeFrame).".qcache";

  if ( !file_exists($hashName) || (time()-filemtime($hashName) > $timeFrame ) )
  {
    $res = db_query($sql);

    $SERIALIZABLE_ARR = array();

    while ( $row = db_fetch_object($res) ) {
      $SERIALIZABLE_ARR[] = $row;
    }

    write_file(serialize($SERIALIZABLE_ARR), $hashName);
  }
  else {
    $SERIALIZABLE_ARR = unserialize(file($hashName));
  }
  return $SERIALIZABLE_ARR;
}

/**
 * Structure used to represent debug info on a query.
 *
 */
Class QueryInfo
{
  var $sql='&nbsp;', $time=0, $size=0, $error='&nbsp;', $extraInfo='&nbsp;';
  function QueryInfo($sql) {
    $this->sql = $sql;
  }
}

?>
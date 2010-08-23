<?php
  // Load the query log output as a shutdown function.
  register_shutdown_function('show_query_log');
  
  // Feedback related stuff.
  // This is what you see as the debugger... format as you please.

  function get_query_log() 
  {
    global $QUERY_HISTORY;

    $TOTAL_QUERY_TIME = 0;
    
    $ONE_SPACE   = '&nbsp;'; 
    
    $FONT_FAMILY = "font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#000000;";
    $TABLE_STYLE = "STYLE='$FONT_FAMILY border-style:solid;border-color:#CCCCCC;border-top-width=1;border-right-width=1;border-left-width=1;border-bottom-width=1;BACKGROUND:#FFFFFF;'";
    $TD_STYLE    = "STYLE='$FONT_FAMILY border-style:solid;border-color:#CCCCCC;border-top-width=1;border-right-width=1;border-left-width=1;border-bottom-width=0;'";
    
    ob_start();
    ?>
      <STYLE type=text/css>
       .db_MainTable {
        font-family:Verdana, Arial, Helvetica, sans-serif; 
        font-size:11px; 
        color:#000000;
        
        border-style:solid;
        border-color:#CCCCCC;
        border-top-width=1;
        border-right-width=1;
        border-left-width=1;
        border-bottom-width=1;
        BACKGROUND:#FFFFFF;
       }
       
       .db_MainTable td {
        font-family:Verdana, Arial, Helvetica, sans-serif; 
        font-size:11px; 
        
        border-style:solid;
        border-color:#CCCCCC;
        border-top-width=1;
        border-right-width=1;
        border-left-width=1;
        border-bottom-width=0;
       }
       
       /* Query #'s */
       .db_QueryNum {
        color:#000000;
        font-weight:bold;
       }
       
       /* Query Time Taken */
       .db_Time {
        color:#000000;
       }
       
       /* Default, non-highlighted format. */
       .db_Query {
        color:#000000;
       }
       
       /* Query Data Size */
       .db_DataSize {
        color:#0000AA;
       }
       
       /* Query 'Extra' Column */
       .db_Extra {
        color:green;
       }
       
       /* Query ERRORS */
       .db_Error {
        color:#FF0000;
       }
       
       /* SQL Keywords */
       .db_Sql {
        color:#0000FF;
       }
       
       /* DB Tables */
       .db_Table {
        color:green;
        font-weight:bold;
       }
       
       /* Quoted Strings */
       .db_String {
        color:#642A61;
       }
       
       /* Integers (Quoted or not) */
       .db_Int {
        color:#FF0000;
       }
       
       /* Everything else ( usually field names ) */
       .db_FieldName{
        font-style: italic;
       }
       
       
       .db_Focus {
        background=#FFC7C7;
       }
       
       .db_slow_time {color: #BF0000;font-weight:bold;}
       .db_med_time  {color: #DC9600;font-weight:bold;}
       .db_fast_time {color: #00B232;font-weight:bold;}
      </STYLE>
    <?
    $retVal = ob_get_flush();
    
    
    $retVal .= "<TABLE BORDER='0' CELLPADDING='1' CELLSPACING='0' CLASS='db_MainTable' WIDTH='100%'>";
    $retVal .= "<TR><TD ALIGN='CENTER' COLSPAN='6'><B>Total Queries: ".number_format(count($QUERY_HISTORY))."</B></TD></TR>";
    
    $addlRetVal = "<TR>
                    <TD ALIGN='CENTER' CLASS='db_QueryNum'><B>Query #</B></TD>
                    <TD ALIGN='CENTER' CLASS='db_Time'><B>Time</B></TD>
                    <TD ALIGN='CENTER' CLASS='db_Query'><B>Query</B></TD>
                    <TD ALIGN='CENTER' CLASS='db_DataSize'><B>Data Size</B></TD>
                    <TD ALIGN='CENTER' CLASS='db_Extra'><B>Extra</B></TD>
                    <TD ALIGN='CENTER' CLASS='db_Error'><B>Error</B></TD>
                  </TR>";
    
    for($i=0; $i<count($QUERY_HISTORY); $i++) 
    {
      $Q_NOW = &$QUERY_HISTORY[$i];
      
      if ( $i%2 == 0 ) {
        $BG = "BGCOLOR='#EEEEEE'";
      }
      else {
        $BG = "BGCOLOR='#FFFFFF'";
      }
      
      $T_FONT = "db_fast_time";
      if ( $Q_NOW->time >= 0.1 ) {
        $T_FONT = "db_slow_time";
      }
      else if ( $Q_NOW->time >= 0.01 ) {
        $T_FONT = "db_med_time";
      }
      

      $TOTAL_QUERY_TIME += $Q_NOW->time;
      
      
      if ( $Q_NOW->size > 12400 ) { // 10k
        $SIZE = '<B>'.number_format(round($Q_NOW->size/1024, 2), 2).'kb</B>';
      }
      else if ($Q_NOW->size) {
        $SIZE = number_format(round($Q_NOW->size/1024, 2), 2).'kb';
      }
      else {
        $SIZE = '&nbsp;';
      }
      
      $EXTRA = $Q_NOW->extraInfo;
      $EXTRA = str_replace("Rows ", "", $EXTRA);
      $EXTRA = preg_replace('/([0-9]) /', '\\1<BR>', $EXTRA);
      
      
      $JS = "onMouseOver=\"this.className='db_Focus';\" onMouseOut=\"this.className='';\""; 
      $addlRetVal .= "<TR $BG $JS>
                        <TD ALIGN='CENTER' CLASS='db_QueryNum'><FONT COLOR='#".myDecHex($i*9)."0000'>".number_format($i+1)."</FONT></TD>
                        <TD ALIGN='LEFT' CLASS='db_Time'><SPAN CLASS=\"$T_FONT\">".round($Q_NOW->time, 5)."</SPAN></TD>
                        <TD ALIGN='LEFT' CLASS='db_Query'>".query_highlight($Q_NOW->sql)."</TD>
                        <TD ALIGN='LEFT' CLASS='db_DataSize'>".$SIZE."</TD>
                        <TD ALIGN='LEFT' CLASS='db_Extra'>".ucfirst($EXTRA)."</TD>
                        <TD ALIGN='LEFT' CLASS='db_Error'>".$Q_NOW->error."</TD>
                      </TR>";
    }

    $retVal .= "<TR><TD ALIGN='CENTER' COLSPAN='6'><B>Total Query Time: ".round($TOTAL_QUERY_TIME, 5)." seconds</B></TD></TR>";
    
    $retVal .= $addlRetVal;
    $retVal .= "</TABLE>";
    
    return $retVal;
  }
  
  function myDecHex($num) {
    if ( $num > 255 ) $num = 255;

    $D1 = dechex( (int)($num >> 4) );
    $D2 = dechex( (int)($num & 0x0F) );
    return (string)($D1.$D2);
  }
  
  function show_query_log() {
    echo get_query_log();
  }
  
    
  function query_highlight($q) 
  {
    $mysql_words = array( '*',                  'ADD',                'ALL',                'ALTER',              'ANALYZE',            
                          'AND',                'AS',                 'ASC',                'ASENSITIVE',         'AVG',                
                          'BEFORE',             'BETWEEN',            'BIGINT',             'BINARY',             'BLOB',               
                          'BOTH',               'BY',                 'CALL',               'CASCADE',            'CASE',               
                          'CHANGE',             'CHAR',               'CHARACTER',          'CHECK',              'COLLATE',            
                          'COLUMN',             'CONDITION',          'CONNECTION',         'CONSTRAINT',         'CONTINUE',           
                          'CONVERT',            'COUNT',              'CREATE',             'CROSS',              'CURRENT_DATE',       
                          'CURRENT_TIME',       'CURRENT_TIMESTAMP',  'CURRENT_USER',       'CURSOR',             'DATABASE',           
                          'DATABASES',          'DAY_HOUR',           'DAY_MICROSECOND',    'DAY_MINUTE',         'DAY_SECOND',         
                          'DEC',                'DECIMAL',            'DECLARE',            'DEFAULT',            'DELAYED',            
                          'DELETE',             'DESC',               'DESCRIBE',           'DETERMINISTIC',      'DISTINCT',           
                          'DISTINCTROW',        'DIV',                'DOUBLE',             'DROP',               'DUAL',               
                          'EACH',               'ELSE',               'ELSEIF',             'ENCLOSED',           'ESCAPED',            
                          'EXISTS',             'EXIT',               'EXPLAIN',            'FALSE',              'FETCH',              
                          'FLOAT',              'FOR',                'FORCE',              'FOREIGN',            'FROM',               
                          'FULLTEXT',           'GOTO',               'GRANT',              'GROUP',              'HAVING',             
                          'HIGH_PRIORITY',      'HOUR_MICROSECOND',   'HOUR_MINUTE',        'HOUR_SECOND',        'IF',                 
                          'IGNORE',             'IN',                 'INDEX',              'INFILE',             'INNER',              
                          'INOUT',              'INSENSITIVE',        'INSERT',             'INT',                'INTEGER',            
                          'INTERVAL',           'INTO',               'IS',                 'ITERATE',            'JOIN',               
                          'KEY',                'KEYS',               'KILL',               'LEADING',            'LEAVE',              
                          'LEFT',               'LIKE',               'LIMIT',              'LINES',              'LOAD',               
                          'LOCALTIME',          'LOCALTIMESTAMP',     'LOCK',               'LONG',               'LONGBLOB',           
                          'LONGTEXT',           'LOOP',               'LOW_PRIORITY',       'MATCH',              'MEDIUMBLOB',         
                          'MEDIUMINT',          'MEDIUMTEXT',         'MIDDLEINT',          'MINUTE_MICROSECOND', 'MINUTE_SECOND',      
                          'MOD',                'MODIFIES',           'NATURAL',            'NOT',                'NO_WRITE_TO_BINLOG', 
                          'NULL',               'NUMERIC',            'ON',                 'OPTIMIZE',           'OPTION',             
                          'OPTIONALLY',         'OR',                 'ORDER',              'OUT',                'OUTER',              
                          'OUTFILE',            'PRECISION',          'PRIMARY',            'PROCEDURE',          'PURGE',              
                          'RAND',               'READ',               'READS',              'REAL',               'REFERENCES',         
                          'REGEXP',             'RENAME',             'REPEAT',             'REPLACE',            'REQUIRE',            
                          'RESTRICT',           'RETURN',             'REVOKE',             'RIGHT',              'RLIKE',              
                          'SCHEMA',             'SCHEMAS',            'SECOND_MICROSECOND', 'SELECT',             'SENSITIVE',          
                          'SEPARATOR',          'SET',                'SHOW',               'SMALLINT',           'SONAME',             
                          'SPATIAL',            'SPECIFIC',           'SQL',                'SQLEXCEPTION',       'SQLSTATE',           
                          'SQLWARNING',         'SQL_BIG_RESULT',     'SQL_CALC_FOUND_ROWS','SQL_SMALL_RESULT',   'SSL',                
                          'STARTING',           'STRAIGHT_JOIN',      'TABLE',              'TERMINATED',         'THEN',               
                          'TINYBLOB',           'TINYINT',            'TINYTEXT',           'TO',                 'TRAILING',           
                          'TRIGGER',            'TRUE',               'TRUNCATE',           'UNDO',               'UNION',              
                          'UNIQUE',             'UNLOCK',             'UNSIGNED',           'UPDATE',             'USAGE',              
                          'USE',                'USING',              'UTC_DATE',           'UTC_TIME',           'UTC_TIMESTAMP',      
                          'VALUES',             'VARBINARY',          'VARCHAR',            'VARCHARACTER',       'VARYING',            
                          'WHEN',               'WHERE',              'WHILE',              'WITH',               'WRITE',              
                          'XOR',                'YEAR_MONTH',         'ZEROFILL',           '=',                  '+',
                          '-',                  '!',                  '<',                  '>');
    
    $q = preg_replace('/ +/', ' ', $q);
    
    $PATTERN = "(".
                "'.*'".           // Quoted stuff
                "|".              // Or
                "\".*\"".         // Other Quoted stuff
                "|".              // Or
                "[ \(\)=\+\-<>]". // Spaces, Open Parent, Close Parent, Equals, Plus, Less/Greater than, Exclamation.
               ")";
    $pieces  = preg_split('/'.$PATTERN.'/U', $q, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    
    for($i=0; $i<count($pieces); $i++) {
      $word = &$pieces[$i];
      
      if ( in_array(strtoupper($word), $mysql_words) ) {
        $pieces[$i] = '<SPAN CLASS="db_Sql">'.strtoupper($word).'</SPAN>';
      }
      else if ( isset($GLOBALS['TABLE_TO_DB'][$word]) ) {
        $pieces[$i] = '<SPAN CLASS="db_Table">'.$word.'</SPAN>';
      }
      else if ( is_numeric(trim($word,"'")) ) {
        $pieces[$i] = '<SPAN CLASS="db_Int">'.$word.'</SPAN>';
      }
      else if ( is_quoted($word) ) {
        $pieces[$i] = '<SPAN CLASS="db_String">'.htmlentities($word).'</SPAN>';
      }
      else if ( in_array($word, array('(', ')')) ) {
        $pieces[$i] = htmlentities($word);
      }
      else {
        $pieces[$i] = '<SPAN CLASS="db_FieldName">'.htmlentities($word).'</SPAN>'; 
      }
    }
    
    return implode('', $pieces);
  }

  function is_quoted($word) {
    $char1 = $word{0};
    $char2 = $word{ strlen($word)-1 };
    if ( $char1 == "'" || $char1 == '"' ) {
      if ( $char2 == "'" || $char2 == '"' ) {
        return true;
      }
    }
    return false;
  }
?>
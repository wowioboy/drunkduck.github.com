<?php 
# this file supercedes database_package/global_db_data.inc.php and database_package/global_db_func.inc.php
class DB 
{
	static private $_instance;
	protected $_link, 
			  $_host, 
			  $_user, 
			  $_pass, 
			  $_scheme;

	static public function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function __construct(array $credentials = array())
	{
		$this->_host = '173.201.18.129';
		$this->_user = 'drunkduck';
		$this->_pass = 'ice22hdi5m';
		$this->_scheme = 'drunkduck';
    
    # THESE ARE THE STAGING DATABASE VARIABLES
/*    $this->_host = '192.168.1.205';*/
/*    $this->_pass = 'Sites3rv3r3';*/
    
    if ($credentials['scheme']) {
      $this->_scheme = $credentials['scheme'];
    }
    
		$this->_link = @mysql_connect($this->_host, $this->_user, $this->_pass); 
		@mysql_select_db($this->_scheme, $this->_link);
	}
	
	public function __destruct()
	{
		@mysql_close($this->_host);
	}
	
	public function query($query)
	{
		if ($resource = @mysql_query($query, $this->_link)) {
			return $resource;
		} else {
			die(mysql_error());
		}
	}
	
	public function fetchAll($query)
	{
		$res = $this->query($query);
		while ($row = mysql_fetch_assoc($res)) {
			$results[] = $row;
		}
		return $results;
	}
	
	public function fetchRow($query)
	{
		$res = $this->query($query);
		return mysql_fetch_assoc($res);
	}
	
	public function fetchCol($query)
	{
		$res = $this->query($query);
		while ($row = mysql_fetch_array($res)) {
			$results[] = $row[0];
		}
		return $results;
	}
	
	public function fetchOne($query)
	{
		$res = $this->query($query);
		$row = mysql_fetch_array($res);
		return $row[0];
	}
}
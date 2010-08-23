<?php
include 'ppsdk_include_path.inc';

require_once 'SampleLogger.php';

session_start();

$profile = $_SESSION['APIProfile'];

$logger = new SampleLogger('DoCapture.php', PEAR_LOG_DEBUG);

// Verify that user is logged in
if(! isset($profile)) {
   // Not logged in -- Back to the login page
   
   $logger->_log('You are not logged in;  return to index.php'); 
   $location = 'index.php';
   header("Location: $location"); 
   exit;
} else {
   $logger->_log('caller: '. print_r($caller, true));   
}

require_once 'pages/DoCapture.html.php';

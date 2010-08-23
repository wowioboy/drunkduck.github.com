<?php

include 'ppsdk_include_path.inc';

require_once 'SampleLogger.php';

session_start();

$caller = $_SESSION['caller'];
// $profile = $_SESSION['APIProfile'];

$logger = new SampleLogger('GetTransactionDetails.php', PEAR_LOG_DEBUG);

// Verify that user is logged in
if (!isset($caller)) {
   // Not logged in -- Back to the login page
   
   $logger->_log('You are not logged in;  return to index.php');
   header("Location: index.php");
   exit;
} else {
   $logger->_log('caller: '. print_r($caller, true));
}

require_once 'pages/GetTransactionDetails.html.php';

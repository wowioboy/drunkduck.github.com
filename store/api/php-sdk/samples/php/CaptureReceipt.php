<?php
include 'ppsdk_include_path.inc';

require_once 'PayPal.php';
require_once 'PayPal/Profile/Handler/Array.php';
require_once 'PayPal/Profile/API.php';
require_once 'PayPal/Type/DoCaptureRequestType.php';
require_once 'SampleLogger.php';
require_once 'lib/constants.inc.php';

session_start();

$was_submitted = false;

$logger = new SampleLogger('CaptureReceipt.php', PEAR_LOG_DEBUG);
$logger->_log('POST variables: '. print_r($_POST, true));

$profile = $_SESSION['APIProfile'];

// Verify that user is logged in
if(! isset($profile)) {
   // Not logged in -- Back to the login page
   
   $logger->_log('You are not logged in;  return to index.php'); 
   $location = 'index.php';
   header("Location: $location"); 
} else {
   $logger->_log('Profile from session: '.print_r($profile, true)); 
}

// Build our request from $_POST
$capture_request =& PayPal::getType('DoCaptureRequestType');
if (PayPal::isError($capture_request)) {
   $logger->_log('Error in request: '. $capture_request);    
} else {
   $logger->_log('Create request: '. $capture_request);
}

// Set request fields
$authorization_id = $_POST['authorization_id'];
if(isset($authorization_id)) {
   $capture_request->setAuthorizationID($authorization_id, 'iso-8859-1'); 
} 

$complete_code_type = $_POST['CompleteCodeType'];
$capture_request->setCompleteType($complete_code_type);

$invoice_id = $_POST['invoice_id'];
if(isset($invoice_id))
   $capture_request->setInvoiceID($invoice_id);
   
$note = $_POST['note'];
if(isset($note))
   $capture_request->setNote($note);
   
$amount = $_POST['amount'];
$amtType =& PayPal::getType('BasicAmountType');
$amtType->setattr('currencyID', 'USD');
$amtType->setval($amount, 'iso-8859-1');
$capture_request->setAmount($amtType);

$logger->_log('Initial request: '. print_r($capture_request, true));

$caller =& PayPal::getCallerServices($profile);

$response = $caller->DoCapture($capture_request);

$ack = $response->getAck();

$logger->_log('Ack='.$ack);

switch($ack) {
   case ACK_SUCCESS:
   case ACK_SUCCESS_WITH_WARNING:
      // Good to break out;
      break;
   
   default:
      $_SESSION['response'] = $response;   
      $logger->_log('DoCapture failed: ' . print_r($response, true));
      $location = "ApiError.php";
      header("Location: $location");  
}

// Otherwise, load the HTML response
require_once 'pages/CaptureReceipt.html.php';

?>
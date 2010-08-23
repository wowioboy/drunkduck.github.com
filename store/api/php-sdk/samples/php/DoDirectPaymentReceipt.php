<?php

include 'ppsdk_include_path.inc';

require_once 'PayPal.php';
require_once 'PayPal/Profile/Handler/Array.php';
require_once 'PayPal/Profile/API.php';
require_once 'PayPal/Type/DoDirectPaymentRequestType.php';
require_once 'PayPal/Type/DoDirectPaymentRequestDetailsType.php';
require_once 'PayPal/Type/DoDirectPaymentResponseType.php';
// Add all of the types
require_once 'PayPal/Type/BasicAmountType.php';
require_once 'PayPal/Type/PaymentDetailsType.php';
require_once 'PayPal/Type/AddressType.php';
require_once 'PayPal/Type/CreditCardDetailsType.php';
require_once 'PayPal/Type/PayerInfoType.php';
require_once 'PayPal/Type/PersonNameType.php';

require_once 'lib/constants.inc.php';
require_once 'SampleLogger.php';

define('UNITED_STATES', 'US');

session_start();

$was_submitted = false;

$logger = new SampleLogger('DoDirectPaymentReceipt.php', PEAR_LOG_DEBUG);
$logger->_log('POST variables: '. print_r($_POST, true));

$profile = $_SESSION['APIProfile'];
// $caller = $_SESSION['caller'];

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
// $dp_request = new TransactionSearchRequestType();
$dp_request =& PayPal::getType('DoDirectPaymentRequestType');
if (PayPal::isError($dp_request)) {
   $logger->_log('Error in request: '. $dp_request);    
} else {
   $logger->_log('Create request: '. $dp_request);
}

$logger->_log('Initial request: '. print_r($dp_request, true));

/**
 * Get posted request values
 */
$paymentType = $_POST['paymentType'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$creditCardType = $_POST['creditCardType'];
$creditCardNumber = $_POST['creditCardNumber'];
$expDateMonth = $_POST['expDateMonth'];
// Month must be padded with leading zero
$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

$expDateYear = $_POST['expDateYear'];
$cvv2Number = $_POST['cvv2Number'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$amount = $_POST['amount'];

// Populate SOAP request information
// Payment details
$OrderTotal =& PayPal::getType('BasicAmountType');
if (PayPal::isError($OrderTotal)) {
    var_dump($OrderTotal);
    exit;
}
$OrderTotal->setattr('currencyID', 'USD');
$OrderTotal->setval($amount, 'iso-8859-1');
$PaymentDetails =& PayPal::getType('PaymentDetailsType');
$PaymentDetails->setOrderTotal($OrderTotal);

$shipTo =& PayPal::getType('AddressType');
$shipTo->setName($firstName.' '.$lastName);
$shipTo->setStreet1($address1);
$shipTo->setStreet2($address2);
$shipTo->setCityName($city);
$shipTo->setStateOrProvince($state);
$shipTo->setCountry(UNITED_STATES);
$shipTo->setPostalCode($zip);
$PaymentDetails->setShipToAddress($shipTo);

$dp_details =& PayPal::getType('DoDirectPaymentRequestDetailsType');
$dp_details->setPaymentDetails($PaymentDetails);

// Credit Card info
$card_details =& PayPal::getType('CreditCardDetailsType');
$card_details->setCreditCardType($creditCardType);
$card_details->setCreditCardNumber($creditCardNumber);
$card_details->setExpMonth($padDateMonth);
// $card_details->setExpMonth('01');
$card_details->setExpYear($expDateYear);
// $card_details->setExpYear('2010');
$card_details->setCVV2($cvv2Number);
$logger->_log('card_details: '. print_r($card_details, true));

$payer =& PayPal::getType('PayerInfoType');
$person_name =& PayPal::getType('PersonNameType');
$person_name->setFirstName($firstName);
$person_name->setLastName($lastName);
$payer->setPayerName($person_name);

$payer->setPayerCountry(UNITED_STATES);
$payer->setAddress($shipTo);

$card_details->setCardOwner($payer);

$dp_details->setCreditCard($card_details);
$dp_details->setIPAddress($_SERVER['SERVER_ADDR']);
$dp_details->setPaymentAction($paymentType);

$dp_request->setDoDirectPaymentRequestDetails($dp_details);

$caller =& PayPal::getCallerServices($profile);

// Execute SOAP request
$response = $caller->DoDirectPayment($dp_request);

$ack = $response->getAck();

$logger->_log('Ack='.$ack);

switch($ack) {
   case ACK_SUCCESS:
   case ACK_SUCCESS_WITH_WARNING:
      // Good to break out;
      break;
   
   default:
      $_SESSION['response'] =& $response;   
      $logger->_log('DoDirectPayment failed: ' . print_r($response, true));
      $location = "ApiError.php";
      header("Location: $location");  
}

// Otherwise, load the HTML response

require_once 'pages/DirectPaymentReceipt.html.php';

?>

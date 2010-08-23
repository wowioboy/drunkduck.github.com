<?php

/************************************************************
This is the main web page for the DoDirectPayment sample.
This page allows the user to enter name, address, amount,
and credit card information. It also accept input variable
paymentType which becomes the value of the PAYMENTACTION
parameter.

When the user clicks the Submit button, DoDirectPaymentReceipt.php
is called.

Called by index.html.

Calls DoDirectPaymentReceipt.php.

************************************************************/
// clearing the session before starting new API Call
session_unset();
$paymentType = $_REQUEST['paymentType'];

?>


<html>
<head>
    <title>PayPal PHP SDK - DoDirectPayment API</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<br>
	<center>
	<font size=2 color=black face=Verdana><b>DoDirectPayment</b></font>
	<br><br>
    <form action="DoDirectPaymentReceipt.php" method="POST" >
	<input type=hidden name=paymentType value="<?=$paymentType?>" />
        <center>
        <table class="api">
            <tr>
                <td class="thinfield"> First Name:</td>
                <td align=left><input type="text" size="30" maxlength="32" name="firstName" value="John" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    Last Name:</td>
                <td>
                    <input type="text" size="30" maxlength="32" name="lastName" value="Doe" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    Card Type:</td>
                <td>
                    <select name="creditCardType">
                    <option></option>
                    <option value="Visa" selected="selected">Visa</option>
                    <option value="MasterCard">MasterCard</option>
                    <option value="Discover">Discover</option>
                    <option value="Amex">American Express</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Card Number:</td>
                <td>
                    <input type="text" size="19" maxlength="19" name="creditCardNumber" value="4059042064101342" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    Expiration Date:</td>
                <td>
                    <select name="expDateMonth">
                    <option value="1">01</option>
                    <option value="2">02</option>
                    <option value="3">03</option>
                    <option value="4">04</option>
                    <option value="5">05</option>
                    <option value="6">06</option>
                    <option value="7">07</option>
                    <option value="8">08</option>
                    <option value="9">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    </select>
                    <select name="expDateYear">
                    <option value="2004">2004</option>
                    <option value="2005">2005</option>
                    <option value="2006">2006</option>
                    <option value="2007">2007</option>
                    <option value="2008">2008</option>
                    <option value="2009">2009</option>
                    <option value="2010" selected>2010</option>
                    <option value="2011">2011</option>
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Card Verification Number:</td>
                <td>
                    <input type="text" size="3" maxlength="4" name="cvv2Number" value="962" /></td>
            </tr>
            <tr>
                <td class="header">
                    Billing Address:
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Address 1:
                </td>
                <td>
                    <input type="text" size="25" maxlength="100" name="address1" value="123 Fake St" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    Address 2:
                </td>
                <td>
                    <input type="text" size="25" maxlength="100" name="address2" />(optional)</td>
            </tr>
            <tr>
                <td class="thinfield">
                    City:
                </td>
                <td>
                    <input type="text" size="25" maxlength="40" name="city" value="San Jose" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    State:
                </td>
                <td>
                    <select name="state">
                    <option></option>
                    <option value="AK">AK</option>
                    <option value="AL">AL</option>
                    <option value="AR">AR</option>
                    <option value="AZ">AZ</option>
                    <option value="CA" selected>CA</option>
                    <option value="CO">CO</option>
                    <option value="CT">CT</option>
                    <option value="DC">DC</option>
                    <option value="DE">DE</option>
                    <option value="FL">FL</option>
                    <option value="GA">GA</option>
                    <option value="HI">HI</option>
                    <option value="IA">IA</option>
                    <option value="ID">ID</option>
                    <option value="IL">IL</option>
                    <option value="IN">IN</option>
                    <option value="KS">KS</option>
                    <option value="KY">KY</option>
                    <option value="LA">LA</option>
                    <option value="MA">MA</option>
                    <option value="MD">MD</option>
                    <option value="ME">ME</option>
                    <option value="MI">MI</option>
                    <option value="MN">MN</option>
                    <option value="MO">MO</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="NC">NC</option>
                    <option value="ND">ND</option>
                    <option value="NE">NE</option>
                    <option value="NH">NH</option>
                    <option value="NJ">NJ</option>
                    <option value="NM">NM</option>
                    <option value="NV">NV</option>
                    <option value="NY">NY</option>
                    <option value="OH">OH</option>
                    <option value="OK">OK</option>
                    <option value="OR">OR</option>
                    <option value="PA">PA</option>
                    <option value="RI">RI</option>
                    <option value="SC">SC</option>
                    <option value="SD">SD</option>
                    <option value="TN">TN</option>
                    <option value="TX">TX</option>
                    <option value="UT">UT</option>
                    <option value="VA">VA</option>
                    <option value="VT">VT</option>
                    <option value="WA">WA</option>
                    <option value="WI">WI</option>
                    <option value="WV">WV</option>
                    <option value="WY">WY</option>
                    <option value="AA">AA</option>
                    <option value="AE">AE</option>
                    <option value="AP">AP</option>
                    <option value="AS">AS</option>
                    <option value="FM">FM</option>
                    <option value="GU">GU</option>
                    <option value="MH">MH</option>
                    <option value="MP">MP</option>
                    <option value="PR">PR</option>
                    <option value="PW">PW</option>
                    <option value="VI">VI</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    ZIP Code:
                </td>
                <td>
                    <input type="text" size="10" maxlength="10" name="zip" value="95131" />(5 or 9 digits)
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Country:
                </td>
                <td>
                    United States
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Amount:</td>
                <td>
                    <input type="text" size="4" maxlength="7" name="amount" value="1.00" /> USD	
                                                            
                </td>
            </tr>
			<tr>
			<td/>
			<td align=left><b>(DoDirectPayment only supports USD at this time)</b></td>
			</tr>
            <tr>
                <td class="field">
                </td>
                <td>
                    <input type="Submit" value="Submit" /></td>
            </tr>
        </table>
        </center></center>
        <a class="home" id="CallsLink" href="index.html">Home</a>
    </form>
</body>
</html>

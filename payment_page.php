<?php
/* By Ali Alavi, #40876144. Based on SecureXML Example script provided.
 * 
 */

echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
session_start();

//check user is logged in
if(!isset($_SESSION['email']))
{


header("location: login.html");
}
// Do not allow browser to cache this page.
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// NOTE. Apache on Windows can generate the following warning: Warning: fgets(): SSL: fatal protocol error in ...  This is not really fatal, so we set the following:
error_reporting(E_ERROR | E_PARSE);

//if(isset($_SESSION['email'])){

//Variables 
$_POST["merchant_id"]= 'CAX0001';
$_POST["request_type"] = 'payment';
$_POST["payment_type"] = 0;//credit card payment
$_POST["transaction_password"] = 'oguxue9i';
$_POST["server"]= 'test';
$_POST["payment_amount"] = 1;

$_POST["payment_reference"] = 'PaymentRef';

$_POST["currency"] = 'AUD';

//cart total from session array
$cartTotal=$_SESSION['cart'][0]['total'];
$cartTotal=number_format($cartTotal, 2, '.', '');

switch ($_GET[pageid])
{
	case "":
?>
	<html>
	<head>
		<title>MQ Book Store Payment Page.</title>
		
		<script language="javascript" type="text/javascript">
		function checkForm(formObj)
		{
			//Check name field
			if (formObj.card_holder.value == "") {
			window.alert("You must enter a value in the field.");
			formObj.card_holder.focus();
			return false;
			}

			var cardnum = formObj.card_number.value;
			//Check if credit card number field is empty
			if (cardnum.length == 0) {
				window.alert("You must provide a credit card number.");
				formObj.card_number.focus();
				return false;
				}
			if (cardnum.length != 16) {
				window.alert("Invalid credit card number please check number. Must be 16 digits long.");
				formObj.card_number.focus();
				return false;
				}

			for (i=0; i<cardnum.length; i++) {
				if (cardnum.charAt(i) < "0" || cardnum.charAt(i) > "9") {
				window.alert("Credit Card must only contain numbers.");
				return false;
					}
				}
			
			var ccvnum = formObj.card_cvv.value;
			//Check if CCV number field is empty
			if (ccvnum.length == 0) {
				window.alert("You must provide a CCV number.");
				formObj.card_cvv.focus();
				return false;
				}
			if (ccvnum.length != 3) {
				window.alert("Invalid CCV, please check number. Must be 3 digits long.");
				formObj.card_cvv.focus();
				return false;
				}

			for (i=0; i<ccvnum.length; i++) {
				if (ccvnum.charAt(i) < "0" || ccvnum.charAt(i) > "9") {
				window.alert("CVV must only contain numbers.");
				formObj.card_cvv.focus();
				return false;
				}
				
				}

				return formValid;
				
			
		}
		</script>
		
	</head>

	<body>
		<form method="post" action="payment_page.php?pageid=process" onSubmit="return checkForm(this);">

		<table cellspacing="0" cellpadding="5" border="1">
		
		<tr>
			<td colspan="2" align="center"><h4>Credit Card Details</h4></td>
		</tr>
		<tr>
			<td>Name:</td>
			<td><input type="text" name="card_holder" size="40" value="" /></td>
		</tr>
		<tr>
			<td>Card No:</td>
			<td><input name="card_number" type="text" id="card_number" size="16" maxlength="19" value="" /></td>
		</tr>
		<tr>
			<td>CVV No:</td>
			<td><input type="text" name="card_cvv" size="3" value="" maxlength="6" /></td>
		</tr>
		<tr>
			<td>Exp:</td>
			<td>
				<select name="card_expiry_month">
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				</select>
				&nbsp;/&nbsp;
				<select name="card_expiry_year">
					<option value="12" selected="selected">2012</option>
					<option value="13">2013</option>
					<option value="14">2014</option>
					<option value="15">2015</option>
					<option value="16">2016</option>
					<option value="17">2017</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Order Total:</td>
			<td>$<?php echo $cartTotal; ?></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Purchase" name="submit" /></td>
		</tr>
		</table>

		</form>

	</body>
	</html>

<?php
		break;
	case "process":

if ($_POST["server"] == "live")
	if ($_POST["payment_type"] == 15 || $_POST["payment_type"] == 17)
		$host = "www.securepay.com.au/xmlapi/directentry";
	else
		$host = "www.securepay.com.au/xmlapi/payment";
else
	if ($_POST["payment_type"] == 15 || $_POST["payment_type"] == 17)
		$host = "www.securepay.com.au/test/directentry";
	else
		//$host = "test.securepay.com.au/xmlapi/payment";
		//Or if using SSL:
		$host = "www.securepay.com.au/test/payment";

$timestamp = getGMTtimestamp();

$vars =
"<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
"<SecurePayMessage>" .
	"<MessageInfo>" .
		"<messageID>8af793f9af34bea0cf40f5fb5c630c</messageID>" .
		"<messageTimestamp>" .urlencode($timestamp). "</messageTimestamp>" .
		"<timeoutValue>60</timeoutValue>" .
		"<apiVersion>xml-4.2</apiVersion>" .
	"</MessageInfo>" .
	"<MerchantInfo>" .
		"<merchantID>" .urlencode($_POST["merchant_id"]). "</merchantID>" .
		"<password>" .urlencode($_POST["transaction_password"]). "</password>" .
	"</MerchantInfo>" .
	"<RequestType>" .urlencode($_POST["request_type"]). "</RequestType>" .
	"<Payment>" .
		"<TxnList count=\"1\">" .
			"<Txn ID=\"1\">" .
				"<txnType>" .urlencode($_POST["payment_type"]). "</txnType>" .
				"<txnSource>23</txnSource>" .
				"<amount>" .str_replace(".", "", urlencode($_POST["payment_amount"])). "</amount>" .
				"<purchaseOrderNo>" .urlencode($_POST["payment_reference"]). "</purchaseOrderNo>" .
				"<currency>" .urlencode($_POST["currency"]). "</currency>" .
				"<preauthID>" .urlencode($_POST["preauthid"]). "</preauthID>" .
				"<txnID>" .urlencode($_POST["txnid"]). "</txnID>" .
				"<CreditCardInfo>" .
					"<cardNumber>" .urlencode($_POST["card_number"]). "</cardNumber>" .
					"<cvv>" .urlencode($_POST["card_cvv"]). "</cvv>" .
					"<expiryDate>" .urlencode($_POST["card_expiry_month"]). "/" .urlencode($_POST["card_expiry_year"]). "</expiryDate>" .
				"</CreditCardInfo>" .
				"<DirectEntryInfo>" .
					"<bsbNumber>" .urlencode($_POST["bsb_number"]). "</bsbNumber>" .
					"<accountNumber>" .urlencode($_POST["account_number"]). "</accountNumber>" .
					"<accountName>" .urlencode($_POST["account_name"]). "</accountName>" .
				"</DirectEntryInfo>" .
			"</Txn>" .
		"</TxnList>" .
	"</Payment>" .
"</SecurePayMessage>";

$response = openSocket($host, $vars);

$xmlres = array();
$xmlres = makeXMLTree ($response);

/*
// Display Array contents.
echo "<pre>";
print_r($xmlres);
echo "</pre>";
*/

echo "<h3>Transaction Details</h3>";

//fetch current date in MONTH/YEAR(eg. 01/12) format for checking expiry date
$currentDate = date("m/y");
$expiryDate = trim($xmlres[SecurePayMessage][Payment][TxnList][Txn][CreditCardInfo][expiryDate]);

//explode current date
$cur = explode("/", $currentDate);
//explode expiry date
$exp = explode("/", $expiryDate);

//fetch credit card error message using reg ex, if 'Invalid'
$responseText = trim($xmlres[SecurePayMessage][Payment][TxnList][Txn][responseText]);

//if year is bigger than current year
if($exp[1]>$cur[1]){
	//check if credit card number i valid
	if(preg_match('/Invalid/', $responseText)){
		//If invalid response
		echo "<p>Invalid credit card number</p>";
	} else {
	//success msg
	echo "</p>Success! Your order has been placed. Please check your email for an order confirmation and wait up to 5 business days for shipment information.</p>";
	//should only show last 4 digits number according to standards
	$pan = trim($xmlres[SecurePayMessage][Payment][TxnList][Txn][CreditCardInfo][pan]);
	
	//show only last three digits of pan response	
	$pan = substr($pan, -3);
	echo "<p>Credit card number ending in $pan has been succesfully charged. Thank you for your order.</p>";
	
	//delete cart
	unset($_SESSION['cart']);
	
		
	}
//if expirey year is current year, check month	
} else if (($exp[0]>$cur[0])&&($exp[1]==$cur[1])){
 	if(preg_match('/Invalid/', $responseText)){
 		echo "<p>Invalid credit card number</p>";
 	} else {
 		//success msg
 		echo "</p>Success! Your order has been placed. Please check your email for an order confirmation and wait up to 5 business days for shipment information.</p>";
 		//should only show last 4 digits number
 		$pan = trim($xmlres[SecurePayMessage][Payment][TxnList][Txn][CreditCardInfo][pan]);
 	
 		//show only last three digits of pan response
 	
 		$pan = substr($pan, -3);
 		echo "<p>Credit card number ending in $pan has been succesfully charged. Thank you for your order.</p>";
 	
 		//delete cart
 		unset($_SESSION['cart']);
 	
 	}
//Reject expiry dates before current date 	
} else {
	echo "<p>Expired credit card, please enter a valid credit card.</p>";
}


echo "<p><hr /><a href=\"payment_page.php\">Return to Payment Page</a></p>";
echo "<p><br><a href=\"main.php\">Return to Main Page</a></p>";


		// expression
		break;
	default:
		// expression
		break;
}

function getGMTtimeStamp()
{
	$stamp = date("YmdGis")."000+1000";
	return $stamp;
}

/**************************/
/* Secure Socket Function */
/**************************/
function openSocket($host,$query){
        // Break the URL into usable parts
        $path = explode('/',$host);
        $host = $path[0];
        unset($path[0]);
        $path = '/'.(implode('/',$path));



        // Prepare the post query
        $post  = "POST $path HTTP/1.1\r\n";
        $post .= "Host: $host\r\n";
        $post .= "Content-type: application/x-www-form-urlencoded\r\n";
        $post .= "Content-type: text/xml\r\n";
        $post .= "Content-length: ".strlen($query)."\r\n";
        $post .= "Connection: close\r\n\r\n$query";

		//echo "<p>post = </p>";
		//echo $post;

        /***********************************************/
        /* Open the secure socket and post the message */
        /***********************************************/
       $h = fsockopen("ssl://".$host, 443, $errno, $errstr);

        if ($errstr)
                print "$errstr ($errno)<br/>\n";
        fwrite($h,$post);

        /*******************************************/
        /* Retrieve the HTML headers (and discard) */
        /*******************************************/

//echo "<pre>";

        $headers = "";
        while ($str = trim(fgets($h, 4096))) {
//echo "Headers1: ".$str."\n";
                $headers .= "$str\n";
        }

        $headers2 = "";
        while ($str = trim(fgets($h, 4096))) {
//echo "Headers2: ".$str."\n";
                $headers2 .= "$str\n";
        }

//echo "</pre>";


        /**********************************************************/
        /* Retrieve the response */
        /**********************************************************/

        $body = "";
        while (!feof($h)) {
                $body .= fgets($h, 4096);
        }

        // Close the socket
        fclose($h);

        // Return the body of the response

        return $body;
}

	function makeXMLTree ($data) {
	   $output = array();
	
	   $parser = xml_parser_create();

	   xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	   xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	   xml_parse_into_struct($parser, $data, $values, $tags);
	   xml_parser_free($parser);
	
	   $hash_stack = array();
	
	   foreach ($values as $key => $val)
	   {
		   switch ($val['type'])
		   {
			   case 'open':
				   array_push($hash_stack, $val['tag']);
				   break;
		
			   case 'close':
				   array_pop($hash_stack);
				   break;
		
			   case 'complete':
				   array_push($hash_stack, $val['tag']);
				   eval("\$output['" . implode($hash_stack, "']['") . "'] = \"{$val['value']}\";");
				   array_pop($hash_stack);
				   break;
		   }
	   }

	   return $output;
   }
   
/*} else {
	//Dsiplay this if user is not logegd in
	echo 'Not logged in.<br><a href="login.html">Login to view page</a></br>';
	
} */ 
?> 
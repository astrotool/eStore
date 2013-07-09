<?php
echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
session_start();

//session update
$old_sessionid = session_id();

session_regenerate_id();

$new_sessionid = session_id();
$_SESSION['token'] = session_id();

include 'dbOracle.php';

$order = array();
isset($_SESSION['cart']);

/* *** A8 - Failure to Restrict URL Access. Check authentication before providing access to page.
 *
*/
if(isset($_SESSION['email'])){
$cartLen = count($_SESSION['cart']);


//for loop to iterate through cart items
for ($i=0; $i < $cartLen; $i++){

	if($cartLen){
		
		$newconn = conndb();
		//sql
		$s = oci_parse($newconn, "select * from PRODUCT where PRODUCTID=:pid_prefix");
				
		$plook=$_SESSION['cart'][$i]['productid'];
		oci_bind_by_name($s, ':pid_prefix', $plook);
		oci_execute($s);
		//fetch a single row depending on product id
		$res = oci_fetch_assoc($s);
		
		array_push($order, $res['PRODUCTNAME']);
		//$order .= $res['PRODUCTNAME'];
		
		echo "";

	}

}

//Send email regarding order
function smail($order){
	//assign form values to variables
	$email = $_SESSION['email'];
	
	//ordered items
		
	$subject = "Order confirmation";
	$message = "Hello! Your order has been recieved for $order. We will notify you after dispatch. Thank you for your purchase. \nFrom: Ali Alavi - #40876144";
	$from = "admin@mqbookstore.com";
	$headers = "From:" . $from;
	
	

	//if statement to check mail function works, else returns error message.
	if (mail($email,$subject,$message,$headers))
	{
		
		print("You ordered: $order.\nYour order is being processed. An email confirmation has been sent to, $email.");
		echo '<br><a href="main.php">Return to shop.</a><br>';
		//clear cart
		unset($_SESSION['cart']);
	}
	else {
		echo "Unable to send email.<br>";
	}

}

$order=implode(", ", $order);
//call send email function

	
	smail($order);
} else {
	echo 'Not logged in.<br><a href="login.html">Login to view page</a></br>';
}
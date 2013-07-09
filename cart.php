<?php

echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
session_start();

/* *** A8 - Failure to Restrict URL Access. Check authentication before providing access to page. 
 * *** A4 - Insecure Direct Object Reference. User requests are only allowed by those logged in, avoiding strangers from accessing records.
 */
if(isset($_SESSION['email'])){
//
echo "<h3>This is the contents of your cart:</h3><br>";


//for database connection
include 'dbOracle.php';

//create cart array if none exists
if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = array();
}


//add to cart function
function addToCart($pID,$qty){
	
	$cartLen = count($_SESSION['cart']);	
//check it item is already in cart
if(isset($_SESSION['cart'][0]['productid'])){
	$flag = 0;
	for ($i=0; $i < $cartLen; $i++){
		if($pID==$_SESSION['cart'][$i]['productid']){
			$flag=1;
			break;
		} 
	}
	if($flag==0){
	//add new item to session
	$_SESSION['cart'][$cartLen]['productid']= $pID;
	$_SESSION['cart'][$cartLen]['qty']=$qty;
	
	}
} else {
//add first item to session
	
$_SESSION['cart'][0]['productid']=$pID;
$_SESSION['cart'][0]['qty']=1;
}
}
//display cart function
function dispCart(){
	$total = 0;//Price total of shopping cart.
	$cartLen = count($_SESSION['cart']);

	if($cartLen<1){
		echo 'You have no items in your cart.<br><a href="main.php">Keep shopping</a><br>';
		die;
	}

//for loop to iterate through cart items	
for ($i=0; $i < $cartLen; $i++){
		
	if($cartLen>0){
		
		$newconn = conndb();
		/* *** A1 - Injection attacks, converted all SQL statments to include binding/placeholders to prevent injection attacks.
		*
		*/
		$s = oci_parse($newconn, "select * from PRODUCT where PRODUCTID=:pid_prefix");
		
		$plook=$_SESSION['cart'][$i]['productid'];
		oci_bind_by_name($s, ':pid_prefix', $plook);
		oci_execute($s);
		//fetch a single row depending on product id
		$res = oci_fetch_assoc($s);
		
		//quantity update
		$qty_ = $_SESSION['cart'][$i]['qty'];
		$price_ = $res['PRODUCTPRICE'];
		//display cart total
		$total_ =($qty_*$price_);
		
		$total += $total_;
		 
		if($res){		
		echo '<form name="form2" method="get">';
		
		echo "Product name: " , $res['PRODUCTNAME'] , " Price: " , number_format($res['PRODUCTPRICE'], 2, '.', '');
		echo '<a href="cart.php?del='.$i.'"> Remove item</a><br>';
		
		echo "</form>";
		}
		
				
		}
	
		
}

//add the cart total to the session array and display total
//setlocale(LC_MONETARY, 'en_AUS');
$_SESSION['cart'][0]['total']=$total;
$cartTotal=$_SESSION['cart'][0]['total'];
$cartTotal=number_format($cartTotal, 2, '.', '');
echo "<br>Your cart total is $$cartTotal<br>";


if($cartLen>0){
	echo '<a href="payment_page.php">Proceed to checkout</a><br>';
	
}

}

//if item has been added
if(isset($_GET['add']))
{
	
	/* *** A2 - Cross-Site Scripting. Using reg ex and sanitize functions to remove unwanted get requests. Add value must be a digit with a length of either 3 or 4. 
	 * *** A4 - Insecure Direct Object Reference. Validating input using reg ex(numbers only) avoids the use of malicious character sequences.
	 */
	if(preg_match('/(^[0-9]{3,4}$)/', $_GET['add'])){
	$pID = filter_input(INPUT_GET, 'add', FILTER_SANITIZE_SPECIAL_CHARS);
	$pID=strip_tags($pID);
	
	addToCart($pID,1);
	dispCart();
	
	} else {
		//Only allow 'add' request made up of 3 or 4 digits 
		die('WARNING: Invalid value entry.<br><a href="main.php">Keep shopping</a>'); 
	}
	
}


//if item has been deleted
if(isset($_GET['del'])){
	/* *** A2 - Cross-Site Scripting. Using reg ex and sanitize functions to remove unwanted delete item requests. 'del' value must be a digit with a length of either 1 or 2.
	* *** A4 - Insecure Direct Object Reference. Validating input using reg ex(numbers only) avoids the use of malicious character sequences.
	*/
	if(preg_match('/(^[0-9]{1,2}$)/', $_GET['del'])){
	$val= filter_input(INPUT_GET, 'del', FILTER_SANITIZE_SPECIAL_CHARS);
	$val= strip_tags($val);
	unset($_SESSION['cart'][$val]);
	//keep array indexes intact
	$_SESSION['cart'] = array_values($_SESSION['cart']);
	} else {
		//Only allow 'add' request made up of 1 or 2 digits 
		die('WARNING: Invalid value entry.<br><a href="main.php">Keep shopping</a>'); 
	}

}

if(!isset($_GET['add'])){
	dispCart();
}
//
echo '<a href="main.php">Keep shopping</a><br>';


//echo "<br>$cartLen";
} else {
	echo 'Please <a href="login.html">login</a> to view your cart.';
}

//
if(isset($_GET['qty'])){
		$updateVal = $_GET['qty'];
		$updateVal_ = explode(":", $updateVal);
		$cartLen = count($_SESSION['cart']);
		//$val=$_GET["qty"];
		/*for ($i=0; $i < $cartLen; $i++){
			if($updateVal_[0]==$_SESSION['cart'][$i]['productid']){
				$_SESSION['cart'][$i]['qty']=$updateVal_[1];
			}*/
		
		echo "$updateVal<br>Qty:$updateVal_[1]<br>ID:$updateVal_[0]<br>$val";
		
		
	//dispCart();
	
	}
	



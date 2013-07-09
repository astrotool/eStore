<?php

echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
session_start();

//check user is logged in
if(isset($_SESSION['email'])){
//
echo "<h3>This is the contents of your cart:</h3><br>";


//for database connection
include 'dbOracle.php';


if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = array();
}



//add to cart function
function addToCart($pID){
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
	$_SESSION['cart'][$cartLen]['qty']=1;
	
	}
} else {
//add first item to session
	
$_SESSION['cart'][0]['productid']=$pID;
$_SESSION['cart'][0]['qty']=1;
}
}
//display cart function
function dispCart(){
$cartLen = count($_SESSION['cart']);

if($cartLen<1){
	echo 'You have no items in your cart.<br><a href="main.php">Keep shopping</a><br>';
	die;
}

//for loop to iterate through cart items	
for ($i=0; $i < $cartLen; $i++){
		
	if($cartLen>0){
		
		$newconn = conndb();
		//sql
		$s = oci_parse($newconn, "select * from PRODUCT where PRODUCTID=:pid_prefix");
		
		$plook=$_SESSION['cart'][$i]['productid'];
		oci_bind_by_name($s, ':pid_prefix', $plook);
		oci_execute($s);
		//fetch a single row depending on product id
		$res = oci_fetch_assoc($s);
		
		echo "Product name: " , $res['PRODUCTNAME'] , " Price: " , $res['PRODUCTPRICE'];
		echo '<a href="cart.php?del='.$i.'"> Remove item</a><br>';
		
		}
	
}


if($cartLen>0){
	echo '<a href="checkout.php">Proceed to checkout</a><br>';
	
}

}

//if item has been added
if(isset($_GET['add']))
{
	$pID = $_GET['add'];
	addToCart($pID);

	dispCart();
}


//if item has been deleted
if(isset($_GET['del'])){
	$val=$_GET['del'];
	unset($_SESSION['cart'][$val]);
	//keep array indexes intact
	$_SESSION['cart'] = array_values($_SESSION['cart']);

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




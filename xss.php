<?php
if(isset($_GET['xss']))
{

	/* *** A2 - Cross-Site Scripting. Using reg ex and sanitize functions to remove unwanted get requests. Add value must be a digit with a length of either 3 or 4.
	 * *** A4 - Insecure Direct Object Reference. Validating input using reg ex(numbers only) avoids the use of malicious character sequences.
	
	if(preg_match('/(^[0-9]{3,4}$)/', $_GET['add'])){
		$pID = filter_input(INPUT_GET, 'add', FILTER_SANITIZE_SPECIAL_CHARS);
		$pID=strip_tags($pID);

		addToCart($pID,1);
		dispCart();

	} else {
		//Only allow 'add' request made up of 3 or 4 digits
		die('WARNING: Invalid value entry.<br><a href="main.php">Keep shopping</a>');
	}*/
	
	$val = $_GET['xss'];
	//$val = filter_input(INPUT_GET, 'xss', FILTER_SANITIZE_SPECIAL_CHARS);
	//$val = htmlspecialchars($val, ENT_QUOTES); // insert HTML encodes
	if($val) echo "$val";
	

}
echo '<br><a href="xss.php?xss=<script>alert(document.cookie);</script>">session details via xss</a>';
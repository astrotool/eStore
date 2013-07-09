<?php
session_start();


$old_sessionid = session_id();

session_regenerate_id();

$new_sessionid = session_id();

echo "Old Session: $old_sessionid<br />";
echo "New Session: $new_sessionid<br />";
echo "New Session: $session_id()<br />";

print_r($session_id());
  /* $decoded_64=base64_decode($_SESSION['email']); 
    $key = "40876144";// same as you used to encrypt 
    $td = mcrypt_module_open('cast-256', '', 'ecb', ''); 
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
    mcrypt_generic_init($td, $key, $iv); 
    $decrypted_data = mdecrypt_generic($td, $decoded_64); 
    mcrypt_generic_deinit($td); 
    mcrypt_module_close($td);
    
    echo $decrypted_data;
    
    
    
    
if(isset($_SESSION['email']))
{

	
	$text = '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>';
	//$new = htmlspecialchars($text, ENT_QUOTES);
	strip_tags($text);
	echo $text;
	$vemail = filter_var('bob@exam%ple.com', FILTER_VALIDATE_EMAIL);
	
	if(!$vemail){
		//exit("unable to reg");
		//die;
		
	} else {
		echo "true";
	
	}
	
	
	$new2 = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $text);
	echo $new2;	
	*/
	
	
	
    
//header("location: payment_page.php");
/*} else {
	echo "<br>please login";
}*/



//echo $new;
/*
$num = 1234;
$num = substr($num, -1);


$ccdate='12/12';
$date2=date("m/y");
//echo date("m/y");
if($date1>$date2){ echo 'true';} else{echo 'false';}

$date_=date("m/y");
$expiryDate = $date1;


echo "<br>$num<br>$date_";

$c = explode("/", $date_);
echo "<br>DATE:$c[0], $c[1]";

$x = explode("/", $ccdate);
echo "<br>CC:$x[0], $x[1]";


if($x[1]>=$c[1]){
	echo "Year bigger";
	
}

//year check ($c[0]>$x[0])&&
if($x[1]>$c[1]){
	
	echo "<p>TRUE</p>";
} else if(($x[0]>$c[0])&&($x[1]==$c[1])) {
	echo "<p>TRUE</p>";
} else {
	echo "<p>expiryDate = Expired credit card.$date2</p>";
}
*/
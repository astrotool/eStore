<?php

/* *** A2 - Cross-Site Scripting. I have used both strip_tags and a reg expression because I had mixed results. Either way this will avoid cross site scripting.
 * Other solutions include htmlspecialchars(), strtr(), utf8_decode().
*/

//Sanitize user inputs
$username = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$firstname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);
$lastname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'pass1', FILTER_SANITIZE_SPECIAL_CHARS);

//Remove html tags
$username = strip_tags($username);
$username=preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $username);

$firstname = strip_tags($firstname);
$firstname = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $firstname);

$lastname = strip_tags($lastname);
$lastname = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $lastname);

$email = strip_tags($email);
$email = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $email);

$password = strip_tags($password);
$password = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $password);


//function connect to db
function connDB(){

	$oraUser="40876144";
	$oraPass="pepsi2000";
	$oraDB="(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=matrix.science.mq.edu.au)(PORT=1521)))(CONNECT_DATA=(SID=neo)))";

	$conn = oci_connect($oraUser,$oraPass,$oraDB);
	return $conn;
}


//function
function addusr($conn){
	echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
	
	global $firstname, $lastname, $username, $email, $password;
	
	$password_enc = createEnc($password);

	//Stop script if email account is empty after sanitization process(used for error checking by myself)
	if($email==null){
		exit('Unable to create account');
	}



	//check username - validate server side
	$s = oci_parse($conn, "select email from tblusers where username=:username_prefix");
	
	oci_bind_by_name($s, ':username_prefix', $username);
	oci_execute($s);
	
	$res = oci_fetch_array($s);
	if ($res){
		oci_free_statement($s);
		oci_close($conn);
		echo 'Could not register user, account already exists.</br> <a href="index.html">Return to login page</a>';
		return false;
	} else {
		
		/* *** A1 - Injection attacks, converted all SQL statments to include binding/placeholders to prevent injection attacks.
		 * 
		 */
		
		//build sql command to register new user
		$s = oci_parse($conn, "INSERT INTO TBLUSERS (FIRSTNAME, LASTNAME, EMAIL, USERNAME, PASSWORD) VALUES (:fn, :ln, :e, :un, :pw)");
		
		//execute insert statement
		oci_bind_by_name($s, ':un', $username);
		oci_bind_by_name($s, ':fn', $firstname);
		oci_bind_by_name($s, ':ln', $lastname);
		oci_bind_by_name($s, ':e', $email);
		oci_bind_by_name($s, ':pw', $password_enc);
		oci_execute($s);
		
		oci_free_statement($s);
		oci_close($conn);
		return true;
		
	}
		

}	
//call database connection function
$newconn = conndb();
//call add user to database funtion
if(isset($_POST["fname"]))
{
	$regUsr =addusr($newconn);
	//send email
	function smail(){
		//assign form values to variables
		global $email, $firstname;
		$subject = "Online bookstore registration";
		$message = "Hello $name! Please fallow these steps to complete registration. \nFrom: Ali Alavi - #40876144";
		$from = "admin@mqbookstore.com";
		$headers = "From:" . $from;
	
		//if statement to check mail function works, else returns error message.
		if (mail($email,$subject,$message,$headers))
		{
			print("Thank you for registering for the bookstore, <i>$name</i>. Please check your email address(<i>$email</i>) for instruction to complete registration.");
		}
		else {
			echo "Unable to send email.";
		}
		
		}
		//call send email function
		smail();
}
//print a success message
if(isset($regUsr))	{
	echo 'Account created successfully!</br> <a href="login.html">Return to login page</a>';
}

/* *** A7 - Insecure Cryptographic Storage. Using php's inbuilt mcrypt extension provides a standardized interface.
 *
*/

function createEnc($val){
	//assign key(my student number)
	$key = "40876144";
	//assign value to be encrypted
	$input = $val;

	//open module and select encryption mode
	$td = mcrypt_module_open('cast-256', '', 'ecb', '');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	//initialize encryption buffer
	mcrypt_generic_init($td, $key, $iv);
	//encryption
	$enc_data = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$enc_val=base64_encode($enc_data);
	return $enc_val;
}


/* *** A7 - Insecure Cryptographic Storage. Encryption decoding, using php's inbuilt mcrypt extension which provides a standardized encryption interface.
 *
*/
function decEnc($val){

	$dec_val=base64_decode($val);
	$key = "40876144";
	$td = mcrypt_module_open('cast-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$dec_data = mdecrypt_generic($td, $dec_val);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

	return $dec_data;
}

/* *** A3 - Broken Authentication, implement session saving to database.
 *
*/

function addSessDb($conn){
	$sess = session_id();
	$username = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);

	$s = oci_parse($conn, "INSERT INTO SESSTABLE VALUES ($sess, :username_prefix)");

	oci_bind_by_name($s, ':username_prefix', $username);
	oci_execute($s);

	oci_free_statement($s);

	oci_close($conn);

}


?> 



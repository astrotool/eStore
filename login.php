<?php
//Functions included, database connection, encryption, user registration
include 'dbOracle.php';

//capture form details, sanitize
$username = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);

$password = filter_input(INPUT_POST, 'pass1', FILTER_SANITIZE_SPECIAL_CHARS);

/*** A2 - Cross-Site Scripting. I have used both strip_tags and a reg expression because I had mixed results. Either way this will avoid cross site scripting.
 * Other solutions include htmlspecialchars(), strtr(), utf8_decode().
*/

$username = strip_tags($username);

$username=preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $username);

//$username=$_POST["email"];
//$password=$_POST["pass1"];

$password_enc = createEnc($password);


//function to check in credentials from login form
function loginChk($conn){
	isset($_POST["email"]);
	isset($_POST["pass1"]);

	global $username, $password, $password_enc;
	
	/* *** A1 - Injection attacks, converted all SQL statments to include binding/placeholders to prevent injection attacks.
	 *
	*/

	//check password in database
	$s = oci_parse($conn, "SELECT username FROM tblusers WHERE username=:username_prefix AND password=:pw");
	
	oci_bind_by_name($s, ':username_prefix', $username);
	oci_bind_by_name($s, ':pw', $password_enc);
	oci_execute($s);
	
	//evaluate based on db information
	$res = oci_fetch_row($s);
	if ($res){
		oci_free_statement($s);
		oci_close($conn);
		
		return true;
	} else {
		
		oci_free_statement($s);

		oci_close($conn);
		
		echo "Username or password were incorrect.</br> Please try to login again, <a href='login.html'>click to return to login page</a>.";
		return false;
		
	}
		

}	
//call database connection function
$newconn = conndb();
//call add user to database funtion
$loginUsr = loginChk($newconn);
//once login is succesfull, create seassion and forward user
if($loginUsr)	{
	//echo 'logged in';
	session_start();
	global $username;
	
	$encrypted_data= createEnc($username);

	$_SESSION['email'] = $encrypted_data;
	$_SESSION['loggedin'] = time();
	$_SESSION['token'] = session_id();
	
	//addSessDb($newconn);

	session_write_close();
	
	//sess to db
	
	/* *** A10 -  Unvalidated Redirects and Forwards. Only relative url is given and the full/absolute is avoided.
	 *
	*/
    
	header("location: main.php");
	
}






?> 



<?php

//function connect to db
function connDB(){

	//addusr($conn);
	$oraUser="40876144";
	$oraPass="pepsi2000";
	$oraDB="(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=matrix.science.mq.edu.au)(PORT=1521)))(CONNECT_DATA=(SID=neo)))";

	$conn = oci_connect($oraUser,$oraPass,$oraDB);
	return $conn;
}



//function
function addusr($conn){

	//
	isset($_POST["fname"]);
	isset($_POST["lname"]);
	isset($_POST["email"]);
	isset($_POST["pass1"]);

	//capture form details
	$firstname=$_POST["fname"];
	$lastname=$_POST["lname"];
	$email=$_POST["email"];
	$username=$_POST["email"];
	$password=$_POST["pass1"];

	//check username - validate server side
	$s = oci_parse($conn, "select email from tblusers where username=:username_prefix");
	//$username = 'ali.alavi@students.mq.edu.au';
	oci_bind_by_name($s, ':username_prefix', $username);
	oci_execute($s);

	$res = oci_fetch_array($s);
	if ($res){
		oci_free_statement($s);
		oci_close($conn);
		echo 'Could not register user, account already exists.</br> <a href="index.html">Return to login page</a>';
		return false;
	} else {
		//build sql command
		
		$bbsSQL = "INSERT INTO TBLUSERS (FIRSTNAME, LASTNAME, EMAIL, USERNAME, PASSWORD) VALUES ('$firstname', '$lastname', '$email', '$username', '$password')";
		
		//create connection
		//$conn =  connDB();
		
		$personinfo=oci_parse($conn,$bbsSQL);
		oci_execute($personinfo);
		
		//echo "success";
		oci_free_statement($personinfo);
		oci_close($conn);
		return true;
		
	}
		


	



}	

if(isset($_POST["fname"])){
$newconn = conndb();
$regUsr =addusr($newconn);
if($regUsr)	{
	echo 'Account created successfully!</br> <a href="index.html">Return to login page</a>';
}}

?> 



<?php
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
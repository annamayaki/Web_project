<?php

$ciphers             = openssl_get_cipher_methods();
$ciphers_and_aliases = openssl_get_cipher_methods(true);
$cipher_aliases      = array_diff($ciphers_and_aliases, $ciphers);

//ECB mode should be avoided
$ciphers = array_filter( $ciphers, function($n) { return stripos($n,"ecb")===FALSE; } );

//At least as early as Aug 2016, Openssl declared the following weak: RC2, RC4, DES, 3DES, MD5 based
$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"des")===FALSE; } );
$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"rc2")===FALSE; } );
$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"rc4")===FALSE; } );
$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"md5")===FALSE; } );
$cipher_aliases = array_filter($cipher_aliases,function($c) { return stripos($c,"des")===FALSE; } );
$cipher_aliases = array_filter($cipher_aliases,function($c) { return stripos($c,"rc2")===FALSE; } );


// print_r(hash_algos());
// print_r($ciphers);
// print_r($cipher_aliases);

// $hash = password_hash("rasmuslerdorf", PASSWORD_BCRYPT);
// echo $hash."\n";

// if (password_verify('rasmuslerdorf', $hash)) {
//     echo 'Password is valid!';
// } 
// else {
//     echo 'Invalid password.';
// }

// $plaintext = "jAppleseed@icloud.com";
// $key = "@ppleSeed20";
// $cipher = "aes-128-cbc";
// $ciphertext = openssl_encrypt($plaintext, $cipher, $key);
// print "ciphertext: ".$ciphertext."\n";
// $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key);
// print "original plaintext: ".$original_plaintext."\n";

// $plaintext = "anna@me.com";
// $key = "anna1234";
// $cipher = "aes-128-cbc";
// $ciphertext = openssl_encrypt($plaintext, $cipher, $key);
// print "ciphertext: ".$ciphertext."\n";
// $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key);
// print "original plaintext: ".$original_plaintext."\n";
// $hash = password_hash($key, PASSWORD_BCRYPT);
// print $hash."\n";

$plaintext = "theBoss@supertrouper.co";
$key = "1toRuleth3m@ll";
$cipher = "aes-128-cbc";
$ciphertext = openssl_encrypt($plaintext, $cipher, $key);
print "ciphertext: ".$ciphertext."\n";
$original_plaintext = openssl_decrypt($ciphertext, $cipher, $key);
print "original plaintext: ".$original_plaintext."\n";
$hash = password_hash($key, PASSWORD_BCRYPT);
print $hash."\n";

?>

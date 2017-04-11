<?php
var_dump( $_REQUEST );

echo '<br/><br/><br/>';
echo route('profile', ['id'=>2, 'aaa'=>'1111']);


echo '<br/><br/><br/>';
var_dump($data);

$environment = app()->environment();
var_dump($environment);
echo '<br/><br/><br/>';

// $bytes = openssl_random_pseudo_bytes (16, $cstrong);
// $hex   = bin2hex($bytes);
// echo strlen( $hex );
// echo '<br/><br/><br/>';
// echo $hex;
echo env('version');

echo '<br/><br/><br/>';
echo phpinfo();

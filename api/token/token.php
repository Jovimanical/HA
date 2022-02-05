<?php

define('SECRET_KEY', $_ENV['HA_JWT_SECRET_KEY']);  // secret key can be a random string and keep in secret from anyone
define('ALGORITHM', $_ENV['HA_JWT_ALGORITHM']);   // Algorithm used to sign the token
$iat = time() + (1 * 24 * 60 * 60);       // time of token issued at + (1 day converted into seconds)
$nbf = $iat + 100; //not before in seconds
$tokenExp = $iat + 60 * 60; // expire time of token in seconds (1 min * 60)
$token = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "exp" => $tokenExp,
    "data" => array() // add anything you want to add to token //php array
);
?>

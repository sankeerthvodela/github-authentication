<?php
// Start session
if(!session_id()){
    session_start();
}

require_once 'OAuth.php';

$clientID         = 'da2ed4c948090829b84e';
$clientSecret     = '2588693c65cb878a653cf8b53d6bb19528a384d5';
$redirectURL     = 'http://localhost/github_authentication/';

$gitClient = new OAuth(array(
    'client_id' => $clientID,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectURL,
));

if(isset($_SESSION['access_token'])){
    $accessToken = $_SESSION['access_token'];
}
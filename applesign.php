<?php
include 'vendor/autoload.php';
use AppleSignIn\ASDecoder;

$clientUser = "example_client_user";
$identityToken = "eyJraWQiOiI4NkQ4OEtmIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLmx0ZHkudGFsaSIsImV4cCI6MTU5Mzc3MDI1NywiaWF0IjoxNTkzNzY5NjU3LCJzdWIiOiIwMDA3MzcuMDhjMzBmNzk2MjczNGZmNWJhYzBlYjBmNmM5NDUyMzMuMDU1NiIsImNfaGFzaCI6ImJnOExEcHZfUWNtandHMklDVjU2VUEiLCJlbWFpbCI6ImE0MjQ3MjU2QDEyNi5jb20iLCJlbWFpbF92ZXJpZmllZCI6InRydWUiLCJhdXRoX3RpbWUiOjE1OTM3Njk2NTcsIm5vbmNlX3N1cHBvcnRlZCI6dHJ1ZX0.SZV5xBEvW8_4EQ6jd-IdZEGVHeukUmGNQ0q6SjYoQEpJcbxn0bUrWJtAmR11yM3oNyNCIPZf16N_j9WR09ijGCBDvRcuKCkp1r79Z-MMJnR38Tql0slrxknl9O7YTkNtKvVkrz1zMWFFJ57rIoEU2bv_fhWn0S-mnbYgBq6ZMqIE-tyeOVkF5oP2BRASY3qjY-FOm1T3m_uGAgsy6BSMClT6OuZqKERECImSVP2N0Bho0KXls4tTTBwDo4IkYWuhsR7dW9hQ7Wo7huXerHpcjHKHElrvGNFHVwhM80V-tHiio38H_fLhdm0aVkwWLNZ46rz-Ut9y8Cdk4Dgknr6kew";

$appleSignInPayload = ASDecoder::getAppleSignInPayload($identityToken);

/**
 * Obtain the Sign In with Apple email and user creds.
 */
$email = $appleSignInPayload->getEmail();
$user = $appleSignInPayload->getUser();

/**
 * Determine whether the client-provided user is valid.
 */
$isValid = $appleSignInPayload->verifyUser($clientUser);


var_dump($email, $user, $isValid);
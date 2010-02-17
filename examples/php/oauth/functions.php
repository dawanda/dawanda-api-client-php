<?php
  function doHttpRequest($urlreq) {
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "$urlreq");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

    // grab URL and pass it to the browser
    $request_result = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);

    return $request_result;
  }
  
  $key = "3xUBSLroOcNJ2aPFf7bX";
  $secret = "2xAQlGqlp0ImaPGXICSYw881CsuTFa9Lijx96UjL";
  $base_url = "http://localhost/~prototype/dawanda-api/examples/php/oauth";

  $request_token_endpoint = "http://de.devanda.com/oauth/request_token";
  $authorize_endpoint = "http://de.devanda.com/oauth/authorize";
  $access_token_endpoint = "http://de.devanda.com/oauth/access_token";
?>
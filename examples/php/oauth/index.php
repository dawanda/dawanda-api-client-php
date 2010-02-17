<?
  require("functions.php");
  require("lib/OAuth.php");

  $test_consumer = new OAuthConsumer($key, $secret, NULL);
  $sig_method = new OAuthSignatureMethod_HMAC_SHA1();
  
  $parsed = parse_url($request_token_endpoint);
  $params = array(callback => $base_url);
  parse_str($parsed['query'], $params);
  
  $req_req = OAuthRequest::from_consumer_and_token($test_consumer, NULL, "GET", $request_token_endpoint, $params);
  $req_req->sign_request($sig_method, $test_consumer, NULL);

  $req_token = doHttpRequest ($req_req->to_url());
  
  parse_str ($req_token,$tokens);

  $oauth_token = $tokens['oauth_token'];
  $oauth_token_secret = $tokens['oauth_token_secret'];

  $callback_url = "$base_url/callback.php?key=$key&token=$oauth_token&token_secret=$oauth_token_secret&endpoint=" . urlencode($authorize_endpoint);
  $auth_url = $authorize_endpoint . "?oauth_token=$oauth_token&oauth_callback=".urlencode($callback_url);

  Header("Location: $auth_url");
?>
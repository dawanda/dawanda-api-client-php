<?php
  class DaWandaAPI {
    private $apiKey = null;
    private $host = null;
    public static $API_VERSION = 1;
    public static $AVAILABLE_LANGUAGES = array("de", "fr", "en");
    public static $BASE_HOST = ".dawanda.com";
    
    function __construct($apiKey, $language) {
      if(!in_array($language, DaWandaAPI::$AVAILABLE_LANGUAGES))
        throw new Exception("DaWanda only supports the following languages: ".join(", ", DaWandaAPI::$AVAILABLE_LANGUAGES));
      
      $this->apiKey = $apiKey;
      $this->host = "http://".$language.DaWandaAPI::$BASE_HOST;
    }
    
    function getHost() {
      return $this->host;
    }

    function searchUsers($keyword, $params=array()) {
      $params["keyword"] = $keyword;        
      $url = $this->getRequestPath("searchUsers");
      return $this->requestApi($url, $params);
    }
    
    function searchShops($keyword, $params=array()) {
      $params["keyword"] = $keyword;        
      $url = $this->getRequestPath("searchShops");
      return $this->requestApi($url, $params);
    }
    
    function searchProducts($keyword, $params=array()) {
      $params["keyword"] = $keyword;        
      $url = $this->getRequestPath("searchProducts");
      return $this->requestApi($url, $params);
    }
    
    function searchProductForColor($id, $keyword, $params=array()) {
      $params["keyword"] = $keyword;
      $url = $this->getRequestPath("searchProductForColor", $id);
      return $this->requestApi($url, $params);
    }
    
    function getUserDetails($id, $params=array()) {
      $url = $this->getRequestPath("getUserDetails", $id);
      return $this->requestApi($url, $params);
    }
    
    function getUserPinboards($id, $params=array()) {
      $url = $this->getRequestPath("getUserPinboards", $id);
      return $this->requestApi($url, $params);
    }
    
    function getShopDetails($id, $params=array()) {
      $url = $this->getRequestPath("getShopDetails", $id);
      return $this->requestApi($url, $params);
    }
    
    function getProductsForShop($id, $params=array()) {
      $url = $this->getRequestPath("getProductsForShop", $id);
      return $this->requestApi($url, $params);
    }
    
    function getCategoriesForShop($id, $params=array()) {
      $url = $this->getRequestPath("getCategoriesForShop", $id);
      return $this->requestApi($url, $params);
    }
    
    function getShopCategoryDetails($id, $params=array()) {
      $url = $this->getRequestPath("getShopCategoryDetails", $id);
      return $this->requestApi($url, $params);
    }
    
    function getProductsForShopCategory($id, $params=array()) {
      $url = $this->getRequestPath("getProductsForShopCategory", $id);
      return $this->requestApi($url, $params);
    }
    
    function getTopCategories($params=array()) {
      $url = $this->getRequestPath("getTopCategories");
      return $this->requestApi($url, $params);
    }
    
    function getCategoryDetails($id, $params=array()) {
      $url = $this->getRequestPath("getCategoryDetails", $id);
      return $this->requestApi($url, $params);
    }
    
    function getChildrenOfCategory($id, $params=array()) {
      $url = $this->getRequestPath("getChildrenOfCategory", $id);
      return $this->requestApi($url, $params);
    }
    
    function getProductsForCategory($id, $params=array()) {
      $url = $this->getRequestPath("getProductsForCategory", $id);
      return $this->requestApi($url, $params);
    }
    
    function getProductDetails($id, $params=array()) {
      $url = $this->getRequestPath("getProductDetails", $id);
      return $this->requestApi($url, $params);
    }
    
    function getColors($params=array()) {
      $url = $this->getRequestPath("getColors", $id);
      return $this->requestApi($url, $params);
    }
    
    function getProductsForColor($id, $params=array()) {
      $url = $this->getRequestPath("getProductsForColor", $id);
      return $this->requestApi($url, $params);
    }
    
    function getRequestPath($callee, $id="#{id}") {
      $endpoints = array(
        "searchUsers"                   => "/users/search.json",
        "searchShops"                   => "/shops/search.json",
        "searchProducts"                => "/products/search.json",
        "searchProductForColor"         => "/colors/#{id}/products/search.json",
        "getUserDetails"                => "/users/#{id}.json",
        "getUserPinboards"              => "/users/#{id}/pinboards.json",
        "getShopDetails"                => "/shops/#{id}.json",
        "getProductsForShop"            => "/shops/#{id}/products.json",
        "getCategoriesForShop"          => "/shops/#{id}/shop_categories.json",
        "getShopCategoryDetails"        => "/shop_categories/#{id}.json",
        "getProductsForShopCategory"    => "/shop_categories/#{id}/products.json",
        "getTopCategories"              => "/categories/top.json",
        "getCategoryDetails"            => "/categories/#{id}.json",
        "getChildrenOfCategory"         => "/categories/#{id}/children.json",
        "getProductsForCategory"        => "/categories/#{id}/products.json",
        "getProductDetails"             => "/products/#{id}.json",
        "getColors"                     => "/colors.json",
        "getProductsForColor"           => "/colors/#{id}/products.json"
      );
      
      return $this->host."/api/v".(DaWandaAPI::$API_VERSION).str_replace("#{id}", $id, $endpoints[$callee]);
    }
    
    function requestApi($url, $params) {
      $url .= "?api_key=".$this->apiKey;
      foreach(array_keys($params) as $key) $url .= "&".$key."=".$params[$key];

      $json = @file_get_contents($url);
      
      if($json)
        return (json_decode($json)->response);
      else
        throw new Exception("An error occurred while requesting: ".$url);
    }
  }
  
  class DaWandaOAuth {
    private $key = null;
    private $secret = null;
    private $endpoints = null;
    private $base_url = null;
    private $consumer = null;
    private $host = null;
    private $sig_method = null;
  
    function __construct($key, $secret, $language, $sig_method = null) {
      if(!in_array($language, DaWandaAPI::$AVAILABLE_LANGUAGES))
        throw new Exception("DaWanda only supports the following languages: ".join(", ", DaWandaAPI::$AVAILABLE_LANGUAGES));
      
      if(!class_exists("OAuthConsumer"))
        throw new Exception("Unable to find OAuth classes! Please require OAuth.php.");
      
      $url = "http".((!empty($_SERVER['HTTPS'])) ? "s" : "")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
      $this->base_url = substr($url, 0, strrpos($url, "/"));
      
      $this->key = $key;
      $this->secret = $secret;
      $this->consumer = new OAuthConsumer($this->key, $this->secret, NULL);
      $this->host = "http://".$language.DaWandaAPI::$BASE_HOST;
      
      $this->endpoints = array(
        "authorize"     => $this->host."/oauth/authorize",
        "request_token" => $this->host."/oauth/request_token",
        "access_token"  => $this->host."/oauth/access_token"
      );
      
      $this->sig_method = is_null($sig_method) ? new OAuthSignatureMethod_HMAC_SHA1() : $sig_method;
    }
  
    function doHttpRequest($urlreq) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, "$urlreq");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $request_result = curl_exec($curl);
      curl_close($curl);

      return $request_result;
    }
  
    function requestAuthorization($callback_file) {
      $parsed = parse_url($this->endpoints["request_token"]);
      $params = array(callback => $this->base_url);
      parse_str($parsed['query'], $params);

      $req_req = OAuthRequest::from_consumer_and_token($this->consumer, NULL, "GET", $this->endpoints["request_token"], $params);
      $req_req->sign_request($this->sig_method, $this->consumer, NULL);

      $req_token = $this->doHttpRequest($req_req->to_url());
      parse_str ($req_token,$tokens);

      $oauth_token = $tokens['oauth_token'];
      $oauth_token_secret = $tokens['oauth_token_secret'];

      $callback_url = "$this->base_url/$callback_file?key=$this->key&token=$oauth_token&token_secret=$oauth_token_secret&endpoint=" . urlencode($this->endpoints["authorize"]);
      $auth_url = $this->endpoints["authorize"] . "?oauth_token=$oauth_token&oauth_callback=".urlencode($callback_url);

      Header("Location: $auth_url");
    }
    
    function getAccessToken($token, $token_secret) {
      $auth_token = new OAuthConsumer($token, $token_secret);

      // rq_token => acc_token
      $req = new OAuthRequest("GET", $this->endpoints["access_token"]);
      $req = $req->from_consumer_and_token($this->consumer, $auth_token, "GET", $this->endpoints["access_token"]);
      $req->sign_request($this->sig_method, $this->consumer, $auth_token);
      $req_result = $this->doHttpRequest($req->to_url());
      parse_str($req_result, $access_tokens);
      
      return new OAuthConsumer($access_tokens['oauth_token'], $access_tokens['oauth_token_secret']);
    }
    
    function saveAccessToken($accessToken) {
      $file = fopen("token.csv", "w+");
      fputcsv($file, array($accessToken->key, $accessToken->secret));
      fclose($file);
    }
    
    function loadAccessToken() {
      $file = fopen("token.csv", "r");
      $data = fgetcsv($file);
      fclose($file);
      
      if(count($data) == 2)
        return new OAuthConsumer($data[0], $data[1]);
      else
        return null;
    }
    
    function getUserDetails($access_token) {
      $req = new OAuthRequest("GET", $this->endpoints["access_token"]);
      $req = $req->from_consumer_and_token($this->consumer, $access_token, "GET", $this->host."/api/v1/oauth/users.json");
      $req->sign_request($this->sig_method, $this->consumer, $access_token);
      $req_result = $this->doHttpRequest($req->to_url());

      return json_decode($req_result)->response->result->user;
    }
    
    function getOrders($access_token, $timestamp = null) {
      $url = $this->host."/api/v1/oauth/orders.json" . (is_null($timestamp) ? "" : "?from=".@date('Y/m/d', $timestamp));
      
      $req = new OAuthRequest("GET", $this->endpoints["access_token"]);
      $req = $req->from_consumer_and_token($this->consumer, $access_token, "GET", $url);
      $req->sign_request($this->sig_method, $this->consumer, $access_token);
      $req_result = $this->doHttpRequest($req->to_url());
      return json_decode($req_result)->response->result->orders;
    }
  }
?>
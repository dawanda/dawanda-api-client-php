<?php
  class DaWandaAPI {
    private $apiKey = null;
    private $host = null;
    private $API_VERSION = 1;
    private $AVAILABLE_LANGUAGES = array("de", "fr", "en");
    
    function DaWandaAPI($apiKey, $language) {
      if(!in_array($language, $this->AVAILABLE_LANGUAGES))
        throw new Exception("DaWanda only supports the following languages: ".join(", ", $this->AVAILABLE_LANGUAGES));
      
      $this->apiKey = $apiKey;
      $this->host = "http://". $language .".devanda.com";
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
      
      return $this->host."/api/v".($this->API_VERSION).str_replace("#{id}", $id, $endpoints[$callee]);
    }
    
    function requestApi($url, $params) {
      $url .= "?api_key=".$this->apiKey;
      foreach(array_keys($params) as $key) $url .= "&".$key."=".$params[$key];
      
      $json = file_get_contents($url);
      return json_decode($json);
    }
  }
?>

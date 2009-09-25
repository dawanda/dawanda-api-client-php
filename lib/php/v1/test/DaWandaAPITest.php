<?php
  require_once 'PHPUnit/Framework.php';
  include("../src/dawanda.php");
  
  class DaWandaAPITest extends PHPUnit_Framework_TestCase {
    private $api;
    
    function DaWandaAPITest() {
      $this->api = new DaWandaAPI("380d7924396f5596116f3d8815c97dfd8c975582", "de");
    }
    
    public function testSearchUsers() {
      $users = $this->api->searchUsers("dawanda");
      $this->assertEquals($users->type, "user");
    }
    
    public function testSearchShops() {
      $shops = $this->api->searchShops("dawanda");
      $this->assertEquals($shops->type, "shop");
    }
    
    public function testSearchProducts() {
      $products = $this->api->searchProducts("tasche");
      $this->assertEquals($products->type, "product");
    }
    
    public function testSearchProductForColor() {
      $products = $this->api->searchProductForColor("ffffff", "tasche");
      $this->assertEquals($products->type, "product");
    }
    
    public function testGetUserDetails() {
      $user = $this->api->getUserDetails("dawanda-merchandise");
      $this->assertEquals($user->type, "user");
    }
    
    public function testGetUserPinboards() {
      $pinboards = $this->api->getUserPinboards("dawanda-merchandise");
      $this->assertEquals($pinboards->type, "pinboard");
    }
    
    public function testGetShopDetails() {
      $shop = $this->api->getShopDetails("dawanda-merchandise");
      $this->assertEquals($shop->type, "shop");
    }
    
    public function testGetProductsForShop() {
      $products = $this->api->getProductsForShop("dawanda-merchandise");
      $this->assertEquals($products->type, "product");
    }
    
    public function testGetCategoriesForShop() {
      $categories = $this->api->getCategoriesForShop("dawanda-merchandise");
      $this->assertEquals($categories->type, "shop_category");
    }
    
    public function testGetShopCategoryDetails() {
      $category = $this->api->getShopCategoryDetails(22934);
      $this->assertEquals($category->type, "shop_category");
    }
    
    public function testGetProductsForShopCategory() {
      $products = $this->api->getProductsForShopCategory(22934);
      $this->assertEquals($products->type, "product");
    }
    
    public function testGetTopCategories() {
      $categories = $this->api->getTopCategories();
      $this->assertEquals($categories->type, "category");
    }
    
    public function testGetCategoryDetails() {
      $category = $this->api->getCategoryDetails(318);
      $this->assertEquals($category->type, "category");
    }
    
    public function testGetChildrenOfCategory() {
      $category = $this->api->getChildrenOfCategory(318);
      $this->assertEquals($category->type, "category");
    }
    
    public function testGetProductsForCategory() {
      $products = $this->api->getProductsForCategory(610);
      $this->assertEquals($products->type, "product");
    }
    
    public function testGetProductDetails() {
      $product = $this->api->getProductDetails(325606);
      $this->assertEquals($product->type, "product");
    }
    
    public function testGetColors() {
      $colors = $this->api->getColors();
      $this->assertEquals($colors->type, "image_color");
    }
    
    public function testGetProductsForColor() {
      $products = $this->api->getProductsForColor("ffffff");
      $this->assertEquals($products->type, "product");
    }
  }
?>
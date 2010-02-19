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
      
      foreach($users->result->users as $user) {
        $this->assertGreaterThan(-1, (int) stripos($user->name, "dawanda"));
      }
    }
    
    public function testSearchShops() {
      $shops = $this->api->searchShops("dawanda");
      $this->assertEquals($shops->type, "shop");
      
      foreach($shops->result->shops as $shop) {
        $this->assertGreaterThan(-1, (int) stripos($shop->name, "dawanda"));
      }
    }
    
    public function testSearchProducts() {
      $products = $this->api->searchProducts("tasche");
      $this->assertEquals($products->type, "product");
      
      foreach($products->result->products as $product) {
        $this->assertGreaterThan(-1, (int) stripos($product->name, "tasche"));
      }
    }
    
    public function testSearchProductForColor() {
      $products = $this->api->searchProductForColor("ffffff", "tasche");
      $this->assertEquals($products->type, "product");
      
      foreach($products->result->products as $product) {
        $containers_white_color = false;
        $this->assertGreaterThan(-1, (int) stripos($product->name, "tasche"));
        
        foreach($product->image_colors as $color)
          if($color->hex == "ffffff") $containers_white_color = true;
        
        $this->assertTrue((count($product->image_colors) == 0) || $containers_white_color);
      }
    }
    
    public function testGetUserDetails() {
      $user = $this->api->getUserDetails("dawanda-merchandise");
      $this->assertEquals($user->type, "user");
      $this->assertEquals($user->result->user->name, "DaWanda-Merchandise");
    }
    
    public function testGetUserPinboards() {
      $pinboards = $this->api->getUserPinboards("dawanda-merchandise");
      $this->assertEquals($pinboards->type, "pinboard");
      
      foreach($pinboards->result->pinboards as $pinboard)
        $this->assertGreaterThan(-1, (int) stripos($pinboard->restful_path, "pinboards"));
    }
    
    public function testGetShopDetails() {
      $shop = $this->api->getShopDetails("dawanda-merchandise");
      $this->assertEquals($shop->type, "shop");
      $this->assertEquals($shop->result->shop->name, "DaWanda Merchandise-Shop");
    }
    
    public function testGetProductsForShop() {
      $products = $this->api->getProductsForShop("dawanda-merchandise");
      $this->assertEquals($products->type, "product");
      foreach($products->result->products as $product)
        $this->assertEquals($product->user->name, "DaWanda-Merchandise");
    }
    
    public function testGetCategoriesForShop() {
      $categories = $this->api->getCategoriesForShop("dawanda-merchandise");
      $this->assertEquals($categories->type, "shop_category");
      foreach($categories->result->shop_categories as $category)
        $this->assertGreaterThan(-1, (int) stripos($category->restful_path, "shop_categories"));
    }
    
    public function testGetShopCategoryDetails() {
      $category = $this->api->getShopCategoryDetails(22934);
      $this->assertEquals($category->type, "shop_category");
      $this->assertEquals($category->result->shop_category->id, 22934);
    }
    
    public function testGetProductsForShopCategory() {
      $products = $this->api->getProductsForShopCategory(22934);
      $this->assertEquals($products->type, "product");
      foreach($products->result->products as $product)
        $this->assertEquals($product->user->name, "DaWanda-Merchandise");
    }
    
    public function testGetTopCategories() {
      $categories = $this->api->getTopCategories();
      $this->assertEquals($categories->type, "category");
      foreach($categories->result->categories as $category)
        $this->assertGreaterThan(-1, (int) stripos($category->restful_path, "categories"));
    }
    
    public function testGetCategoryDetails() {
      $category = $this->api->getCategoryDetails(318);
      $this->assertEquals($category->type, "category");
      $this->assertEquals($category->result->category->id, 318);
    }
    
    public function testGetChildrenOfCategory() {
      $categories = $this->api->getChildrenOfCategory(318);
      $this->assertEquals($categories->type, "category");
      foreach($categories->result->categories as $category)
        $this->assertEquals($category->parent_id, 318);
    }
    
    public function testGetProductsForCategory() {
      $products = $this->api->getProductsForCategory(610);
      $this->assertEquals($products->type, "product");
      foreach($products->result->products as $product)
        $this->assertEquals($product->category->id, 610);
    }
    
    public function testGetProductDetails() {
      $product = $this->api->getProductDetails(325606);
      $this->assertEquals($product->type, "product");
      $this->assertEquals($product->result->product->id, 325606);
    }
    
    public function testGetColors() {
      $colors = $this->api->getColors();
      $this->assertEquals($colors->type, "image_color");
      $this->assertGreaterThan(10, count($colors->result->image_colors));
    }
    
    public function testGetProductsForColor() {
      $products = $this->api->getProductsForColor("ffffff");
      $this->assertEquals($products->type, "product");
      foreach($products->result->products as $product) {
        $containers_white_color = false;

        foreach($product->image_colors as $color)
          if($color->hex == "ffffff") $containers_white_color = true;
        
        $this->assertTrue((count($product->image_colors) == 0) || $containers_white_color);
      }
    }
  }
?>
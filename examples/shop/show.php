<?php
  include("../../lib/v1/src/dawanda.php");
  $api = new DaWandaAPI("380d7924396f5596116f3d8815c97dfd8c975582", "de");
  
  // empty variables
  $error = "";
  $category_content = "";
  $product_content = "";
  $current_page = 1;
  $max_page = 1;
  // empty variables - end
  
  if($_GET["username"]) {
    try {
      $shop_details = $api->getShopDetails($_GET["username"]);
      
      foreach($shop_details->result->shop->shop_categories as $shop_category) {
        $category_content .= "<li><a href='show.php?username=".$_GET["username"]."&shop_cat=".$shop_category->id."'>".$shop_category->name."</a></li>";
      }

      $current_page = isset($_GET["page"]) ? $_GET["page"] : 1;
      $shop_category = isset($_GET["shop_cat"]) ? $_GET["shop_cat"] : $shop_details->result->shop->shop_categories[0]->id;
      $products = $api->getProductsForShopCategory($shop_category, array("page" => $current_page));
      
      $max_page = $products->pages;
      if(count($products->result->products) == 0) {
        $product_content .= "<li>No products in this category.</li>";
      } else {
        foreach($products->result->products as $product) {
          var_dump($product);
          $product_content .= "<li>".
            "<a href='".$api->getHost()."/product/$product->id' target='_blank'>".
              "<img border='0' src='".$product->images[0]->image_160x120."'>".
            "</a>".
            "<span>".substr($product->name, 0, 25).((strlen($product->name) > 25) ? "..." : "")."</span>".
            "<div style='clear:both'></div>".
            "<span>".substr($product->user->name, 0, 20).((strlen($product->name) > 20) ? "..." : "")."</span>".
            "<span style='float:right'>".($product->price->cents/100)." ".$product->price->currency_code."</span>".
          "</li>";
        }
      }
    } catch(Exception $e) {
      $error = "Username is invalid!";
    }
  }
?>

<html>
  <head><? require("head.php"); ?></head>
  <body>
    <div id="mainContainer">
      <div id="pages">
        Page:
        <span id="currentPage"><?= $current_page ?></span> / 
        <span id="maxPage"><?= $max_page ?></span>
      </div>

      <form action="show.php" method="GET">
        Username:
        <input type="text" name="username"></input>
        <input type="submit" value="Go!"></input>
      </form>

      <div id="container">
        <div id="category_container"><ul id="categories"><?= $category_content ?></ul></div>
        <div id="product_container">
          <div style="text-align: center;" id="pagination_container">
            <? if($current_page > 1) { ?>
              <span id="back_button"><a href="show.php?username=<?= $_GET["username"] ?>&shop_cat=<?= $_GET["shop_cat"] ?>&page=<?= $current_page -1 ?>">Zurück</a></span>&nbsp;&nbsp;&nbsp;
            <? } ?>
            <? if($current_page < $max_page) { ?>
              <span id="forward_button"><a href="show.php?username=<?= $_GET["username"] ?>&shop_cat=<?= $_GET["shop_cat"] ?>&page=<?= $current_page + 1 ?>">Weiter</a></span>
            <? } ?>
          </div>
          <ul id="products"><?= ($product_content == "")?"<li>Currently no data!</li>":$product_content ?><div style='clear:both;'></div></ul>
          <div style="clear: both"></div>
        </div>
      </div>
      <div style="clear: both"></div>
    </div>
  </body>
</html>
  



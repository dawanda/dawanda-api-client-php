<?php
  include("../../../lib/php/v1/dawanda.php");
  $api = new DaWandaAPI("380d7924396f5596116f3d8815c97dfd8c975582", "de");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title>DaWanda API: PHP Framework</title>
  <script type="text/javascript" charset="utf-8" src="lib/jquery.js"></script>
  <script type="text/javascript" src="lib/jquery.puzzle.js"></script>
  <script type="text/javascript">
    
    function init() {
      $("#puzzles").empty();
      $("#others").empty();
      var username = $("#username")[0].value;
      $.ajax({
        dataType: 'jsonp',
        data: "per_page=7&api_key="+dawanda_api_key,
        jsonp: 'callback',
        url: "http://de.dawanda.com/api/v1/shops/"+username+"/products.js",
        success: function(data) {
      	  if(data.response.result.products) {
        	  var p = data.response.result.products[0];
    	      $(data.response.result.products).each(function(){
      	      $("#others").append('<img src="'+ this.images[0].image_80x80 +'" onclick="changePuzzle(\''+ this.images[0].image_80x80 +'\'); return false;" />');
      	    });

        	  changePuzzle(p.images[0].image_80x80);
      	  }
      	}
      });
    }
    
    function changePuzzle(source) {
      var src = source.replace("thumb", "big");
      var img = new Image();
      img.src = src;
      
      img.onload = function() {
        $("#puzzles").empty();
        $("#puzzles")
  	    .append(
  	      $('<div class="puzzle" style="float: left ; margin-right: 50px ;"></div>')
  	      .append('<img src="'+ src +'" width="'+img.width+'" height="'+img.height+'" />')
  	    );
  	    $("div.puzzle, p").puzzle(150);
  	    $("div.puzzle").css({
  	      left: "50%",
  	      marginLeft: -Math.floor(img.width/2),
  	      marginRight: 0,
  	      border: "none",
  	      position: "absolute"
  	    });
  	    
  	    $("#puzzles").append('<div style="clear:both"></div>');
  	    $("#container").css({height: img.height+5});
      }
    }
  </script>
  <style type="text/css" media="screen">
    #container {
      border:1px solid black;
      left:50%;
      margin-left:-450px;
      margin-top:20px;
      padding:5px;
      position:relative;
      width:900px;
    }
    
    #others {
      float: right;
      width: 90px;
    }
    
    #others img {
      display: block;
      margin-top: 5px;
    }
    
    #puzzles {
      float: left;
    }
  </style>
</head>
<body>
  <center>
    <form onsubmit="init(); return false;">
    Username:
    <input type="text" id="username" />
    <input type="button" value="Go!" onclick="init(); return false;" />
    </form>
  </center>
  
  <div id="container">
    <div id="others">
    
      <?php 
        if($_GET["username"]) {
          $products = $api->getProductsForShop($_GET["username"], array("per_page" => 7));
          foreach($products as $product) {
            print_r($product);
  //          echo '<img src="'+ $product->this.images[0].image_80x80 +'" onclick="changePuzzle(\''+ this.images[0].image_80x80 +'\';
          }
        }
      ?>
    </div>
    <div id="puzzles"></div>
    <div style="clear: both"></div>
  </div>
</body>
</html>
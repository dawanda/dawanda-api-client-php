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
    function changePuzzle(source) {
      var src = source.replace("thumb", "big");
      var img = new Image();
      img.src = src;
      
      img.onload = function() {
        $("#puzzle").empty();
        $("#puzzle")
  	    .append(
  	      $('<div class="puzzle" style="float: left ; margin-right: 50px ;"></div>')
  	      .append('<img src="'+ src +'" width="'+img.width+'" height="'+img.height+'" />')
  	    );
  	    $("div.puzzle, p").puzzle(120);
  	    $("div.puzzle").css({
  	      left: "50%",
  	      marginLeft: -Math.floor(img.width/2),
  	      marginRight: 0,
  	      border: "none",
  	      position: "absolute"
  	    });
  	    
  	    $("#puzzle").append('<div style="clear:both"></div>');
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
    
    #puzzle {
      float: left;
    }
  </style>
</head>
<body>
  <center>
    <form action="index.php" method="get">
      Username:
      <input type="text" name="username" value="<?= $_GET["username"] ?>" />
      <input type="button" value="Go!" />
    </form>
  </center>

  <div id="container">
    <div id="others">
      <?php
        $firstPuzzle = null;
      
        if($_GET["username"]) {
          $products = $api->getProductsForShop($_GET["username"], array("per_page" => 7));
          
          foreach($products->result->products as $product) {
            $img = $product->images[0]->image_80x80;
            if($firstPuzzle == null) $firstPuzzle = $img;
            
            echo '<img src="'. $img .'" onclick="changePuzzle(\''. $img .'\'); return false;" />';
          }
        }
      ?>
      <div style="clear: both"></div>
    </div>
    <div id="puzzle"></div>
    <div style="clear: both"></div>
  </div>
  <?php if($firstPuzzle != null) { ?>
    <script type="text/javascript" charset="utf-8">
      changePuzzle("<?= $firstPuzzle ?>");
    </script>
  <?php } ?>
</body>
</html>
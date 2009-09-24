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
        $("#puzzle").append('<img src="'+ src +'" width="'+img.width+'" height="'+img.height+'" />');
  	    $("#puzzle, p").puzzle(120);
  	    $("#puzzle").css({
  	      left: "50%",
  	      position: "absolute",
  	      margin: "10px",
  	      marginLeft: -(Math.floor(img.width/2) - 30)
  	    });
  	    
  	    window.setTimeout(function(){
  	      $("#others").css({
              left: Math.round($("#puzzle").offset().left + $("#puzzle").width())
          });
  	    }, 10);
      }
    }
  </script>
  <style type="text/css" media="screen">
    body {
      overflow-x:hidden;
      width:100%;
    }
    
    #container {
      left:50%;
      margin-left:-450px;
      margin-top:20px;
      padding:5px;
      position:relative;
      width:900px;
    }
    
    #others {
      width: 90px;
      position: relative;
      top: 5px;
    }
    
    #others div {
      display: block;
      width: 80px;
      height: 80px;
      margin-top: 5px;
      border: 2px solid #aaa;
      -moz-border-radius: 8px;
      -webkit-border-radius: 8px;
    }
    
    #puzzle {
      border: 2px solid #aaa !important;
      -moz-border-radius: 10px;
      -webkit-border-radius: 10px;
      background-color: #f7f7f7;
    }
    
    #puzzle div {
      -moz-border-radius: 8px;
      -webkit-border-radius: 8px;
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

  <?php if($_GET["username"]) { ?>
    <div id="puzzle" valign="middle"></div>
    <div id="others">
      <?php
        $firstPuzzle = null;      
        $products = $api->getProductsForShop($_GET["username"], array("per_page" => 7));

        foreach($products->result->products as $product) {
          $img = $product->images[0]->image_80x80;
          if($firstPuzzle == null) $firstPuzzle = $img;
          
          echo '<div style="background: transparent url('. $img .')" onclick="changePuzzle(\''. $img .'\'); return false;"></div>';
        }
      ?>
    </div>
  
    <?php if($firstPuzzle != null) { ?>
      <script type="text/javascript" charset="utf-8">
        changePuzzle("<?= $firstPuzzle ?>");
      </script>
    <?php } ?>
  <?php } ?>
</body>
</html>
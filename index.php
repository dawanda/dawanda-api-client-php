<html>
  <head><? require("head.php"); ?></head>
  <body>
    <div id="mainContainer">
      <div id="pages">
        Page:
        <span id="currentPage">0</span> / 
        <span id="maxPage">0</span>
      </div>
      <form action="show.php" method="GET">
        Username:
        <input type="text" name="username"></input>
        <input type="submit" value="Go!"></input>
      </form>

      <div id="container">
        <div id="category_container"><ul id="categories"><li>Currently no data!</li></ul></div>
        <div id="product_container">
          <ul id="products"><li>Currently no data!</li></ul>
          <div style="clear: both"></div>
        </div>
      </div>
      <div style="clear: both"></div>
    </div>
  </body>
</html>
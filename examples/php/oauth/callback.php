<?
  require_once ('config.php');
  require_once ("lib/OAuth.php");
  include("../../../lib/php/v1/src/dawanda.php");

  session_start();

  $dawanda_oauth = new DaWandaOAuth($key, $secret, "de");

  if($_REQUEST['token'] && $_REQUEST['token_secret']) {
    $access_token = $dawanda_oauth->getAccessToken($_REQUEST['token'], $_REQUEST['token_secret']);
    $dawanda_oauth->saveAccessToken($access_token);
    
    Header("Location: callback.php");
  }
  
  $access_token = $dawanda_oauth->loadAccessToken();
  $user         = $dawanda_oauth->getUserDetails($access_token);
  $orders       = $dawanda_oauth->getOrders($access_token);
  $date_orders  = $dawanda_oauth->getOrders($access_token, time());
?>

<html>
  <head>
    <style>
      html, body {
        font-family: Verdana;
        font-size: 12px;
        min-height: 100%;
        padding: 0px;
        margin: 0px;
      }

      #outer_container {
        width: 900px;
        position: absolute;
        left: 50%;
        margin-left: -450px;
        border: 1px solid #999;
        border-top: none;
        border-bottom: none;
        background-color: #f5f5f5;
        min-height: 100%;
      }

      table {
        border: 1px solid #444;
        border-bottom: none;
        border-right: none;
      }

      td, th {
        border-right: 1px solid #444;
        border-bottom: 1px solid #444;
        padding: 10px;
        text-align: center;
        background-color: #fafafa;
        font-size: 12px;
      }

      th {
        background-color: #eee;
      }
    </style>
  </head>
  <body>
    <div id="outer_container">
      <div style="margin: 10px">
        <a href="index.php">Aktualisieren</a>

        <? if($user) { ?>
          <h2>Hello <?= $user->username?></h2>
          <h2>Your DaWanda user data:</h2>
          <pre><? var_dump($user); ?></pre>
        <? } ?>

        <? if($orders && (count($orders) > 0)) { ?>
          <h2>Your DaWanda orders:</h2>
          <h3>Statistics</h3>
          <div class="statistics">
            All orders: <?= count($orders) ?><br>
            Todays orders (<?= @date("Y/m/d", time()) ?>): <?= count($date_orders) ?>
          </div>

          <h3>All orders</h3>
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <th>Order ID</th>
              <th>Buyer</th>
              <th>Date</th>
              <th>Total price</th>
              <th>Status</th>
            </tr>
            <?php $i=0; foreach ($orders as $order) { ?>
              <tr class="<?= $i++%2==0 ? "even" : "odd" ?>">
                <td><?= $order->id ?></td>
                <td>
                  <?= $order->buyer->username ?><br>
                  (<?= $order->buyer->firstname ?> <?= $order->buyer->lastname ?>)<br>
                  Payment method: <?= $order->payment_method->id ?>
                </td>
                <td><?= $order->created_at ?></td>
                <td>
                  <?= $order->cents / 100.0 ?> <?= $order->currency ?><br>
                  <? if($order->cents_shipping) { ?>
                    + <?= $order->cents_shipping / 100.0 ?> <?= $order->currency ?><br>
                  <? } ?>
                  = <?= $order->total_cents / 100.0 ?> <?= $order->currency ?><br>
                </td>
                <td>
                  Created at: <?= $order->created_at ?><br>
                  Updated at: <?= $order->updated_at ?><br>
                  Confirmed at: <?= $order->confirmed_at ?><br>
                  Marked as paid at: <?= $order->marked_as_paid_at ?><br>
                  Sent at: <?= $order->sent_at ?><br>
                  is cancelled?: <?= $order->is_cancelled ? "true" : "false" ?>
                </td>
              </tr>
              <tr>
                <td></td>
                <td colspan="10" style="text-align: left">
                  <? if($order->seller_comment) { ?>
                    <strong>Seller notice:</strong>
                    <?= $order->seller_comment ?>
                    <br><br>
                  <? } ?>
                  <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <th>Article ID</th>
                      <th>Image</th>
                      <th>Title</th>
                      <th>Amount</th>
                      <th>Single item price</th>
                      <th>Total price</th>
                    </tr>
                    <? foreach ($order->order_items as $item) { ?>
                      <tr>
                        <td><?= $item->product->id ?></td>
                        <td><img src="http://dawanda.com/<?= $item->image_url ?>"></td>
                        <td><?= $item->product->name ?></td>
                        <td><?= $item->quantity ?></td>
                        <td><?= $item->cents / 100.0 ?> <?= $order->currency ?></td>
                        <td><?= $item->quantity * ($item->cents / 100.0) ?> <?= $order->currency ?></td>
                      </tr>
                    <? } ?>
                  </table>
                  <? if($order->comments) { ?>
                    <br><strong>Comments:</strong><br>
                    <? foreach($order->comments as $comment) { ?>
                      <?= $comment->text ?> (<?= $comment->user->name ?>)<br>
                    <? } ?>
                  <? } ?>
                </td>
              </tr>
            <? } ?>
          </table>
        <? } ?>
      </div>
    </div>
  </body>
</html>
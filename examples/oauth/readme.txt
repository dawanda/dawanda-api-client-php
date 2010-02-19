To get this demonstration run, you have to prepare some things:
  1. Login to DaWanda: https://de.dawanda.com/account/login
  2. Register the demo app: http://de.dawanda.com/oauth_clients/new
    2.1 Name: PHP Demo App
    2.2-2.4: <Local project URL> (e.g. http://localhost/~username/dawanda-api/examples/php/oauth)
  3. Insert the consumer key and the consumer secret to config.php.
  4. Set permissions of token.csv to 777.
  5. Open the demo app in the browser.
  6. You will be redirected to DaWanda, where the logged in user is able to allow the app to receive it's data. Check the authorization checkbox and hit the save button.
  7. When finished the authorization, you will be redirected to your local app, where you can see the user's data and the order data.
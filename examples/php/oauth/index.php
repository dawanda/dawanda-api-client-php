<?
  require("config.php");
  require("lib/OAuth.php");
  include("../../../lib/php/v1/src/dawanda.php");
  
  $dawanda_oauth = new DaWandaOAuth($key, $secret, "de");
  $dawanda_oauth->requestAuthorization("callback.php");
?>
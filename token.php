<?php

$consumerKey     = 'NMlTaMuklqKSDVCtamy4LHNeRZsptOXqXnrRaXxqSyZvSslKB7';
$consumerSecret  = 'Mgo7JAI2yfdcHCBzZLG71o6HbdmX2R4wYVc3Meg7BsOzlqPN3m';

include("vendor/autoload.php");

session_start();

  $client = new Tumblr\API\Client($consumerKey, $consumerSecret);
  $requestHandler = $client->getRequestHandler();
  $requestHandler->setBaseUrl('https://www.tumblr.com/');

  if (!$_GET['oauth_verifier']) {
      $resp = $requestHandler->request('POST', 'oauth/request_token', array());
      $out = $result = $resp->body;
      $data = array();
      parse_str($out, $data);

      echo '<a style="font-size:20px;" href="https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token'].'">Click here for tumblr authorization</a><br />';
      $_SESSION['t']=$data['oauth_token'];
      $_SESSION['s']=$data['oauth_token_secret'];
  } else {
      $verifier = $_GET['oauth_verifier'];
      $client->setToken($_SESSION['t'], $_SESSION['s']);

      $resp = $requestHandler->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
      $out = $result = $resp->body;
      $data = array();
      parse_str($out, $data);

      $token = $data['oauth_token'];
      $secret = $data['oauth_token_secret'];

      $client = new Tumblr\API\Client($consumerKey, $consumerSecret, $token, $secret);
      $info = $client->getUserInfo();
      $username=$info->user->name;

      if (!empty($info->user->name) AND !empty($token) AND !empty($secret)) {
           echo "<h2>Your token info for posting</h2>\r\n";
           echo "<table border=0 cellpadding=3>
      <tr><td>username: </td><td><input type=\"text\" size=\"30\" value=\"$username\" /></td></tr>
      <tr><td>token: </td><td><input type=\"text\" size=\"60\" value=\"$token\" /></td></tr>
      <tr><td>secret: </td><td><input type=\"text\" size=\"60\" value=\"$secret\" /></td></tr>
           </table>
           ";
      } else {
        echo "<h2>** ERROR with request</h2>\r\n";
        echo "<pre>"; print_r($resp); echo "</pre>";
      }

}


?>
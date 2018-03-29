<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html>

<head>
  <title>tumblr poster</title>
 <meta charset="utf-8">

<link rel="stylesheet" type="text/css" href="style.css" />


</head>

<body>

<div id="main">

 <h1 style="margin-top: 0px; padding-top: 0px;">Tumblr post</h1>

 <form action="" method="post" name="myform">
   <fieldset>
    <legend>Account/Profile</legend>
    <table border="0" cellpadding="2"><tr><td><label>username</label></td><td><input type="text" class="inp1" name="username" style="width: 184px;" required='required' value="" /></td><td colspan="2"><a href="token.php" title="get token" class="link36" target="_blank">Get token</a></td></tr><tr><td><label>token</label></td><td><input type="text" class="inp1" name="token" style="width: 184px;" value="" /></td><td><label>secret</label></td><td><input type="text" class="inp1" name="secret" style="width: 187px;" value="" /></td></tr></table>
  </fieldset>

   <fieldset>
    <legend>Post data</legend>
    <table border="0" cellpadding="2">
        <tr><td><label>Blogname</label><input type="text" class="inp2" name="blogname" style="width: 200px; float:left;" value="" /> *leave empty if account have only one blog</td></tr>
        <tr><td><label>Title</label><input type="text" class="inp2" name="title" style="width: 690px;" required='required' value="" /></td></tr>
        <tr><td><label>Tags <i>(*separated by comma , ex: tag1,tag2,tag3)</i></label><input type="text" class="inp2" name="tags" style="width: 400px;" value="" /></td></tr>
        <tr><td><label>Content body <i>(*html)</i></label><textarea rows="8" cols="45" name="content" class="txt1" required='required'></textarea></td></tr>
        <tr><td><label style="display: inline;">post status </label> <select name="post_status"><option value="draft">draft</option><option value="published" selected="selected">published</option></select>
        </td></tr>

        <tr><td align="right"><input type="submit" name="sbmSave" class="sbm1" value="SAVE" /></td></tr>

    </table>
  </fieldset>


 </form>

<?php

$consumerKey     = 'NMlTaMuklqKSDVCtamy4LHNeRZsptOXqXnrRaXxqSyZvSslKB7';
$consumerSecret  = 'Mgo7JAI2yfdcHCBzZLG71o6HbdmX2R4wYVc3Meg7BsOzlqPN3m';
include("vendor/autoload.php");


 if (!empty($_POST['sbmSave'])) {

    echo "<hr />";
    $username        = $_POST['username'] ;
    $token           = $_POST['token'] ;
    $secret          = $_POST['secret'] ;
    $tags            = $_POST['tags'] ;
    $title           = $_POST['title'] ;
    $content         = $_POST['content'] ;
    $post_status     = $_POST['post_status'] ;
    $blogname        = $_POST['blogname'] ;



    if (empty($username) OR  empty($title) OR empty($content )) {
        die("<h3 class='err'>error. field empty. username/title/content</h3>");
    }

    if (empty($token) or empty($secret)) {
        die("error with token/secret. You must get token first");
    }

      $data=array();
      $data['type'] = 'text'; //text, photo, quote, link, chat, audio, video
      $data['state'] = $post_status;    //published, draft, queue, private
      $data['tags'] = $tags;     //Comma-separated tags for this post
      $data['native_inline_images'] = 'true'; //Convert any external image URLs to Tumblr image URLs

      //text post
      $data['title'] = $title;
      $data['body'] = $content;
      //text post

    $client = new Tumblr\API\Client($consumerKey, $consumerSecret);
    $client->setToken($token, $secret);

    try {
        $userinfo=$client->getUserInfo();
    } catch(Exception $e) {
        $msg=$e->getMessage();
        echo $msg;
        die();
    }


    if (empty($blogname)) {
        $userinfo=$client->getUserInfo();
        foreach ($userinfo->user->blogs as $blog) {
    	    $blogname = $blog->name ;
        }
    }


    try {
        $response=$client->createPost($blogname, $data);

        $postid=$response->id;
        if (!empty($postid)) {
            $permalink="https://$blogname.tumblr.com/post/$postid";
            echo "<h2>SAVED: <a href=\"$permalink\" target=\"_blank\">$permalink</a></h2>";

        } else {
          echo "*** ERROR *** <br />";

        echo "<pre>"; print_r($response); echo "</pre>";die();

        }
    } catch(Exception $e) {
        $msg=$e->getMessage();
        echo $msg;
        die();
    }


 }

?>


</div><!-- /main -->

<br /><br /><br /><br />
</body>

</html>
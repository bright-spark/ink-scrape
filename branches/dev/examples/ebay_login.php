<?php

error_reporting(E_ALL | E_STRICT);

include("InkScrape.php");

function login_ebay($p_user, $p_pwd, $p_cookie) {
  $scraper = new InkScrape();
  $scraper->curler->setCookieStorePath($p_cookie);
  $scraper->curler->clearCookieStore();

  //useful if you want to see page output
  $scraper->curler->fileTrail = true;
  $scraper->curler->fileTrailNamer = create_function('$str', 'return "curler/".md5($str).".html";');

  $scraper->curler->options[CURLOPT_SSL_VERIFYPEER]=false;
  $scraper->curler->options[CURLOPT_FOLLOWLOCATION]=true;
  $scraper->curler->options[CURLOPT_AUTOREFERER]=true;

  //go to the main page, see if we're signed in
  $scraper->sendGetToUrl("http://www.ebay.com/");
  if($scraper->checkFrontBoundaries(array('<span class="greeting"><!-- BEGIN: GREETING:SIGNEDOUT -->Welcome!'))!==false) {
    echo("do sign in\n");
  } elseif($scraper->checkFrontBoundaries(array('<!-- BEGIN: GREETING:SIGNEDIN -->Hi, '))!==false) {
    echo("signed in already\n");
    return;
  } else {
    die ("unabled to determine session status");
  }

  //go to the login page
  echo("accessing login page...");
  $scraper->sendGetToUrl("https://signin.ebay.com/ws/eBayISAPI.dll?SignIn");
  echo("done\n");

  //search login page for login form
  $form_tag = $scraper->boundedTextAsForm(array(
    '<div class="signinyukon-rcp">',
    '<div class="signinyukon-n">',
    '<div class="signinyukon-e">',
    '<div class="signinyukon-w">',
    '<div class="signinyukon-mid">'), array(
    '<div class="signinyukon-s">',
    '<div class="signinyukon-e">',
    '<div class="signinyukon-w">'));

  $attrs = $form_tag->attributes();

  $link = $attrs["action"];
  $options = $form_tag->inputs();
  $options["userid"] = $p_user;
  $options["pass"] = $p_pwd;

  //send login data
  echo("sending login data...");
  $scraper->sendPostToUrlFields($link, $options);
  $scraper->treatDataAsRefreshPage();
  echo("done\n");

  //at this step, you should be at myEbay.
}

?>
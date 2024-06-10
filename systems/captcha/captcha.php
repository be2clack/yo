<?php

session_start();

$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';

$length = 6;

$code = substr(str_shuffle($chars), 0, $length);

if(isset($_GET["name"])){

  $_SESSION['captcha'] = [];

  if($_GET["name"] == "feedback" || $_GET["name"] == "auth" || $_GET["name"] == "forgot"){
    $_SESSION['captcha'][$_GET["name"]] = $code;
  }

  $image = imagecreatefrompng(__DIR__ . '/bg.png');

  $size = 42;

  $color = imagecolorallocate($image, 0, 48, 143);

  $font = __DIR__ . '/cour.ttf';

  $angle = rand(-10, 10);

  $x = 56;
  $y = 64;

  imagefttext($image, $size, $angle, $x, $y, $color, $font, $code);

  header('Cache-Control: no-store, must-revalidate');
  header('Expires: 0');
  header('Content-Type: image/png');

  imagepng($image);

  imagedestroy($image);

}

?> 
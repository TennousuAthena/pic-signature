<?php
if(!extension_loaded("imagick")){
    exit("Error: Imagick is required");
}

header("Content-type: image/JPEG");
use UAParser\Parser;
require_once 'function.php';
require_once 'vendor/autoload.php';
require_once 'gaClass.php';
$CONF = require_once 'config.php';


$img =new Imagick();
$img->newImage(500,200,'white','png');

$draw=new ImagickDraw();
$draw->setFillColor(new ImagickPixel('black'));
$draw->setFontSize('25');
$draw->setFont("./assets/fonts/zpix.ttf");
$draw->setTextAlignment(Imagick::ALIGN_RIGHT);
$draw->setTextEncoding("utf-8");
$draw->annotation(200,100,'我是散兵');
$img->drawImage($draw);
if (function_exists("fastcgi_finish_request")) {
    fastcgi_finish_request();
}

if($CONF['GA']['tid'] != "") {
    $ga = new GA($CONF['GA']['tid']);
}

echo $img;


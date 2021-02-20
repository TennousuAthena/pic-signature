<?php
header("Content-type: image/JPEG");

$img =new Imagick();
$img->newImage(500,200,'white','png');

$draw=new ImagickDraw();
$draw->setFillColor(new ImagickPixel('black'));
$draw->setFontSize('25');
$draw->setFont(SYS_PATH."/assets/fonts/zpix.ttf");
$draw->setTextAlignment(Imagick::ALIGN_RIGHT);
$draw->setTextEncoding("utf-8");
$draw->annotation(200,100,'我是散兵');
$img->drawImage($draw);
if (function_exists("fastcgi_finish_request")) {
    fastcgi_finish_request();
}

return $img;
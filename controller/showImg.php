<?php
header("Content-type: image/JPEG");

$testXML = file_get_contents(SYS_PATH."/assets/full.xml");
$I = new pics\mlc\Interpreter($testXML);


$img =new Imagick();
$img->newImage(500,200,'white','jpeg');


$uInfo = viewerInfo();

$fontPath = SYS_PATH."/assets/fonts/"."Zpix.ttf";

$draw=new ImagickDraw();
$draw->setFillColor(new ImagickPixel('black'));
$draw->setFontSize('25');
$draw->setFont($fontPath);
$draw->setTextAlignment(Imagick::ALIGN_RIGHT);
$draw->setTextEncoding("utf-8");
$bbox = imageftbbox(25, 0, $fontPath, '154辽宁省');
$width_of_text = $bbox[2] - $bbox[0];
$draw->annotation($width_of_text-10,40,$width_of_text.$uInfo['site']['country']);
$img->drawImage($draw);
if (function_exists("fastcgi_finish_request")) {
    fastcgi_finish_request();
}

return $img;
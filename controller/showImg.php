<?php

use UAParser\Parser;

$testXML = file_get_contents(SYS_PATH."/assets/full.xml");
$I = new pics\mlc\Interpreter($testXML);

$ua = Parser::create()->parse($_SERVER['HTTP_USER_AGENT']);

$hitokoto = jsonUrl("https://v1.hitokoto.cn/?max_length=20&c=a&c=b&c=h&c=j");

$uInfo = viewerInfo();
$I->drawText(":)", 0, 50, 65, "zpix.ttf", "#9565b1");
$I->drawText("WOW! It works! From ".$uInfo['ip']."\n 你好啊！来自".
    $uInfo['site']['country'].$uInfo['site']['region']."的朋友~\n您正在"
    .$ua->os->toString()."上使用". $ua->ua->family  ."浏览器\n当前天气".$uInfo['city']['weather']
    ."，气温".$uInfo['city']['temperature']."°C\n「" . $hitokoto['hitokoto'] . "」"
    , 250, 85, 22, "XiaolaiSC-Regular.ttf", "pink", 2);
$I->drawText("Powered by pic-signature", 500, 220, 14, "975MaruSC-Regular.ttf", "#349e69", 3);

$I->drawText("Processed in " . round(time_float() - TIME_START, 4) . "s with".((memory_get_usage()-MEM_START)/1000000)."MB used ", 500, 20, 10, "975MaruSC-Regular.ttf", "grey", 3);

$I->show();
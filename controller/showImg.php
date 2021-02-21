<?php

$testXML = file_get_contents(SYS_PATH."/assets/full.xml");
$I = new pics\mlc\Interpreter($testXML);

$uInfo = viewerInfo();
$I->drawText(":)", 0, 50, 65, "zpix.ttf", "#9565b1");
$I->drawText("WOW! It works! From ".$uInfo['ip']."\n 你好啊！来自".
    $uInfo['site']['country'].$uInfo['site']['region']."的朋友~\n当前天气".$uInfo['city']['weather']
    ."，气温".$uInfo['city']['temperature']."°C"
    , 0, 85, 22, "XiaolaiSC-Regular.ttf", "pink");
$I->drawText("Powered by pic-signature", 500, 190, 14, "975MaruSC-Regular.ttf", "#349e69", 3);

$I->show();
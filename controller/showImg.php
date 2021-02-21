<?php

$testXML = file_get_contents(SYS_PATH."/assets/full.xml");
$I = new pics\mlc\Interpreter($testXML);

$uInfo = viewerInfo();
$I->drawText(":)", 0, 50, 65, "zpix.ttf");
$I->drawText("WOW! It works!", 0, 85, 22, "XiaolaiSC-Regular.ttf", "green");

$I->show();
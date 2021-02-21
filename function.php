<?php

use pics\mlc\log;

set_exception_handler('picException');

function jsonUrl($url): array
{
    $headerArray = array("Content-type:application/json;", "Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    return $output;
}

function getIp(): string
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = $_SERVER['REMOTE_ADDR'];
    else $ip = "0.1.2.3";
    return ($ip);
}

function viewerInfo(): array
{
    $uip = getIp();
    if ($uip == "::1"):
        return jsonUrl("https://api.xhboke.com/ip/v1.php");
    else:
        return jsonUrl("https://api.xhboke.com/ip/v1.php?ip=".$uip);
    endif;
}

function picException($ex){
    $errMsg = "异常 : " . $ex->getMessage() . " on Line ".
    $ex->getLine() . "\n in ". $ex->getFile();
    log::newLog($errMsg, "Error");

    $errorXML = file_get_contents(SYS_PATH."/assets/error.xml");
    try {
        $I = new pics\mlc\Interpreter($errorXML);
        $I->drawText(":(", 0, 50, 65, "zpix.ttf", "red");
        $I->drawText($errMsg, 0, 85, 14, "HanaMinA.ttf", "red");
    } catch (Exception $e) {
        exit("Fatal Error!!");
    }
    $I->show();
}
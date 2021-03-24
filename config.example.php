<?php
ini_set('date.timezone','Asia/Shanghai'); //设置系统时区
define("SYS_PATH", realpath("./")); //请勿更改此选项
error_reporting(E_ALL ^ E_NOTICE); //设置错误报告
return [
    "GA"=>[
        "PROXY_API_ADDR" => "", //GA镜像URL，不知道是什么请留空
        "tid" => "UA-114514-1",
    ]
];
<?php
if(!extension_loaded("imagick")){
    exit("Error: Imagick is required");
}

use UAParser\Parser;
require_once 'function.php';
require_once 'vendor/autoload.php';
require_once 'class/manually_load.php';
$CONF = require_once 'config.php';
$router = new \Bramus\Router\Router();

$router->get('/i/(\w+)', function($pid) {
    global $CONF;
    if($CONF['GA']['tid'] != "") {
        $ga = new pics\mlc\GA($CONF['GA']['tid']);
    }
    include "controller/showImg.php";
});

$router->get('/docs/(\w+)', function($file) {
    $file = "doc/".$file.".html";
    if(file_exists($file)){
        echo file_get_contents($file);
    }else{
        echo file_get_contents("doc/404.html");
    }
});

$router->set404(function() {
    http_response_code(404);
    echo file_get_contents("doc/404.html");
});

$router->run();


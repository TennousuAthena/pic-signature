<?php
namespace pics\mlc;

/**
 * Class GA
 * @package pics\mlc
 */
class GA{
    /**
     * @var string Google 统计ID
     */
    public $tid = "";
    /**
     * @var string UUID
     */
    private $uuid;

    public function __construct(string $tid, array $exQuery = []){
        if($tid){
            $this->tid = $tid;
        }
        if (!isset($_COOKIE["uuid"])) {
            $this->uuid=$this->create_uuid();
            setcookie("uuid", $this->uuid, time()+368400000);
        }else{
            $this->uuid=$_COOKIE["uuid"];
        }
        $this->send($exQuery);
    }

    private function create_uuid(): string
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('QC-%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function send($exQuery = []){
        $ref = @$_SERVER["HTTP_REFERER"];
        $url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ul = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $ul = strtolower(substr($ul, 0, strpos($ul, ",")));
        $uip = getIp();
        if ($uip === "::1"){
            $uip = viewerInfo()['ip'];
        }
        $query = [
            "v"  => 1,
            "a"  => time(),
            "t"  => "pageview",
            "dl" => $ref? $ref : $url,
            "cid"=> $this->uuid,
            "tid"=> $this->tid,
            "uip"=> $uip,
            "ua" => @$_SERVER['HTTP_USER_AGENT'],
            "ul" => $ul

        ];
        if ($exQuery !== []){
            $query = array_merge($query, $exQuery);
        }
        $url='https://www.google-analytics.com/collect?'.http_build_query($query);
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

}

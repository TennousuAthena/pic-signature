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
    public string $tid = "";
    /**
     * @var string UUID
     */
    private $uuid;

    /**
     * @var string 后端请求地址
     */
    public string $ga_url = "https://www.google-analytics.com/";

    /**
     * GA constructor.
     * @param string $tid
     * @param array $exQuery
     * @param string $ga
     */
    public function __construct(string $tid, array $exQuery = [], string $ga=""){
        if($tid){
            $this->tid = $tid;
        }
        if (isset($_COOKIE["uuid"])) {
            $this->uuid=$_COOKIE["uuid"];
        }
        $this->ga_url = !$ga? $this->ga_url : $ga;
        $this->send($exQuery);
    }

    /**
     * 设置Cookie
     */
    public static function set_cookie(){
        if (!isset($_COOKIE["uuid"])) {
            setcookie("uuid", self::create_uuid(), time() + 368400000);
        }
    }

    /**
     * 生成UUID
     * @return string
     */
    private static function create_uuid(): string
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * 发送GA
     * @param array $exQuery
     * @return mixed
     */
    public function send($exQuery = []){
        //DOC: https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters
        $ref = @$_SERVER["HTTP_REFERER"];
        $url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ul = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $ul = strtolower(substr($ul, 0, strpos($ul, ",")));
        $uip = getIp();
        if ($uip === "::1"){
            $uip = viewerInfo()['ip'];
        }
        $dl = $ref? $ref : $url;
        $query = [
            "v"  => 1,
            "a"  => time(),
            "t"  => "pageview",
            "dl" => $dl,
            "cid"=> $this->uuid,
            "tid"=> $this->tid,
            "uip"=> $uip,
            "ua" => @$_SERVER['HTTP_USER_AGENT'],
            "ul" => $ul,
            "dr" => parse_url($dl)['host']
        ];
        $headers[] = @$_SERVER['HTTP_USER_AGENT'];
        if ($exQuery !== []){
            $query = array_merge($query, $exQuery);
        }
        $url=$this->ga_url . '/collect?'.http_build_query($query);
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_exec($ch);
        $rcode = curl_getinfo($ch)['http_code'];
        curl_close($ch);
        return $rcode;
    }

}

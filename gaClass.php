<?php

class GA{
    /**
     * @var string Google 统计ID
     */
    public $tid = "";
    /**
     * @var string UUID
     */
    private $uuid = "";

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
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return $uuid;
    }

    public function send($exQuery = []){
        $ref = @$_SERVER["HTTP_REFERER"];
        $url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ul = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $ul = strtolower(substr($ul, 0, strpos($ul, ",")));
        $query = [
            "v"  => 1,
            "a"  => time(),
            "t"  => "pageview",
            "dl" => $ref? $ref : $url,
            "cid"=> $this->uuid,
            "tid"=> $this->tid,
            "uip"=> getIp(),
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




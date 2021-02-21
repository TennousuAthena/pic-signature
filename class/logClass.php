<?php
namespace pics\mlc;

class log{

    public static function newLog($content, $level="Log"){
        file_put_contents(self::logFile(), "[".date("Y-m-d H:i:s")."][$level]".$content."\n", FILE_APPEND | LOCK_EX);
    }

    /**
     * @return string 日志文件路径
     */
    public static function logFile(): string
    {
        $logDir = "logs/";
        $Dir = $logDir.date("Y")."/".date("m")."/";
        is_dir($Dir) or mkdir($Dir, 0755, true);
        return $Dir.date("d").".log";
    }
}
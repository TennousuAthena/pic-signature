<?php
namespace pics\mlc;
use SQLite3;

class DB extends SQLite3
{
    /**
     * DB constructor.
     */
    function __construct()
    {
        $db = 'assets/data/pic.db';
        $this->open($db);
    }

    /**
     * @param $hid
     * @param $content
     * @param $from
     * @return int
     * @throws \Exception
     */
    public function insert($hid, $content, $from): int
    {
        try {
            $sql = "INSERT INTO `hitokoto` (`hid`, `content`, `from`)
            VALUES ($hid, '$content', '$from')";
            $db = new DB();
            $db->exec($sql);
        }catch (Exception $e){
            throw new Exception("数据写入错误");
        }
        if(!$db){
            throw new Exception($this->db->lastErrorMsg());
        }else{
            return $db->lastInsertRowID();
        }
    }

    /**
     * @return array|false
     */
    public function get(){
        try {
            $db = new DB();
            $sql = "SELECT * FROM `hitokoto` ORDER BY RANDOM() limit 1";
            $result = $db->query($sql);
        }catch (Exception $e){
            throw new Exception("数据写入错误");
        }
        return $result->fetchArray(SQLITE3_ASSOC);
    }
}

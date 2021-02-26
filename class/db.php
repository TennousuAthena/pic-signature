<?php
namespace pics\mlc;
use SQLite3;

class DB extends SQLite3{
    public function __construct($db)
    {
        $this->open($db);
    }
}
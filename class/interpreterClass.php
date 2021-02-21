<?php
namespace pics\mlc;
use Exception;
use Imagick;
use SimpleXMLElement;

/**
 * Class Interpreter JSON->解释器->图片
 * @package pics\mlc
 */
class Interpreter{

    /**
     * @var array
     */
    public $arr = [];

    /**
     * Interpreter constructor.
     * @param string $XML
     */
    public function __construct($XML = ""){
        $this->arr = new SimpleXMLElement($XML);
        try {
            $this->check_input();
        } catch (Exception $e) {
            log::newLog($e, "Error");
            exit($e);
        }
    }

    /**
     * @throws Exception
     * @return bool Flag
     */
    private function check_input(){
        $flag = true;
        $basicInf = $this->arr->basicInfo;
        @$basicInf->bgColor or $basicInf->bgColor = "white";
        @$basicInf->format or $basicInf->format = "jpg";
        @$basicInf->width !=0 or $flag = 0;
        @$basicInf->height !=0 or $flag = 0;

        $this->arr['basicInfo'] = $basicInf;
        if(!$flag){
            throw new Exception("Interpreter Error");
        }
        return $flag;
    }
}
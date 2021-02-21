<?php
namespace pics\mlc;
use Exception;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use SimpleXMLElement;

/**
 * Class Interpreter JSON->解释器->图片
 * @package pics\mlc
 */
class Interpreter{

    /**
     * @var object
     */
    public $arr;

    /**
     * @var object
     */
    private $img;

    /**
     * Interpreter constructor.
     * @param string $XML
     * @throws Exception
     */
    public function __construct($XML = ""){
        $this->arr = new SimpleXMLElement($XML);
        $this->checkInput();
        $basicInf = $this->arr->basicInfo;
        $this->img =new Imagick();
        $this->img->newImage((int)$basicInf->width,(int)$basicInf->height,(string)$basicInf->bgColor,$basicInf->format);
        return null;
    }

    /**
     * 检查配置
     * @throws Exception
     * @return bool Flag
     */
    private function checkInput(){
        $flag = true;
        $basicInf = $this->arr->basicInfo;
        @$basicInf->bgColor or $basicInf->bgColor = "white";
        @$basicInf->format or $basicInf->format = "JPEG";
        if (!(@$basicInf->width != 0 && @$basicInf->height != 0)) {
            $flag = 0;
        }

        $this->arr['basicInfo'] = $basicInf;
        if(!$flag){
            throw new Exception("Interpreter Error");
        }
        return $flag;
    }

    /**
     * 绘制文本
     * @param $content string 文本内容
     * @param $x int X坐标
     * @param $y int Y坐标
     * @param int $size 字体大小
     * @param string $font 字体文件名
     * @param string $color 颜色名
     * @param int $ali 对齐方式
     * @return null
     * @throws Exception
     */
    public function drawText(string $content, int $x, int $y, $size=18, $font="sarasa-fixed-sc-regular.ttf", $color="black", $ali=1){
        try {
            $draw=new ImagickDraw();
            $draw->setFillColor(new ImagickPixel($color));
            $draw->setFontSize($size);
            $ExFont = SYS_PATH."/assets/fonts/".$font;
            $draw->setFont($ExFont);
            $draw->setTextAlignment($ali);
            $draw->setTextEncoding("utf-8");
            $this->calculateText($content, $size, $ExFont);
            $draw->annotation($x, $y, $content);
            $this->img->drawImage($draw);
        }catch (Exception $e){
            throw new Exception("Failed to draw text");
        }
        return null;
    }

    /**
     * 计算文字宽高
     * @param $content string
     * @param $size int
     * @param $ExFont string
     * @return int[]
     */
    public static function calculateText(string $content, int $size , string $ExFont): array
    {
        $bbox = imageftbbox($size, 0, $ExFont, $content);
        $textWidth = $bbox[4] - $bbox[6];
        $textHeight = $bbox[3] - $bbox[5];
        return [(int)$textWidth, (int)$textHeight];
    }

    /**
     * 显示图片内容
     * @return null
     */
    public function show(){
        $format = $this->arr->basicInfo->format;
        header("Content-type: image/$format");
        echo $this->img;
        if (function_exists("fastcgi_finish_request")) {
            fastcgi_finish_request();
        }
        return null;
    }

    /**
     * 获取本地字体列表
     * @return array
     */
    private function listFonts(): array
    {
        $files = [];
        $fontDir = SYS_PATH."/assets/fonts/";

        $handler = opendir($fontDir);
        while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
            if ($filename != "." && $filename != ".." && $filename != ".gitattributes") {
                $files[] = $filename ;
            }
        }
        closedir($handler);
        return $files;
    }
}
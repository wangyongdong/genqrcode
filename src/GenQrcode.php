<?php
namespace GenQrcode;

/**
 * Class GenQrcode
 * @package GenQrcode
 * Created on 2018/5/4 10:24
 * Created by wangyongdong
 */
class GenQrcode {

    /**
     * @var bool
     */
    private static $init = false;

    /**
     * 二维码图片存储路径
     * @var string
     */
    private static $sPath;

    /**
     * logo存放位置
     * @var string
     */
    private static $logoPath;

    /**
     * 二维码图片访问地址
     * @var string
     */
    private static $url;

    /**
     * 二维码生成参数
     * @var array
     */
    private static $_config = [
        'level' => 'H',     //容错级别
        'size' => '4',      //生成图片大小
        'margin' => '1',    //周围边框空白区域间距值
    ];

    /**
     * GenQrcode constructor.
     * 私有化构造函数，防止外界调用构造新的对象
     */
    private function __construct() {}

    private static function init() {
        if(!self::$init instanceof self) {
            self::$init = new self();
        }

        self::setConfig();
        self::setPath();
    }

    /**
     * 设置图片信息
     * @param array $config
     */
    public static function setConfig($config = array()) {
        if(!self::$init instanceof self) {
            self::$init = new self();
        }

        //设置图像参数
        if(!empty($config)) {
            array_walk($config, function($row, $key) {
                if(isset(self::$_config[$key])) {
                    self::$_config[$key] = $row;
                }
            });
        }
    }

    /**
     * 设置图片路径及url信息
     * @param array $aPath
     */
    public static function setPath($aPath = array()) {
        if(!self::$init instanceof self) {
            self::$init = new self();
        }

        //设置图片路径
        if(!empty($aPath['path'])) {
            self::$sPath = $aPath['path'];
        }
        //logo路径
        if(!empty($aPath['logoPath'])) {
            self::$logoPath = $aPath['logoPath'];
        }
        //图片访问地址
        if(!empty($aPath['url'])) {
            self::$url = $aPath['url'];
        }
    }

    /**
     * 查看路径
     * @return array
     */
    private static function checkPath() {
        if(empty(self::$sPath)) {
            return self::setErrors('path is empty');
        }

        if(empty(self::$logoPath)) {
            return self::setErrors('logoPath is empty');
        }

        if(!self::validateFile(self::$logoPath)) {
            return self::setErrors('logoPath is not exists');
        }

        if(empty(self::$url)) {
            return self::setErrors('url is empty');
        }
    }

    /**
     * @param $sInput url text
     * @param bool $bLogo
     * @return array
     */
    public static function create($sInput, $bLogo = false) {
        if(empty($sInput)) {
            return self::setErrors('二维码内容为空');
        }

        //set init
        self::init();

        // check path
        $error = self::checkPath();
        if(!empty($error)) {
            return $error;
        }

        list($fileName, $fileUrl) = self::getFileName(self::$sPath, $sInput);
        if(!self::validateFile($fileName)) {
            \QRcode::png($sInput, $fileName, self::$_config['level'], self::$_config['size'], self::$_config['margin']);
        }

        //判断是否添加logo
        if ($bLogo !== FALSE && !empty(self::$logoPath)) {
            self::gdQrimg($fileName, self::$logoPath);
        }

        if(!self::validateFile($fileName)) {
            return self::setErrors('生成失败');
        }

        return self::setSuccess($fileUrl);
    }

    /**
     * @param $fileName
     * @param $logoPath
     */
    public static function gdQrimg($fileName, $logoPath) {
        if(file_exists($fileName)) {
            $reOr = imagecreatefromstring(file_get_contents($fileName));
            $reLogo = imagecreatefromstring(file_get_contents($logoPath));
            $QR_width = imagesx($reOr);			//二维码图片宽度
            $QR_height = imagesy($reOr);		//二维码图片高度
            $logo_width = imagesx($reLogo);		//logo图片宽度
            $logo_height = imagesy($reLogo);	//logo图片高度
            $logo_qr_width = $QR_width / 4;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($reOr, $reLogo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            //输出图片
            imagepng($reOr, $fileName);
        }
    }

    /**
     * @param $sPath
     * @param $sInput
     * @return array
     */
    public static function getFileName($sPath, $sInput) {
        $file = md5($sInput.time()) . '.png';
        $fileName = $sPath . '/' . $file;

        $fileUrl = self::$url . $file;

        return array($fileName, $fileUrl);
    }

    /**
     * @param $sFile
     * @return bool
     */
    public static function validateFile($sFile) {
        $sDir = dirname($sFile);
        if (!file_exists($sDir)) {
            umask(0000);
            mkdir($sDir, 0777, TRUE);
        }

        if (!file_exists($sFile)) {
            return false;
        } else {
            return true;
        }
    }

    private static function setErrors($msg) {
        return array(
            'msg' => 'error',
            'data' =>$msg,
        );
        die;
    }

    private static function setSuccess($data) {
        return array(
            'msg' => 'success',
            'data' => $data,
        );
        die;
    }
}
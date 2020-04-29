<?php
require_once '../init.php';
use GenQrcode\GenQrcode;
GenQrcode::setConfig(array('level'=>'6', 'size'=>'7'));
GenQrcode::setPath(array(
    'path' => './files/qrcode',
    'logoPath' => './files/logo.jpg',
    'url' => 'http://php.modules/qrcode/example/files/qrcode/',
));
$ret = GenQrcode::create('http://www.wangyd.com');
print_r($ret);
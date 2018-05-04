## 基于 qrcode 二维码生成

### 使用方法


```php
use GenQrcode\GenQrcode;
GenQrcode::setConfig(array('level'=>'6', 'size'=>'7'));
GenQrcode::setPath(array(
    'path' => './files/qrcode',
    'logoPath' => './files/logo.jpg',
    'url' => 'http://php.modules/qrcode/example/files/qrcode/',
));
$ret = GenQrcode::create('http://www.wangyd.com');
```


### 参数说明

setConfig 方法：设置图片信息参

`level：默认为L，这个参数可传递的值分别是L(QR_ECLEVEL_L，7%)、M(QR_ECLEVEL_M，15%)、Q(QR_ECLEVEL_Q，25%)、H(QR_ECLEVEL_H，30%)，这个参数控制二维码容错率，不同的参数表示二维码可被覆盖的区域百分比，也就是被覆盖的区域还能识别；`

`size：控制生成图片的大小，默认为4`

`margin：控制生成二维码的空白区域大小`

setPath 方法：设置图片路径及访问地址

`path：二维码图片存储位置`
`logoPath：logo图片存储位置`
`url：图片访问地址（http://xxx.com/qrcode/{filename}）`

### 返回值
`msg：error or success`
`data：error msg，fileurl`
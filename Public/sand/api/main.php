<?php
date_default_timezone_set('Asia/Shanghai');
header('Content-type:text/html;charset=utf-8');
require './Common.php';
/**
 * 多应用
 * 1.统一下单并支付接口（用户被扫）
 * 2.订单查询接口
 * 3.退货申请接口
 * 4.商户自主重发异步通知接口
 * 5.对账单申请接口
 */

function main($method)
{
    $config = include('../config/Basics.php');
    if(empty($_COOKIE['config'])){
        $post = $config['variable'];
    }else{
        $post = unserialize($_COOKIE['config']);
    }


    $datalist = [];
    try{
        $params =  unserialize($_COOKIE['params']) ;
        if (!$params) throw new Exception('未获取入参，保存入参后再进行测试');
    }catch(\Exception $e){
        exit('未获取入参，保存入参后再进行测试');
    }  
    $datalist['body'] = $params;
    // step2: 生成AESKey并使用公钥加密
    $AESKey = aes_generate(16); 
    $pubKey = loadX509Cert($config['publicKeyPath']);
    $priKey = loadPk12Cert($config['privateKeyPath'], $config['privateKeyPwd']);
    $encryptKey = RSAEncryptByPub($AESKey, $pubKey);
    // step3: 使用AESKey加密报文
    $encryptData = AESEncrypt($datalist['body'], $AESKey);
    // step4: 使用私钥签名报文
    $sign = sign($datalist['body'], $priKey);
    // step5: 拼接post数据


    $post['sign'] = $sign ;
    $post['encryptKey'] = $encryptKey ;
    $post['encryptData'] = $encryptData;
    $datalist['head'] = $post;
    $apiMap = include('../helper/Map.php');
    $url = $config['apiUrl'].$apiMap[$method]['url'];
    $ret = http_post_json($url,$post);
    parse_str($ret, $arr);
    try {
        // step7: 使用私钥解密AESKey
        $decryptAESKey = RSADecryptByPri($arr['encryptKey'], $priKey);
        // step8: 使用解密后的AESKey解密报文
        $decryptPlainText = AESDecrypt($arr['encryptData'], $decryptAESKey);
        // step9: 使用公钥验签报文
        verify($decryptPlainText, $arr['sign'], $pubKey);
        return  json_encode([
            'verify'    => $decryptPlainText,
            'jjson'    => json_encode($datalist['body']),
            'vjson'    => json_encode($datalist['head']),
            'json'     =>  json_encode($arr),
            'url'      => 'https://open.sandpay.com.cn/product/detail/43324/43895/',
        ]);
    } catch (\Exception $e) {
        return  json_encode([
            'json'     =>  $e->getMessage(),
            'url'      => 'https://open.sandpay.com.cn/product/detail/43324/43895/',
        ]);
        echo $e->getMessage();
        exit;
    }
}
$method=$_GET['method'];
echo main($method);
// if(empty($method)){
//     throw new Exception('请选择接口');
// }else{
//     $apiMap = include('../helper/Map.php');
//     if($apiMap[$method]['custom']){
//         echo $method($method);
//     }else{
//         echo main($method);
//     }
// }


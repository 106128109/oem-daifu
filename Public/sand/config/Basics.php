<?php
return [
    // 公钥文件
   
    'publicKeyPath'  =>  '../cert/sand.cer',
    // 私钥文件
    'privateKeyPath' =>  '../cert/MID_RSA_PRIVATE_KEY_100211701160001_new.pfx',
    // 私钥证书密码
    'privateKeyPwd'  =>  '123',
        // 接口地址
    'apiUrl'         =>  'https://caspay.sandpay.com.cn/agent-main/openapi',

    'variable' =>[
        //交易码 https://open.sandpay.com.cn/product/detail/43996//
        'transCode'     => 'RTPM',
         // 接入类型  	0-商户接入，默认   1-平台接入
        'accessType'    => '0',
        'merId'         => '100211701160001',
       
    ],
]; 


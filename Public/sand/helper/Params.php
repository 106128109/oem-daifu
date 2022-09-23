<?php
/**
 * 字段参数说明
 * 
 */
return [
    'body' => [
        'agentpay' => [
            'version' =>  [
                'name'      => '版本号',
                'param'     => 'version',
                'type'      => 'String',
                'lengthmax' => '2',
                'require'   => 'true',
                'describe'  => '',
                'value'     => '01'
            ],
            'productId' =>  [
                'name'      => '产品ID',
                'param'     => 'productId',
                'type'      => 'String',
                'lengthmax' => '8',
                'require'   => 'true',
                'describe'  => '付款对私：00000004 付款对公：00000003',
                'value'     => '00000004'
            ],
            'tranTime' =>  [
                'name'      => '交易时间',
                'param'     => 'tranTime',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '交易时间',
                'value'     => date('YmdHis', time())
            ],
            'orderCode' =>  [
                'name'      => '订单号',
                'param'     => 'orderCode',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '订单号',
                'value'     => date('YmdHis', time())+6000
            ],

            'tranAmt' =>  [
                'name'      => '金额',
                'param'     => 'tranAmt',
                'type'      => 'String',
                'lengthmax' => '12',
                'require'   => 'true',
                'describe'  => '金额',
                'value'     => '000000000001'
            ],

            'currencyCode' =>  [
                'name'      => '币种',
                'param'     => 'currencyCode',
                'type'      => 'String',
                'lengthmax' => '3',
                'require'   => 'true',
                'describe'  => '币种',
                'value'     => '156'
            ],
            
            'accAttr' =>  [
                'name'      => '账户属性-0对私(默认)',
                'param'     => 'accAttr',
                'type'      => 'String',
                'lengthmax' => '1',
                'require'   => 'true',
                'describe'  => '0-对私(默认)',
                'value'     => '0'
            ],

            'accType' =>  [
                'name'      => '账号类型',
                'param'     => 'accType',
                'type'      => 'String',
                'lengthmax' => '1',
                'require'   => 'true',
                'describe'  => '2-存折 3-公司账户  4-银行卡 注：accAttr选择对公时，accType选公司账户',
                'value'     => '4'
            ],

            'accNo' =>  [
                'name'      => '收款人账户号',
                'param'     => 'accNo',
                'type'      => 'String',
                'lengthmax' => '50',
                'require'   => 'true',
                'describe'  => '收款人账户号',
                'value'     => ''
            ],

            'accName' =>  [
                'name'      => '收款人账户名',
                'param'     => 'accName',
                'type'      => 'String',
                'lengthmax' => '50',
                'require'   => 'true',
                'describe'  => '收款人账户名',
                'value'     => ''
            ],

            'remark' =>  [
                'name'      => '摘要',
                'param'     => 'remark',
                'type'      => 'String',
                'lengthmax' => '50',
                'require'   => 'true',
                'describe'  => '摘要',
                'value'     => ''
            ],


            'extend' =>  [
                'name'      => '扩展域',
                'param'     => 'extend',
                'type'      => 'String',
                'lengthmax' => '256',
                'require'   => 'false',
                'describe'  => '如上送，在异步通知和查询接口中将返回相同的值',
                'value'     => ''
            ],

        ],
        'queryOrder' => [
            'version' =>  [
                'name'      => '版本号',
                'param'     => 'version',
                'type'      => 'String',
                'lengthmax' => '2',
                'require'   => 'true',
                'describe'  => '',
                'value'     => '01'
            ],
            'productId' =>  [
                'name'      => '产品ID',
                'param'     => 'productId',
                'type'      => 'String',
                'lengthmax' => '8',
                'require'   => 'true',
                'describe'  => '付款对私：00000004 付款对公：00000003',
                'value'     => '00000004'
            ],
            'tranTime' =>  [
                'name'      => '交易时间',
                'param'     => 'tranTime',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '交易时间',
                'value'     => date('YmdHis', time())
            ],
            'orderCode' =>  [
                'name'      => '订单号',
                'param'     => 'orderCode',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '订单号',
                'value'     => date('YmdHis', time())+6000
            ],
            // 'accNo' =>  [
            //     'name'      => '收款人账户号',
            //     'param'     => 'accNo',
            //     'type'      => 'String',
            //     'lengthmax' => '50',
            //     'require'   => 'true',
            //     'describe'  => '收款人账户号',
            //     'value'     => ''
            // ],
            'extend' =>  [
                'name'      => '扩展域',
                'param'     => 'extend',
                'type'      => 'String',
                'lengthmax' => '256',
                'require'   => 'false',
                'describe'  => '如上送，在异步通知和查询接口中将返回相同的值',
                'value'     => ''
            ],

        ],
        'queryAgentpayFee' => [
            'version' =>  [
                'name'      => '版本号',
                'param'     => 'version',
                'type'      => 'String',
                'lengthmax' => '2',
                'require'   => 'true',
                'describe'  => '',
                'value'     => '01'
            ],
            'productId' =>  [
                'name'      => '产品ID',
                'param'     => 'productId',
                'type'      => 'String',
                'lengthmax' => '8',
                'require'   => 'true',
                'describe'  => '付款对私：00000004 付款对公：00000003',
                'value'     => '00000004'
            ],
            'tranTime' =>  [
                'name'      => '交易时间',
                'param'     => 'tranTime',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '交易时间',
                'value'     => date('YmdHis', time())
            ],
            'orderCode' =>  [
                'name'      => '订单号',
                'param'     => 'orderCode',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '订单号',
                'value'     => date('YmdHis', time())+6000
            ],

            'tranAmt' =>  [
                'name'      => '金额',
                'param'     => 'tranAmt',
                'type'      => 'String',
                'lengthmax' => '12',
                'require'   => 'true',
                'describe'  => '金额',
                'value'     => '000000000001'
            ],

            'currencyCode' =>  [
                'name'      => '币种',
                'param'     => 'currencyCode',
                'type'      => 'String',
                'lengthmax' => '3',
                'require'   => 'true',
                'describe'  => '币种',
                'value'     => '156'
            ],
            
            'accAttr' =>  [
                'name'      => '账户属性-0对私(默认)',
                'param'     => 'accAttr',
                'type'      => 'String',
                'lengthmax' => '1',
                'require'   => 'true',
                'describe'  => '0-对私(默认)',
                'value'     => '0'
            ],

            'accType' =>  [
                'name'      => '账号类型',
                'param'     => 'accType',
                'type'      => 'String',
                'lengthmax' => '1',
                'require'   => 'true',
                'describe'  => '2-存折 3-公司账户  4-银行卡 注：accAttr选择对公时，accType选公司账户',
                'value'     => '4'
            ],

            'accNo' =>  [
                'name'      => '收款人账户号',
                'param'     => 'accNo',
                'type'      => 'String',
                'lengthmax' => '50',
                'require'   => 'true',
                'describe'  => '收款人账户号',
                'value'     => ''
            ],

            'extend' =>  [
                'name'      => '扩展域',
                'param'     => 'extend',
                'type'      => 'String',
                'lengthmax' => '256',
                'require'   => 'false',
                'describe'  => '如上送，在异步通知和查询接口中将返回相同的值',
                'value'     => ''
            ],

        ],
        'queryBalance' => [
            'version' =>  [
                'name'      => '版本号',
                'param'     => 'version',
                'type'      => 'String',
                'lengthmax' => '2',
                'require'   => 'true',
                'describe'  => '',
                'value'     => '01'
            ],
            'productId' =>  [
                'name'      => '产品ID',
                'param'     => 'productId',
                'type'      => 'String',
                'lengthmax' => '8',
                'require'   => 'true',
                'describe'  => '付款对私：00000004 付款对公：00000003',
                'value'     => '00000004'
            ],
            'tranTime' =>  [
                'name'      => '交易时间',
                'param'     => 'tranTime',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '交易时间',
                'value'     => date('YmdHis', time())
            ],
            'orderCode' =>  [
                'name'      => '订单号',
                'param'     => 'orderCode',
                'type'      => 'String',
                'lengthmax' => '30',
                'require'   => 'true',
                'describe'  => '订单号',
                'value'     => date('YmdHis', time())+6000
            ],
            'extend' =>  [
                'name'      => '扩展域',
                'param'     => 'extend',
                'type'      => 'String',
                'lengthmax' => '256',
                'require'   => 'false',
                'describe'  => '如上送，在异步通知和查询接口中将返回相同的值',
                'value'     => ''
            ],

        ],

        'getClearFileContent' => [
            'version' =>  [
                'name'      => '版本号',
                'param'     => 'version',
                'type'      => 'String',
                'lengthmax' => '2',
                'require'   => 'true',
                'describe'  => '',
                'value'     => '01'
            ],
            'clearDate' =>  [
                'name'      => '对账日期',
                'param'     => 'clearDate',
                'type'      => 'String',
                'lengthmax' => '8',
                'require'   => 'true',
                'describe'  => '对账日期',
                'value'     => ''
            ],
            'busiType' =>  [
                'name'      => '业务类型',
                'param'     => 'busiType',
                'type'      => 'String',
                'lengthmax' => '4',
                'require'   => 'true',
                'describe'  => '固定填1',
                'value'     => '1'
            ],
            'fileType' =>  [
                'name'      => '文件返回类型',
                'param'     => 'fileType',
                'type'      => 'String',
                'lengthmax' => '1',
                'require'   => 'true',
                'describe'  => '固定填1',
                'value'     => '1'
            ]

        ],

        'getVoucherContent' => [
            'version' =>  [
                'name'      => '版本号',
                'param'     => 'version',
                'type'      => 'String',
                'lengthmax' => '2',
                'require'   => 'true',
                'describe'  => '',
                'value'     => '01'
            ],
            'productId' =>  [
                'name'      => '产品ID',
                'param'     => 'productId',
                'type'      => 'String',
                'lengthmax' => '8',
                'require'   => 'true',
                'describe'  => '产品ID',
                'value'     => '00000004'
            ],
            'tranTime' =>  [
                'name'      => '交易时间',
                'param'     => 'tranTime',
                'type'      => 'String',
                'lengthmax' => '14',
                'require'   => 'true',
                'describe'  => '待生成凭证交易的交易请求时间',
                'value'     => date('YmdHis', time())
            ],
            'orderCode' =>  [
                'name'      => '订单号',
                'param'     => 'orderCode',
                'type'      => 'String',
                'lengthmax' => '50',
                'require'   => 'true',
                'describe'  => '待生成凭证交易的订单号',
                'value'     => date('YmdHis', time()+3600*8)
            ],
            'voucherType' =>  [
                'name'      => '凭证类型',
                'param'     => 'voucherType',
                'type'      => 'String',
                'lengthmax' => '4',
                'require'   => 'true',
                'describe'  => '1-付款凭证',
                'value'     => '1'
            ],
            'fileType' =>  [
                'name'      => '文件返回类型',
                'param'     => 'fileType',
                'type'      => 'String',
                'lengthmax' => '1',
                'require'   => 'true',
                'describe'  => '1:文件下载链接(默认)',
                'value'     => '1'
            ],

        ],
        

    ]
]; 



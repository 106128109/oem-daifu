<?php
/**
 * Created by PhpStorm.
 * User: win 10
 * Date: 2018/6/11
 * Time: 11:41
 */

namespace Payment\Controller;

/**
 * 单笔转账到支付宝账户接口
 * Class IndexController
 * @package User\Controller
 */
use think\Log;

class AliTransferController extends PaymentController
{
    //代付状态
    const PAYMENT_SUBMIT_SUCCESS = 1; //处理中
    const PAYMENT_PAY_SUCCESS = 2; //已打款
    const PAYMENT_PAY_FAILED = 3; //已驳回
    const PAYMENT_PAY_UNKNOWN = 4; //待确认

    public function __construct(){
        parent::__construct();
    }
    
    public function queryBalance() 
    {
        $id = I('post.id', '11');
        
        if(!IS_AJAX) {
            $this->ajaxReturn(['status' => 0, 'msg' => '提交方式错误']);
            
            exit;
        }
        
        $config = $this->findPaymentType($id);
        
        //var_dump($config);
        
        $privateKey = $config['private_key'];
        //$privateKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJPoXsGe7KzarDYdvo71CRaF0bXFflOpZpdvA41hKvNXQ+AoXgyBd/O8u0sDNFXYbZOAEHY4UVEg1TW3JMrE35dLJsZAbFM6HaKGWv7Q0jjyNrBx4Ti+pAoR6gG0wLsd5iWeCLB98Q0MOgF3i6VQW6BDy93oOTAxKA/JFRd25uC694O1ruUf0hVz0LkLJrPyhT4igN3OK4GB5uE65u78lLJbUHdwrnNCs8nL/b/8CY7geXpwVcYYwdxeivbnPMh1aSDUMO3HzbLt0blqnjhrhFDr6lesosUPOYSO1Sa/BqF5YbfQlucGEU+tqVtR837LkaCLBmcsFZdSgpenY2XD/LAgMBAAECggEALtM+LgfLCTaShIbm2NqNyo6o9aTT+Em89860ty+SwGSkfGOv+blLCwYDwmo7k/cNAx+weiziQwYdtcsFfFNtZycBmSmnhbDQD/aoexWN3gwAMYwEHvclvVc7c7TchUDyduvjSIwu9zXDCOP5NNm9UnIPp1g72/S4Y3nIutrcun6RNtIg1G5uBjmSr+eZ1irEMMfWgIaAwLPvQ+IzstDOVidTegQ4RsQes1TtFk/ZKUdZUap5XRG8RZdr2ese4Nz6nA+InKFAAGF3rXFpfskT+6DhF1S5dEcX/5DM8yfBWjG4NV7GWMEfwD8JRHqQKjYBwjUHD4i1nAv+X63d4vF+wQKBgQDU2tzTQiTsz7qxfYzKEXrXive+R+WEFygXW4l0e+uvzRbvK/IXzts+23BqVivQSS1i/5lmoNvx/O4b78Jkxm2vIve7hGQnpYpt1yi3+lGy5b4II3MHpMJY4sab+DtCbv+2b/eT67RzaMPa8c9FpaEbVVWk87Lxo2wh2cX0lrDUtwKBgQClEDABUspkEoJW31KoK4094FEwEOxFuYWwhRra6SoBamRkkj/FcrLN4rwJjkoZPYUUDMosbkY0d/8TiLqFaCa2Qou4rZ6/nvTaE4G9s4Expibkk4ymrI3SmHcXgytoQcRn4L98Yq7TL9i7o3zVB179BY9+EcF+I76IVILMuFChjQKBgQC4rgR3D6a2CS40nXgwQqZQqXR2li7fQrA4Q+WpOXOunsVNUtXELmgvy3ln6cgt+a/1e0t/rgXnmcqGVqpVgYzdLfu/qQi1FX5b+xiLOBb2nzsYGJnPgfZV5LzpyqCv7VrU0aT/pLx2femg57ks9p4n9wxOCFu0KFTDsg19P9nBVQKBgHk2H+p434MJTIl6yXoRMVEk7rm7U6YIDLKJrCThYCVV+Y8ZDpdyGPez7p2dzbAnSxhkI/rop8lT7Q5tM3tP9k2VJIFjjdXtZqTV+kpSDSdmed0UtQ6YXDUwHRQ5EKEo2o/lrPgsh3EyC5gPAFZ3aTuo36yWYV695Oa8GKk5GIzpAoGAbYiZBBFJ1Qtcmz4dRqniNxGJ4VpouszzHGBMZEpMj/bZnJuLv4XTdVzEsz48She8rQyICYoMlPwHyhzcxJLZPwcWbwlB0MJFT5VUQDemEVyC7RiyjaoAg0kqGl2ymHJm6Kph8YkvlRAmIuC0KX7+vmKg1RBdbJn9Fez+BddXJXk=';
        $alipayPublicKey = $config['public_key'];
        
        $content = [
            'alipay_user_id' => $config['mch_id'],
            'account_type' => 'ACCTRANS_ACCOUNT'
        ];
        
        $data = [
            'app_id' => $config['appid'],
            'method' => 'alipay.fund.account.query',
            'format' => 'json',
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'biz_content' => json_encode($content)
        ];
        
        ksort($data);
        
        $signText = '';
        foreach($data as $k => $v) {
            $signText .= $k.'='.$v.'&';
        }
        
        $signText = rtrim($signText, '&');
        
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($privateKey, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
        
        $piKey = openssl_pkey_get_private($res);
        if(!$piKey) {
            $this->ajaxReturn(['status' => 0, 'msg' => '私钥格式错误']);
            
            exit;
        }
        
        $res = openssl_get_privatekey($res);
        openssl_sign($signText, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        openssl_free_key($piKey);
        
        $data['sign'] = $sign;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $config['exec_gateway']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);

        curl_close($ch);
        
        if(strlen($output) <= 0) {
            $this->ajaxReturn(['status' => 0, 'msg' => '上游无响应']);
            
            exit;
        }
        
        $result = json_decode($output, true);
        if($result['alipay_fund_account_query_response']['code'] != '10000') {
            $this->ajaxReturn(['status' => 0, 'msg' => $result['alipay_fund_account_query_response']['msg']]);
            
            exit;
        }
        
        $html = '<p style="color: blue;font-weight:600;font-size: 20px;margin: 10px 20px;">';
        $html .= '可用余额：'.$result['alipay_fund_account_query_response']['available_amount'].'元';
        $html .= '</p>';
        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'data' => $html]);
    }
    
    

    public function PaymentExec($data,$config)
    {
        // data 订单数据
        // config 支付宝配置
        
        $privateKey = $config['private_key'];
        $alipayPublicKey = $config['public_key'];
        
        // 获取根序列号
        $cert_file_path = '/www/wwwroot/dudu.sjzwywl.com/alipay/zshu/alipayRootCert.crt';
        $cert_content = file_get_contents($cert_file_path);
        
        if(strlen($cert_content) <= 0) return false;
        $alipayRootCertSN = $this->getRootCertSNFromContent($cert_content);
        
        $cert_file_path = '/www/wwwroot/dudu.sjzwywl.com/alipay/zshu/appCertPublicKey_2021003130630492.crt';
        $cert_content = file_get_contents($cert_file_path);
        if(strlen($cert_content) <= 0) return false;
        
        $alipayCertSN = $this->getCertSNFromContent($cert_content);
        
        $log = date('Y-m-d H:i:s').' => 订单号:'.$data['orderid'].', 支付宝账号:'.$data['banknumber'].', 支付宝账户:'.$data['bankfullname'].', 结果:';
        
        $payee_info = [
            'identity' => $data['banknumber'],
            'identity_type' => 'ALIPAY_LOGON_ID',
            'name' => $data['bankfullname']
        ];
        
        $business_params = [
            'payer_show_name' => '广西南宁逸境轩农业发展有限公司'
        ];
        
        $content = [
            'out_biz_no' => $data['orderid'],
            'trans_amount' => sprintf('%.2f', $data['money']),
            'product_code' => 'TRANS_ACCOUNT_NO_PWD',
            'biz_scene' => 'DIRECT_TRANSFER',
            'order_title' => 'payment',
            'payee_info' => json_encode($payee_info),
            'remark' => '',
            'business_params' => json_encode($business_params)
        ];
        
        $data = [
            'alipay_root_cert_sn' => $alipayRootCertSN,
            'app_cert_sn' => $alipayCertSN,
            'app_id' => $config['appid'],
            'method' => 'alipay.fund.trans.uni.transfer',
            'format' => 'json',
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'biz_content' => json_encode($content)
        ];
        
        ksort($data);
        
        $signText = '';
        foreach($data as $k => $v) {
            $signText .= $k.'='.$v.'&';
        }
        
        $signText = rtrim($signText, '&');
        
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($privateKey, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
        
        $piKey = openssl_pkey_get_private($res);
        if(!$piKey) {
            $this->ajaxReturn(['status' => 0, 'msg' => '私钥格式错误']);
            
            exit;
        }
        
        $res = openssl_get_privatekey($res);
        openssl_sign($signText, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        openssl_free_key($piKey);
        
        $data['sign'] = $sign;
        
        // $log = '提交参数 => '.json_encode($data).PHP_EOL;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $config['exec_gateway']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);

        curl_close($ch);
        
        if(strlen($output) <= 0) return false;
        
        $encode = mb_detect_encoding($output, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')); 
        $str_encode = mb_convert_encoding($output, 'UTF-8', $encode);
        
        $result = json_decode($str_encode, true);
        
        if($result['alipay_fund_trans_uni_transfer_response']['code'] != '10000') $log .= $result['alipay_fund_trans_uni_transfer_response']['msg'].' - '.$result['alipay_fund_trans_uni_transfer_response']['sub_msg'];
        else $log .= 'success';
        
        file_put_contents('/www/wwwroot/dudu.sjzwywl.com/alidf.log', $log.PHP_EOL, 8);
        
        if($result['alipay_fund_trans_uni_transfer_response']['code'] == '10000') return ['status' => 1, 'msg' => '提交成功'];
        else return false;
    }

    public function PaymentQuery($data, $config)
    {
        // data 订单数据
        // config 支付宝配置
        
        $privateKey = $config['private_key'];
        $alipayPublicKey = $config['public_key'];
        
        $content = [
            'product_code' => 'TRANS_ACCOUNT_NO_PWD',
            'biz_scene' => 'DIRECT_TRANSFER',
            'out_biz_no' => $data['orderid'],
            'order_id' => '',
            'pay_fund_order_id' => ''
        ];
        
        $data = [
            'app_id' => $config['appid'],
            'method' => 'alipay.fund.trans.common.query',
            'format' => 'json',
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'biz_content' => json_encode($content)
        ];
        
        ksort($data);
        
        $signText = '';
        foreach($data as $k => $v) {
            $signText .= $k.'='.$v.'&';
        }
        
        $signText = rtrim($signText, '&');
        
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($privateKey, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
        
        $piKey = openssl_pkey_get_private($res);
        if(!$piKey) {
            $this->ajaxReturn(['status' => 0, 'msg' => '私钥格式错误']);
            
            exit;
        }
        
        $res = openssl_get_privatekey($res);
        openssl_sign($signText, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        openssl_free_key($piKey);
        
        $data['sign'] = $sign;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $config['exec_gateway']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);

        curl_close($ch);
        
        if(strlen($output) <= 0) return null;
        
        $encode = mb_detect_encoding($output, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')); 
        $str_encode = mb_convert_encoding($output, 'UTF-8', $encode);
        
        $result = json_decode($str_encode, true);
        
        if($result['alipay_fund_trans_common_query_response']['code'] == '10000' && $result['alipay_fund_trans_common_query_response']['status'] == 'SUCCESS') return ['status' => self::PAYMENT_PAY_SUCCESS, 'msg' => '代付成功'];
        else return ['status' => self::PAYMENT_PAY_UNKNOWN, 'msg' => "错误：".$result['alipay_fund_trans_common_query_response']['msg']];
    }
    
    /**
     * 提取根证书序列号
     * @param $cert  根证书
     * @return string|null
     */
    private function getRootCertSNFromContent($certContent){
        $array = explode("-----END CERTIFICATE-----", $certContent);
        $SN = null;
        for ($i = 0; $i < count($array) - 1; $i++) {
            $ssl[$i] = openssl_x509_parse($array[$i] . "-----END CERTIFICATE-----");
            if(strpos($ssl[$i]['serialNumber'],'0x') === 0){
                $ssl[$i]['serialNumber'] = $this->hex2dec($ssl[$i]['serialNumberHex']);
            }
            if ($ssl[$i]['signatureTypeLN'] == "sha1WithRSAEncryption" || $ssl[$i]['signatureTypeLN'] == "sha256WithRSAEncryption") {
                if ($SN == null) {
                    $SN = md5($this->array2string(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                } else {

                    $SN = $SN . "_" . md5($this->array2string(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                }
            }
        }
        return $SN;
    }
    
    /**
     * 从证书内容中提取序列号
     * @param $certContent
     * @return string
     */
    private function getCertSNFromContent($certContent){
        $ssl = openssl_x509_parse($certContent);
        $SN = md5($this->array2string(array_reverse($ssl['issuer'])) . $ssl['serialNumber']);
        return $SN;
    }
    /**
     * 0x转高精度数字
     * @param $hex
     * @return int|string
     */
    private function hex2dec($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
    
    private function array2string($array)
    {
        $string = [];
        if ($array && is_array($array)) {
            foreach ($array as $key => $value) {
                $string[] = $key . '=' . $value;
            }
        }
        return implode(',', $string);
    }
}
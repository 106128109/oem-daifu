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

class HeePayController extends PaymentController
{
    //代付状态
    const PAYMENT_SUBMIT_SUCCESS = 1; //处理中
    const PAYMENT_PAY_SUCCESS = 2; //已打款
    const PAYMENT_PAY_FAILED = 3; //已驳回
    const PAYMENT_PAY_UNKNOWN = 4; //待确认

    public function __construct(){
        parent::__construct();
    }
    
    private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }
    
    private function encrypt($input, $key) {
        if (empty($input)){
            return '';
        }
        $size = mcrypt_get_block_size ( MCRYPT_3DES, 'ecb' );
        $input = $this->pkcs5_pad ( $input, $size );
        $key = str_pad ( $key, 24, '0' );
        $td = mcrypt_module_open ( MCRYPT_3DES, '', 'ecb', '' );
        $iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
        @mcrypt_generic_init ( $td, $key, $iv );
        $data = mcrypt_generic ( $td, $input );
        mcrypt_generic_deinit ( $td );
        mcrypt_module_close ( $td );
        
        return $this->strToHex($data);
    }
    
    private function strToHex($string){
		$hex="";   
        for($i=0;$i<strlen($string);$i++)  { 
        	$iHex=dechex(ord($string[$i]));   
			if(strlen($iHex)==1)
				$hex .='0' . $iHex;
			else
				$hex .= $iHex;
		}
		
        $hex = strtoupper($hex);   
        
        return $hex; 
	 }
	 
	public function PaymentNotify() {
        $str = file_get_contents('php://input');
        
        file_put_contents('/www/wwwroot/daifu.fk08.cn/Data/daifu/heepay_notify.log', $str.PHP_EOL, 8);
	    echo '123';
	}

    public function PaymentExec($data, $config){
        // 根据银行名称查询具体代付银行编号
        $Systembank = M("Systembank");
        $where['bankname'] = $data['bankname'];
        $bankinfo = $Systembank->where($where)->find();
        
        $bank_data = [
            $data['orderid'],
            $bankinfo['df_code'],
            '0',
            $data['banknumber'],
            $data['bankfullname'],
            sprintf('%.2f', $data['money']),
            '代发工资',
            $data['sheng'],
            $data['shi'],
            $data['bankzhiname'],
        ];
        
        $detail_data = implode('^', $bank_data);
        
        $post_data = [
            'version' => '3',
            'agent_id' => $config['mch_id'],
            'batch_no' => $data['orderid'],
            'batch_amt' => sprintf('%.2f', $data['money']),
            'batch_num' => 1,
            'detail_data' => $detail_data,
            'notify_url' => 'http://'.$_SERVER['SERVER_NAME'].'/Payment_HeePay_paymentNotify.html',
            'ext_param1' => $data['orderid'],
            'sign_type' => 'MD5'
        ];
        
        //组织签名
    	$signStr='';
    	$signStr  = $signStr . 'agent_id=' . $post_data['agent_id'];
    	$signStr  = $signStr . '&batch_amt=' . $post_data['batch_amt'];
    	$signStr  = $signStr . '&batch_no=' . $post_data['batch_no'];
    	$signStr  = $signStr . '&batch_num=' . $post_data['batch_num'];
    	$signStr  = $signStr . '&detail_data=' . $post_data['detail_data'];
    	
    	$signStr  = $signStr . '&ext_param1=' . $post_data['ext_param1'];
    	$signStr  = $signStr . '&key=' . $config['signkey'];
    	$signStr  = $signStr . '&notify_url=' . $post_data['notify_url'];
    	$signStr  = $signStr . '&version=' . $post_data['version'];
    	
        $sign = md5(strtolower($signStr));
        
        // des加密
        $detail_data = iconv("utf-8","gbk//IGNORE", $detail_data);
        $detail_data_des = $this->encrypt($detail_data, $config['appsecret']);
        
        $post_data['detail_data'] = $detail_data_des;
        $post_data['sign'] = $sign;
        
        $url = $data['money'] > 50000 ? 'https://Pay.heepay.com/API/PayTransit/PayTransferWithLargeWork.aspx' : 'https://Pay.heepay.com/API/PayTransit/PayTransferWithSmallAll.aspx';
        $url .= '?'.http_build_query($post_data);
        
        $log = '请求时间: '.date('Y-m-d H:i:s').PHP_EOL;
        $log .= '订单编号: '.$post_data['batch_no'].PHP_EOL;
        $log .= '请求地址: '.$url.PHP_EOL;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        
        $res = curl_exec($curl);
        
        curl_close($curl);
        
        $output = iconv("gbk","utf-8//IGNORE", $res);
        
        if(empty($output)) {
            $return = ['status' => 3, 'msg' => '上游无响应'];
            
            return $return;
        }
        
        $log .= '上游响应: '.$output.PHP_EOL;
        
        file_put_contents('/www/wwwroot/daifu.fk08.cn/Data/daifu/post.log', $log.PHP_EOL, 8);
        
        $obj = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($obj);
        $arr = json_decode($json, true);
        
        if($arr['ret_code'] != '0000') {
            $return = ['status' => 3, 'msg' => $arr['ret_msg']];
            
            return $return;
        }
        
        $return = ['status' => 1, 'msg' => '处理中！'];
        
        return $return;
    }

    public function PaymentQuery($data,$config){
		
		$post_data = [
		    'version' => '3',
		    'agent_id' => $config['mch_id'],
            'batch_no' => $data['orderid']
		];
		
		$signStr = 'agent_id='.$post_data['agent_id'].'&batch_no='.$post_data['batch_no'].'&key='.$config['signkey'].'&version='.$post_data['version'];
		$post_data['sign'] = md5(strtolower($signStr));
		
		$url .= 'https://Pay.heepay.com/API/PayTransit/QueryTransfer.aspx?'.http_build_query($post_data);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        
        $res = curl_exec($curl);
        
        curl_close($curl);
        
        $output = iconv("gbk","utf-8//IGNORE", $res);
        
        if(empty($output)) {
            $return = ['status' => 3, 'msg' => '上游无响应'];
            
            return $return;
        }
        
        $obj = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($obj);
        $arr = json_decode($json, true);
        
        if($arr['ret_code'] != '0000') {
            $return = ['status' => 3, 'msg' => $arr['ret_msg']];
        } else {
            if(!array_key_exists('batch_amt', $arr)) {
                $return = ['status' => 3, 'msg' => $arr['ret_msg']];
            } else {
                $return = ['status' => 2, 'msg' => '处理成功！'];
            }
        }
        
        return $return;
    }
}

<?php

namespace Pay\Controller;

use Org\Util\WxH5Pay;

class BossdfController extends PayController {

    public function __construct() {
        parent::__construct();
    }

    public function Pay($array) {
        $orderid = I("request.pay_orderid");
        $body = I('request.pay_productname');
        $notify_ip = $return['notify_ip'];
        $notifyurl = $this->_site . 'Pay_Bossdf_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Bossdf_callbackurl.html'; //返回通知

        $parameter = array(
            'code' => 'Bossdf', // 通道名称
            'title' => 'BOSS的代付',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid' => '',
            'out_trade_id' => $orderid,
            'body' => $body,
            'channel' => $array
        );
        
        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);


        $pay_memberid = "ceshi00";   //商户ID
        $pay_orderid = $return['orderid'];    //订单号
        $pay_amount = $return['amount'];    //交易金额
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        $pay_notifyurl = $notifyurl;   //服务端返回地址
        $pay_callbackurl = $callbackurl;  //页面跳转返回地址
        $Md5key = "47ae8ae0f7dfa9c9a9590a3749ae7e537a87a2de";   //密钥
        $pay_bankcode = "df";//$return['gateway'];   //银行编码
  
        	 $tjurl = "https://boss.sifangbox.com/index.php?c=pay&a=dfapi";   //提交地址	
        	 $native = array(
	        	'time'=>time(),
				'mch_id'=>$pay_memberid,
				'out_order_sn'=>$pay_orderid,
				'money'=>$pay_amount,
				'bank_name'=>"张三",
				'bank_card'=>"622848100125466215",
				'bank_khh'=>"中国农业银行",
				'df_code'=>"1",
				'df_khdz'=>"浙江省宁波市鄞州支行",
	        );
    
        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = md5($md5str . "key=" . $Md5key);
        $native["sign"] = $sign;
         if($pay_bankcode== "df"){
          $native["df_notify_url"] =$notifyurl;
         	
         }
       //  $native['post'] = $_POST;
    	$postData = http_build_query($native);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $tjurl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($data,true);
 	
 	echo $data;
 	
 	/*	if($url){
			header("Location:$url");die;
		}
		var_dump('<pre>',$json);die;*/
   	
    }
    
 function getSign($data,$key){

        $string= $this->formatBizQueryParaMap($data,$key);

        $result = strtoupper(md5($string));

        return $result;

    }
    /**
     * 将数组转为uri字符串
     * @param $str
     */
function formatBizQueryParaMap($paraMap,$key){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }
        $buff.='key='.$key;
        return $buff;
    }

    public function callbackurl() {
  exit('ok');
        $Order = M("Order");
        $pay_status = $Order->where(['pay_orderid' => $_REQUEST["partner_order"]])->getField("pay_status");
        $callbackurl = $Order->where(['pay_orderid' => $_REQUEST["partner_order"]])->getField("pay_callbackurl");
        //var_dump($callbackurl);die;
        if ($pay_status <> 0) {
            $this->EditMoney($_REQUEST['partner_order'], 'Hbkhuafei', 1);
            header("location:$callbackurl");
            die;
            exit('交易成功！');
        } else {
            header("location:$callbackurl");
            die;
        }
    }
    //获取真实IP
	public function getIp(){
		if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
			$ip = getenv("REMOTE_ADDR");
		}else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
			$ip = $_SERVER['REMOTE_ADDR'];
		}else{
			$ip = '0.0.0.0';
		}
		return $ip;
	}

    // 服务器点对点返回
    public function notifyurl() {

    file_put_contents("ceshi88888.txt",json_encode($_REQUEST)."\n", FILE_APPEND);
    $Md5key="47ae8ae0f7dfa9c9a9590a3749ae7e537a87a2de";
    $native=array(
    	"money"=>$_REQUEST['money'],
    	"order_sn"=>$_REQUEST['order_sn'],
    	"real_money"=>$_REQUEST['real_money'],
    	"status"=>$_REQUEST['status'],
    	"time"=>$_REQUEST['time'],
    	);
 ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }

        $sign = md5($md5str . "key=" . $Md5key);
        $native["sign"] = $sign;
        if( $native["sign"] == $sign){
        
        	//这里执行您的业务逻辑
        	exit('success');
        }else{
        	exit('sign error');
        }
         
}
}

?>
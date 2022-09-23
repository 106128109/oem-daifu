<?php
	
	
class Crypt3Des {

    public $key = "";

    /* 构造方法 */

    // public function encrypt($input) { // 数据加密
    //     if (empty($input)) {
    //         return null;
    //     }
    //     $size = mcrypt_get_block_size(MCRYPT_3DES, 'ecb');
    //     $input = $this->pkcs5_pad($input, $size);
    //     $key = str_pad($this->key, 24, '0');
    //     $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
    //     $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    //     @mcrypt_generic_init($td, $key, $iv);
    //     $data = mcrypt_generic($td, $input);
    //     mcrypt_generic_deinit($td);
    //     mcrypt_module_close($td);
    //     return $this->strToHex($data);
    // }

    // public function decrypt($encrypted) { // 数据解密
    //     if (!$encrypted || empty($encrypted)) {
    //         return null;
    //     }
    //   $encrypted = $this->hexToStr($encrypted);
    //     if (!$encrypted || empty($encrypted)) {
    //         return null;
    //     }
    //     $key = str_pad($this->key, 24, '0');
    //     $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
    //     $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    //     $ks = mcrypt_enc_get_key_size($td);
    //     @mcrypt_generic_init($td, $key, $iv);
    //     $decrypted = mdecrypt_generic($td, $encrypted);
    //     mcrypt_generic_deinit($td);
    //     mcrypt_module_close($td);
    //     $y = $this->pkcs5_unpad($decrypted);
    //     return $y;
    // }

    // function pkcs5_pad($text, $blocksize) {
    //     $pad = $blocksize - (strlen($text) % $blocksize);
    //     return $text . str_repeat(chr($pad), $pad);
    // }

    // function pkcs5_unpad($text) {
    //     $pad = ord($text {strlen($text) - 1});
    //     if ($pad > strlen($text)) {
    //         return false;
    //     }
    //     if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
    //         return false;
    //     }
    //     return substr($text, 0, - 1 * $pad);
    // }

    // function PaddingPKCS7($data) {
    //     $block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
    //     $padding_char = $block_size - (strlen($data) % $block_size);
    //     $data .= str_repeat(chr($padding_char), $padding_char);
    //     return $data;
    // }

    // function strToHex($string) {
    //     $hex = "";
    //     for ($i = 0; $i < strlen($string); $i++) {
    //         $iHex = dechex(ord($string[$i]));
    //         if (strlen($iHex) == 1)
    //             $hex .= '0' . $iHex;
    //         else
    //             $hex .= $iHex;
    //     }
    //     $hex = strtoupper($hex);
    //     return $hex;
    // }

    // function hexToStr($hex) {
    //     $string = "";
    //     for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
    //         $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    //     }
    //     return $string;
    // }
     
    /*构造方法*/
     
    public function encrypt($input) { // 数据加密
        if (empty($input)){
            return null;
        }
        $size = mcrypt_get_block_size ( MCRYPT_3DES, 'ecb' );
        $input = $this->pkcs5_pad ( $input, $size );
        $key = str_pad ( $this->key, 24, '0' );
        $td = mcrypt_module_open ( MCRYPT_3DES, '', 'ecb', '' );
        $iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
        @mcrypt_generic_init ( $td, $key, $iv );
        $data = mcrypt_generic ( $td, $input );
        mcrypt_generic_deinit ( $td );
        mcrypt_module_close ( $td );
       // $data = base64_encode ( $data );
        //return $data;
        return $this->strToHex($data);
    }
     
    public function decrypt($encrypted) { // 数据解密
        if (!$encrypted || empty($encrypted)){
            return null;
        }
        $encrypted = base64_decode ( $encrypted );
        if (!$encrypted || empty($encrypted)){
            return null;
        }
        $key = str_pad ( $this->key, 24, '0' );
        $td = mcrypt_module_open ( MCRYPT_3DES, '', 'ecb', '' );
        $iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
        $ks = mcrypt_enc_get_key_size ( $td );
        @mcrypt_generic_init ( $td, $key, $iv );
        $decrypted = mdecrypt_generic ( $td, $encrypted );
        mcrypt_generic_deinit ( $td );
        mcrypt_module_close ( $td );
        $y = $this->pkcs5_unpad ( $decrypted );
        return $y;
    }
     
    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }
     
    function pkcs5_unpad($text) {
        $pad = ord ( $text {strlen ( $text ) - 1} );
        if ($pad > strlen ( $text )) {
            return false;
        }
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad) {
            return false;
        }
        return substr ( $text, 0, - 1 * $pad );
    }
     
    function PaddingPKCS7($data) {
        $block_size = mcrypt_get_block_size ( MCRYPT_3DES, MCRYPT_MODE_CBC );
        $padding_char = $block_size - (strlen ( $data ) % $block_size);
        $data .= str_repeat ( chr ( $padding_char ), $padding_char );
        return $data;
    }
    
    function strToHex($string){
		$hex="";   
        for($i=0;$i<strlen($string);$i++)  { 
        	$iHex=dechex(ord($string[$i]));   
			if(strlen($iHex)==1)
				$hex .='0' . $iHex;
			else
				$hex .= $iHex;
		}
        $hex=strtoupper($hex);   
        return   $hex; 
	 }
	
	function hexToStr($hex){
	 	$string="";
	 	for($i=0;$i<strlen($hex)-1;$i+=2){
	 		$string.=chr(hexdec($hex[$i].$hex[$i+1]));
	 	}
	 	return $string;
	 }
}	

	
	/*
	注意   关于网站编码问题
	请注意，我司接口编码为gb2312
	如涉及编码问题  请根据   iconv 和 urlencode  
	请在传递参数时 进行相应的转码
	例：	urlencode(iconv("UTF-8","gb2312//IGNORE",$goods_name))
	 	urlencode($goods_name)
	*/
	
	/*
	以下仅为参考
	*/
	/*
	 
	 $rep = new Crypt3Des (); // 初始化一个对象
	$rep ->key = "6ECA1884D1614F69B0C36B77";
    $input = "123456";
    echo "原文：" . $input . "";
    $encrypt_card = $rep->encrypt ( $input );
    echo "加密：" . $encrypt_card . "";
    
    echo "解密：" . $rep->decrypt ( $rep->encrypt ( $input ) );
    echo "解密2：".$rep->decrypt("");
   
    echo "解密：" . $rep->decrypt ( $rep->encrypt ( $input ) );
    */
	

	
    $key = $_POST['keyMD5'];
    $keydes = $_POST['keyDES'];
    $agent_id = $_POST['agentId'];                            
    $batch_no = $_POST['batchNo'];                           
    $batch_amt = $_POST['batchAmt'];                                          
    $batch_num=1;
    $bankNo=$_POST['bankNo'];
    $billNo=$_POST['billNo'];
    $version=3;
    $bankCardNum=$_POST['bankCardNum'];
    $idCardName=$_POST['idCardName'];
    //$idCardName=iconv("UTF-8","gb2312//IGNORE",$idCardaNme);
    
    
    $answer=$_POST['answer'];
    $toWho=$_POST['toWho'];
    $notify_url=isset($_POST['notifyUrl']) ? $_POST['notifyUrl'] : 'http://2022.juhe.fk08.cn/Public/heepay/notify.php';
    
    $ext_param1=$_POST['extParam1'];
    
    $detail_data= $billNo."^".$bankNo."^".$toWho."^".$bankCardNum."^".$idCardName."^".$batch_amt."^".$answer."^"."天津市"."^"."南开区"."^"."光大银行天津南开支行";
    var_dump($detail_data);
        	   
	//组织签名
	$signStr='';
	$signStr  = $signStr . 'agent_id=' . $agent_id;
	$signStr  = $signStr . '&batch_amt=' . $batch_amt;
	$signStr  = $signStr . '&batch_no=' . $batch_no;
	$signStr  = $signStr . '&batch_num=' . $batch_num;
	$signStr  = $signStr . '&detail_data=' . $detail_data;
	
	$signStr  = $signStr . '&ext_param1=' . $ext_param1;
	$signStr  = $signStr . '&key=' . $key;
	$signStr  = $signStr . '&notify_url=' . $notify_url;
	$signStr  = $signStr . '&version=' . $version;
	
    $rep = new Crypt3Des (); // 初始化一个对象
    $rep ->key = $keydes;
    
    $detail_data = iconv("utf-8","gbk//IGNORE",$detail_data);
    $detail_data_des = $rep->encrypt ($detail_data);
        
	//获取sign密钥
	$sign='';
	$sign=md5(strtolower($signStr));
?>
<form id='frmSubmit' method='post' name='frmSubmit' action='https://Pay.heepay.com/API/PayTransit/PayTransferWithSmallAll.aspx'>
<input type='hidden' name='version' value='<?php echo $version ?>' />
<input type='hidden' name='agent_id' value='<?php echo $agent_id  ?>' />
<input type='hidden' name='batch_no' value='<?php echo  $batch_no ?>' />
<input type='hidden' name='batch_amt' value='<?php echo $batch_amt ?>' />
<input type='hidden' name='batch_num' value='<?php echo $batch_num  ?>' />
<input type='hidden' name='detail_data' value='<?php echo  $detail_data_des ?>' />
<input type='hidden' name='notify_url' value='<?php echo  $notify_url ?>' />
<input type='hidden' name='ext_param1' value='<?php echo $ext_param1 ?>' />
<input type='hidden' name='sign' value='<?php echo $sign  ?>' />
<input type="button" value="批 付" onclick="shortPaySubmit()">
</form>
<script language='javascript'>
function shortPaySubmit(){document.frmSubmit.submit();}
</script>






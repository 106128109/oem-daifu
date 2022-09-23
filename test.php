<?php
header('Content-Type:text/html;charset=utf-8');

$data = count($_GET) > 0 ? $_GET : $_POST;

file_put_contents("text.log", json_encode($data).PHP_EOL, 8);

$code = isset($_GET['code']) ? trim($_GET['code']) : '';
if(empty($code)) exit;

$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxf377897cb24c13b1&secret=30db3788cd2fdb6a8ff557f133531118&code='.$code.'&grant_type=authorization_code';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$output  = curl_exec($ch);
$err   = curl_error($ch);
$errno = curl_errno($ch);
if ($errno) {
    $msg = 'curl errInfo: ' . $err . ' curl errNo: ' . $errno;
    throw new \Exception($msg);
}

curl_close($ch);

if(empty($output)) exit('微信无响应');

$result = json_decode($output, true);
echo 'openid => '.$result['openid'];
?>
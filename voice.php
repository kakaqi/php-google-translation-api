<?php
/**
 * Created by PhpStorm.
 * User: chensongjian
 * Date: 2017/9/1
 * Time: 14:15
 */
require 'vendor/autoload.php';
use Stichoza\GoogleTranslate\TranslateClient;
header("Access-Control-Allow-Origin: *");

!is_dir('./voice/') && @mkdir('./voice/',0777);

$fileext = strtolower(substr(strrchr($_FILES['file']['name'],'.'),1,10));//获取文件扩展名
$filename = date('Ymdhis',$this->time).mt_rand(10,99).'.'.$fileext; //生成文件名
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    move_uploaded_file($_FILES['file']['tmp_name'],'./voice/'.$filename);
    @unlink($_FILES[$field]['tmp_name']);
}

$return = [
    'code'=> 0,
    'text' => 'success',
    'result' => $filename
];
die(json_encode($return));

define('AUDIO_FILE', "./voice/test.pcm");
$url = "http://vop.baidu.com/server_api";

//put your params here
$cuid = "2227135";
$apiKey = "aVwnyvWgNZGbhAP54ZA0mlPv";
$secretKey = "ZER0PDyDiDKybzCRntqERcrbyj1M5gkn";

$auth_url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=".$apiKey."&client_secret=".$secretKey;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($ch);
if(curl_errno($ch))
{
    print curl_error($ch);
}
curl_close($ch);
$response = json_decode($response, true);
$token = $response['access_token'];

$audio = file_get_contents(AUDIO_FILE);
$base_data = base64_encode($audio);
$array = array(
    "format" => "pcm",
    "rate" => 8000,
    "channel" => 1,
    //"lan" => "zh",
    "token" => $token,
    "cuid"=> $cuid,
    //"url" => "http://www.xxx.com/sample.pcm",
    //"callback" => "http://www.xxx.com/audio/callback",
    "len" => filesize(AUDIO_FILE),
    "speech" => $base_data,
);
$json_array = json_encode($array);
$content_len = "Content-Length: ".strlen($json_array);
$header = array ($content_len, 'Content-Type: application/json; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_array);
$response = curl_exec($ch);
if(curl_errno($ch))
{
    print curl_error($ch);
}
curl_close($ch);
$response = json_decode($response, true);
if($response['err_no'] == 0) {
    $get = $_GET;
    $source_lan = isset($get['source_lan']) ? $get['source_lan'] : 'zh-CN';
    $target_lan = isset($get['target_lan']) ? $get['target_lan'] : 'en';
    $content = $response['result'][0];

    $tr = new TranslateClient();
    $r = $tr->setUrlBase('http://translate.google.cn/translate_a/single');
    $tr->setSource($source_lan);
    $tr->setTarget($target_lan);
    $return = [
        'code'=> 0,
        'text' => 'success',
        'result' => $content ? $tr->translate($content)  : ''
    ];
    die(json_encode($return));
}

$return = [
    'code'=> 0,
    'text' => 'success',
    'result' => ''
];
die(json_encode($return));

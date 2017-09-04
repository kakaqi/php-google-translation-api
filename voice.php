<?php
/**
 * Created by PhpStorm.
 * User: chensongjian
 * Date: 2017/9/1
 * Time: 14:15
 */
///usr/bin/sh /usr/local/src/silk-v3-decoder/converter_beta.sh /www/TranslationForGoogle/voice/2017090409564955.silk wav
require 'vendor/autoload.php';
require_once 'AipSpeech.php';
use Stichoza\GoogleTranslate\TranslateClient;
header("Access-Control-Allow-Origin: *");

!is_dir('./voice/') && @mkdir('./voice/',0777);

$fileext = strtolower(substr(strrchr($_FILES['file']['name'],'.'),1,10));//获取文件扩展名
$filename = date('Ymdhis',time()).mt_rand(10,99);
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    move_uploaded_file($_FILES['file']['tmp_name'],'./voice/'.$filename.'.'.$fileext);
    @unlink($_FILES[$field]['tmp_name']);
}

$type = 'wav';
$cmd = '/usr/bin/sh /usr/local/src/silk-v3-decoder/converter_beta.sh  /www/TranslationForGoogle/voice/'.$filename.'.'.$fileext.' '.$type;
exec($cmd, $out);

//define('AUDIO_FILE', "./voice/test.pcm");
$audio_file = "./voice/".$filename.'.'.$type;

// 定义常量
const APP_ID = '2227135';
const API_KEY = 'aVwnyvWgNZGbhAP54ZA0mlPv';
const SECRET_KEY = 'ZER0PDyDiDKybzCRntqERcrbyj1M5gkn';

// 初始化AipSpeech对象
$aipSpeech = new AipSpeech(APP_ID, API_KEY, API_SECRET);
// 识别本地文件
$response = $aipSpeech->asr(file_get_contents($audio_file), $type, 16000, array(
    'lan' => 'zh',
));

$response = json_decode($response, true);
file_put_contents('./voice/test.txt',$response['result'][0]);
$return = [
    'code'=> 0,
    'text' => 'success',
    'result' => $response['result'][0]
];
die(json_encode($return));
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
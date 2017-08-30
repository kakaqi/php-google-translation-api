<?php
/**
 * Created by PhpStorm.
 * User: chensongjian
 * Date: 2017/8/30
 * Time: 17:20
 */

require 'vendor/autoload.php';
use Stichoza\GoogleTranslate\TranslateClient;

header("Access-Control-Allow-Origin: *");
//header('Content-type: text/json');
$get = $_GET;

$source_lan = isset($get['source_lan']) ? $get['source_lan'] : 'zh-CN';
$target_lan = isset($get['target_lan']) ? $get['target_lan'] : 'en';
$content = $get['content'];
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


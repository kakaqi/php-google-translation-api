<?php
/**
 * Created by PhpStorm.
 * User: chensongjian
 * Email:183567333@qq.com
 * Date: 2017/5/11
 * Time: 11:20
 */
require 'vendor/autoload.php';

use Stichoza\GoogleTranslate\TranslateClient;

set_time_limit(0);

$tr = new TranslateClient();
$r = $tr->setUrlBase('http://translate.google.cn/translate_a/single');
//语言源
$source_lan = 'zh-CN';
//设置语言源
$tr->setSource($source_lan);
//设置语言目录
$dir = './lang/json/';
if( ! is_dir($dir) ) {
    mkdir($dir, 0777, true);
}
//获取语言列表
//$names = getDirName($dir);
$names = langs();
foreach ($names as $name => $value) {
    if( $name == $source_lan) {
        continue;
    }
    //目标语言
    $tr->setTarget($name);
    //获取每个文件的内容
    $data = file_get_contents($dir.'/'.$source_lan.'.json');//json格式
    $data = json_decode($data, true);
    foreach ($data as &$v) {
        if(is_array($v)) {
            foreach ($v as &$vv) {
                if(is_array($vv)) {
                    foreach ($vv as &$vvv) {
                        if( ! is_array($vvv) ) $vvv = $tr->translate($vvv);
                    }
                } else {
                    $vv = $tr->translate($vv);
                }

            }
        } else {
            $v = $tr->translate($v);
        }

    }

    echo $dir.'/'.$value.'.json'.'-The translation is complete';
    echo "\n";
    saveFile($data, $dir.'/'.$value.'.json', 'json');
}
echo 'The translation is complete';
echo "\n";
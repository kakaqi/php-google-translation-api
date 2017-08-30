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
$dir = './lang';
//获取语言列表
//$names = getDirName($dir);
$names = langs();
foreach ($names as $name => $value) {
    $path = $dir.'/'.$value;
    if( ! is_dir($path) ) {
        mkdir($path, 0777, true);
    }

    if( $name == $source_lan) {
        continue;
    }
    //目标语言
    $tr->setTarget($name);
    //获取源 语言列表文件
    $r = scandir($dir.'/'.$source_lan);
    unset($r[0],$r[1]);

    foreach ($r as $rr) {
        //获取每个文件的内容
        $data = require $dir.'/'.$source_lan.'/'.$rr; //php数组格式

//        $data = file_get_contents($dir.'/'.$name.'/'.$rr);//json格式
//        $data = json_decode($data, true);

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

        echo $path.'/'.$rr.'-The translation is complete';
        echo "\n";
        saveFile($data, $path.'/'.$rr, 'array');
    }
}
echo 'The translation is complete';
echo "\n";
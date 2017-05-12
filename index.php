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
$tr->setSource('zh-CN');
$dir = './lang';
$names = getDirName($dir);

foreach ($names as $name) {

    $tr->setTarget($name);

    $r = scandir($dir.'/'.$name);
    unset($r[0],$r[1]);

    foreach ($r as $rr) {
//        $data = require $dir.'/'.$name.'/'.$rr; //php数组格式
        $data = file_get_contents($dir.'/'.$name.'/'.$rr);//json格式
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
        echo $dir.'/'.$name.'/'.$rr.'-翻译完毕';
        echo "\n";
        saveFile($data,$dir.'/'.$name.'/'.$name.'.'.explode('.',$rr)[1],'json');
    }
}
echo '全部翻译完毕！';
echo "\n";
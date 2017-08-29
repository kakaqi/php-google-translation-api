<?php
/**
 * Created by PhpStorm.
 * User: chensongjian
 * Date: 2017/5/11
 * Time: 12:17
 */

/**循环目录下的目录名称
 * @param string $dir 目录
 * @return array|string
 */
function getDirName( string $dir = '')
{
    if( !$dir ) {
        return '';
    }
    if( is_dir($dir) ) {
        $dirs = scandir($dir);
        unset($dirs[0],$dirs[1]);
        return array_values($dirs);
    }
    return '';
}

/**
 * 保存文件内容
 * @param array $data 数据
 * @param string $file 文件名
 * @param string $type 数据格式类型 array :php数组，json
 */
function saveFile( array $data, string $file, string $type = 'array'){
    if($type == 'array') {
        ob_start();
        var_export($data);
        $arrStr = ob_get_contents();
        ob_end_clean();
        $config = '<?php' . PHP_EOL
            . 'return ' . $arrStr.';';
    } else {
        $config = json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    file_put_contents($file, $config);
}

/**语言列表
 * @param string $key 语言标识
 * @return array|mixed|string
 */
function langs($key='')
{
    $lang = [
        'km' => 'cam',//高棉语
        'my' => 'bur',//缅甸语
        'ja' => 'ja-jp',//日语
        'th' => 'th-th',//泰语
        'vi' => 'vi-vn',//越南语
        'en' => 'en',//英语
        'de' => 'ge',//德语
        'ko' => 'ko-kr',//韩语
        'ms' => 'ma',//马来语
        'tl' => 'ph',//菲律宾语
        'zh-TW' => 'zh-hk',//中文繁体
        'zh-CN' => 'zh-cn',//中文简体
    ];
    if( $key ) {
        return isset($lang[$key]) ? $lang[$key] : $key;
    }
    return $lang;

}
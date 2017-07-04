<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(0);
require './vendor/autoload.php';
use QL\QueryList;
/*
 * 测试对应深度采集文件
 * $url     string      网站url
 * $length  int         深度          默认深度为2 即只采集网站首页以及网站首页中出现的href的页面
 */
function collection($url,$length=2)
{
    //輸出域名
    echo '目标网站是：'.$url."<br>";
    //获取采集页面内容
    $html = file_get_contents($url);
    //采集规则
    $rules = array(
        //采集所有a标签的href
        'link' => array('a', 'href', '', function ($content) use ($url) {
            /*
             * 利用回调函数补全相对链接
             * 特别注意href 为 ./  ../  /
             */
            if (0 != strpos($content, '/') && false === strpos($content, './') && false === strpos($content, '../')) {
                return $content;
            } else {
                $url = parse_url($url);
                $url = $url['scheme'] . '://' . $url['host'];
                $baseUrl = $url;
                return $baseUrl . $content;
            }
        }),
        //采集script的src
        'script' => array('script', 'src'),
        //采集title的text
        "title" => array("title", "text"),
    );
    //获取所有的页面
    $data = QueryList::Query($html, $rules)->data;

}

collection('http://www.gzjxw.gov.cn');


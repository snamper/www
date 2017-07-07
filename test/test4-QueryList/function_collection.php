<?php
require './vendor/autoload.php';
use QL\QueryList;
error_reporting(0);
/*
 * 采集函数
 * $url string   网址
 *
 */
function collection($url)
{
    /**
     * 目前已经有：HTTP操作、多线程、模拟登陆等QueryList扩展
     * 下面来利用QueryList扩展来组合上面的例子，实现多线程采集文章并保存文章图片到本地
     */
    $urls = []; //用于存放href的容器
    $GLOBALS['Links'][] =[];
    //HTTP操作扩展
    $urls = QueryList::run('Request', [
        'target' => $url,
        'referrer' => 'http://cms.querylist.cc',
        'method' => 'GET',
        //'params' => ['var1' => 'testvalue', 'var2' => 'somevalue'],
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
        'cookiePath' => './cookie.txt',
        'timeout' => '30'
    ])->setQuery([
            'link' => ['a', 'href', '', function ($content) use ($url) {
            //利用回调函数补全相对链接
                $str = parse_url($content);
                if(!empty($str['host'])){
                    return $content;
                }else{
                    return $url.'/'.$content;
                }
             }
        ]
    ])->getData(function ($item) {
        return $item;
    });
    //将返回结果变成一维数组
    foreach ($urls as $key => $val) {
        $links[] = $val['link'];
    }
//多线程扩展
   QueryList::run('Multi', [
        'list' => $links,
        'curl' => [
            'opt' => array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER => true,
            ),
            //设置线程数
            'maxThread' => 100,
            //设置最大尝试数
            'maxTry' => 3
        ],
        'success' => function ($result) {
            //采集规则
            $reg = array(
                //采集目标页的script标签的scr
                'script' => array('script', 'src'),
            );

            $Query = QueryList::Query($result['content'], $reg)->data;

            $GLOBALS['Links'][] = $Query;
        },
        'error' => function($resutl){
            return  '错误信息是'.$resutl;
        }
    ]);
}
collection('http://47.94.101.249/');
array_filter($GLOBALS['Links']);
var_dump($GLOBALS['Links']);
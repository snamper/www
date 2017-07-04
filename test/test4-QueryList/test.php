<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(0);
require './vendor/autoload.php';
use QL\QueryList;

function collection($url)
{
    //获取域名
    echo $site_url = '目标网址是:' . $url . '<br>';

    //根据域名获取html
    $html = file_get_contents($url);
    //采集规则
    $rules = array(
        //采集所有a标签的href
        'link' => array('a', 'href', '', function ($content) use ($url) {
            //利用回调函数补全相对链接
            if (0 != strpos($content, '/') && false === strpos($content, './') && false === strpos($content, '../')) {
                return $content;
            } else {
                $url = parse_url($url);
                $url = $url['scheme'] . '://' . $url['host'].'/';
                // $baseUrl = 'http://www.gzysdkj.gov.cn/sy/';
                $baseUrl = $url;
                return $baseUrl . $content;
            }
        }),
    );

    //获取所有的页面
    $data = QueryList::Query($html, $rules)->data;
    $count = count($data);
    echo $site_count = '网站首页总共'.$count.'个url';
    echo '---------------------------------------------------------------------<br>';
    $S_rules = array(
        'script' => array('script', 'src'),
        "title" => array("title", "text"),
    );
    //获取每个页面的script的src
    $is_chain=0;
    $is_exist = 0;
    $is_not_exist = 0;
    $is_empty = 0;
    foreach ($data as $key => $val) {
        if (!empty($val['link'])) {

            $link_host = parse_url($val['link']);
            $link_host = $link_host['host'];
            $url_host = parse_url($url);
            $url_host = $url_host['host'];
            if ($url_host != $link_host) {
                //echo '不是本站页面';
                //echo '--------------------------------------------------------------------------------<br>';
                $is_chain++;
                continue;
            }
            $data1 = QueryList::Query($val['link'], $S_rules)->data;

            foreach ($data1 as $index => $item) {
                if (!empty($item['script'])) {
                    $it = parse_url($item['script']);
                    $hosturl[] = $it['host'];
                }
            }

            if (in_array('fxsjcj.kaipuyun.cn', $hosturl)) {
                //echo '这个页面存在采集代码';
                //echo '------------------------------------------------------------------------------<br>';
                $is_exist++;
                continue;
            } else {

                //echo '这个页面不存在采集代码<br>';
                //echo '------------------------------------------------------------------------------<br>';
                $is_not_exist++;
                continue;
            }
        } else {
            //echo '这个url为空';
            //echo '------------------------------------------------------------------------------<br>';
            $is_empty++;
            continue;
        }
    }
    echo "<br>";
    echo '空地址占有率：'.round(($is_empty/$count)*100).'%<br>';
    echo '外链占有率：'.round(($is_chain/$count)*100).'%<br>';
    echo '嵌码页占有率：'.round(($is_exist/$count)*100).'%<br>';
    echo '未嵌码页占有率：'.round(($is_not_exist/$count)*100).'%<br>';

}

collection('http://www.gzss.gov.cn/');


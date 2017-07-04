<?php
header("Content-type: text/html; charset=utf-8");
echo '<pre>';
require './vendor/autoload.php';
use QL\QueryList;

$url    = 'http://www.gzqz.gov.cn/sites/MainSite/';
$html   = file_get_contents($url);
/*$html = <<<STR
<div id="one">
    <div class="two">
        <a href="http://querylist.cc">QueryList官网</a>
        <img src="http://querylist.com/1.jpg" alt="这是图片">
        <img src="http://querylist.com/2.jpg" alt="这是图片2">
    </div>
    <span>其它的<b>一些</b>文本</span>
</div>        
STR;*/
/*$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'text' => array('#one','text'),
    //采集class为two下面的超链接的链接
    'link' => array('.collapse navbar-collapse>a','href'),
    //采集class为two下面的第二张图片的链接
    'img' => array('.two>img:eq(1)','src'),
    //采集span标签中的HTML内容
    'other' => array('span','html')
);*/
$rules = array(
    //采集class为two下面的超链接的链接
    'link' => array('script','src'));
$data = QueryList::Query($html,$rules)->data;
var_dump($data);
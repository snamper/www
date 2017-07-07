<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(0);


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
                $url = $url['scheme'] . '://' . $url['host'] . '/';
                // $baseUrl = 'http://www.gzysdkj.gov.cn/sy/';
                $baseUrl = $url;
                return $baseUrl . $content;
            }
        }),
    );

    //获取所有的页面
    $data = QueryList::Query($html, $rules)->data;
    $count = count($data);
    echo $site_count = '网站首页总共' . $count . '个url';
    var_dump($data);
    die;
    echo '---------------------------------------------------------------------<br>';
    $S_rules = array(
        'script' => array('script', 'src'),
    );
    //获取每个页面的script的src
    $is_chain = 0;
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


}
function Collection_Multithreading()
{
    /**
     * 下面实现多线程采集文章信息
     */
    //多线程扩展
    QueryList::run('Multi', [
        //待采集链接集合
        'list' => [
            'http://cms.querylist.cc/news/it/547.html',
            'http://cms.querylist.cc/news/it/545.html',
            'http://cms.querylist.cc/news/it/543.html'
            //更多的采集链接....
        ],
        'curl' => [
            'opt' => array(
                //这里根据自身需求设置curl参数
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER => true,
                //........
            ),
            //设置线程数
            'maxThread' => 100,
            //设置最大尝试数
            'maxTry' => 3
        ],
        'success' => function ($a) {
            //采集规则
            $reg = array(
                //采集文章标题
                'title' => array('h1', 'text'),
                //采集文章正文内容,利用过滤功能去掉文章中的超链接，但保留超链接的文字，并去掉版权、JS代码等无用信息
                'content' => array('.post_content', 'html', 'a -.content_copyright -script')
            );
            $rang = '.content';
            $ql = QueryList::Query($a['content'], $reg, $rang);
            $data = $ql->getData();
            //打印结果，实际操作中这里应该做入数据库操作
            print_r($data);
        }
    ]);
}

collection('http://www.qiannan.gov.cn/');
/*echo "<br>";
echo '空地址占有率：' . round(($is_empty / $count) * 100) . '%<br>';
echo '外链占有率：' . round(($is_chain / $count) * 100) . '%<br>';
echo '嵌码页占有率：' . round(($is_exist / $count) * 100) . '%<br>';
echo '未嵌码页占有率：' . round(($is_not_exist / $count) * 100) . '%<br>';*/
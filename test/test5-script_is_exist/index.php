<?php
//测试脚本是否存在
$url    = 'https://jg.kaipuyun.cn/';
$str = <<<STR
<script language="JavaScript">var _trackDataType = 'web';var _trackData = _trackData || [];</script>
<script type="text/javascript" charset="utf-8" id="kpyfx_js_id_10000002" src="//fxsjcj.kaipuyun.cn/count/10000002/10000002.js"></script>
STR;

$html   = file_get_contents($url);
$html   = file_get_contents($url);
$html   = file_get_contents($url);
$res = strpos($html,$url);
var_dump($res);
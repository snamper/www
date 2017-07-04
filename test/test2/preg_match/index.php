<?php
$coding = 'src="//fxtest.kaipuyun.cn/phpstat/count/10000052/10000052.js"';
$Html = file_get_contents('http://www.baidu.com');
var_dump(strpos($Html,$coding));
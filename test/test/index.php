<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
$p = new test();
$p->index();
function func_imei($data1,$appname)
{
	$file = 'imei.txt';
	$handle = fopen($file,"w+");
	$content = "func_imei:".$data1."\t"."appname:".$appname."\t";
	//var_dump($content);die;
	if(!fwrite($handle,$content)){
		echo "写入失败！";
		exit();
	}
}

function func_android_id($data2)
{	
/**
	$file = 'android-id.txt';
	$handle = fopen($file,"w+");
	$content = "func_android_id:".$data2;
	if(!fwrite($handle,$content)){
		echo "写入失败！";
		exit();
	}
	*/

}

function func3($data)
{
    //echo "func3" . $data;
}

class test
{
    private $Name = 'log.txt';
    private $log;
    private $url;
    private $arr;
    private $data;
	
	private $txt = 'test.txt';
	private $data1;
	private $data2;
	private $appname;
	
    private function returnarray1()
    {
        return array(
            //测试例子
            'iface.iqiyi.com/api/getMyMenu' => array(
                'did' => "func_imei",
                'app_key' => "func_android_id",
                'secure_p' => "func3",
				'appname' => "爱奇艺",
            ),
            //10010 app
            'm.client.10010.com/mobileService/defaultSiteMap.htm' => array(
                'deviceCode' => "func2",
            ),
            //美团app
            'lvyou.meituan.com/volga/v1/magpie/route' => array(
                'utm_content' => "func2",
            ),
            //今日头条
            'ic.snssdk.com/push/get_service_addrs/' => array(
                'uuid' => "func2", "openudid" => "func1",
            ),
            //一点资讯
            'a1.go2yd.com/Website/proxy/open-app-log' => array(
                'deviceid' => 'func2',
            ),
            //凤凰新闻
            'api.newad.ifeng.com/ClientAdversApi1508' => array(
                'uid' => "func2", 'deviceid' => "func2",
            ),
            //爱奇艺
            'iface.iqiyi.com/api/ip2area' => array(
                'device_id' => "func2", 'udid' => "func1",
            ),

            'iface2.iqiyi.com/fusion/3.0/patch' => array(
                'aqyid' => "func2" . "_" . "func1",
            ),
            //搜狐新闻app
            'api.k.sohu.com/api/channel/v6/news.go' => array(
                'AndroidID' => "func1", 'imei' => "func2",
            ),
            //酷狗app
            'uniservice.kugou.com/v2/User/qryUser' => array(
                'imei' => "func2",
            ),
            //
            'log.stat.kugou.com/mobile/ad.html' => array(
                'mid' => "func2",
            ),
            //墨迹天气
            'cdn.moji.com/adlink/common/7f/9d/8e/85/7f9d8e85e922de06ca71da7d63e7b333.png' => array(),
            //腾讯新闻
            'w.inews.qq.com/setPushSwitch' => array(
                'uid' => "func1", 'devid' => "func2",
            ),
            //
            'r.inews.qq.com/appEventNotice' => array(
                'uid' => "func1", 'origin_imei' => "func2",
            ),
            //
            'shnk.fcloud.store.qq.com/16891/32070C667E86BE384868372DC4207DE3.apk' => array(),
            //天天快报
            'w.cnews.qq.com/reportUserTime' => array(
                'devid' => "func2", 'android_id' => "func1",
            ),
            //
            'w.cnews.qq.com/reportUserTime' => array(
                'uid' => "func1", 'devid' => "func2",
            ),
            'r.cnews.qq.com/getLocChl' => array(
                'uid' => "func1", 'devid' => "func2",
            ),
            //qq音乐
            'commdata.v.qq.com/commdatav2' => array(
                'imei' => "func2", 'device_id' => "func1",
            ),
            //
            'sdkconfig.video.qq.com/getmfomat' => array(
                'imei' => "func2", 'device_id' => "func1",
            ),
            //搜狗输入法
            'config.push.sogou.com/config/sdk' => array(
                'data' => "func2",
            ),
            //淘宝
            'w.m.taobao.com' => array(
                'baid' => "func3",
            ),
            //优酷
            'api.mobile.youku.com' => array(
                'bidfa' => "func3",
            ),
            //腾讯新闻
            'r.inews.qq.com' => array(
                'bidfa' => "func3",
            ),
            //sohu.com
            'i.go.sohu.com' => array(
                'bidfa' => "func3",
            ),
            //log.umtrack.com
            'log.umtrack.com' => array(
                'bidfa' => "func3",
            ),
            //唱吧
            'api.changba.com' => array(
                'bmacaddress' => "func3",
            ),
            //新浪
            'forecast.sina.cn' => array(
                'buid' => "func3",
            ),
            //蘑菇街
            'www.mogujie.com' => array(
                'b_did' => "func3",
            ),
            //搜狐
            's.go.sohu.com' => array(
                'bidfa' => "func3",
            ),
            //酷我
            'artistpicserver.kuwo.cn' => array(
                'bidfa' => "func3",
            ),
            //优酷
            'user.api.3g.youku.com' => array(
                'bidfa' => "func3",
            ),
            //爱奇艺
            'iface2.iqiyi.com' => array(
                'bqyid' => "func3",
            ),
            //美丽说
            'mobapi.meilishuo.com' => array(
                'bidfa' => "func3",
            ),
            //土豆
            'val.atm.youku.com' => array(
                'bck' => "func3",
            ),
            //土豆
            'user.api.3g.tudou.com' => array(
                'bidfa' => "func3",
            ),

        );

    }

    public function index()
    {
        $this->log = @ fopen($this->Name, "r");
        if ($this->log) {
            while (($buffer = fgets($this->log, 4096)) !== false) {
                preg_match('/\"(.*?)\"/', $buffer, $matches);
                $result = trim(strstr($matches[1], ' '));
                $data = $this->returnarray1();
				
                foreach ($data as $key => $val) {
                    if (substr($result, 0, strlen($key)) == $key) {
						//var_dump($data);die;
                        foreach ($val as $k => $v) {
                            $pos = strpos($result, $key);
                            if ($pos !== false) {
                                if($k == "appname") {
                                    unset($data[$key][$k]);
                                }
                                $data = strstr($result, $k);
                                $newdata = $this->_cut("=", "&", $data);
								$appname = $val['appname'];


								
								call_user_func_array($v, array($newdata,$appname));
								 
							
                            }
                        }
                    }
                }
            }
            if ($this->log) {
                if (!feof($this->log)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($this->log);
            }
        }
    }

	//获取两个字符之前的字符串
    private function _cut($begin, $end, $str)
    {
        $b = mb_strpos($str, $begin) + mb_strlen($begin);
        $e = mb_strpos($str, $end) - $b;

        return mb_substr($str, $b, $e);
    }

    //把url解析拼成数组
    private function convertUrlQuery($query)
    {

        $queryParts = explode('&', $query);
        $params = array();

        foreach ($queryParts as $param) {

            $item = explode('=', $param);

            $params[$item[0]] = $item[1];

        }

        return $params;

    }

}
?>
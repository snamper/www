<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(0);

$p = new logredis();
$p->index();

class logredis
{
    private $Name = 'php://stdin';
    private $log;
    private $redis;
    private $wronglog;//错误日志

    function __construct()
    {
        $this->wronglog = dirname(__FILE__) . '/wrong.log';
        $this->saveError(json_encode($_REQUEST).'par-1');
        $this->redis = include(dirname(__FILE__) . '/redis.php');
        if (empty($this->redis)) {
            $this->saveError("未链接redis");
            return false;
        }
    }

    public function index()
    {
        $this->log = fopen($this->getparam(), "r");
        if (!$this->log) {
            $this->saveError("读日志错误！");
            fclose($this->log);
            return false;
        }
        while (!feof($this->log)) {
            $buffer = fgets($this->log);
            if (!$buffer) {
                continue;
            }
            $this->preg_method($buffer);
        }
        fclose($this->log);
    }

    private function preg_method($buffer)
    {
        preg_match('/\"(.*?)\"/', $buffer, $matches);
        $result = trim(strstr($matches[1], ' '));
        $data = $this->returnarray1();
        foreach ($data as $key => $val) {
            if (substr($result, 0, strlen($key)) != $key) {
                continue;
            }

            foreach ($val as $k => $v) {

                if ($k != 'appname') {
                    $appnameData = $v;
                } else {
                    continue;
                }
                $cutdata = strstr($result, $k);

                $newdata = $this->_cut("=", "&", $cutdata);
                //call_user_func_array($appnameData, array($newdata, $val['appname']));
                $this->$appnameData($newdata, $val['appname']);
            }

        }
    }

    private function func_imei($data1, $appname)
    {

        $content = $appname . "---" . $data1;

        $this->redis->sadd("apptxt:imei", json_encode($content));
    }

    private function func_android_id($data2, $appname)
    {
        $content = $appname . "---" . $data2;

        $this->redis->sadd("apptxt:android_id", json_encode($content));
    }

    private function func_imei_android_id($data3, $appname)
    {
        $str = substr($data3, 0, 15);
        if (strlen($str) != 15) {
            $this->saveError($data3 . "未截取到id");
        }
        $this->func_imei($str, $appname);
        $newdata = substr($data3, 16, 16);
        $this->func_android_id($newdata, $appname);

    }

    private function func_idfa($data4, $appname)
    {
        $content = $appname . "---" . $data4;

        $this->redis->sadd("apptxt:idfa", json_encode($content));
    }

    /**
     * 记录错误日志
     *
     * @param $msg
     * @author: zhaibaoming
     * @Date: ${DATE}
     */
    private function saveError($msg)
    {
        $msg = $msg . ',time:' . date('Y-m-d H:i:s', time()) . "\r\n";
        $fp = fopen($this->wronglog, 'a+');
        fwrite($fp, $msg);
        fclose($fp);
    }

    private function returnarray1()
    {
        return array(
            //测试例子
            'iface.iqiyi.com/api/getMyMenu' => array(
                'did' => "func_imei",
                'app_key' => "func_android_id",
                'appname' => '爱奇艺',
            ),
            //10010 app
            'm.client.10010.com/mobileService/defaultSiteMap.htm' => array(
                'deviceCode' => "func_imei",
                'appname' => "10010",
            ),
            //美团app
            'lvyou.meituan.com/volga/v1/magpie/route' => array(
                'utm_content' => "func_imei",
                'appname' => '美团app',
            ),
            //今日头条
            'ic.snssdk.com/push/get_service_addrs/' => array(
                'uuid' => "func_imei",
                "openudid" => "func_android_id",
                'appname' => "今日头条",
            ),
            //一点资讯
            'a1.go2yd.com/Website/proxy/open-app-log' => array(
                'deviceid' => 'func_imei',
                'appname' => "一点资讯",
            ),
            //凤凰新闻
            'api.newad.ifeng.com/ClientAdversApi1508' => array(
                'uid' => "func_imei",
                'deviceid' => "func_android_id",
                'appname' => "凤凰新闻",
            ),
            //爱奇艺
            'iface.iqiyi.com/api/ip2area' => array(
                'device_id' => "func_imei",
                'udid' => "func_android_id",
                'appname' => "爱奇艺",
            ),
            //爱奇艺2
            'iface2.iqiyi.com/fusion/3.0/patch' => array(
                'aqyid' => "func_imei_android_id",
                'appname' => "爱奇艺2",
            ),
            //搜狐新闻app
            'api.k.sohu.com/api/channel/v6/news.go' => array(
                'AndroidID' => "func_android_id",
                'Authorization' => "func_imei",
                'appname' => "搜狐新闻app",
            ),
            //酷狗app
            'uniservice.kugou.com/v2/User/qryUser' => array(
                'imei' => "func_imei",
                'appname' => "酷狗app",
            ),
            //网易新闻app
            'p.3g.163.com/nc/push/register' => array(
                'deviceid' => "func_imei",
                'appname' => "网易新闻app",
            ),
            //
            'log.stat.kugou.com/mobile/ad.html' => array(
                'deviceid' => "func2",
            ),
            //腾讯新闻
            'w.inews.qq.com/setPushSwitch' => array(
                'origin_imei' => "func_imei",
                'uid' => "func_android_id",
                'appname' => '腾讯新闻',
            ),
            //腾讯新闻2
            'r.inews.qq.com/appEventNotice' => array(
                'origin_imei' => "func_imei",
                'appname' => '腾讯新闻2',
            ),
            //天天快报
            'w.cnews.qq.com/reportUserTime' => array(
                'devid' => "func_imei",
                'android_id' => "func_android_id",
                'appname' => "天天快报",
            ),
            //天天快报2
            'r.cnews.qq.com/getLocChl' => array(
                'devid' => "func_imei",
                'android_id' => "func_android_id",
                'appname' => "天天快报2",
            ),
            //qq音乐
            'w.cnews.qq.com/reportUserTime' => array(
                'imei' => "func_imei",
                'device_id' => "func_android_id",
                'appname' => "qq音乐",
            ),
            //qq音乐2
            'commdata.v.qq.com/commdatav2' => array(
                'imei' => "func_imei",
                'device_id' => "func_android_id",
                'appname' => "qq音乐2",
            ),
            //搜狗输入法
            'config.push.sogou.com/config/sdk' => array(
                'data' => "func_imei",
                'appname' => "搜狗输入法",
            ),
            //淘宝
            'w.m.taobao.com' => array(
                'baid' => "func_idfa",
                'appname' => "淘宝",
            ),
            //优酷
            'api.mobile.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "优酷",
            ),
            //腾讯新闻
            'r.inews.qq.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "优酷",
            ),
            //sohu.com
            'i.go.sohu.com' => array(
                'bidfa' => "腾讯新闻",
                'appname' => "sohu.com",
            ),
            //log.umtrack.com
            'log.umtrack.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "log.umtrack.com",
            ),
            //唱吧
            'api.changba.com' => array(
                'bmacaddress' => "func_idfa",
                'appname' => "唱吧",
            ),
            //新浪
            'forecast.sina.cn' => array(
                'buid' => "func_idfa",
                'appname' => "新浪",
            ),
            //蘑菇街
            'www.mogujie.com' => array(
                'b_did' => "func_idfa",
                'appname' => "蘑菇街",
            ),
            //搜狐
            's.go.sohu.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "搜狐",
            ),
            //酷我
            'artistpicserver.kuwo.cn' => array(
                'bidfa' => "func_idfa",
                'appname' => "酷我",
            ),
            //优酷
            'user.api.3g.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "酷我",
            ),
            //爱奇艺
            'iface2.iqiyi.com' => array(
                'bqyid' => "func_idfa",
                'appname' => "爱奇艺",
            ),
            //美丽说
            'mobapi.meilishuo.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "美丽说",
            ),
            //土豆
            'val.atm.youku.com' => array(
                'bck' => "func_idfa",
                'appname' => "土豆",
            ),
            //土豆
            'user.api.3g.tudou.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "土豆",
            ),
            //report.adview.cn
            'report.adview.cn' => array(
                'bidfa' => "func_idfa",
                'appname' => "report.adview.cn",
            ),
            //tqt.weibo.cn
            'tqt.weibo.cn' => array(
                'buid' => "func_idfa",
                'appname' => "tqt.weibo.cn",
            ),
            //api.oneniceapp.com
            'api.oneniceapp.com' => array(
                'bim' => "func_idfa",
                'appname' => "api.oneniceapp.com",
            ),
            //client.gushitong.baidu.com
            'client.gushitong.baidu.com' => array(
                'bcuid' => "func_idfa",
                'appname' => "client.gushitong.baidu.com",
            ),
            //ad.api.3g.youku.com
            'ad.api.3g.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "ad.api.3g.youku.com",
            ),
            //dict.youdao.com
            'dict.youdao.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "dict.youdao.com",
            ),
            //百度地图-附近
            'map.nuomi.com' => array(
                'blbsidfa' => "func_idfa",
                'appname' => "百度地图-附近",
            ),
            //百度地图-附近
            'tsm.nuomi.com' => array(
                'blbsidfa' => "func_idfa",
                'appname' => "百度地图-附近",
            ),
            //大众点评
            '114.80.165.113' => array(
                'bclientuuid' => "func_idfa",
                'appname' => "大众点评",
            ),
            //百度糯米
            '180.97.33.139' => array(
                'blbsidfa' => "func_idfa",
                'appname' => "百度糯米",
            ),
            //idfa识别_1
            'iface.iqiyi.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_1",
            ),
            //idfa识别_2
            'gm.mmstat.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_2",
            ),
            //idfa识别_3
            'ic.snssdk.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_3",
            ),
            //idfa识别_4
            'iface.iqiyi.com' => array(
                'bqyid' => "func_idfa",
                'appname' => "idfa识别_4",
            ),
            //idfa识别_5
            'log.snssdk.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_5",
            ),
            //idfa识别_6
            'ichannel.snssdk.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_6",
            ),
            //idfa识别_7
            'mon.snssdk.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_7",
            ),
            //idfa识别_8
            'mbdlog.iqiyi.com' => array(
                'bqyid' => "func_idfa",
                'appname' => "idfa识别_8",
            ),
            //idfa识别_9
            'iface2.iqiyi.com' => array(
                'bcupid_uid' => "func_idfa",
                'appname' => "idfa识别_9",
            ),
            //idfa识别_10
            'dm.toutiao.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_10",
            ),
            //idfa识别_11
            'iface.iqiyi.com' => array(
                'bcupid_uid' => "func_idfa",
                'appname' => "idfa识别_11",
            ),
            //idfa识别_12
            'isub.snssdk.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_12",
            ),
            //idfa识别_13
            'a2.pstatp.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_13",
            ),
            //idfa识别_14
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            //idfa识别_15
            'iface2.iqiyi.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_15",
            ),
            //idfa识别_16
            'i.snssdk.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            //idfa识别_17
            'msg.71.am' => array(
                'bu' => "func_idfa",
                'appname' => "idfa识别_17",
            ),
            //idfa识别_17
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),
            'val.atm.youku.com' => array(
                'bidfa' => "func_idfa",
                'appname' => "idfa识别_14",
            ),

        );

    }


//获取参数
    public function getparam()
    {
        $this->saveError($argv[1].'par-2');
        return $argv[1] ? $argv[1] : $this->Name;
    }

//获取两个字符之前的字符串
    private function _cut($begin, $end, $str)
    {
        try {
            $b = mb_strpos($str, $begin) + mb_strlen($begin);
            $e = mb_strpos($str, $end) - $b;
        } catch (\Exception $error) {
            var_dump($error);
            die;
        }

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

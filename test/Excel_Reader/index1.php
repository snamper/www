<?php
header('Content-type:text/html;charset=utf-8');
//error_reporting(0);
$p = new logredis();
$p->index();
include_once './index.php';
class logredis
{
    private $Name = 'php://stdin';
    private $log;
    private $redis;
    private $wronglog;//错误日志

    function __construct()
    {
        $this->wronglog = dirname(__FILE__) . '/wrong.log';
        $this->saveError(json_encode($_REQUEST) . 'par-1');
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

    private function returnarray1() {
        return array(
            'api.mobile.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '优酷',
            ) ,
            'r.inews.qq.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '腾讯新闻',
            ) ,
            'i.go.sohu.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_41',
            ) ,
            'log.umtrack.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'log.umtrack.com',
            ) ,
            'api.changba.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '唱吧',
            ) ,
            'forecast.sina.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => '新浪',
            ) ,
            'www.mogujie.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '蘑菇街',
            ) ,
            's.go.sohu.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_44',
            ) ,
            'artistpicserver.kuwo.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => '酷我',
            ) ,
            'user.api.3g.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '优酷',
            ) ,
            'iface2.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_15',
            ) ,
            'mobapi.meilishuo.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '美丽说',
            ) ,
            'val.atm.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_14',
            ) ,
            'user.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '土豆',
            ) ,
            'report.adview.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_66',
            ) ,
            'tqt.weibo.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'tqt.weibo.cn',
            ) ,
            'api.oneniceapp.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'api.oneniceapp.com',
            ) ,
            'client.gushitong.baidu.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'client.gushitong.baidu.com',
            ) ,
            'ad.api.3g.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'ad.api.3g.youku.com',
            ) ,
            'dict.youdao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'dict.youdao.com',
            ) ,
            'map.nuomi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '百度地图-附近',
            ) ,
            'tsm.nuomi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => '百度地图-附近',
            ) ,
            '114.80.165.113' => array(
                'bidfa' => 'func_idfa',
                'appname' => '大众点评',
            ) ,
            '180.97.33.139' => array(
                'bidfa' => 'func_idfa',
                'appname' => '百度糯米',
            ) ,
            'iface.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_35',
            ) ,
            'gm.mmstat.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_2',
            ) ,
            'ic.snssdk.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_3',
            ) ,
            'log.snssdk.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_5',
            ) ,
            'ichannel.snssdk.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_6',
            ) ,
            'mon.snssdk.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_7',
            ) ,
            'mbdlog.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_8',
            ) ,
            'dm.toutiao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_10',
            ) ,
            'isub.snssdk.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_12',
            ) ,
            'a2.pstatp.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_13',
            ) ,
            'i.snssdk.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_16',
            ) ,
            'msg.71.am' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_17',
            ) ,
            'api.cupid.qiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_30',
            ) ,
            'i.play.api.3g.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_20',
            ) ,
            'api.cupid.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_50',
            ) ,
            's.51wnl.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_23',
            ) ,
            'mcc.ijinshan.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_25',
            ) ,
            'c.51wnl.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_27',
            ) ,
            'imp.adsmogo.net' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_28',
            ) ,
            'imp.adsmogo.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_29',
            ) ,
            'imp.adsmogo.mobi' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_31',
            ) ,
            'das.api.mobile.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_32',
            ) ,
            'imp.adsmogo.org' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_33',
            ) ,
            'impservice.dictapp.youdao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_34',
            ) ,
            'ifacelog.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_43',
            ) ,
            'api.gamex.mobile.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_37',
            ) ,
            'kouyu.youdao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_38',
            ) ,
            'way.pptv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_40',
            ) ,
            'ad.app.autohome.com.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_42',
            ) ,
            'cfg.adsmogo.mobi' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_45',
            ) ,
            'ad.51wnl.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_46',
            ) ,
            'cfg.adsmogo.org' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_47',
            ) ,
            'track.adsage.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_48',
            ) ,
            'vapi.changba.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_51',
            ) ,
            'cfg.adsmogo.net' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_52',
            ) ,
            'applog.camera360.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_53',
            ) ,
            'ios.config.synacast.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_54',
            ) ,
            'sdk.mobad.ijinshan.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_55',
            ) ,
            'apimysong.changba.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_56',
            ) ,
            'api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_57',
            ) ,
            'config.adview.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_59',
            ) ,
            'pdata.video.qiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_90',
            ) ,
            'ad.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_61',
            ) ,
            'i.bid.limei.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_62',
            ) ,
            'a.pstatp.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_63',
            ) ,
            'ins.adsmogo.mobi' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_64',
            ) ,
            'ins.adsmogo.org' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_65',
            ) ,
            'bj1.smartcover.appdao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_67',
            ) ,
            'ins.adsmogo.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_68',
            ) ,
            'passport.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_69',
            ) ,
            'wx.houyi.baofeng.net' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_70',
            ) ,
            'ins.adsmogo.net' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_71',
            ) ,
            'api.tv.sohu.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_72',
            ) ,
            'search.api.3g.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_73',
            ) ,
            'bid.adview.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_76',
            ) ,
            'qq.irs01.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_75',
            ) ,
            'iospush.youdao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_77',
            ) ,
            'discover.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_78',
            ) ,
            'an.m.liebao.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_79',
            ) ,
            'sns.amap.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_80',
            ) ,
            'app.nuomi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_81',
            ) ,
            'dm.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_82',
            ) ,
            'timeline.api.changba.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_83',
            ) ,
            'w.bid.limei.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_84',
            ) ,
            'rec.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_85',
            ) ,
            'v1.ios.tj.itlily.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_86',
            ) ,
            'push.pptv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_87',
            ) ,
            'api.budejie.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_88',
            ) ,
            'ma.apptao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_89',
            ) ,
            'html.atm.youku.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_91',
            ) ,
            'plt.data.pplive.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_92',
            ) ,
            'bj1.pics.appdao.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_93',
            ) ,
            'ent.coolad.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_94',
            ) ,
            '115.28.21.142' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_95',
            ) ,
            'doota.meilishuo.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_96',
            ) ,
            'gapi.changba.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_97',
            ) ,
            'recommend.pptv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_98',
            ) ,
            'bs.da.hunantv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_99',
            ) ,
            'u.ttpod.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_100',
            ) ,
            'ark.letv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_101',
            ) ,
            'ios3.app.i4.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_102',
            ) ,
            'dynamic.app.m.letv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_103',
            ) ,
            'api.sina.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_104',
            ) ,
            'im.meilishuo.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_105',
            ) ,
            'l.rcd.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_106',
            ) ,
            'play.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_107',
            ) ,
            'vl.api.3g.tudou.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_108',
            ) ,
            'nl.rcd.iqiyi.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_109',
            ) ,
            'spark.api.xiami.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_110',
            ) ,
            'ios3.update.i4.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_111',
            ) ,
            'jt.rsscc.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_112',
            ) ,
            'passport.fanli.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_113',
            ) ,
            't.soquair.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_114',
            ) ,
            'cfg.adsmogo.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_115',
            ) ,
            'cldctrl.mobile.pptv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_116',
            ) ,
            'billing.uc.pptv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_117',
            ) ,
            'de.as.pptv.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_118',
            ) ,
            'conf.appwill.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_119',
            ) ,
            'sa.appwill.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_120',
            ) ,
            'ads.mp.mydas.mobi' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_121',
            ) ,
            '122.224.199.239' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_122',
            ) ,
            'req.adsmogo.org' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_123',
            ) ,
            'zad.zplay.cn' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_124',
            ) ,
            'bj1.smartlink.apphope.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_125',
            ) ,
            'online.dongting.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_126',
            ) ,
            'picspush.apphope.com' => array(
                'bidfa' => 'func_idfa',
                'appname' => 'idfa识别_127',
            ) ,
        );
    }


//获取参数
    public function getparam()
    {
        $this->saveError($argv[1] . 'par-2');
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

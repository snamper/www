<?php
/*
 * ��ʱ�ű� ÿ����ѯһ��
 * �ݶ�Ϊÿ������ʮһ��
 *
 * */
session_start();
include_once './include.inc/config.inc.php';
include_once './include.inc/global.inc.php';
include_once './include.inc/conn.db.inc.php';
include_once './include.inc/function.php';
include_once './include.inc/function_kpy.php';
$TIME = time();
$Count = 0; //��¼վ���������
$Flag = 0;  //�Ƿ�Ƕ��
$counts = 0; //����վ������
$timeout = 0;   //��������
$Organization_SiteData_URL  = 'http://120.27.198.189/web/api/kpy_api.php?mod=organization&action=sitelist';  //��֯��λ��ȡվ����Ϣ
$Reporting_SiteData_URL     = 'http://120.27.198.189/web/api/kpy_api.php?mod=site&action=sitelist';  //���λ��ȡվ����Ϣ
$Reporting_SiteData_UPDATA  = 'http://120.26.192.225:9081/cloud_backweb/yunOpenInfo_modifyOpenYunDetail.action';//�޸ĵ���վ����Ϣ
$Organization_SiteData_UPDATA   = 'http://120.26.192.225:9081/cloud_backweb/yunOpenInfo_modifyOpenYunInfo.action';//�޸����õ�λ��Ϣ

/*
 * �޸�ע��:
 *  ��֯��λ��¼����Ϣ �Ʒ��������id=�û���id �Ʒ��������id=վ���id
 *  ���λ��¼����Ϣ �Ʒ��������id=������id �Ʒ��������id=������id
 *
 * */
/*-----------------------------------------------------------��ʱ��ѯ�ű�-----------------------------------------------------------------------------*/
$O_sitecode = $Conn->query('select '.DB_PREFIX.'web.'.DB_PREFIX.'users.id,currentsitecode,expirtime from  '.DB_PREFIX.'web.'.DB_PREFIX.'users inner join '.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate on '.DB_PREFIX.'web.'.DB_PREFIX.'users.currentsitecode ='.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate.organizational group by currentsitecode')->fetchall();

foreach ($O_sitecode as $key => $value){
    if(!empty($value[currentsitecode])&&$value[currentsitecode]!='admin'){
        $Organization_SiteData[action] = 'sitelist';
        $Organization_SiteData[mod] = 'organization';
        $Organization_SiteData[faccounts] = $value[currentsitecode];
        $Organization_SiteData[timestamp] = time();
        $Organization_SiteData[sign] = getSign($Organization_SiteData[mod], $Organization_SiteData[action], $Organization_SiteData[timestamp], $Organization_SiteData);
        $Result = postcurl($Organization_SiteData_URL, $Organization_SiteData, 1);
        $Response = jsondecode($Result);
        if($Response[code] == '0'){     //���ݷ����������ж�
            var_dump($Response);
            foreach ($Response[data] as $key => $val){
                if($val[pv]!='0'||$val[today_pv]!='0'){ //�ж��Ƿ��������  ������ڴ����Ѿ�����, �����Ƽ�ܽӿ��޸�����
                    $website[]=$val[$website];
                    $Count+=1
                }
                $counts+=1;
            }
            //���ݷ��ص����ݲ�ѯ���Ӧվ�����Ч�� Ȼ���޸ĵ���վ����Ϣ

die;
        }
      /*  $Organization_Site[id]=$Value[id];   //�û���id
        $Organization_Site[useDate]='';     //��������
        $Organization_Site[serviceEndDate]='';  //�����ֹ����
        $Organization_Site[inCodeCondition]=$Count.'/'.$counts; //Ƕ�����
        $Organization_Site[inCodeDate]='--';    //Ƕ������
        $Organization_Site[isOrg]=1;    //�Ƿ�����֯��λ
        $Organization_Site[is_cost]='0';    //�Ƿ�ǩ��
        $Organization_Site[siteNum]=$counts;    //����վ������
        $Organization_Site[endTask]=$timeout.'/'.$counts;   //��������
        $Organization_Site  = json_encode($Organization_Site);
        $Ress = encrypt('ucap2016',$Organization_Site);
        $O_data = "{'encryptData':'".$Res."'}";
         $Result = postcurl_json($Organization_SiteData_UPDATA, $O_data, 1);
        var_dump($Result);*/
    }
}
$sitedata = $Conn->query()->fetchall('select '.DB_PREFIX.'web.'.DB_PREFIX.'site.id as s_id,expirtime from  '.DB_PREFIX.'web.'.DB_PREFIX.'site inner join '.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate on '.DB_PREFIX.'web.'.DB_PREFIX.'site.managerid ='.DB_PREFIX.'web.'.DB_PREFIX."user_expirydate.reportunit where  managerid in ($website)");

echo 'select * from  '.DB_PREFIX.'web.'.DB_PREFIX.'site inner join '.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate on '.DB_PREFIX.'web.'.DB_PREFIX.'site.managerid ='.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate.reportunit where  '.DB_PREFIX.'web.'.DB_PREFIX."site.website in ($websites)".'and '.DB_PREFIX.'web.'.DB_PREFIX."site.website in ($websites)";die;
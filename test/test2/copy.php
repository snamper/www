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
/*$CurrentSiteCode = $Conn->query('select *,'.DB_PREFIX.'web.'.DB_PREFIX.'users.id as o_id,'.DB_PREFIX.'web.'.DB_PREFIX.'site.id as s_id from  '.DB_PREFIX.'web.'.DB_PREFIX.'users left join '.DB_PREFIX.'web.'.DB_PREFIX.'site on '.DB_PREFIX.'web.'.DB_PREFIX.'users.manager ='.DB_PREFIX.'web.'.DB_PREFIX.'site.managerid inner join '.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate on '.DB_PREFIX.'web.'.DB_PREFIX.'users.manager ='.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate.reportunit ')->fetchall();*/
$CurrentSiteCode = $Conn->query('select *,'.DB_PREFIX.'web.'.DB_PREFIX.'users.id as o_id,'.DB_PREFIX.'web.'.DB_PREFIX.'site.id as s_id from  '.DB_PREFIX.'web.'.DB_PREFIX.'users left join '.DB_PREFIX.'web.'.DB_PREFIX.'site on '.DB_PREFIX.'web.'.DB_PREFIX.'users.manager ='.DB_PREFIX.'web.'.DB_PREFIX.'site.managerid left join '.DB_PREFIX.'web.'.DB_PREFIX.'user_expirydate on '.DB_PREFIX.'web.'.DB_PREFIX.'users.manager ='.DB_PREFIX.'web.'.DB_PREFIX."user_expirydate.reportunit where currentsitecode = 'bm2914'")->fetchall();
foreach ($CurrentSiteCode as $Key => $Value){
    if(!empty($Value[currentsitecode]&&$Value[manager]!='admin')){
        $Organization_SiteData[action] = 'sitelist';
        $Organization_SiteData[mod] = 'organization';
        $Organization_SiteData[faccounts] = $Value[currentsitecode];
        $Organization_SiteData[timestamp] = time();
        $Organization_SiteData[sign] = getSign($Organization_SiteData[mod], $Organization_SiteData[action], $Organization_SiteData[timestamp], $Organization_SiteData);
        $Result = postcurl($Organization_SiteData_URL, $Organization_SiteData, 1);
        $Response = jsondecode($Result);
        if($Response[code] == '0'){     //���ݷ����������ж�
            foreach ($Response[data] as $key => $val){

                if($val[pv]!='0'||$val[today_pv]!='0'){ //�ж��Ƿ��������  ������ڴ����Ѿ�����, �����Ƽ�ܽӿ��޸�����
                    $Count+=1;
                    $Flag=1;
                    $CODE_TIME = time();
                    /*-------------------------------------------���ýӿ��޸�����--------------------------------------------------------*/
                    $Reporting_SiteData[id]=$Value[s_id]; //վ��id
                    $Reporting_SiteData[loadJsUrl]='';
                    $Reporting_SiteData[isInCode]=$Flag;
                    $Reporting_SiteData[inCodeDate]= empty($CODE_TIME)?'--':$CODE_TIME;
                    $Reporting_SiteData[endDate]='';
                    $Reporting_SiteData[state]=1;
                    $Reporting_SiteData  = json_encode($Reporting_SiteData);
                    $Res = encrypt('ucap2016',$Reporting_SiteData);
                    $R_data = "{'encryptData':'".$Res."'}";
                    //$Result = postcurl_json($Reporting_SiteData_UPDATA, $R_data, 1);
                    //var_dump($Result);
                }
                $counts+=1;
            }
        }
        if(time()>$Value[expirtime]){   //�жϹ�������
            $timeout+=1;
        }
        /*-----------------�޸ĵ���վ����Ϣ---------------------------------------------------*/

        /*-----------------�޸����õ�λ��Ϣ---------------------------------------------------*/
        if($Value[managerid] == $Value[currentsitecode]){
            echo 1;
            $Organization_Site[id]=$Value[o_id];    //������id
            $Organization_Site[useDate]='';
            $Organization_Site[serviceEndDate]='';
            $Organization_Site[inCodeCondition]=$Count.'/'.$counts;
            $Organization_Site[inCodeDate]='--';
            $Organization_Site[isOrg]=1;
            $Organization_Site[is_cost]='0';
            $Organization_Site[siteNum]=$counts;
            $Organization_Site[endTask]=$timeout.'/'.$counts;   //��������
            $Organization_Site  = json_encode($Organization_Site);
            $Ress = encrypt('ucap2016',$Organization_Site);
            $O_data = "{'encryptData':'".$Res."'}";
           // $Result = postcurl_json($Organization_SiteData_UPDATA, $O_data, 1);
            //var_dump($Result);
        }
    }
}
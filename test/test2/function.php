<?php

 /*********************************************循环读取目录及其文件**************************************************************/ 
function file_list($path){  
    if ($handle = opendir($path))//打开路径成功  
    {  
        while (false !== ($file = readdir($handle)))//循环读取目录中的文件名并赋值给$file  
        {  
            if ($file != "." && $file != "..")//排除当前路径和前一路径  
            {  
                if (is_dir($path."/".$file))  
                {  
                    //echo $path.": ".$file."<br>";//去掉此行显示的是所有的非目录文件  
                    file_list($path."/".$file);  
                }  
                else  
                {  
					//include_once	"$path/$file";		此处加载文件
                   // echo $path."/".$file."<br>";  
                }  
            }  
        }  
    }  
}
//file_list('E:/wamp/www/test');

/*******************循环创建文件及文件夹********************/ 
function mkdirs($path){
	/*
		  
	*/
	file_put_contents($path.'index.lock','this dir is aleardy mkdir');
	for($i=1;$i<=10;$i++){	
		$file=$path.date('YmdHis').$i;
		
		if(!file_exists($file)){
			mkdir($file);
		}
		for($j=1;$j<=10;$j++){	
			$con=file_get_contents('./function.php');
			//file_put_contents($path.'/'.$j.'.html',$con);
			file_put_contents($file.'/'.$j.'.php',$con);
		}
	}
}

/*******************随机读取国内ip********************/ 
function rand_ip(){
	$ip_long = array(
		array('607649792', '608174079'), //36.56.0.0-36.63.255.255
		array('975044608', '977272831'), //58.30.0.0-58.63.255.255
		array('999751680', '999784447'), //59.151.0.0-59.151.127.255
		array('1019346944', '1019478015'), //60.194.0.0-60.195.255.255
		array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
		array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
		array('1947009024', '1947074559'), //116.13.0.0-116.13.255.255
		array('1987051520', '1988034559'), //118.112.0.0-118.126.255.255
		array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
		array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
		array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
		array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
		array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
		array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
		array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
	);
	$rand_key = mt_rand(0, 14);
	$huoduan_ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
	return $huoduan_ip;
}


/*********************************************人民币数字转中文**************************************************************/  
//
function cny($ns) {
	static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"), 
	$cnyunits = array("圆","角","分"), 
	$grees = array("拾","佰","仟","万","拾","佰","仟","亿"); 
	list($ns1,$ns2) = explode(".",$ns,2); 
	$ns2 = array_filter(array($ns2[1],$ns2[0])); 
	$ret = array_merge($ns2,array(implode("", _cny_map_unit(str_split($ns1), $grees)), "")); 
	$ret = implode("",array_reverse(_cny_map_unit($ret,$cnyunits))); 
	return str_replace(array_keys($cnums), $cnums,$ret); 
}
function _cny_map_unit($list,$units) { 
	$ul = count($units); 
	$xs = array(); 
	foreach (array_reverse($list) as $x){ 
		$l = count($xs); 
		if($x!="0" || !($l%4)){
			$n=($x=='0'?'':$x).($units[($l-1)%$ul]); 
		}
		else{
			$n=is_numeric($xs[0][0]) ? $x : ''; 
		}
		array_unshift($xs, $n); 
	} 
	return $xs; 
}
//$value='23058.04';
//print cny($value);


/************易分析秘钥解密******************/  
function display_file_code($string = '', $skey = 'phpstat_license_file') {
        $strArr = str_split(str_replace(array('O01O0O', 'o0200o', 'oo030o'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
}


/************易分析秘钥加密******************/  
function fetch_file_code($string = '', $skey = 'phpstat_license_file') {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return str_replace(array('=', '+', '/'), array('O01O0O', 'o0200o', 'oo030o'), join('', $strArr));
}
//echo fetch_file_code('218.17.83.66:8000');
//echo display_file_code('MpjhEp4sLtjaEt3_LljigczeLnjsYe2_OfjiglweMDAO01O0O');


/************按时间顺序输出文件夹中的文件******************/  
function dir_size($dir,$url) {  
	$dh = @opendir ( $dir ); // 打开目录，返回一个目录流  
	$return = array ();  
	$i = 0;  
	while ( $file = @readdir ( $dh ) ) { // 循环读取目录下的文件  
		if ($file != '.' and $file != '..') {  
			$path = $dir . '/' . $file; // 设置目录，用于含有子目录的情况  
			if (is_dir ( $path )) {  
				echo 1;
			} elseif (is_file ( $path )) {   
				$filetime [] = date ( "Y-m-d H:i:s", filemtime ( $path ) ); // 获取文件最近修改日期   
			    $return [] = $url . '/' . $file;  
			}  
		}  
	}
	@closedir ( $dh ); // 关闭目录流   
	array_multisort($filetime,SORT_DESC,SORT_STRING, $return);//按时间排序  
	return $return; // 返回文件  
}  
//define('path',"E:/wamp/www/test/");   
//define('dirRoot',"../../..common/upload_thumb");   
//$thumbsNames=dir_size(path,dirRoot);
//var_dump($thumbsNames);   


/************PDO链接数据库******************/  
function pdo($localhost,$dbname,$username,$password){
	try {
		 $dbh = new PDO("mysql:host=$localhost;dbname=$dbname", $username, $password);
	} catch (PDOException $e) {
		 print "Error!: " . $e->getMessage() . "<br/>";
	}
}
/************获取url参数******************/ 
function Get_url($url){
    parse_str((parse_url($url)['query']),$arr);
    return $arr;
}
function postcurl($url,$params=false,$ispost=0){
	    $httpInfo = array();
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_0 );
		curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
		curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
		if( $ispost )
		{
			curl_setopt( $ch , CURLOPT_POST , true );
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
			curl_setopt( $ch , CURLOPT_URL , $url );
		}
		else
		{
			if($params){
				curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
			}else{
				curl_setopt( $ch , CURLOPT_URL , $url);
			}
		}
		$response = curl_exec( $ch );
		if ($response === FALSE) {
			#"cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
		$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
		curl_close( $ch );
		return $response;
	}
	//--------------------  
// 基本数据结构算法 
//--------------------  
//二分查找（数组里查找某个元素）  
function bin_sch($array,  $low, $high, $k){   
    if ( $low <= $high){   
        $mid =  intval(($low+$high)/2 );   
        if ($array[$mid] ==  $k){   
            return $mid;   
        }elseif ( $k < $array[$mid]){   
            return  bin_sch($array, $low,  $mid-1, $k);   
        }else{   
            return  bin_sch($array, $mid+ 1, $high, $k);   
        }   
    }   
    return -1;   
}   
//顺序查找（数组里查找某个元素）  
function  seq_sch($array, $n,  $k){   
    $array[$n] =  $k;   
    for($i=0;  $i<$n; $i++){   
        if( $array[$i]==$k){   
            break;   
        }   
    }   
    if ($i<$n){   
        return  $i;   
    }else{   
        return -1;   
    }   
}   
//线性表的删除（数组中实现）  
function delete_array_element($array , $i)  
{  
        $len =  count($array);   
        for ($j= $i; $j<$len; $j ++){  
                $array[$j] = $array [$j+1];  
        }  
        array_pop ($array);  
        return $array ;  
}  
//冒泡排序（数组排序）  
function bubble_sort( $array)  
{  
        $count = count( $array);  
        if ($count <= 0 ) return false;  
        for($i=0 ; $i<$count; $i ++){  
                for($j=$count-1 ; $j>$i; $j--){  
                        if ($array[$j] < $array [$j-1]){  
                                 $tmp = $array[$j];  
                                 $array[$j] = $array[ $j-1];  
                                $array [$j-1] = $tmp;  
                        }  
                }  
        }  
        return $array;  
}  
//快速排序（数组排序）  
function quick_sort($array ) {  
        if (count($array) <= 1) return  $array;  
        $key = $array [0];  
        $left_arr  = array();  
        $right_arr = array();  
        for ($i= 1; $i<count($array ); $i++){  
                if ($array[ $i] <= $key)  
                        $left_arr [] = $array[$i];  
                else  
                         $right_arr[] = $array[$i ];  
        }  
        $left_arr = quick_sort($left_arr );  
        $right_arr = quick_sort( $right_arr);  
        return array_merge($left_arr , array($key), $right_arr);  
}  

//------------------------  
// PHP内置字符串函数实现  
//------------------------  
//字符串长度  
//function strlen ($str)
//{
//        if ($str == '' ) return 0;
//        $count =  0;
//        while (1){
//                if ( $str[$count] != NULL){
//                         $count++;
//                        continue;
//                }else{
//                        break;
//                }
//        }
//        return $count;
//}
//截取子串  
//function substr($str, $start,  $length=NULL)
//{
//        if ($str== '' || $start>strlen($str )) return;
//        if (($length!=NULL) && ( $start>0) && ($length> strlen($str)-$start)) return;
//        if (( $length!=NULL) && ($start< 0) && ($length>strlen($str )+$start)) return;
//        if ($length ==  NULL) $length = (strlen($str ) - $start);
//
//        if ($start <  0){
//                for ($i=(strlen( $str)+$start); $i<(strlen ($str)+$start+$length ); $i++) {
//                        $substr .=  $str[$i];
//                }
//        }
//        if ($length  > 0){
//                for ($i= $start; $i<($start+$length ); $i++) {
//                        $substr  .= $str[$i];
//                }
//        }
//        if ( $length < 0){
//                for ($i =$start; $i<(strlen( $str)+$length); $i++) {
//                        $substr .= $str[$i ];
//                }
//        }
//        return $substr;
//}
////字符串翻转
//function strrev($str)
//{
//        if ($str == '') return 0 ;
//        for ($i=(strlen($str)- 1); $i>=0; $i --){
//                $rev_str .= $str[$i ];
//        }
//        return $rev_str;
//}
//
////字符串比较
//function strcmp($s1,  $s2)
//{
//        if (strlen($s1) <  strlen($s2)) return -1 ;
//        if (strlen($s1) > strlen( $s2)) return 1;
//        for ($i =0; $i<strlen($s1 ); $i++){
//                if ($s1[ $i] == $s2[$i]){
//                        continue;
//                }else{
//                        return false;
//                }
//        }
//        return  0;
//}
//
////查找字符串
//function  strstr($str, $substr)
//{
//         $m = strlen($str);
//        $n = strlen($substr );
//        if ($m < $n) return false ;
//        for ($i=0; $i <=($m-$n+1); $i ++){
//                $sub = substr( $str, $i, $n);
//                if ( strcmp($sub, $substr) ==  0)  return $i;
//        }
//        return false ;
//}
////字符串替换
//function str_replace($substr , $newsubstr, $str)
//{
//         $m = strlen($str);
//        $n = strlen($substr );
//        $x = strlen($newsubstr );
//        if (strchr($str, $substr ) == false) return false;
//        for ( $i=0; $i<=($m- $n+1); $i++){
//                 $i = strchr($str,  $substr);
//                $str = str_delete ($str, $i, $n);
//                $str = str_insert($str,  $i, $newstr);
//        }
//        return $str ;
//}

//--------------------  
// 自实现字符串处理函数 
//--------------------  
//插入一段字符串  
function str_insert($str, $i , $substr)  
{  
        for($j=0 ; $j<$i; $j ++){  
                $startstr .= $str[$j ];  
        }  
        for ($j=$i; $j <strlen($str); $j ++){  
                $laststr .= $str[$j ];  
        }  
        $str = ($startstr . $substr  . $laststr);  
        return $str ;  
}  
//删除一段字符串  
function str_delete($str , $i, $j)  
{  
        for ( $c=0; $c<$i;  $c++){  
                $startstr .= $str [$c];  
        }  
        for ($c=( $i+$j); $c<strlen ($str); $c++){  
                $laststr  .= $str[$c];  
        }  
         $str = ($startstr . $laststr );  
        return $str;  
}  
//复制字符串  
function strcpy($s1, $s2 )  
{  
        if (strlen($s1)==NULL || !isset( $s2)) return;  
        for ($i=0 ; $i<strlen($s1);  $i++){  
                $s2[] = $s1 [$i];  
        }  
        return $s2;  
}  
//连接字符串  
function strcat($s1 , $s2)  
{  
        if (!isset($s1) || !isset( $s2)) return;  
        $newstr = $s1 ;  
        for($i=0; $i <count($s); $i ++){  
                $newstr .= $st[$i ];  
        }  
        return $newsstr;  
}  
//简单编码函数（与php_decode函数对应）  
function php_encode($str)  
{  
        if ( $str=='' && strlen( $str)>128) return false;  
        for( $i=0; $i<strlen ($str); $i++){  
                 $c = ord($str[$i ]);  
                if ($c>31 && $c <107) $c += 20 ;  
                if ($c>106 && $c <127) $c -= 75 ;  
                $word = chr($c );  
                $s .= $word;  
        }   
        return $s;   
}  
//简单解码函数（与php_encode函数对应）  
function php_decode($str)  
{  
        if ( $str=='' && strlen($str )>128) return false;  
        for( $i=0; $i<strlen ($str); $i++){  
                $c  = ord($word);  
                if ( $c>106 && $c<127 ) $c = $c-20;  
                if ($c>31 && $c< 107) $c = $c+75 ;  
                $word = chr( $c);  
                $s .= $word ;  
        }   
        return $s;   
}  
//简单加密函数（与php_decrypt函数对应）  
function php_encrypt($str)  
{  
         $encrypt_key = 'abcdefghijklmnopqrstuvwxyz1234567890';  
         $decrypt_key = 'ngzqtcobmuhelkpdawxfyivrsj2468021359';  
        if ( strlen($str) == 0) return  false;  
        for ($i=0;  $i<strlen($str); $i ++){  
                for ($j=0; $j <strlen($encrypt_key); $j ++){  
                        if ($str[$i] == $encrypt_key [$j]){  
                                $enstr .=  $decrypt_key[$j];  
                                break;  
                        }  
                }  
        }  
        return $enstr;  
}  
//简单解密函数（与php_encrypt函数对应）  
function php_decrypt($str)  
{  
         $encrypt_key = 'abcdefghijklmnopqrstuvwxyz1234567890';  
         $decrypt_key = 'ngzqtcobmuhelkpdawxfyivrsj2468021359';  
        if ( strlen($str) == 0) return  false;  
        for ($i=0;  $i<strlen($str); $i ++){  
                for ($j=0; $j <strlen($decrypt_key); $j ++){  
                        if ($str[$i] == $decrypt_key [$j]){  
                                $enstr .=  $encrypt_key[$j];  
                                break;  
                        }  
                }  
        }  
        return $enstr;  
}  

function encrypt ($key, $encrypt)
{
	// 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 加入 Padding
	$block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
	$pad = $block - (strlen($encrypt) % $block);
	$encrypt .= str_repeat(chr($pad), $pad);
	// 不需要設定 IV 進行加密
	$passcrypt = mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB);
	return base64_encode($passcrypt);
}
function decrypt ($key, $decrypt)
{
	// 不需要設定 IV
	$str = mcrypt_decrypt(MCRYPT_DES, $key, base64_decode($decrypt), MCRYPT_MODE_ECB);

	// 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 移除 Padding
	$pad = ord($str[strlen($str) - 1]);
	return substr($str, 0, strlen($str) - $pad);
}
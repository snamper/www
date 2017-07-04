<?php

$str = 'RhV5oXZy0PY';
function decrypt ($key, $decrypt)
	{
	    // 不需要設定 IV
	    $str = mcrypt_decrypt(MCRYPT_DES, $key, base64_decode($decrypt), MCRYPT_MODE_ECB);

	    // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 移除 Padding
	    $pad = ord($str[strlen($str) - 1]);
	    return substr($str, 0, strlen($str) - $pad);
	}
///$key = 'ucap2016';
//$result = decrypt($key,$str);
//$site = json_decode($result,true);
//var_dump($site);
echo "<pre>";
$arr= file_get_contents('./data.txt');
var_export($arr);
?>
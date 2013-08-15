<?php 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$file_misc_arr = array();
if($file_misc_arr = file(DISCUZ_ROOT . "misc.php")){
    $patten_require = '/require\s*\'\.\/source\/class\/class_core\.php\';/';
    $patten_mod = '/\'tag\'\s*,\s*\'diyhelp\'\s*,\s*\'mobile\'\s*,\s*\'patch\'\s*,\s*\'getatuser\'\s*,\s*\'imgcropper\'\s*,\s*/';
    $is_change = 0;
    foreach($file_misc_arr as $k=>$v){
        if(preg_match($patten_require, $v)){
            $arr_insert = array();
            $arr_insert[0] = '//Begin Of Inserted Code' . "\r\n";
            $arr_insert[1] = "require './source/function/function_qiniu.php';" . "\r\n";
            $arr_insert[2] = '//End Of Inserted Code' . "\r\n";
            array_splice($file_misc_arr, $k+1, 0, $arr_insert);
            $is_change++;
            break;
        }
    }
    foreach($file_misc_arr as $k=>$v){
        if(preg_match($patten_mod, $v)){
            $arr_insert = array();
            $arr_insert[0] = '//Begin Of Inserted Code' . "\r\n";
            $arr_insert[1] = "\t\t\t\t" . '\'uploadcallback\',' . "\r\n";
            $arr_insert[2] = '//End Of Inserted Code' . "\r\n";
            array_splice($file_misc_arr, $k+1, 0, $arr_insert);
            $is_change++;
            break;
        }
    }
    if($is_change>1){
        $file_misc_str = implode("", $file_misc_arr);
        if($fp = fopen(DISCUZ_ROOT . "/misc.php", 'w+')){
            fwrite($fp, $file_misc_str);
            fclose($fp);
        }
    }
}

$file_forum_arr = array();
if($file_forum_arr = file(DISCUZ_ROOT . "/forum.php")){
    $patten_require = '/require\s*\'\.\/source\/function\/function_forum\.php\';/';
    $is_change = 0;
    foreach($file_forum_arr as $k=>$v){
        if(preg_match($patten_require, $v)){
            $arr_insert = array();
            $arr_insert[0] = '//Begin Of Inserted Code' . "\r\n";
            $arr_insert[1] = "require_once './source/function/function_qiniu.php';" . "\r\n";
            $arr_insert[2] = '//End Of Inserted Code' . "\r\n";
            array_splice($file_forum_arr, $k+1, 0, $arr_insert);
            $is_change++;
            break;
        }
    }
    if($is_change>0){
        $file_forum_str = implode("", $file_forum_arr);
        if($fp = fopen(DISCUZ_ROOT . "/forum.php", 'w+')){
            fwrite($fp, $file_forum_str);
            fclose($fp);
        }
    }
}

$file_forum_image_arr = array();
if($file_forum_image_arr = file(DISCUZ_ROOT . "/source/module/forum/forum_image.php")){
    $patten = '/\$filename\s*=\s*\$_G\[\'setting\'\]\[\'attachdir\'\]/';
    $is_change = 0;
    foreach($file_forum_image_arr as $k=>$v){
        if(preg_match($patten, $v)){
            $arr_insert = array();
            $arr_insert[0] = '//Begin Of Inserted Code' . "\r\n";
            $arr_insert[1] = "\t\t" . 'loadcache(\'plugin\');' . "\r\n";
            $arr_insert[2] = "\t\t" . '$qiniu_switch = $_G[\'cache\'][\'plugin\'][\'dz_qiniu_upload\'][\'switch\'];' . "\r\n";
            $arr_insert[3] = "\t\t" . '$file_prefix = $qiniu_switch ? $_G[\'cache\'][\'plugin\'][\'dz_qiniu_upload\'][\'domain\'] .\'/\' . $_G[\'cache\'][\'plugin\'][\'dz_qiniu_upload\'][\'prefix\'] . \'/\' : $_G[\'setting\'][\'attachdir\'];'  . "\r\n";
            $arr_insert[4] = "\t\t" . '$filename = $file_prefix .\'forum/\'.$attach[\'attachment\'];' . "\r\n";
            $arr_insert[5] = '//End Of Inserted Code' . "\r\n";
            array_splice($file_forum_image_arr, $k, 1, $arr_insert);
            $is_change++;
            break;
        }
    }
    if($is_change>0){
        $file_forum_image_str = implode("", $file_forum_image_arr);
        if($fp = fopen(DISCUZ_ROOT . "/source/module/forum/forum_image.php", 'w+')){
            fwrite($fp, $file_forum_image_str);
            fclose($fp);
        }
    }
}

$finish = true;

?>

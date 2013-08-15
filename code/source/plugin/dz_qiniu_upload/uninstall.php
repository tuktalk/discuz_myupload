<?php 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$file_misc_arr = array();
if($file_misc_arr = file(DISCUZ_ROOT . "/misc.php")){
    $is_change = 0;
    foreach($file_misc_arr as $k=>$v){
        if(strpos($v, 'Begin Of Inserted Code')){
            $arr_insert = array();
            array_splice($file_misc_arr, $k, 3, $arr_insert);
            $is_change++;
            break;
        }
    }
    foreach($file_misc_arr as $k=>$v){
        if(strpos($v, 'Begin Of Inserted Code')){
            $arr_insert = array();
            array_splice($file_misc_arr, $k, 3, $arr_insert);
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
    $is_change = 0;
    foreach($file_forum_arr as $k=>$v){
        if(strpos($v, 'Begin Of Inserted Code')){
            $arr_insert = array();
            array_splice($file_forum_arr, $k, 3, $arr_insert);
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
    $is_change = 0;
    foreach($file_forum_image_arr as $k=>$v){
        if(strpos($v, 'Begin Of Inserted Code')){
            $arr_insert = array();
            $arr_insert[0] = "\t\t" . '$filename = $_G[\'setting\'][\'attachdir\'].\'forum/\'.$attach[\'attachment\'];' . "\r\n";
            array_splice($file_forum_image_arr, $k, 6, $arr_insert);
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

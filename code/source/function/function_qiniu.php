<?php 

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require_once("qiniu/rs.php");

function getuptoken() {
    global $_G;
    $bucket = $_G['config']['qiniu']['bucket'];
    $accessKey = $_G['config']['qiniu']['accesskey'];
    $secretKey = $_G['config']['qiniu']['secretkey'];
    Qiniu_SetKeys($accessKey, $secretKey);
    $putPolicy = new Qiniu_RS_PutPolicy($bucket);        
    $putPolicy->CallbackUrl = $_G['config']['qiniu']['callbackurl'];
    $putPolicy->CallbackBody = $_G['config']['qiniu']['callbackbody'];
    $upToken = $putPolicy->Token(null);
    return $upToken; 
}

function upload_rename($type, $src_key, $ext='', $dest_key='', $extid = 0) {    
    global $_G;    
    $bucket = $_G['config']['qiniu']['bucket'];
    $accessKey = $_G['config']['qiniu']['accesskey'];
    $secretKey = $_G['config']['qiniu']['secretkey'];
    Qiniu_SetKeys($accessKey, $secretKey);
    $client = new Qiniu_MacHttpClient(null);        
    
    list($exist_ret, $exists_err) = Qiniu_RS_Stat($client, $bucket, $src_key);              
    if ($exists_err != null) {
        return 31;
    }                   
    
    while ($dest_key == '') {
        list($newkey, $attachment) = upload_makekey($type, $extid, $ext);    
        list($ret, $newkey_err) = Qiniu_RS_Stat($client, $bucket, $newkey);
        if ($newkey_err != null) {
            $dest_key = $newkey;
        }
    }
        
    $rename_err = Qiniu_RS_Move($client, $bucket, $src_key, $bucket, $dest_key);
    if ($err != null) {
        return 32;
    } else {
        return $attachment;
    }
}

function upload_makekey($type, $extid = 0, $ext = '', $forcename = '') {
    global $_G;
    if($type == 'group' || ($type == 'common' && $forcename != '')) {
        $filename = $type.'_'.intval($extid).($forcename != '' ? "_$forcename" : '');
    } else {
        $filename = date('His').strtolower(random(16));
    }
    $prefix = $_G['config']['qiniu']['prefix'] ? $_G['config']['qiniu']['prefix'] . '/' : '';   
    $type = $type ? $type . '/' : '';
    $ext = $ext ? '.' . $ext : '';
    $attachment = date('Ym') . '/' . date('d') . '/' . $filename . $ext;
    return array($prefix . $type . $attachment, $attachment);
}

?>

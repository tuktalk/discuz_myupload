<?php


$_config = array();

// ---------------------------  QINIU CLOUD  --------------------------- //
$_config['qiniu']['switch'] = 1;
$_config['qiniu']['up_host'] = 'http://up.qiniu.com/';
$_config['qiniu']['domain'] = 'http://<your-bucket>.qiniudn.com/';
$_config['qiniu']['bucket'] = '<your-bucket>';
$_config['qiniu']['accesskey'] = '<your-access-key>';
$_config['qiniu']['secretkey'] = '<your-secret-key>';
$_config['qiniu']['prefix'] = 'data/attachment';
$_config['qiniu']['callbackurl'] = 'http://<your-host>/myupload.php?mod=uploadcallback&operation=upload';
$_config['qiniu']['callbackbody'] = 'fid=$(x:fid)&uid=$(x:uid)&hash=$(x:hash)&filename=$(fname)&filesize=$(fsize)&mimetype=$(mimeType)&exif=$(exif)&width=$(imageInfo.width)&attachment=$(etag)&type=$(x:type)';

// -------------------  THE END  -------------------- //

?>

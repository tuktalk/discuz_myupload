<?php 

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class forum_uploadcallback {
    
    var $uid;
    var $aid;
    var $simple;
    var $statusid;
    #var $attach;
    var $filename;    
    var $isimage;
    var $attachment;
    var $error_sizelimit;
    var $getaid;

    function forum_uploadcallback($getaid=0) {
        global $_G;        
        
        $_G['uid'] = $this->uid = intval($_GET['uid']);        
        $swfhash = md5(substr(md5($_G['config']['security']['authkey']), 8) . $this->uid);
        $this->aid = 0;
        $this->getaid = $getaid;
        $this->simple = !empty($_GET['simple']) ? $_GET['simple'] : 0;      
        
        #operation poll  
        
        #hash验证
        if($_GET['hash'] != $swfhash) {
            return $this->uploadmsg(10);
        }
        
        #attachment
        if (empty($_GET['attachment'])) {
            return $this->uploadmsg(21);#todo
        }
        
        $this->filename = $filename = diconv(urldecode($_GET['filename']), 'UTF-8');    
        $fileext = $this->getfileext($filename);
        if (empty($fileext)) {
            return $this->uploadmsg(22);#todo
        }    
        $filesize = intval($_GET['filesize']); 
        $mimetype = $_GET['mimetype'];
        $this->isimage = $isimage = count(explode('image', $mimetype)) > 1;
        
        $etag = $_GET['attachment'];
        $this->attachment = $attachment = upload_rename('forum', $etag, $fileext); 
        
        if (!is_string($attachment)) {
            return $this->uploadmsg($attachment);
        }
                
        #upload限制包含：
        #用户当天上传附件总个数
        $allowupload = !$_G['group']['maxattachnum'] || $_G['group']['maxattachnum'] && $_G['group']['maxattachnum'] > getuserprofile('todayattachs');
        if(!$allowupload) {
            return $this->uploadmsg(6);
        }

        #文件后缀名        
        if($_G['group']['attachextensions'] && (!preg_match("/(^|\s|,)".preg_quote($fileext, '/')."($|\s|,)/i", $_G['group']['attachextensions']) || !$fileext)) {
            return $this->uploadmsg(1);
        }
        
        #空文件        
        if(empty($filesize)) {
            return $this->uploadmsg(2);
        }

        #单个文件大小限制
        if($_G['group']['maxattachsize'] && $filesize > $_G['group']['maxattachsize']) {
            $this->error_sizelimit = $_G['group']['maxattachsize'];
            return $this->uploadmsg(3);
        }

        #文件类型对应的大小限制
        loadcache('attachtype');
        if($_G['fid'] && isset($_G['cache']['attachtype'][$_G['fid']][$fileext])) {
            $maxsize = $_G['cache']['attachtype'][$_G['fid']][$fileext];
        } elseif(isset($_G['cache']['attachtype'][0][$fileext])) {
            $maxsize = $_G['cache']['attachtype'][0][$fileext];
        }
        if(isset($maxsize)) {
            if(!$maxsize) {
                $this->error_sizelimit = 'ban';
                return $this->uploadmsg(4);
            } elseif($filesize > $maxsize) {
                $this->error_sizelimit = $maxsize;
                return $this->uploadmsg(5);
            }
        }

        #用户当天上传的大小限制
        if($filesize && $_G['group']['maxsizeperday']) {
            $todaysize = getuserprofile('todayattachsize') + $filesize;
            if($todaysize >= $_G['group']['maxsizeperday']) {
                $this->error_sizelimit = 'perday|'.$_G['group']['maxsizeperday'];
                return $this->uploadmsg(11);
            }
        }

        #更新用户当天上传个数、大小记录
        updatemembercount($_G['uid'], array('todayattachs' => 1, 'todayattachsize' => $filesize));
        
        if($_GET['type'] == 'image' && !$isimage) {
            return $this->uploadmsg(7);
        }

        $thumb = $width = 0;
        $remote = 0;
        #针对图像文件处理        
        if ($isimage) {
            if ($_G['setting']['showexif']) {
                $exif = !empty($_GET['exif']) ? $_GET['exif'] : '';
            }
            $width = !empty($_GET['width']) ? intval($_GET['width']) : 0;
        }

        #生成缩略
        #todo
        
        #分配attachid
        $this->aid = $aid = getattachnewaid($this->uid);
                
        if($_GET['type'] != 'image' && $isiamge) {
            $insert_isiamge = -1;
        } else {
            $insert_isimage = $isimage;
        }
           
        $insert = array(
            'aid' => $aid,
            'uid' => $this->uid,
            'dateline' => $_G['timestamp'],
            'filename' => dhtmlspecialchars(censor($filename)),
            'filesize' => $filesize,
            'attachment' => $attachment,
            'isimage' => $insert_isimage,
            'thumb' => $thumb,
            'remote' => $remote,
            'width' => $width,
        );        
        #插入unused记录
        C::t('forum_attachment_unused')->insert($insert);
        #插入exif记录
        if($isimage && $_G['setting']['showexif']) {
            C::t('forum_attachment_exif')->insert($aid, $exif);
        }
        return $this->uploadmsg(0);

    }

    function getfileext($filename) {        
        return strrchr($filename, '.') ? addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10))) : "";
    }

    function uploadmsg($statusid) {
        header('Content-Type: application/json');
        #返回错误信息
        global $_G;
        $this->error_sizelimit = !empty($this->error_sizelimit) ? $this->error_sizelimit : 0;
        if($this->getaid) {
            $this->getaid = $statusid ? -$statusid : $this->aid;
            return;
        }
        if($this->simple == 1) {
            #echo 'DISCUZUPLOAD|'.$statusid.'|'.$this->aid.'|'.$this->attach['isimage'].'|'.$this->error_sizelimit;
            echo 'simple:1' . -$statusid;
        } elseif($this->simple == 2) {
#            echo 'DISCUZUPLOAD|'.($_GET['type'] == 'image' ? '1' : '0').'|'.$statusid.'|'.$this->aid.'|'.$this->attach['isimage'].'|'.($this->attach['isimage'] ? $this->attach['attachment'] : '').'|'.$this->attach['name'].'|'.$this->error_sizelimit;
            echo 'simple:2' . -$statusid;
        } else {
            echo $statusid ? -$statusid : $this->aid;
        }
        exit;        
    }
}


?>

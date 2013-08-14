<?php


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$_G['uid'] = intval($_POST['uid']);

if((empty($_G['uid']) && $_GET['operation'] != 'upload') || $_POST['hash'] != md5(substr(md5($_G['config']['security']['authkey']), 8).$_G['uid'])) {
    exit();
} else {
    if($_G['uid']) {
        $_G['member'] = getuserbyuid($_G['uid']);
    }
    $_G['groupid'] = $_G['member']['groupid'];
    loadcache('usergroup_'.$_G['member']['groupid']);
    $_G['group'] = $_G['cache']['usergroup_'.$_G['member']['groupid']];
}

if($_GET['operation'] == 'upload') {    
    $forumattachextensions = '';
    $fid = intval($_GET['fid']);
    if($fid) {
        $forum = $fid != $_G['fid'] ? C::t('forum_forum')->fetch_info_by_fid($fid) : $_G['forum'];
        if($forum['status'] == 3 && $forum['level']) {
            $levelinfo = C::t('forum_grouplevel')->fetch($forum['level']);
            if($postpolicy = $levelinfo['postpolicy']) {
                $postpolicy = dunserialize($postpolicy);
                $forumattachextensions = $postpolicy['attachextensions'];
            }
        } else {
            $forumattachextensions = $forum['attachextensions'];
        }
        if($forumattachextensions) {
            $_G['group']['attachextensions'] = $forumattachextensions;
        }
    }
    $uploadcb = new forum_uploadcallback();

}else if($_GET['operation'] == 'uploadtoken'){
    $uploadtoken = new forum_uploadtoken();
}else {
    echo "-777";
}

?>

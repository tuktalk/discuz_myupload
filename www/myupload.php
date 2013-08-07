<?php

define('APPTYPEID', 200);
define('CURSCRIPT', 'misc');


require './source/class/class_core.php';
require './source/function/function_qiniu.php';

$discuz = C::app();

$discuz->reject_robot();
$modarray = array('uploadcallback');

$mod = getgpc('mod');
$mod = (empty($mod) || !in_array($mod, $modarray)) ? 'error' : $mod;

if(in_array($mod, array('uploadcallback'))) {
	define('ALLOWGUEST', 1);
}

$discuz->init();

define('CURMODULE', $mod);
runhooks();

require DISCUZ_ROOT.'./source/module/myupload/myupload_'.$mod.'.php';

?>

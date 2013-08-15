===
使用说明
===

###使用七牛直传接管discuz的论坛附件上传。  

0.  假设discuz代码位于 `/my/website/` 下，插件的代码位于 `discuz_myupload/` 下。  
1.  拷贝 `discuz_myupload/code/` 中的所有文件到 `/my/website/` 中。  

2.  将 `/my/website/misc.php` `/my/website/forum.php`
 `/my/website/source/module/forum/forum_image.php` 三个文件的权限改为666。  

3.  如果使用了自定义模板，需要修改 `/my/website/template/custom_template/forum/` 文件夹中的
`forumdisplay_fastpost.htm`、`post.htm`、`viewthread_fastpost.htm` 三个文件中swfupload相关设置。
可参考 `discuz_my_upload/code/template/default/` 中的相应文件。  

  forumdisplay_fastpost.htm:  
  ![forumdisplay_fastpost](http://t-test-public.qiniudn.com/forumdisplay_fastpost_w.png)  
  viewthread_fastpost.htm:  
  ![viewthread_fastpost](http://t-test-public.qiniudn.com/viewthread_fastpost_w.png)  
  post.htm 第一处修改:  
  ![post_1](http://t-test-public.qiniudn.com/post_1_w.png)  
  post.htm 第二处修改:  
  ![post](http://t-test-public.qiniudn.com/post_2_w.png)  


4.  进入discuz管理中心->应用->插件，安装并启用“七牛云存储直传助手 v0.0.1”。  
  ![before_installed](http://t-test-public.qiniudn.com/before_install.png)  
  ![installed](http://t-test-public.qiniudn.com/installed.png)  
  ![turned_on](http://t-test-public.qiniudn.com/turned_on.png)  

5.  进入discuz管理中心->应用->七牛云存储直传助手的设置页面，按照自己的实际情况填写参数，
并将“是否使用七牛直传”设置为“是”。  
  ![settings](http://t-test-public.qiniudn.com/settings.png)  

6.  进入discuz管理中心->全局->上传设置->基本设置，
设置“本地附件url地址”为`http://<your-qiniu-domain>/data/attachment`。  
  ![local_url](http://t-test-public.qiniudn.com/local_url.png)  

7.  如果要停止使用，只需将“是否使用七牛直传”设置为“否”。  

===
使用说明
===

###使用七牛直传接管discuz的论坛附件上传。  

0.  假设discuz代码位于 /my/website/ 下。  
1.  拷贝discuz_myupload/code/中的所有代码到 /my/website/ 中。  
  `>>> cp -r code/* /my/website/`  

2.  如果使用了自定义模板，需要修改 /my/website/template/custom_template/forum/ 文件夹中的
forumdisplay_fastpost.htm、post.htm、viewthread_fastpost.htm 三个文件中swfupload相关设置，
内容可参考code/template/default/ 中的相应文件。  

3.  拷贝插件代码到discuz的插件文件夹中。  
  `>>> cp -r dz_qiniu_upload /my/website/source/plugin/`  

4.  进入discuz管理中心->应用->插件，安装并启用“七牛云存储直传助手 v0.0.1”。  
5.  进入discuz管理中心->应用->七牛云存储直传助手的设置页面，按照自己的实际情况填写参数，并要将“是否使用七牛直传”设置为“是”。  
6.  进入discuz管理中心->全局->上传设置->本地附件，设置“本地附件url地址”为`http://<your-qiniu-domain>/data/attachment`。  
7.  如果需要停止使用，只要将“是否使用七牛直传”设置为“否”。  

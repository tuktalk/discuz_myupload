1. 用途  
 1.1. 接管Discuz!中的论坛附件上传功能，改为使用七牛云存储的直传功能。  

2.  简述  
 2.1. 基于Discuz! X3 进行修改。  
 2.2. 核心思路是修改swfupload的上传路径及参数，配合增加新的接口及功能。  

3.  基本使用  
 3.0. 备份/your/discuz/path/文件夹。  
 3.1. 在/your/discuz/path/config/config_global.php 的设置中追加 discuz_myupload/config/config.conf的内容   
 3.2. 将discuz_myupload/code/ 中的文件复制到/yout/discuz/path/中，部分文件会覆盖原有的。  

4. discuz_myupload/code/中的代码详述  
 4.0. 对于一些已经修改过代码或者使用非默认模板的使用者，直接复制覆盖的方法可能无效，这部分用户可以根据下面的详述自行增改。  
 4.1. qiniu/ **新增** [七牛的php sdk](https://github.com/qiniu/php-sdk/tags#)。  
 4.2. forum.php **修改** 增加了对 source/function/function_qiniu.php 的引用。  
 4.3. myupload.php **新增** 用于七牛服务器回调。  
 4.4. source/class/forum/forum_callback.php **新增** 回调数据处理类  
 4.5. source/function/fucntion_qiniu.php **新增** 提供相关的方法。  
 4.6. source/module/forum/froum_iamge.php **修改** 增加开启七牛直传功能时的附件地址输出。  
 4.7. source/module/myupload/myupload_uploadcallback.php **新增** callback的分支选择  
 4.8. template/default/forum/ **修改** 修改swfupload的设置中的file_post_name、upload_url、post_params，使之符合直传模式和回调接口的需要    

5. 配置说明  
 5.1. switch：是否开启七牛直传，1为是，0为否。  
 5.2. up_host：上传地址，一般情况下无需修改。  
 5.2. domain：使用者的七牛域名地址，以"/"结尾。  
 5.3. bucket：使用者的七牛空间名。  
 5.4. accesskey：使用者的access key。  
 5.5. secretkey：使用者的secret key。  
 5.6. prefix：前缀，保证使用不同的上传模式的文件拥有类似的地址。  
 5.7. callback：回调地址，将<your-host>部分替换为使用者的域名或者ip。  
 5.8. callbackbody：回调的参数格式，一般情况下无需修改。  

6. 附加说明  
 6.1. 仅限于将论坛附件上传修改为七牛直传，其他如相册、头像未做修改。  
 6.2. 需要修改Discuz!管理中心->全局->上传设置中的本地附件URL地址，以保证输出正确的附件地址。  
 6.3. 需要同时开启七牛的镜像加速功能。  

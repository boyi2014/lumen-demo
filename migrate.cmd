=============================更改依赖库镜像源=======================
composer config repo.packagist composer https://packagist.phpcomposer.com

为项目添加新扩展包
composer require vendor/package

composer install

==============================添加依赖库======================
--redis缓存
composer require illuminate/redis (依赖的predis/predis也会被安装)
--token机制
composer require tymon/jwt-auth
--跨域lumen-cors
composer require "palanik/lumen-cors:dev-master"
--七牛文件上传
composer require qiniu/php-sdk


=============================数据库表结构=======================
--创建表
php artisan make:migration create_email_table --create=email
php artisan migrate


--添加字段到现有表
php artisan make:migration add_columes_to_email_table --table=email
php artisan migrate

--回滚最近的1条记录
php artisan migrate:rollback --step=1


删除或者更改migration中文件后
>>composer dump-autoload


=============================apidoc生成文档=======================
--官网
http://apidocjs.com

--安装
npm install apidoc -g

--制作文档
a.项目中创建文件apidoc.json，内容如下：
	{
	    "name": "============麦萌漫画===========",
	    "version": "0.1.0",
	    "description": "接口文档",
	    "title": "麦萌漫画-接口文档",
	    "url" : "http://lumen-api.appzd.net/api"
	}

b.控制台运行命令 
E:\workspace\php>apidoc -i lumen-api/ -o apidoc/ -f .php
E:\workspace\php\lumen-api>apidoc -i app/Http/Controllers/ -o public/apidoc/ -f .php

--Eclipse中导入ApiDoc插件
https://github.com/DWand/eclipse_pdt_apiDoc_editor_templates/blob/master/README.md



=================================测试用例================================
--参考文档
http://laravelacademy.org/post/3467.html

--运行测试用例
E:\workspace\php\maimeng-blog>phpunit
E:\workspace\php\maimeng-blog>.\vendor\bin\phpunit --verbose tests/ExampleTest

--测试数据
1.运行所有测试用例
phpunit

 2.运行某个测试类
phpunit --filter testGetImageInfo tests/services/FileServiceTest

3.运行单个测试方法
phpunit --filter testGetImageInfo tests/services/FileServiceTest

4.分组测试
为测试方法或者类中打标记
/**
 * @group repository
 * 分组测试
 */
phpunit --group repository



https://lumen.laravel-china.org/docs/5.1/testing
测试数据库清理

=================================ORM查询===================================
--参考文档
http://laravelacademy.org/post/2995.html
应用全局作用域
class User extends Model{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AgeScope);
    }
}

==========================
维护模式
php artision down
php artision up

============================集成laravel的常用命令==================
https://github.com/flipboxstudio/lumen-generator
composer require flipbox/lumen-generator


实例：php artisan make:model Models/Comment


------------------Laravel Eloquent 的缓存层-------------------
1. 加入文件夹Models中Common目录 + Interfaces目录  + Providers目录
2. 配置Laravel Eloquent 的缓存层。
          在bootstrap/app.php中配置
   $app->register(App\Models\Providers\EloquentCacheProvider::class);
3. model继承App\Models\Common\BaseModel
         在model类中 配置属性开启缓存：protected $needCache = true;
注释：支持行级缓存 + 表级缓存 + 自动过期
   

=====================jwt======================
https://zhuanlan.zhihu.com/p/22531819?refer=lsxiao

https://segmentfault.com/q/1010000002449556/a-1020000007035812
JWT实现的时候，一般会有两个过期时间

第一个是Token本身的过期时间，这个时间一般1到2个小时，不能太长，也可以在短一点，不过5s的简直纯属扯淡。
第二个是Token过期后，再次刷新的有效期，也就是Token过期后，你还有一段时间可以重新刷新，把过期的Token发给服务端，如果没有过刷新截止期，则服务端返回一个新的Token，不再需要通过用户名密码重新登录获取Token了。
所以为了减少过期后重新获取Token所带来的麻烦，我们一般在每次Http请求成功后，将目前的Token刷新，然后可以在Http响应中返回新的Token。

JWT由于过期数据(exp claim)是封装在Payload中的，所以必须返回一个新Token，而不是在旧Token的基础上刷新。

但是在并发的时候也会出现问题，如果前一个请求刷新了Token(为了安全，刷新后一般会把旧Token加入黑名单)，后面的请求使用了一个旧的Token像服务请求数据，这个时候请求会被拒绝。

可以说这真的是JWT的一个缺陷，目前没有特别好的办法来解决并发刷新的问题。

不过可以通过设置一个宽限时间，在Token刷新后，如果旧Token仍处于刷新宽限时间内，就放行。

我最近在写一个JWT的扩展包，给Lumen用的，如果想了解一些JWT的原理，构成，可以关注我的专栏，和这个系列的文章。

Code杂货铺-lsxiao的知乎专栏

从零实现Lumen-JWT扩展包


--------------------------------
token生成规则：
登录用户与匿名用户
1. 什么时候生成？
         启动app时候，根据设备信息，获得一个token。
         如果之前登录过，这时候如何处理？
                  之前如果登录，保存的有token，将原来的token一并带上，
                           如果已经过期或者无效，则生成一个新的token。
                           如果还有效，则刷新当前token   
                           
  ====》对应一个生成token的接口《====
      请求头部（Headers）：clientVersion，clientId，deviceType，deviceToken，accessToken，qudao，requestTime
      参数：deviceID + userID    
  
        备注：刷新token，只刷新失效时间。
                      用户身份认证
                      如何存储这些数据，目前是放在cache中
   
2. 干什么用？
          统计用户在线时长
                   什么时候打开？
                   使用时长？
                   是否在线？ 
          修改密码或者其他敏感操作时，需要重新认证。
                   设置之前的token失效===》刷新当前token
          
          是否支持多设备同时在线？
                    旧设备认证通过后，在新的设备认证时候，提醒是否强制下线旧设备，点击继续后，失效旧设备token，生成新设备token
   
3. 什么时候失效？
    token过期，切换设备，退出登录，主动刷新
    
4. 失效后如何处理？
   token是每次请求时候，都会带上的。
   ====》 解析token《====
          解析token拿到用户凭证，不抛异常，在具体的接口中，写业务逻辑。
   
   <1>不需要认证的接口
      
   <2>需要认证的接口
                     用户相关数据，支付等行为
    
    
    
4. 哪些接口需要认证？哪些接口需要刷新token
         一些默认的动作，比如添加历史记录






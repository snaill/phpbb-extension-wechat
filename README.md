# phpbb-extension-wechat
phpbb微信OAuth扩展，实现微信公众号关联和自动登录

# 安装
- 复制文件**WechatService.php**到phpBB/phpbb/auth/provider/oauth/
- 复制文件**wechat.php**到phpBB/phpbb/auth/provider/oauth/service/
- 修改yml，在phpBB/config/default/container/services_auth.yml最后添加
```yml
      auth.provider.oauth.service.wechat:
        class: phpbb\auth\provider\oauth\service\wechat
        arguments:
            - '@config'
            - '@request'
        tags:
            - { name: auth.provider.oauth.service } 
```
- 修改英文语言包/language/cn/common.php，添加：
```php
  		'AUTH_PROVIDER_OAUTH_SERVICE_WECHAT'					=> 'Wechat',
```
- 修改中文文语言包/language/zh_cmn_hans/common.php，添加：
```php
 		'AUTH_PROVIDER_OAUTH_SERVICE_WECHAT'					=> '公众号登录',
```
- ACP中修改认证设置
- OK

# 特别说明
phpbb的OAuth基于[Lusitanian/PHPoAuthLib](https://github.com/Lusitanian/PHPoAuthLib)库实现，WechatService类是对该库的扩展，已经提交代码给官方，或许将来就不需要自行添加了。当然到那个时候，wechat类中的名字空间和引用都需要修改。
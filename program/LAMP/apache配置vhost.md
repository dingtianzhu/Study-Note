* 将httpd.conf的`Include conf/extra/httpd-vhosts.conf`注释解开
* 再在extra文件夹下的httpd-vhost.conf文件进行编辑
* 配置default这一块完全是为了phpmyadmin，当然了也可以直接给管理工具配个虚拟域名然后再指向
```
<VirtualHost _default_:80>
DocumentRoot "C:/AppServ/www"
#ServerName www.example.com:80
</VirtualHost>
<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host.example.com
    DocumentRoot "C:/AppServ/www/program/console.tfhulian.com/web"
    ServerName testweb.com
    ServerAlias www.testweb.com
    ErrorLog "logs/dummy-host.example.com-error.log"
    CustomLog "logs/dummy-host.example.com-access.log" common
</VirtualHost>

<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host2.example.com
    DocumentRoot "C:/AppServ/www/program/api2.tfhulian.com/web"
    ServerName testapi.com
    ErrorLog "logs/dummy-host2.example.com-error.log"
    CustomLog "logs/dummy-host2.example.com-access.log" common
</VirtualHost>
```

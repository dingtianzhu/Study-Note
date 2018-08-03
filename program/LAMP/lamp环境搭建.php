<?php 
	lamp环境搭建
	一、下载linux下xmapp安装包上传至服务器下
		或者直接用命令：wget https://www.apachefriends.org/xampp-files/7.2.4/xampp-linux-x64-7.2.4-0-installer.run
	二、给安装包执行权限，命令：chmod +x xampp-linux-x64-7.2.4-0-installer.run
	三、解压安装包，命令：./xampp-linux-x64-版本号-0-installer.run
	四、启动lampp，命令：/opt/lampp/lampp start
	五、关闭防火墙，命令：systemctl stop firewalld.service或者/etc/init.d/iptables stop或者service iptables stop （总有一款适合你）
	六、打开浏览器，访问你的ip
	七、如果阿里云配置的话，需要在安全组规则里添加80端口

	八、/opt/lampp/etc/extr/httpd-xampp.conf将    
	<Directory "/opt/lampp/phpmyadmin">
    AllowOverride AuthConfig Limit
    Require Local
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>
改成
<Directory "/opt/lampp/phpmyadmin">
    AllowOverride AuthConfig Limit
    Require all granted
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>


 ?>
 NameVirtualHost 115.28.165.41:80

<VirtualHost 115.28.165.41:80>
     DocumentRoot "/opt/lampp/htdocs/xiaojiuweb/public"
     ServerName smart9.org
     ServerAlias www.smart9.org
</VirtualHost>
<VirtualHost 115.28.165.41:80>
     DocumentRoot "/opt/lampp/htdocs/renfu/public"
     ServerName renfu.zeroioe.com
     ServerAlias www.renfu.zeroioe.com
<Directory "/opt/lampp/htdocs/renfu/public">
Options FollowSymLinks
AllowOverride All
Require all granted
</Directory>
</VirtualHost>

<VirtualHost 115.28.165.41:80>
     DocumentRoot "/opt/lampp/htdocs/GameVPN/public"
     ServerName hojsq.com
     ServerAlias www.hojsq.com
<Directory "/opt/lampp/htdocs/GameVPN/public">
Options FollowSymLinks
AllowOverride All
Require all granted
</Directory>
</VirtualHost>

<VirtualHost 115.28.165.41:80>
     DocumentRoot "/opt/lampp/htdocs/lanen/public"
     ServerName lanen.zeroioe.com
     ServerAlias www.lanen.zeroioe.com
<Directory "/opt/lampp/htdocs/lanen/public">
Options FollowSymLinks
AllowOverride All
Require all granted
</Directory>
</VirtualHost>
<VirtualHost 103.201.26.7:80>
     DocumentRoot "/opt/lampp/htdocs/jianfulun/public"
     ServerName jianfulun.zeroioe.com
     ServerAlias www.jianfulun.zeroioe.com
<Directory "/opt/lampp/htdocs/jianfulun/public">
Options FollowSymLinks
AllowOverride All
Require all granted
</Directory>
</VirtualHost>
<VirtualHost 115.28.165.41:80>
     DocumentRoot "/opt/lampp/htdocs/fgbt"
     ServerName figbot.com.cn
     ServerAlias www.figbot.com.cn
<Directory "/opt/lampp/htdocs/fgbt">

Options FollowSymLinks
AllowOverride All
Require all granted

</Directory>
</VirtualHost>
                        
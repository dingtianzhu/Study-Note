<?php 
	一、linux下软连接  ln -s  sudo ln -s /opt/lampp/var/mysql/mysql.sock /var/run/mysqld/mysqld.sock
	二、建立快速访问听通道：1. ~/.bashrc   添加#PATH  	export PATH=/opt/lampp/bin:$PATH  保存退出
							2.执行命令：source ~/.bashrc
	三、查看服务端口号命令：netstat -atunlp
	四、ubuntu下xampp中mysql端口号显示为0
		1)查看mysqld进程
			命令：ps -ef|grep mysqld 
			##我们可以看到mysqld进程是存在的，而且配置文件中写的也是3306端口
		2)查看配置文件
		##我们看到配置文件中也确实写的是3306端口 
		3)检查3306端口是否被监听
		命令：netstat -lanp|grep 3306
		##什么反应都没有
		4)查看端口号
		命令：show variables like 'port'; 
		##port显示为0，很无语吧。接下来我去查了一下错误日志
		5)分析原因:因为在本地通过socket方案数据库是能够正常登陆的，但是通过网络来登录数据库就报错，这时我们会想到一个参数skip-networking
		那么问题就简单多了    进入mysql的配置文件my.cnf，(windiws是my.ini)，找到配置文件有一句skip-networking注释掉，也就是前面加个'#'的事
		6)重启mysql服务器，命令自己在网上找，因为不同继承环境下都不同，然后你会发现，卧槽，完全ojbk
 ?>
		
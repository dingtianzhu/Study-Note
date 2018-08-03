Liunx搭建服务器
1、开启路由转发功能
vim  /etc/sysctl.conf 打开linux的路由转发功能
将：net.ipv4_forward=0改成1 保存再输入命令 sysctl -p查看（生效）

2、清楚防火墙设置
	iptables -F 清除你的防火墙设置，如果已经有条目在里面那就算了

3、设置nat转发
iptables -t nat -A POSTROUTING -o eth1 -s 192.168.0.2 -j SNAT --to-source=10.11.12.13
说明一下，eth1是楼主自己说的，192.168.0.2是我瞎掰的，就是你笔记本的地址，你自己设置。10.11.12.13是外网分配给你的地址，也是瞎掰的。ifconfig查看  注意大小写！
linux系统，vim /etc/resolv.conf设置DNS为服务器DNS地址，chatt +i /etc/resolv.conf 绑定，然后设置ip地址啥的，网关啥的差不多了。
建议服务器防火墙规则自定义一条写入和客户端上网优化（楼主既然说是测试就算了，不打了，我喝醉了）
另：记得service iptables save；chkconfig iptables on

Mac搭建主机服务器
1、开启路由转发功能
   sudo vim /etc/sysctl.conf
然后在配置文件中写入相应配置即可
net.inet.ip.forwarding=1
修改好之后，:wq保存
sysctl -p执行使配置生效
2、清除防火墙
ipfw是BSD系统中重要的防火墙和通信控制工具，在MacOSX中也很好用。
列出配置表: sudo ipfw list 
禁用ping，即ICMP协议: ipfw add 3333 deny icmp from any to any via en0
取消，则用: sudo ipfw del 3333
封端口, 禁止本机连外部的tcp 80端口:  ipfw add 3333 deny tcp from any 80 to any
3、设置nat转发
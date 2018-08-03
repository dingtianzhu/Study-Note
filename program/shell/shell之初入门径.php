<?php 
	shell是什么：命令解释器
	一、怎么书写shell脚本呢？
		1、首先创建shell文件一般以.sh作为文件后缀（linux下后缀一般都是不起作用的，只是用来认为辨别脚本类型的）
		开头：
		#!/bin/bash //标明解析程序，但是linux目前已经慢慢不适用bin目录了，建议使用#!/usr/bin/env bash(先使用env获取解释器的位置，再调用解析器)
		2、运行shell脚本：
		./test.sh或者bash test.sh（后者会忽略shell文件的指定的解析器语句'#!/bin/bash',即使这句写错，脚本也能正常被解析）
		./test.sh在子shell环境里执行如果 . test.sh或者source test.sh则是在当前shell环境里执行的，一般我们的shell程序都是在子shell环境里执行的，但是在shell调用的时候则需要在当前环境下执行shell				（一种东西存在，都有它存在的价值）
		3、注释使用#
	二、
	Shell编程笔记
O 其他用户
g所在组
a所有用户
u当前用户
Linux下用户与组
0、用户产看
Id user
1、用户的添加
Useradd user
2、用户的删除
Userdel -r user
3、把用户添加到组
gpasswd -a user root
4、把组中用户删除
gpasswd -的user root
Chmod 权限分配
Acl权限分配
1、setfacl设置文件权限
Setfacl -m u:user1:rw root.txt
Setfacl -m u:user2:rwx root.txt

2、getfacl查看文件权限
Getfacl root.txt

3、删除文件权限
Setfacl -b root.txt

创建和删除文件权限：
#需要对目录设置acl权限即可
Setfacl -m u:user4:rwx /mnt
给目录下所有文件以及文件夹设置权限
Setfacl -m u:user4:rwx -R /mnt/

目录中后期添加的子目录如何设置继承父级目录的权限的
Setfacl -m d:u:user4:rwx -R /mnt  (d->default)

sudo一般是用户对命令或者运行程序所操作的权限分配
设置用户对命令的执行权限-visudo:

.shell脚本
1、用途：完成特定的较复杂的系统管理任务
2、格式：集中保存多条Linux命令，形式以普通可执行文本文件 
3、执行方式：按照预设的顺序依次解释执行

 ?>
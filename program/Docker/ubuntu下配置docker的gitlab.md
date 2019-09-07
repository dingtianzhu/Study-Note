### unbuntu搭建docker和GitLab环境
1. 安装docker
#### 方法一
* 卸载老版本,由于apt官方库里的docker版本可能比较旧，所以先卸载可能存在的旧版本
* 命令apt-get remove docker docker-engine docker-ce docker.io
* apt-get update
* [安装以下包以使apt可以通过HTTPS使用存储库（repository）
* apt-get install -y apt-transport-https ca-certificates curl software-properties-common
* 添加Docker官方的GPG密钥
* curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
* 使用下面的命令来设置stable存储库
* add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"]
* 在生产系统上，可能会需要应该安装一个特定版本的Docker CE，而不是总是使用最新版本
* 列出可用的版本
* apt-cache madison docker-ce
* apt-get install docker-ce=对应版本号
* 查看docker服务的状态：systemctl status docker
* systemctl start docker启动docker
* 彻底删除镜像
* docker rmi $(docker images -q)
#### 方法二
* 查看并确定ubuntu内核版本高于3.10
* `uname -r`
* 使用脚本安装docker
* wget -qO- https://get.docker.com/ | sh
* 非root用户会报错，按照提示执行对应的命令sudo usermod -aG docker xxx即可，为docker用户赋予权限
---
2. 安装gitlab
* Gitlab有两个版本：
* Community Edition 社区版
* Enterprise Edition 企业版
* 企业版比社区版功能更丰富，但需要授权码，如果没有授权码的情况下使用企业版相当于使用社区版
* 安装命令
```
docker run --detach \
--hostname gitlab-server \
--publish 4433:443 --publish 8088:80 --publish 2222:22 \
--name gitlab \
--restart always \
--volume /srv/gitlab/config:/etc/gitlab \
--volume /srv/gitlab/logs:/var/log/gitlab \
--volume /srv/gitlab/data:/var/opt/gitlab \
gitlab/gitlab-ce:latest
```
* 注意，这里因为作者的服务器80端口等已经被占用，所以，对端口进行了映射

|参数|值|说明|
|---|---|---|
|hostname|gitlab-server|设置gitlab容器的主机名为 gitlab-server|
|publish|8443:443|设置宿主机与docker容器的端口映射（如宿主端口被占用请更换别的端口，参考本文下面的内容）|
|name|gitlab|设置容器的名称为gitlab（只是一个标识，可随意指定）|
|restart|always|在容器退出时总是重启容器|
|volume|/srv/gitlab/config:/etc/gitlab|将本地目录与容器内的目录映射|

* 映射目录的用途

|本地目录|容器目录|用途|
|----|----|----|
|/srv/gitlab/data|/var/opt/gitlab|存储应用数据|
|/srv/gitlab/logs|/var/log/gitlab|存储日志数据|
|/srv/gitlab/config|/etc/gitlab|存储配置文件|

3. 配置GitLab
* gitlab-ee容器使用的是官方Omnibus GitLab包，所有配置都在一个文件中：/etc/gitlab/gitlab.rb
* docker exec -it gitlab vi /etc/gitlab/gitlab.rb //命令一
* 由于本地目录与容器内的目录建立了映射，直接修改本地文件也是一样的，下面的指令效果跟上面的指令一样：
* vi /srv/gitlab/config/gitlab.rb	//  命令二  
* 命令一相当于链接到容器内的shell，进入交互模式，执行的是容器内的指令：编辑gitlab.rb文件
* 在打开的窗口中将external_url设置为：http://192.168.199.175（该ip是宿主机的ip，根据自己主机上的ip更改）
* 重启gitlab
* `docker restart gitlab`
4. 访问GitLab服务
* 用浏览器打开 http://192.168.199.175/ ，第一次打开需要设置root密码
* 使用root用户登录并设置GitLab，比如：创建项目，创建组，添加用户等
5. 使用不同的端口访问GitLab
* 如果宿主机的80端口被占用，那么我们可以修改映射的端口，将其他端口暴露为GitLab服务
```angular2
//停止当前的gitlab容器
docker stop gitlab
//删除gitlab容器
docker rm gitlab
//使用其他端口重新启动新的gitlab容器
docker run --detach \
	--hostname gitlab-server \
	--publish 9090:9090 --publish 8022:22 \
	--name gitlab \
	--restart always \
	--volume /srv/gitlab/config:/etc/gitlab \
	--volume /srv/gitlab/logs:/var/log/gitlab \
	--volume /srv/gitlab/data:/var/opt/gitlab \
	gitlab/gitlab-ce:latest
//设置external_url及ssh_port
vi /srv/gitlab/config/gitlab.rb
external_url 'http://192.168.199.175:9090'
gitlab_rails['gitlab_shell_ssh_port'] = 8022
//重启gitlab容器使设置生效
docker restart gitlab
```
* ok 可以使用了











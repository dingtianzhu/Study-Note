Hyperledger Fabric简介
Hyperledger项目是Linux基金会发起的一个联盟区块链的项目，吸引了众多大佬级的公司进驻开发。Hyperledger这个组项目下包含了相当多的子项目，每个子项目由一两家商业公司为主导，为Hyperledger提供不同的功能和支持。

本文我们将介绍Hyperledger组项目下的Fabric子项目的开发环境搭建，基于最新的1.1版本。这个子项目可以说是Hyperledger项目的核心项目，是区块链的实现框架，提供chaincode(链码，对标以太坊项目中的智能合约)的编程接口，可以说是整个Hyperledger的重中之重。目前该项目由IBM公司主导开发。

2
在Linux下搭建开发环境
Linux系统对于Fabric的开发还是比较友好的，其中docker在Linux下更是原生支持。本文我们使用的Linux发行版为Ubuntu 16.04(或基于Ubuntu 16.04的发行版均可，如Linux mint 18.3)，搭建Fabric 1.1开发环境。本文主要参考官方文档，并且对官方文档（https://hyperledger-fabric.readthedocs.io/en/release-1.1/）中内容进行少许补充与变通，以便在国内特色环境下可以顺利的搭建开发环境。

3
安装依赖
Docker

如果有旧版本的docker的话，需要先卸载:

sudo apt purge docker docker-engine docker.io
1
然后安装依赖:

sudo apt install apt-transport-https ca-certificates curl gnupg2 software-properties-common
1
添加docker镜像仓库(清华大学):

curl -fsSL https://mirrors.tuna.tsinghua.edu.cn/docker-ce/linux/ubuntu/gpg | sudo apt-key add - echo "deb [arch=amd64] https://mirrors.tuna.tsinghua.edu.cn/docker-ce/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker-ce.list
1
2
3
安装docker-ce软件包:

sudo apt update && sudo apt install -y docker-ce
1
修改docker hub的镜像，编辑/etc/docker/daemon.json文件，没有就创建一个，需要管理员提权，写入以下内容:

{ 

 "registry-mirrors": ["https://registry.docker-cn.com"]
}
1
2
3
4
重启docker服务生效: sudo service docker restart，这样docker就可以从国内镜像站进行pull操作，大大提高了pull image的速度。

GO

下载GO:

wget https://dl.google.com/go/go1.10.2.linux-amd64.tar.gz
1
Golang官网无法访问也可以从镜像站（https://mirrors.ustc.edu.cn/golang/）获取其他版本的go

解压缩并将go移动到/usr/local/go:

tar xvf go1.10.2.linux-amd64.tar.gz 
sudo mv go /usr/localsudo ln -s /usr/local/go/bin /usr/local/bin

这样go就安装完毕了。默认情况下GOPATH为/usr/local/go，GOROOT为~/go，如果不需要修改的话可以不设置这两个环境变量。

4
安装Fabirc可执行程序与Docker image
这一步比较简单，之前依赖安装完毕之后，只需要执行以下命令即可:

curl -sSL https://raw.githubusercontent.com/hyperledger/fabric/master/scripts/bootstrap.sh | bash -s 1.1.0
1
整个过程比较长的时间，由于Fabric的二进制包在国内速度并不快，而且docker镜像容量也很大，整个时间会很久。并且这个脚本并未做幂等处理，一旦中途中断可能不能重试，可能需要清理临时文件重新运行。所以轻易不要中断脚本执行，耐心等待即可。

脚本最后输出类似于下面的内容表示安装开发环境完毕:

===> List out hyperledger docker imageshyperledger/fabric-ca          latest              72617b4fa9b4        8 weeks ago         299MBhyperledger/fabric-ca          x86_64-1.1.0        72617b4fa9b4        8 weeks ago         299MBhyperledger/fabric-tools       latest              b7bfddf508bc        8 weeks ago         1.46GBhyperledger/fabric-tools       x86_64-1.1.0        b7bfddf508bc        8 weeks ago         1.46GBhyperledger/fabric-orderer     latest              ce0c810df36a        8 weeks ago         180MBhyperledger/fabric-orderer     x86_64-1.1.0        ce0c810df36a        8 weeks ago         180MBhyperledger/fabric-peer        latest              b023f9be0771        8 weeks ago         187MBhyperledger/fabric-peer        x86_64-1.1.0        b023f9be0771        8 weeks ago         187MBhyperledger/fabric-javaenv     latest              82098abb1a17        8 weeks ago         1.52GBhyperledger/fabric-javaenv     x86_64-1.1.0        82098abb1a17        8 weeks ago         1.52GBhyperledger/fabric-ccenv       latest              c8b4909d8d46        8 weeks ago         1.39GBhyperledger/fabric-ccenv       x86_64-1.1.0        c8b4909d8d46        8 weeks ago         1.39GBhyperledger/fabric-zookeeper   latest              92cbb952b6f8        2 months ago        1.39GBhyperledger/fabric-zookeeper   x86_64-0.4.6        92cbb952b6f8        2 months ago        1.39GBhyperledger/fabric-kafka       latest              554c591b86a8        2 months ago        1.4GBhyperledger/fabric-kafka       x86_64-0.4.6        554c591b86a8        2 months ago        1.4GBhyperledger/fabric-couchdb     latest              7e73c828fc5b        2 months ago        1.56GBhyperledger/fabric-couchdb     x86_64-0.4.6        7e73c828fc5b        2 months ago        1.56GB
1
同时，这个脚本会在当前目录下克隆一个fabric-samples/项目，在fabric-samples/bin/目录下会存放下载好的fabric二进制工具包，将fabric-samples/bin/目录加入环境变量，即可直接运行节点程序。

export PATH=${PATH}:/path/to/fabric-samples/bin/
1
5
运行Sample
cd fabric-samples/first-network
1
进入first-network目录，此目录有个./byfn.sh脚本，负责快速产生一个区块链网络，直接使用这个脚本产生网络配置，运行docker容器即可:

./byfn.sh -m generate
1
快速产生网络节点配置项，如生成证书等等，产生一个创世纪块，产生一个名为mychannel的Channel（https://hyperledger-fabric.readthedocs.io/en/release-1.1/glossary.html#channel）。

使用以下命令启动网络:

./byfn.sh up
1
这个命令会在docker环境中启动Fabric网络，并且运行chaincode测试(Golang的chaincode)，运行完毕后退出容器。

运行测成功之后，可以使用以下命令关闭Fabric网络，并且清除所有的容器和数据卷:

./byfn.sh down
1
至此整个Fabric开发环境就已经搭建完毕了，我们可以利用它的API编码代码，部署在Docker环境中进行测试。在正式进入开发环节之前，需要先了解清楚Fabric的整个运行原理，才能更好的进行开发。

建议读者先了解一下区块链技术的一些基本概念，主要包括区块，链，分布式一致性算法，智能合约等，以便更好的理解Fabric是如何将这些理论知识通过技术手段实现的。接下来将介绍Fabric中的模型概念，以及二进制工具包的各种作用。

6
Fabric模型
Fabric为了适应企业的定制化区块链需求，抽象出以下部分的模型概念。

资产(Assets)

资产是指一切在网络中可以等价交换的物品，可以是有形资产，也可以是无形资产。Fabric通过链码(Chaincode)方式提供资产修改的能力。

资产在Fabric中抽象成kv对的形式表示，可以是JSON也可以是二进制格式，通过Hyperledger Composer（https://github.com/hyperledger/composer）可以方便的定义资产。

链码(Chaincode)

直接对标智能合约（https://en.wikipedia.org/wiki/Smart_contract）概念，就是Fabric网络中运行的程序，可以提供修改资产的功能，同时可以获取到当前交易中的各种状态。

账本功能(Ledger Features)

账本(Ledger)是一个共享的，不可变的存储单元，记录了每一个交易频道(Channel)的历史，以及提供类SQL的查询功能。

账本的概念其实就涵盖了区块和链两部分的概念。而交易状态是存储在状态数据库的，目前Fabric使用LevelDB作为默认的状态数据库，同时提供可选CouchDB。

隐私保护的交易频道(Privacy through Channels)

账本工作在每个频道上，既可以共享给整个网络，又可以私有化，仅共享给几个参与者。

如果要私有化账本，要求所有参与者加入同一个频道中，通过频道的隔离性隔离交易事务和账本。在记账之前，交易数据可以通过加密算法进行混淆和保护，如AES算法。

安全&会员服务(Security & Membership Services)

在Fabric的交易网络中，所有参与者都具有已知的身份，通过PKI(Public Key Infrastructure，公钥基础设施)产生加密证书，绑定组织，网络组件，最终用户或客户端程序。因此，数据访问控制可以在广泛的网络或频道级别中有效的管理起来。

Fabric引入“许可”(permissioned)的概念，结合频道的特性，可以工作在隐私性和机密性至上的场景中。

共识(Consensus)

可以说是各种区块链首要需要解决的问题，在其他区块链项目都有大量的提及，Fabric这里也是大同小异。目前Fabric通过插件式的架构可以让开发者自由选择多种共识算法，最初的版本支持三种共识算法:

忽略共识(No-op)

传统拜占庭容错(Classic PBFT)

SIEVE(增强版本的PBFT)

关于完整的交易流程可以参考Fabric官方文档中的交易（https://hyperledger-fabric.readthedocs.io/en/release-1.1/txflow.html）流程一文。

7
Fabric工具介绍
如果运行过前文提到的官方bootstrap脚本，会发现在fabric-samples/bin/目录下存放着Fabric的一些二进制工具:

configtxgen

configtxlator

cryptogen

orderer

peer

了解了Fabric的基本模型之后，现在简单了解下各个工具的作用。

configtxgen可以创建和检查channel配置相关的元素，产生的元素由configtx.yaml配置文件的内容决定。主要产生用于以下几种元素:

产生创世纪块(genesis block)

创建频道交易

两个组织间的Anchor Peer（https://hyperledger-fabric.readthedocs.io/en/release-1.1/glossary.html#anchor-peer）的交易

configtxlator用于转换protobuf和JSON格式的fabric数据结构的，也可以创建配置更新。这个工具既可以作为HTTP服务以REST方式运行，也可以像普通的命令行工具一样运行。

cryptogen是用来产生加密证书的和密钥的，用于传输加密和身份验证。

orderer和peer分别用于运行orderer节点和peer节点。在Fabric的网络中，一共存在着三种类型的节点(Nodes):

client

peer

orderer

client代表一个实际的终端用户行为，必须连接到区块链的节点上(可以是peer或orderer，也可以是两者同时，由实际场景需求决定)，从而创建交易。client节点的概念和其他C/S架构中的client没有区别。

peer节点接收ordered状态更新，并未维护账本的状态(实际就是作为账本的副本节点)。此外peer节点还可以作为特殊角色背书人存在。

orderer节点提供排序服务(ordering service)，比如交付担保。orderer节点为client和peer节点之前提供了共享的communication channel。排序服务支持多个频道，client可以连接到指定频道上发送和获取消息。
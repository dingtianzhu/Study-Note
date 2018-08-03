反编译
工具下载
一、apktool （资源文件获取） 
二、dex2jar（源码文件获取）
三、jd-gui  （源码查看）
四、首先安装java环境配置环境变量path
五、将下载好的apktool发给到指定文件夹下，cmd进入到apktool所在目录下，将apk文件放入此目录下，运行java -jar apktool.jar d -f E:\apktool\1.apk后，会生成与apk同名的文件夹。当我们修改完安卓程序内容后即要进行回编译操作。命令为：apktool d -r xxx.apk或者java -jar apktool.jar b -f 1回编译的文件路径。回编译完成后会保存在apk同名的文件夹的dist文件夹中。
找到我们准备测试用的apk，并将 后缀.apk改为.zip，将test.zip解压，并查看目录，找到classes.dex，并将这个文件拷至dex2jar工具存放目录下，打开控制台，使用cd指令进入到dex2jar工具存放的目录下，进入到dex2jar目录下后，输入“dex2jar.bat    classes.dex”指令运行 ，执行完毕，查看dex2jar目录，会发现生成了classes.dex.dex2jar.jar文件，上一步中生成的classes.dex.dex2jar.jar文件，可以通过JD-GUI工具直接打开查看jar文件中的代码。
六、
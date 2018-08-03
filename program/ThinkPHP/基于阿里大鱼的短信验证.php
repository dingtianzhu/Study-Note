
<?php





	#首先将插件放置项目文件夹下/extend中
	#第一步    控制器建立文件Sends.php和文件SmsDemo.php文件
	#send.php文件下的代码如下
	#其中设置cookie('msg',$str,60);







	namespace app\index\controller;
	use think\Controller;

	header('Access-Control-Allow-Origin: *');

	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept");


class Sends{
	public function index(){
		return view();
	}
	public function send(){

		$demo = new SmsDemo(
		    "LTAIn0g2N0HU7sCQ",
		    "uEBfc3NF52nDvU1gmurRfbIuVW7Xwx"
		);
		$str = mt_rand(100000, 999999);
		$tel = $_POST['tel'];
		//echo "SmsDemo::sendSms\n";
		$response = $demo->sendSms(
		    "泽优云", // 短信签名
		    "SMS_105650022", // 短信模板编号
		    $tel, // 短信接收者
		    Array(  // 短信模板中字段的值
		        "code"=>$str,
		        //"product"=>"dsd"
		    ),
		    "123"
		);
		 if($response->Message == 'OK'){
		 	// $res= $this -> jsonDatacount()->toArray();
			// $res['msgs'] ='success';
			$response->Code=$str;
			//短信验证码存取
			cookie('msg',$str,60);
			return json_encode($response);
			// echo $response;
			// @file_put_contents('dysms_php/sendhistory/'.$tel.'.txt',$str);
		 }
		 else{
			// $res=$this -> jsonDatacount()->toArray();
			// $res['msgs'] ='fail';
			return json_encode($response);
		 }
	}
	public function isPhoneNum($mobile){  
	    if(!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){  
	      return false;  
	    }  
	    return true;  
	}
	
 
 ?>







 #第二步控制器中加入文件SmsDemo.php文件
 #其中代码如下
 #引入文件路径require_once  './../extend/php/api_sdk/vendor/autoload.php';








 <?php
namespace app\index\controller;
use think\Controller;
ini_set("display_errors", "on");

require_once  './../extend/php/api_sdk/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

/**
 * Class SmsDemo
 *
 * @property \Aliyun\Core\DefaultAcsClient acsClient
 */
class SmsDemo extends Controller
{

    /**
     * 构造器
     *
     * @param string $accessKeyId 必填，AccessKeyId
     * @param string $accessKeySecret 必填，AccessKeySecret
     */
    public function __construct($accessKeyId, $accessKeySecret)
    {

        // 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
    }

    /**
     * 发送短信范例
     *
     * @param string $signName <p>
     * 必填, 短信签名，应严格"签名名称"填写，参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/sign">短信签名页</a>
     * </p>
     * @param string $templateCode <p>
     * 必填, 短信模板Code，应严格按"模板CODE"填写, 参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/template">短信模板页</a>
     * (e.g. SMS_0001)
     * </p>
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param array|null $templateParam <p>
     * 选填, 假如模板中存在变量需要替换则为必填项 (e.g. Array("code"=>"12345", "product"=>"阿里通信"))
     * </p>
     * @param string|null $outId [optional] 选填, 发送短信流水号 (e.g. 1234)
     * @return stdClass
     */
    public function sendSms($signName, $templateCode, $phoneNumbers, $templateParam = null, $outId = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        // 可选，设置流水号
        if($outId) {
            $request->setOutId($outId);
        }

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;

    }

    /**
     * 查询短信发送情况范例
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $sendDate 必填，短信发送日期，格式Ymd，支持近30天记录查询 (e.g. 20170710)
     * @param int $pageSize 必填，分页大小
     * @param int $currentPage 必填，当前页码
     * @param string $bizId 选填，短信发送流水号 (e.g. abc123)
     * @return stdClass
     */
    public function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId=null) {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumbers);

        // 选填，短信发送流水号
        $request->setBizId($bizId);

        // 必填，短信发送日期，支持近30天记录查询，格式Ymd
        $request->setSendDate($sendDate);

        // 必填，分页大小
        $request->setPageSize($pageSize);

        // 必填，当前页码
        $request->setCurrentPage($currentPage);

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;
    }

}

// 调用示例：
?>







#第三步   js部分   获取电话号码利用ajax给Sends.php发送请求








	var timem = 61;
	$(".getcheck").click(function() {
        var tel = $("#tel").val();
        if (tel == "" ) {
            return false;
        } else {
            var ting = null;
            //倒计时函数
            function clock() {
                timem -= 1;
                if (timem == 0) {
                    $(".getcheck").attr("disabled", false);
                    timem = 61;
                    $(".getcheck").html("获取验证码");
                    clearInterval(ting);
                    $(".getcheck").css({ "background": "deepskyblue" });
                } else {
                    $(".getcheck").css({ "background": "lightgrey" });
                    $(".getcheck").html(timem + "s");
                }
            }
            $.ajax({
                url:"/index/sends/send",
                dataType:"json",
                type:'POST',
                data:{
                    tel:tel
                },
                success:function(dt){
                    code = $.parseJSON(dt).Message;
                    if (code=='OK') {
                        $(".getcheck").attr("disabled", true);
                        //将鼠标光标自动设置到验证码输入框中
                        $('#getCheck').focus();
                        //读秒
                        ting = setInterval(clock, 1000);
                    } else {
                        alert('您今天的短信接收次数已达到上限!或手机号错误');
                    }
                }

            });
           
            
            return false;
        }
    });







	#第四步  在注册的时候进行判断    
	#判断语句为:if($input['yanzhengma'=cookie('msg')])就让其进入注册部分，否则提示验证码错误
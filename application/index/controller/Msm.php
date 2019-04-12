<?php 

namespace app\index\controller;
use think\Controller;
use think\Db;
use alidayu\top\TopClient;
use alidayu\top\request\AlibabaAliqinFcSmsNumSendRequest;

require_once $_SERVER['DOCUMENT_ROOT'].'/extend/dayu2.0/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

use fusms\Ucpaas;

use yuntongxun\Rest;
// 加载区域结点配置
Config::load();

class Msm extends Controller{

	public function testsend()
	{
		$code = 458645;
		$res = $this->sendsms(2175, $code ,15769272583);
		dump($res);
	}

/*
	public function sendsms($uid = 0, $code ,$phone)
	{
		if(!$code){
			return false;
		}

		if(!$phone){
			return false;
		}

		//初始化必填
		$options['accountsid']='f7acc233dc79a57fc37c791db2ccc54c';
		$options['token']='75b58c0ba10f39199fe79c3f54dc5e08';


		//初始化 $options必填
		$ucpass = new Ucpaas($options);

		//开发者账号信息查询默认为json或xml

		$ucpass->getDevinfo('json');
		$appId = "38f8f22da49d4681b9d1aca4ebdf22af";
		$to = $phone;
		$templateId = "128304";
		$param=$code;

		$res = $ucpass->templateSMS($appId,$to,$templateId,$param);
		$arr = json_decode($res,1);
		if(isset($arr["resp"]["respCode"]) && $arr["resp"]["respCode"] == "000000"){
			return true;
		}else{
			return false;
		}
	}
*/
	/**
	 * 短信宝 http://www.smsbao.com/
	 */
	
	public function sendsms($uid = 0, $code ,$phone)
	{
		$conf = getconf('');

		if(!$code){
			return false;
		}

		if(!$phone){
			return false;
		}

		$content = '您的验证码为'.$code.'，在10分钟内有效。';
		
		$smsapi = "http://api.smsbao.com/"; //短信网关
		$user = $conf['msm_appkey']; //短信平台帐号
		$pass = md5($conf['msm_secretkey']); //短信平台密码
		$content="【".$conf['msm_SignName']."】".$content;//要发送的短信内容
		$phone = $phone;
		$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
		
		$result =file_get_contents($sendurl) ;
		if($result != 0){
			return false;
		}else{
			return true;
		}

	}


    /**
     * 发送验证码 云片
     * @param int $userid
     * @param string $type
     * @param string $argu1
     * @param string $argu2
     * @return bool
     */
    public function send($mobile, $type='0', $code, $argu1 = '', $argu2 = '')
    {

        $account='yzgwjy';
        $apikey = '5t7889RyIuTyt674uJ';
        $signature = '【绿色财富】';
        $site =  [
            '0'=>'您的验证码是'.$code,
            '1'=>'模板1',
            '2'=>'模板2',
            '3'=>'模板3',
        ];
        $content = $site[$type];
        $n1 = substr($argu1, 0, 5);
        $n2 = substr($argu2, 0, 5);
        if ($n1) {
            $content = str_replace('#number#', $n1, $content);
        }
        if ($n2) {
            $content = str_replace('#number1#', $n2, $content);
        }
        $text = $signature. $content;
        \Think\Log::record('type-------------' . $type);
        \Think\Log::record('手机-------------' . $mobile);
        \Think\Log::record('发送内容-------------' . $content);
//        // 云片发送短信
//        $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
//        curl_setopt($ch, CURLOPT_URL, 'http://yunpian.com/v1/sms/send.json');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $json_data = curl_exec($ch);
//        $res = json_decode($json_data, true);
//        \Think\Log::record('云片反馈-------------' . serialize($res));
        $url = "http://dx.ipyy.net/sms.aspx?action=send&userid=&account=" . $account . "&password=" . $apikey . "&mobile=" . $mobile . "&content=" . $text;
        \Think\Log::record('-------------url' . $url);
        file_put_contents('1.txt', $url);
        //初始化curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        \Think\Log::record('-------------data' . $data);
        $r=  simplexml_load_string($data);
        $f=(int)$r->successCounts;
        if ($f!==1) {
            return false;
        } else {
            return true;
        }
    }


}


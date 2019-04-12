<?php

namespace app\index\controller;

use think\Controller;
use addons\epay\library\Service;
use think\Db;
use think\Log;

class Pay extends Controller
{
    protected  $alipay = [
    'app_id'         => '2017102709555932',
     //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1OtMY/aDtANcy4HeaXdgqGJ0gCxkUWg6EdD/iOYaD36d2xPbn35yamus0twFnpO9fX2H7tLGJ1U/i6uypWnO17RC2T4JeyiGpo30qGcfOqfXbIK5vrj+QLGY//EKgCY6Q0xlnS4tYsyLYMBjbipvuxrEEFTu98dGodj3NAmeWex7NM6QIICq5uDBDuV3nludP6q8t86KS2PtYbVJpFCSLu49LLiccfkoKnZUA+73aVYLPUPVJJq7HuWifhlxSAp7Icf3cbu/UC+Arv606zBx1yGAMmmTuM7+NpP9XotnhewHpv7IQVn5wZN99npuniDaufMpupeEC9SVSmbGZlJ5HQIDAQAB',
    //商户私钥，您的原始格式RSA私钥
    'private_key'    => 'MIIEowIBAAKCAQEA1OtMY/aDtANcy4HeaXdgqGJ0gCxkUWg6EdD/iOYaD36d2xPbn35yamus0twFnpO9fX2H7tLGJ1U/i6uypWnO17RC2T4JeyiGpo30qGcfOqfXbIK5vrj+QLGY//EKgCY6Q0xlnS4tYsyLYMBjbipvuxrEEFTu98dGodj3NAmeWex7NM6QIICq5uDBDuV3nludP6q8t86KS2PtYbVJpFCSLu49LLiccfkoKnZUA+73aVYLPUPVJJq7HuWifhlxSAp7Icf3cbu/UC+Arv606zBx1yGAMmmTuM7+NpP9XotnhewHpv7IQVn5wZN99npuniDaufMpupeEC9SVSmbGZlJ5HQIDAQABAoIBAFxAMpHhHgEhr9PwydeyRGvQdJ+QHEr1OKu9fOnooIP0HRrtiKubEqGvU2rsr3aclm5HtFyyb+5czjKKytVGebsVvBz59wuobGa/fLB2NZ6pV/oIzcenyWloSV0jJ6SMa5P7Bs/KURnMe9sIoEv8EPrlRuu4kO1EJwM6ySh/vl9A/tm3YCayjHFR58dnrUovfAL/ODjqUs+XTCaCMadcBHMRyPQnqSwYQC10ZhoM0yamlEWboGrYJv1/iWVsDhaOlGeBc8gTYzO4Hl0UIhT9zSOndWzv3M1SN1zKbfcyY54nHgadoJJEABxZeqGkz8nm+8Qq/P6S+jsy6ZYJZbaK96ECgYEA/EYEuKwdM8H4gIvYxKqacZZWmV/3Tv6Iy/jHsJR/JcxdapsNYxX2rafnHpA14ef36AJVNaQCPWl1HwVLJt1ke1GF1lUzQ7CNhohyBnrPkIxa/KeEzACrKb8D68uqtma1BOOQY9i4SMad6590E6KpkVoKv8Mbt4HYQtPUdGaz3rkCgYEA2BB1vp/0eOKoLT4cll+smFKvm+0wf48DEn/siqZWcTwTYNY8i3MF22LT+/25/iaE7HnxjYxBvmY7aYZxRXrA4BYqyQFr5pWj9bjcQuPQ70VzaBqzNt8yGHjhhLdcZcg3ToP2yePcllqXmfmgov43wZRIONXUDh4Ox3XCozZUW4UCgYEAxRfnJGjDv+YxwiyARNaNJ2Uvb1C8pOjT85gAW4MI+3z5FwrgoKNOQEpHlWdR0Zcr5gW8kNX8MgKsUHoQRuL9WCNPDy8tiBrt10fO3iUg+WvOwkoWxFNZZpon9BcA3tTLZHBuJLPy9ljGlInVZGnXVQomD7/dbbdyQHPDLtX2fXECgYA4Js7y448de4pJd8LmMachTxvkYhggjoI15JkMz33Xn81JdWP2ucXj5iNBcgdMTaZt7qMgLjtHyDnYiuAVNnm5wwkLhBsOqgUabxiPKbW6+Ums3IOG1yqlwYSagSy6JvZ4qUMR5O4HBTQxB/b/ZSIIZQj//FJH+PfOToRu9kemIQKBgFQeIVNBgmUhHe1ygarcEfNp/yAwTxPsv1WAjlNEHfm0zAmdaxsnH7ibFH4D6A0O1Qtk/aeI4bAxS+U2YZSFxDx5QnOupvWQsDtOGDZD8w0q/5kTOYMEiF2iWgMnf+/D5dwLnOTcfVfETpICEj5owlCwTaFKZxzXDAizY5jJ3rXS',
     ];
    /**
     * 创建订单 调取支付
     */
    public function  getpay(){
        $post = $this->request->param();
        $order_id = date("YmdHis");   //订单编号
        $price=$post['price'];                //订单金额
//        $price=0.01;                //测试金额
//        创建订单
        Db::table('wp_balance')->insert([
            'bptype'=>3,          //收支类型,1充值成功,3正在充值,0提现,2后台改动4quxiao
            'balance_sn'=>$order_id,
            'bpprice'=>$price,
            'uid'=>$post['uid'],
            'isverified'=>0,      //未审核
            'pay_type'=>'支付宝',
            'remarks'=>'支付宝充值',
            'bptime'=>time()
        ]);
        if($post['type']==1){   //支付宝
            $this->alipay($order_id,$price);
        }
//        if($post['type']==3){   //微信
//            $this->weixin($order_id,$price);
//        }
    }

    /**
     * 支付宝
     * @return \Symfony\Component\HttpFoundation\Response|\Yansongda\Pay\Gateways\Alipay\WebGateway
     */
    private function alipay($order_id,$price)
    {
        $config = [
            'app_id'         => '2017102709555932',
            'notify_url'     => "http://".$_SERVER['SERVER_NAME'].'/index/pay/notify/type/alipay', //请勿修改此配置
            'return_url'     => "http://".$_SERVER['SERVER_NAME'].'/index/pay/returnx/type/alipay', //请勿修改此配置
            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1OtMY/aDtANcy4HeaXdgqGJ0gCxkUWg6EdD/iOYaD36d2xPbn35yamus0twFnpO9fX2H7tLGJ1U/i6uypWnO17RC2T4JeyiGpo30qGcfOqfXbIK5vrj+QLGY//EKgCY6Q0xlnS4tYsyLYMBjbipvuxrEEFTu98dGodj3NAmeWex7NM6QIICq5uDBDuV3nludP6q8t86KS2PtYbVJpFCSLu49LLiccfkoKnZUA+73aVYLPUPVJJq7HuWifhlxSAp7Icf3cbu/UC+Arv606zBx1yGAMmmTuM7+NpP9XotnhewHpv7IQVn5wZN99npuniDaufMpupeEC9SVSmbGZlJ5HQIDAQAB',
            //商户私钥，您的原始格式RSA私钥
            'private_key'    => 'MIIEowIBAAKCAQEA1OtMY/aDtANcy4HeaXdgqGJ0gCxkUWg6EdD/iOYaD36d2xPbn35yamus0twFnpO9fX2H7tLGJ1U/i6uypWnO17RC2T4JeyiGpo30qGcfOqfXbIK5vrj+QLGY//EKgCY6Q0xlnS4tYsyLYMBjbipvuxrEEFTu98dGodj3NAmeWex7NM6QIICq5uDBDuV3nludP6q8t86KS2PtYbVJpFCSLu49LLiccfkoKnZUA+73aVYLPUPVJJq7HuWifhlxSAp7Icf3cbu/UC+Arv606zBx1yGAMmmTuM7+NpP9XotnhewHpv7IQVn5wZN99npuniDaufMpupeEC9SVSmbGZlJ5HQIDAQABAoIBAFxAMpHhHgEhr9PwydeyRGvQdJ+QHEr1OKu9fOnooIP0HRrtiKubEqGvU2rsr3aclm5HtFyyb+5czjKKytVGebsVvBz59wuobGa/fLB2NZ6pV/oIzcenyWloSV0jJ6SMa5P7Bs/KURnMe9sIoEv8EPrlRuu4kO1EJwM6ySh/vl9A/tm3YCayjHFR58dnrUovfAL/ODjqUs+XTCaCMadcBHMRyPQnqSwYQC10ZhoM0yamlEWboGrYJv1/iWVsDhaOlGeBc8gTYzO4Hl0UIhT9zSOndWzv3M1SN1zKbfcyY54nHgadoJJEABxZeqGkz8nm+8Qq/P6S+jsy6ZYJZbaK96ECgYEA/EYEuKwdM8H4gIvYxKqacZZWmV/3Tv6Iy/jHsJR/JcxdapsNYxX2rafnHpA14ef36AJVNaQCPWl1HwVLJt1ke1GF1lUzQ7CNhohyBnrPkIxa/KeEzACrKb8D68uqtma1BOOQY9i4SMad6590E6KpkVoKv8Mbt4HYQtPUdGaz3rkCgYEA2BB1vp/0eOKoLT4cll+smFKvm+0wf48DEn/siqZWcTwTYNY8i3MF22LT+/25/iaE7HnxjYxBvmY7aYZxRXrA4BYqyQFr5pWj9bjcQuPQ70VzaBqzNt8yGHjhhLdcZcg3ToP2yePcllqXmfmgov43wZRIONXUDh4Ox3XCozZUW4UCgYEAxRfnJGjDv+YxwiyARNaNJ2Uvb1C8pOjT85gAW4MI+3z5FwrgoKNOQEpHlWdR0Zcr5gW8kNX8MgKsUHoQRuL9WCNPDy8tiBrt10fO3iUg+WvOwkoWxFNZZpon9BcA3tTLZHBuJLPy9ljGlInVZGnXVQomD7/dbbdyQHPDLtX2fXECgYA4Js7y448de4pJd8LmMachTxvkYhggjoI15JkMz33Xn81JdWP2ucXj5iNBcgdMTaZt7qMgLjtHyDnYiuAVNnm5wwkLhBsOqgUabxiPKbW6+Ums3IOG1yqlwYSagSy6JvZ4qUMR5O4HBTQxB/b/ZSIIZQj//FJH+PfOToRu9kemIQKBgFQeIVNBgmUhHe1ygarcEfNp/yAwTxPsv1WAjlNEHfm0zAmdaxsnH7ibFH4D6A0O1Qtk/aeI4bAxS+U2YZSFxDx5QnOupvWQsDtOGDZD8w0q/5kTOYMEiF2iWgMnf+/D5dwLnOTcfVfETpICEj5owlCwTaFKZxzXDAizY5jJ3rXS',
            'log'            => [
                // optional
                'file'  => LOG_PATH . '/epaylogs/alipay-' . date("Y-m-d") . '.log',
                'level' => 'debug'
            ],
            //            'mode'           => 'dev', // optional,设置此参数，将进入沙箱模式
        ];
        //创建支付对象
        $pay = Service::createPay('alipay', $config);

        //构建订单信息
        $order = [
            'out_trade_no' => $order_id,//你的订单号
            'total_amount' => $price,//单位元
            'subject'      => '微交易订单',
        ];
        //跳转或输出
        return $pay->wap($order)->send();
    }

    /**
     * 微信支付
     * @param $order_id
     * @param $price
     */
    private function weixin($order_id,$price)
    {
       $config = [
        'appid' => 'wx17bc7a102c9e2bd7', // APP APPID
        'app_id' => 'wx17bc7a102c9e2bd7', // 公众号 APPID
//        'miniapp_id' => 'wxb3fxxxxxxxxxxx', // 小程序 APPID
        'mch_id' => '1485245312',
        'key' => 'mF2suE9sU6Mk1Cxxxxxxxxxxx',
        'notify_url' => "http://".$_SERVER['SERVER_NAME'].'/index/pay/notify/type/alipay',
        'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
        'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
        'log' => [ // optional
                   'file' => './logs/wechat.log',
                   'level' => 'debug'
        ],
        'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
        ];
        //创建支付对象
        $pay = Service::createPay('weixin', $config);
        $order = [
            'out_trade_no' => time(),
            'total_fee' => '1', // **单位：分**
            'body' => 'test body - 测试',
            'openid' => 'onkVf1FjWS5SBIixxxxxxx',
        ];
        return $pay->mb($order)->send();
    }

    /**
     * 支付成功回调
     */
    public function notify()
    {

        $type = $this->request->param('type');
        $pay = Service::checkNotify($type,$this->alipay);
        if (!$pay) {
            echo '签名错误';
            return;
        }
        //你可以在这里你的业务处理逻辑,比如处理你的订单状态、给会员加余额等等功能
       $orderid = $this->request->param('out_trade_no');
      $info=  Db::table('wp_balance')->where('balance_sn',$orderid)->find();
      if($info && $info['isverified']==0){
          Db::table('wp_userinfo')->where('uid',$info['uid'])->setInc('usermoney',$info['bpprice']);   //用户充值
          Db::table('wp_balance')->where('bpid',$info['bpid'])->setField('isverified',1);       //改变状态
      }
        //下面这句必须要执行,且在此之前不能有任何输出
        echo $pay->success();
        return;
    }

    /**
     * 支付成功返回
     */
    public function returnx()
    {
        $type = $this->request->param('type');
        $result = Service::checkReturn($type,$this->alipay);
        if (!$result) {
            $this->error('签名错误');
        }
        //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
        $this->success("恭喜你！支付成功!", url("index/index"));

        return;
    }
}

?>
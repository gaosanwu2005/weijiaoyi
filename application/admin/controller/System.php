<?php
namespace app\admin\controller;
use addons\epay\library\Service;
use think\Controller;
use think\Db;
use app\common;

class System extends Base
{


public function __construct(){
		parent::__construct();
		
		if($this->otype != 3){
			echo '死你全家!';exit;
		}
		
}
	/**
	 * 基本信息管理
	 * @author lukui  2017-02-17
	 * @return [type] [description]
	 */
	public function setbasic()
	{
		if (input('post.')) {
			$data = input('post.');
			$data['webname'] = trim($data['webname']);
			if(!$data['webname']){
				return WPreturn('请输入平台名称!',-1);
			}
			if(!isset($data['webisopen']) || $data['webisopen'] == ''){
				$data['webisopen'] = 0;
			}else{
				$data['webisopen'] = 1;
			}



			if(!isset($data['festivalisrec']) || $data['festivalisrec'] == ''){
				$data['festivalisrec'] = 0;
			}else{
				$data['festivalisrec'] = 1;
			}

			if($data['id']){ //存在修改
				 $editconf = Db::name('conf')->update($data);
	            if($editconf){
	            	//分页、网站配置写入缓存 永久
	            	cache('page', $data['pagenum']);
	            	cache('conf', $data);
	                return WPreturn('修改成功',1);
	            }else{
	                return WPreturn('修改失败',-1);
	            }
			}else{ //不存在增加
				$addid = Db::name('conf')->insert($data);
	            if($addid){
	            	//分页、网站配置写入缓存，永久
	            	cache('page', $data['pagenum']);
	            	cache('conf', $data);
	                return WPreturn('添加成功',1);
	            }else{
	                return WPreturn('添加失败',-1);
	            }
			}




		}else{

			$conf = Db::name('conf')->find();
			$this->assign($conf);
			return $this->fetch();
		}

	}


	/**
	 * 添加后台管理员角色
	 * @author lukui  2017-02-18
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function addrole($value='')
	{


	}


	/**
	 * 数据备份列表
	 * @author lukui  2017-02-17
	 * @return [type] [description]
	 */
	public function dbbase()
	{
		$file = $data = array();
		$dir = "./databak/";
		$file = scandir($dir);
		unset($file[0]);
		unset($file[1]);
		$i = 1;
		foreach ($file as $key => $value) {
			$data[$key]['filename'] = $value;
			$data[$key]['id'] = $i;
			$handle = fopen($dir.$value,"r");
			$fstat= fstat($handle);
			$data[$key]['size'] = round($fstat["size"]/1024,2)."kb";
			$data[$key]['time'] = date("Y-m-d H:i:s",$fstat["mtime"]);
			$i++;
		}
		rsort($data);

		$this->assign('database',$data);
		return $this->fetch();
	}

	/**
	 * 数据备份到服务器
	 * @author lukui  2017-02-17
	 * @return [type] [description]
	 */
	public function backupsbase()
	{

		$type=input("tp");
        $name=input("name");
        $sql=new \org\Baksql(\think\Config::get("database"));
        switch ($type)
        {
        case "backup": //备份
          return $sql->backup();
          break;
        case "dowonload": //下载
          $sql->downloadFile($name);
          break;
        case "restore": //还原
          return $sql->restore($name);
          break;
        case "del": //删除
          return $sql->delfilename($name);
          break;
        default: //获取备份文件列表
            return $this->fetch("db_bak",["list"=>$sql->get_filelist()]);

        }


	}




	/**
	 * 微信配置
	 * @author lukui  2017-02-17
	 * @return [type] [description]
	 */
	public function setwx()
	{
		if (input('post.')) {
			$data = input('post.');
			foreach ($data as $key => $value) {
				$data[$key] = trim($value);
 			}


			if($data['wcid']){ //存在修改
				 $editwechat = Db::name('wechat')->update($data);
	            if($editwechat){
	                return WPreturn('修改成功',1);
	            }else{
	                return WPreturn('修改失败',-1);
	            }
			}else{ //不存在增加
				$addid = Db::name('wechat')->insert($data);
	            if($addid){
	                return WPreturn('添加成功',1);
	            }else{
	                return WPreturn('添加失败',-1);
	            }
			}
		}else{

			//微信配置信息
			$wechat = Db::name('wechat')->find();
			$this->assign($wechat);
			return $this->fetch();
		}

	}


	/**
	 * 幻灯片 添加和列表
	 * @author lukui  2017-03-26
	 * @return [type] [description]
	 */
	public function homepic()
	{
		if(input('post.')){
			$path =  '/public/uploads/';

            $file = request()->file('img');

            if(!isset($file)){
                $this->error("请上传图片");
            }

            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data['img'] = $path.$info->getSaveName();
            }else{
                echo $file->getError();
            }

            $ids = Db::name('slide')->insert($data);
            if($ids){
            	$this->success('添加成功',url('system/homepic'),1,1);
            }else{
            	$this->error('添加失败，请重试！');
            }

            die;

		}else{
			$slide = Db::name('slide')->where(array('isdelete'=>0))->select();
			$this->assign('slide',$slide);
			return $this->fetch();
		}


	}

	/**
	 * 删除幻灯片
	 * @author lukui  2017-03-26
	 * @return [type] [description]
	 */
	public function delslide()
	{

		$id = input('get.id',0);
        if(!$id){
            return WPreturn('参数错误',-1);
        }

        $del = Db::name('slide')->where('id', $id)->update(['isdelete' => 1]);
        if($del){
            return WPreturn('删除成功',1);
        }else{
            return WPreturn('删除失败',-1);
        }

	}

	/**添加管理员
	*/
	public function adminadd()
	{
		if(input('post.')){


		$data = input('post.');


		//去除空字符串，无用字符串
		$data = array_filter($data);
		unset($data['upwd2']);

		if(isset($data['uid'])){
			$user = Db::name('userinfo')->where('uid',$data['uid'])->field('utel,upwd,utime')->find();

			//修改密码
			if(isset($data['upwd']) && !empty($data['upwd'])){


				//验证用户密码
				$utime = Db::name('userinfo')->where('uid',$data['uid'])->value('utime');

				if(!isset($data['upwd']) || empty($data['upwd'])){
					return WPreturn('如需修改密码请输入新密码!',-1);
				}
				if(isset($data['upwd']) && isset($data['upwd2']) && $data['upwd'] != $data['upwd2']){
					return WPreturn('两次输入密码不同!',-1);
				}
				unset($data['upwd2']);
				unset($data['ordpwd']);
				$data['upwd'] = md5($data['upwd'].$utime);

			}



			$ids = Db::name('userinfo')->update($data);
		}else{
			$data['username'] = $data['nickname'];
			$data['utime'] = time();
			$data['upwd'] = md5($data['upwd'].$data['utime']);
			$data['otype'] = 1;

			$isset = Db::name('userinfo')->where(array('username'=>$data['username']))->value('uid');
			if($isset) return WPreturn('此用户已存在!',-1);
			//插入数据
			$ids = Db::name('userinfo')->insertGetId($data);
		}


		if ($ids) {
			return WPreturn('操作成功!',1);
		}else{
			return WPreturn('操作失败,请重试!',-1);
		}
	}
	if(input('param.uid')){
		$map['uid'] = input('param.uid');
		$user = db('userinfo')->where($map)->find();
		$this->assign($user);
		$this->assign('isedit',1);

	}
		return $this->fetch();
	}


	public function adminlist()
	{


		$map['otype'] = array('IN',array(1,3));
		$list = Db::name('userinfo')->where($map)->order('uid desc')->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	/**
	 * 清除用户openid
	 * @author lukui  2017-05-31
	 * @return [type] [description]
	 */
	public function clearopenid()
	{
		
		$data['openid'] = null;
		$where['uid'] = array('gt',0);
		$ids = db('userinfo')->where($where)->update($data);

		if( $ids ){
			$this->success('操作成功！');
		}else{
			$this->error('操作失败！');
		}


	}





	/**
	 * 银行卡列表
	 * @author lukui  2017-07-03
	 * @return [type] [description]
	 */
	public function banks()
	{
		

		$banks = db('banks')->select();

		$this->assign('banks',$banks);
		return $this->fetch();

	}

	/**
	 * 删除银行卡
	 * @author lukui  2017-07-03
	 * @return [type] [description]
	 */
	public function deletebanks()
	{
		
		$id = input('id');
		$ids = db('banks')->where('id',$id)->delete();
		if($ids){
			return WPreturn('删除成功',1);
		}else{
			return WPreturn('删除失败',-1);
		}
		

	}

	/**
	 * 编辑银行卡
	 * @author lukui  2017-07-05
	 * @return [type] [description]
	 */
	public function editbanks()
	{
		

		$post = input('post.');

		if(isset($post['id']) && !empty($post['id'])){
			$ids = db('banks')->update($post);
		}else{
			unset($post['id']);
			$ids = db('banks')->insert($post);
		}
		if($ids){
			return WPreturn('操作成功',1);
		}else{
			return WPreturn('操作失败',-1);
		}
		
	}

	/**
	 * 充值配置
	 * @author lukui  2017-07-05
	 * @return [type] [description]
	 */
	public function recharge()
	{
		
		$payment =db('payment')->where('isdelete',0)->order('pay_order desc')->select();
		
		$this->assign('payment',$payment);
		return $this->fetch();

	}

	/**
	 * 添加、编辑充值配置
	 * @author lukui  2017-07-05
	 * @return [type] [description]
	 */
	public function addrech()
	{
        $config =[
        'app_id'         => '2016082000295641',
        'notify_url'     => '/index/api/notify/type/alipay', //请勿修改此配置
        'return_url'     => '/index/api/returnx/type/alipay', //请勿修改此配置
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuWJKrQ6SWvS6niI+4vEVZiYfjkCfLQfoFI2nCp9ZLDS42QtiL4Ccyx8scgc3nhVwmVRte8f57TFvGhvJD0upT4O5O/lRxmTjechXAorirVdAODpOu0mFfQV9y/T9o9hHnU+VmO5spoVb3umqpq6D/Pt8p25Yk852/w01VTIczrXC4QlrbOEe3sr1E9auoC7rgYjjCO6lZUIDjX/oBmNXZxhRDrYx4Yf5X7y8FRBFvygIE2FgxV4Yw+SL3QAa2m5MLcbusJpxOml9YVQfP8iSurx41PvvXUMo49JG3BDVernaCYXQCoUJv9fJwbnfZd7J5YByC+5KM4sblJTq7bXZWQIDAQAB',
        // 加密方式： **RSA2**
        'private_key'    => 'MIIEpAIBAAKCAQEAs6+F2leOgOrvj9jTeDhb5q46GewOjqLBlGSs/bVL4Z3fMr3p+Q1Tux/6uogeVi/eHd84xvQdfpZ87A1SfoWnEGH5z15yorccxSOwWUI+q8gz51IWqjgZxhWKe31BxNZ+prnQpyeMBtE25fXp5nQZ/pftgePyUUvUZRcAUisswntobDQKbwx28VCXw5XB2A+lvYEvxmMv/QexYjwKK4M54j435TuC3UctZbnuynSPpOmCu45ZhEYXd4YMsGMdZE5/077ZU1aU7wx/gk07PiHImEOCDkzqsFo0Buc/knGcdOiUDvm2hn2y1XvwjyFOThsqCsQYi4JmwZdRa8kvOf57nwIDAQABAoIBAQCw5QCqln4VTrTvcW+msB1ReX57nJgsNfDLbV2dG8mLYQemBa9833DqDK6iynTLNq69y88ylose33o2TVtEccGp8Dqluv6yUAED14G6LexS43KtrXPgugAtsXE253ZDGUNwUggnN1i0MW2RcMqHdQ9ORDWvJUCeZj/AEafgPN8AyiLrZeL07jJz/uaRfAuNqkImCVIarKUX3HBCjl9TpuoMjcMhz/MsOmQ0agtCatO1eoH1sqv5Odvxb1i59c8Hvq/mGEXyRuoiDo05SE6IyXYXr84/Nf2xvVNHNQA6kTckj8shSi+HGM4mO1Y4Pbb7XcnxNkT0Inn6oJMSiy56P+CpAoGBAO1O+5FE1ZuVGuLb48cY+0lHCD+nhSBd66B5FrxgPYCkFOQWR7pWyfNDBlmO3SSooQ8TQXA25blrkDxzOAEGX57EPiipXr/hy5e+WNoukpy09rsO1TMsvC+v0FXLvZ+TIAkqfnYBgaT56ku7yZ8aFGMwdCPL7WJYAwUIcZX8wZ3dAoGBAMHWplAqhe4bfkGOEEpfs6VvEQxCqYMYVyR65K0rI1LiDZn6Ij8fdVtwMjGKFSZZTspmsqnbbuCE/VTyDzF4NpAxdm3cBtZACv1Lpu2Om+aTzhK2PI6WTDVTKAJBYegXaahBCqVbSxieR62IWtmOMjggTtAKWZ1P5LQcRwdkaB2rAoGAWnAPT318Kp7YcDx8whOzMGnxqtCc24jvk2iSUZgb2Dqv+3zCOTF6JUsV0Guxu5bISoZ8GdfSFKf5gBAo97sGFeuUBMsHYPkcLehM1FmLZk1Q+ljcx3P1A/ds3kWXLolTXCrlpvNMBSN5NwOKAyhdPK/qkvnUrfX8sJ5XK2H4J8ECgYAGIZ0HIiE0Y+g9eJnpUFelXvsCEUW9YNK4065SD/BBGedmPHRC3OLgbo8X5A9BNEf6vP7fwpIiRfKhcjqqzOuk6fueA/yvYD04v+Da2MzzoS8+hkcqF3T3pta4I4tORRdRfCUzD80zTSZlRc/h286Y2eTETd+By1onnFFe2X01mwKBgQDaxo4PBcLL2OyVT5DoXiIdTCJ8KNZL9+kV1aiBuOWxnRgkDjPngslzNa1bK+klGgJNYDbQqohKNn1HeFX3mYNfCUpuSnD2Yag53Dd/1DLO+NxzwvTu4D6DCUnMMMBVaF42ig31Bs0jI3JQZVqeeFzSET8fkoFopJf3G6UXlrIEAQ==',
        'log'            => [
            // optional
            'file'  => LOG_PATH . '/epaylogs/alipay-' . date("Y-m-d") . '.log',
            'level' => 'debug'
        ],
        'mode'           => 'dev', // optional,设置此参数，将进入沙箱模式
    ];
        //创建支付对象
        $pay = Service::createPay('alipay',$config);

        //构建订单信息
        $order = [
            'out_trade_no' => date("YmdHis"),//你的订单号
            'total_amount' => 0.01,//单位元
            'subject'      => 'FastAdmin企业支付插件测试订单',
        ];

        //跳转或输出
        return $pay->web($order)->send();


//		$post = input('post.');
//
//
//		if($post){
//
//			$data['pay_name'] = trim($post['pay_name']);
//			if($post['is_use'] != 1){
//				$data['is_use'] = 0;
//			}else{
//				$data['is_use'] = $post['is_use'];
//			}
//
//			if($post['pay_point'] < 0 || $post['pay_point'] > 100){
//				$data['pay_point'] = 0;
//			}else{
//				$data['pay_point'] = empty($post['pay_point'])?0:$post['pay_point'];
//			}
//
//
//
//			if(empty($post['pay_conf'])){
//				return WPreturn('请输入配置信息',-1);
//			}
//
//
//			$data['pay_conf'] = $post['pay_conf'];
//			$data['isdelete'] = 0;
//			$data['dotime'] = time();
//			$data['pay_order'] = $post['pay_order'];
//
//			if(isset($post['id']) && !empty($post['id'])){
//				$data['id'] = $post['id'];
//				$ids = db('payment')->update($data);
//			}else{
//				$ids = db('payment')->insert($data);
//			}
//			if($ids){
//				return WPreturn('操作成功',1);
//			}else{
//				return WPreturn('操作失败',-1);
//			}
//
//
//
//
//
//		}
//
//		$id = input('id');
//		if($id && is_numeric($id)){
//
//			$payment = db('payment')->where('id',$id)->find();
//			if(!$payment){
//				$this->error('参数错误！');
//			}
//
//
//
//			$this->assign($payment);
//		}
//
//		return $this->fetch();
	}

	/**
	 * 删除支付方式
	 * @author lukui  2017-07-05
	 * @return [type] [description]
	 */
	public function deletepay()
	{
		
		$id = input('id');
		$ids = db('payment')->where('id',$id)->update(array('isdelete'=>1));
		if($ids){
			return WPreturn('删除成功',1);
		}else{
			return WPreturn('删除失败',-1);
		}

	}












}

<?php
namespace Admin\Controller;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    //列表
    public function index()
    {
        $admin_model = D('Admin');
        $data = $admin_model->getAdminList();
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function addAdmin(){
        $uid               = session('admin_auth')['uid'];
            $verifysms = 0;//是否可以短信验证
            $sms_is_open = smsStatus();
            if($sms_is_open) {
                $adminMobileBind = adminMobileBind($uid);
                if($adminMobileBind) {
                    $verifysms = 1;
                }
            }
            //是否可以谷歌安全码验证
            $verifyGoogle = adminGoogleBind($uid);
              
        if(IS_POST){
            //防止跨站请求伪造
          if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== C('DOMAIN')) {
             $this->ajaxReturn(['status' => 0, 'msg' => '请使用正确地址访问后台']);
          }
          $auth_type = I('request.auth_type',0,'intval');
            if($verifyGoogle && $verifysms) {
                if(!in_array($auth_type,[0,1])) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "参数错误！"]);
                }
            } elseif($verifyGoogle && !$verifysms) {
                if($auth_type != 1) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "参数错误！"]);
                }
            } elseif(!$verifyGoogle && $verifysms) {
                if($auth_type != 0) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "参数错误！"]);
                }
            }
            if ($verifyGoogle && $auth_type == 1) {//谷歌安全码验证
                $google_code   = I('request.google_code');
                if(!$google_code) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "谷歌安全码不能为空！"]);
                } else {
                    $ga = new \Org\Util\GoogleAuthenticator();
                    $uid = session('admin_auth')['uid'];
                    $google_secret_key = M('Admin')->where(['id'=>$uid])->getField('google_secret_key');
                    if(!$google_secret_key) {
                        $this->ajaxReturn(['status' => 0, 'msg' => "您未绑定谷歌身份验证器！"]);
                    }
                    $oneCode = $ga->getCode($google_secret_key);
                    if($google_code !== $oneCode) {
                        $this->ajaxReturn(['status' => 0, 'msg' => "谷歌安全码错误！"]);
                    }
                }
            } elseif($verifysms && $auth_type == 0){//短信验证码
                $code   = I('request.code');
                if(!$code) {
                    $this->ajaxReturn(['status' => 0, 'msg'=>"短信验证码不能为空！"]);
                } else {
                    if (session('send.sysconfigSend') != $code || !$this->checkSessionTime('sysconfigSend', $code)) {
                        $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
                    } else {
                        session('send', null);
                    }
                }
            }
            $data=I("post.");
            if(!$data["username"]){
                $this->ajaxReturn(['status'=>0,'msg'=>'请输入用户名!']);
            }
            if(!$data["password"]){
                $this->ajaxReturn(['status'=>0,'msg'=>'请输入密码!']);
            }
            if($data["password"] != $data["reppassword"]){
                $this->ajaxReturn(['status'=>0,'msg'=>'两次输入密码不一致！']);
            }

            $data["password"]=md5($data["password"].C('DATA_AUTH_KEY'));
            $data["createtime"]=time();

            $admin_model = D('Admin');
            if($admin_model->field("id")->where(array("username"=>$data["username"]))->find()){
                $this->ajaxReturn(['status'=>0,'msg'=>'用户名已存在！']);
            }
            $add_admin_result = $admin_model->add($data);
            if($add_admin_result){
                //更新权限
               $groupAccess= M("auth_group_access")->where(array("uid"=>$add_admin_result))->find();
               if($groupAccess&&$groupAccess["group_id"]!=$data["groupid"]){
                   M("auth_group_access")->where(array("uid"=>$add_admin_result))->setField("group_id",$data["groupid"]);
               }else{
                   M("auth_group_access")->add(array("uid"=>$add_admin_result,"group_id"=>$data["groupid"]));
               }
            }
            $this->ajaxReturn(['status'=>$add_admin_result]);
        }else{
            //用户组
            $groups = M('AuthGroup')->where(['status' => 1])->field('id,title')->select();
            $this->assign('groups',$groups);
                      $uid = session('admin_auth')['uid'];
            $user = M('Admin')->where(['id'=>$uid])->find();
            $this->assign('mobile', $user['mobile']);
            $this->assign('verifysms', $verifysms);
            $this->assign('verifyGoogle', $verifyGoogle);
            $this->assign('auth_type', $verifyGoogle ? 1 : 0);
            $this->display();
        }

    }
    public function deleteAdmin()
    {    //防止跨站请求伪造
       if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== C('DOMAIN')) {
         $this->ajaxReturn(['status' => 0, 'msg' => '请使用正确地址访问后台']);
      }
        $id = I('id', 0, 'intval');
        if(!$id){
            parent::ajaxError('管理员不存在!');
        }

        $admin_model = D('Admin');
        $admin = $admin_model->findAdmin($id);
        if(!$admin){
            $this->ajaxReturn(['status'=>0,'msg'=>'角色不存在!']);
        }
        $change_result= $admin_model->delete($id);
        if($change_result){
            M("auth_group_access")->where(array("uid"=>$id))->delete();
        }
        $this->ajaxReturn(['status'=>$change_result]);
    }

    public function editAdmin(){
       $uid               = session('admin_auth')['uid'];
            $verifysms = 0;//是否可以短信验证
            $sms_is_open = smsStatus();
            if($sms_is_open) {
                $adminMobileBind = adminMobileBind($uid);
                if($adminMobileBind) {
                    $verifysms = 1;
                }
            }
            //是否可以谷歌安全码验证
            $verifyGoogle = adminGoogleBind($uid);
        if(IS_POST){
          //防止跨站请求伪造
           if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== C('DOMAIN')) {
             $this->ajaxReturn(['status' => 0, 'msg' => '请使用正确地址访问后台']);
         }
          $auth_type = I('request.auth_type',0,'intval');
            if($verifyGoogle && $verifysms) {
                if(!in_array($auth_type,[0,1])) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "参数错误！"]);
                }
            } elseif($verifyGoogle && !$verifysms) {
                if($auth_type != 1) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "参数错误！"]);
                }
            } elseif(!$verifyGoogle && $verifysms) {
                if($auth_type != 0) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "参数错误！"]);
                }
            }
            if ($verifyGoogle && $auth_type == 1) {//谷歌安全码验证
                $google_code   = I('request.google_code');
                if(!$google_code) {
                    $this->ajaxReturn(['status' => 0, 'msg' => "谷歌安全码不能为空！"]);
                } else {
                    $ga = new \Org\Util\GoogleAuthenticator();
                    $uid = session('admin_auth')['uid'];
                    $google_secret_key = M('Admin')->where(['id'=>$uid])->getField('google_secret_key');
                    if(!$google_secret_key) {
                        $this->ajaxReturn(['status' => 0, 'msg' => "您未绑定谷歌身份验证器！"]);
                    }
                    $oneCode = $ga->getCode($google_secret_key);
                    if($google_code !== $oneCode) {
                        $this->ajaxReturn(['status' => 0, 'msg' => "谷歌安全码错误！"]);
                    }
                }
            } elseif($verifysms && $auth_type == 0){//短信验证码
                $code   = I('request.code');
                if(!$code) {
                    $this->ajaxReturn(['status' => 0, 'msg'=>"短信验证码不能为空！"]);
                } else {
                    if (session('send.sysconfigSend') != $code || !$this->checkSessionTime('sysconfigSend', $code)) {
                        $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
                    } else {
                        session('send', null);
                    }
                }
            }
            $data=I("post.");
            if(!$data['id']){
                $this->ajaxReturn(['status'=>'error','msg'=>'管理员不存在!']);
            }

            if($data["epassword"]&&$data["epassword"] != $data["ereppassword"]){
                $this->ajaxReturn(['status'=>0,'msg'=>'两次输入密码不一致！']);
            }
            if($data["epassword"]){
                $data["password"]=md5($data["epassword"].C('DATA_AUTH_KEY'));
            }

            if(!$data['username']){
                $this->ajaxReturn(['status'=>0,'msg'=>'请输入用户名！']);

            }
            $admin_model = D('Admin');
            if($admin_model->field("id")->where(array("username"=>$data["username"],"id"=>array("neq",$data['id'])))->find()){
                $this->ajaxReturn(['status'=>0,'msg'=>'用户名已存在！']);
            }

            $admin_result = $admin_model->save($data);
            if($admin_result!==false){
                //更新权限
                $groupAccess= M("auth_group_access")->where(array("uid"=>$data['id'],"group_id"=>$data["groupid"]))->find();

                if($groupAccess){
                   // M("auth_group_access")->where(array("uid"=>$data['id']))->setField("group_id",$data["groupid"]);
                }else{
                    M("auth_group_access")->add(array("uid"=>$data['id'],"group_id"=>$data["groupid"]));
                }
                $this->ajaxReturn(['status'=>1,'msg'=>'修改成功!']);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'修改失败!']);
            }

        }else{
            $id = I('id', 0, 'intval');
            $admin_model = D('Admin');
            $admin_info = $admin_model->findAdmin($id);
            //用户组
            $groups = M('AuthGroup')->field('id,title')->select();
            $this->assign('groups',$groups);
            $this->assign('admin_info', $admin_info);
             $uid = session('admin_auth')['uid'];
            $user = M('Admin')->where(['id'=>$uid])->find();
            $this->assign('mobile', $user['mobile']);
            $this->assign('verifysms', $verifysms);
            $this->assign('verifyGoogle', $verifyGoogle);
            $this->assign('auth_type', $verifyGoogle ? 1 : 0);
            $this->display();
        }
    }


    /**
     * 通用分页列表数据集获取方法
     *
     * 可以通过url参数传递where条件,例如: index.html?name=asdfasdfasdfddds
     * 可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     * 可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model $model
     *            模型名或模型实例
     * @param array $where
     *            where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order
     *            排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *            请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *            否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array $base
     *            基本的查询条件
     * @param boolean $field
     *            单表模型用不到该参数,要用在多表join时为field()方法指定参数
     *
     * @return array|false 返回数据集
     */
    protected function lists($model, $where = array(), $order = '', $base = array('status'=>array('egt',0)), $field = true)
    {
        $options = array();
        $REQUEST = (array) I('request.');
        if (is_string($model)) {
            $model = M($model);
        }

        $OPT = new \ReflectionProperty($model, 'options');
        $OPT->setAccessible(true);

        $pk = $model->getPk();
        if ($order === null) {
            // order置空
        } else
            if (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array(
                'desc',
                'asc'
            ))) {
                $options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
            } elseif ($order === '' && empty($options['order']) && ! empty($pk)) {
                $options['order'] = $pk . ' desc';
            } elseif ($order) {
                $options['order'] = $order;
            }
        unset($REQUEST['_order'], $REQUEST['_field']);

        $options['where'] = array_filter((array) $where, function ($val) {
            if ($val === '' || $val === null) {
                return false;
            } else {
                return true;
            }
        });
        if (empty($options['where'])) {
            unset($options['where']);
        }
        $options = array_merge((array) $OPT->getValue($model), $options);
        $total = $model->where($options['where'])->count();
        if (isset($REQUEST['r'])) {
            $listRows = (int) $REQUEST['r'];
        } else {
            $listRows = 15;
        }
        $page = new \Think\Page($total, $listRows);
        $this->assign('_page', $p = $page->show());
        $this->assign('_total', $total);
        $options['limit'] = $page->firstRow . ',' . $page->listRows;

        $model->setProperty('options', $options);

        return $model->field($field)->select();
    }
}
?>

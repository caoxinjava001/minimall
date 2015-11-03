<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2015/5/26
 * Time: 15:00
 */

class Manage extends MY_Controller{
    private $where; //where条件
    private $perpage=10; //每页条数

    public function __construct(){
        parent::__construct();
        $this->load->model('admin_user_model');
        $this->load->model('role_info_model');
        $this->load->model('bind_next_model');
		$this->load->model('Member_org_model');
        $this->page=$this->input->get('page')>=1?$this->input->get('page'):0;
        $this->where.= 'login_role_id = '.PARTNER_ADMIN;  // 合伙人
        $this->where.= ' and id not in (1) ';  // 过滤 Admin 超级用户

    }

    /**
     * 创建管理员页面
     */
    public function index(){
        $data=array();
        $id = $this->input->get_post('id')?intval(trim($this->input->get_post('id'))):0;
        if($id){ //修改用户
            $r = $this->admin_user_model->get_one('*','id ='.$id);          
            if($r){
                $data['data_info'] = $r;
            }
        }
        $data['roler']=$this->getAllRole();
        //echo '<pre>';
        //print_r($data);exit;
        $this->rendering_admin_template($data,'manage','man_create');
    }

    /**
     * 所有管理员信息
     */
    public function allManager(){
        //$s_name = $this->input->get_post('s_name')?trim($this->input->get_post('s_name')):'';
        //$role_id=$this->input->get_post('role_id');
        //$dele_status=$this->input->get_post('dele_status');
        //
        //if(!empty($s_name)){
        //    $this->where.=" and name like '%".$s_name."%'"; //角色
        //}

        //if($role_id){
        //    $role_id=intval($role_id);
        //    $this->where.=" and role_id =".$role_id; //角色
        //}

        //if(strlen($dele_status)>0){
        //    $dele_status= intval($dele_status);
        //    $this->where.=" and dele_status =".$dele_status; //冻结
        //}

        //$result=$this->admin_user_model->list_info('*',$this->where,$this->page,$this->perpage);
        //foreach($result as $k=>$v){
        //    $result[$k]['role_name']=$this->getRoleName($v['role_id']);
        //    $result[$k]['next_audit']=$this->getAllNext($v['role_id']);
        //    $result[$k]['next_id']=$this->getNextId($v['id']);
        //}

        //$data['data']=$result;
        //$data['s_name']=$s_name;
        //$data['sel_role']=$role_id;
        //$data['dele_status']=$dele_status;
        //$data['all_role']=$this->getAllRole();

        //$data['pages']=pages($this->admin_user_model->getCount($this->where),$this->page,$this->perpage);

        $this->rendering_admin_template($data,'manage','man_list');
    }

    /**
     * 按role_id获取用户角色名称
     * @param $id
     * @return mixed
     */
    private function getRoleName($id){
        $ret=$this->role_info_model->getRoleInfoByRoleId($id);
        return $ret['name'];
    }
    /**
     * 获取所有用户角色名称
     * @param $id
     * @return mixed
     */
    private function getAllRole(){
        return $this->role_info_model->getRoleInfo();
    }

    /**
     * 获取下游审批人
     * @param $id
     * @return mixed
     */
    private function getAllNext($id){
        $ret=$this->role_info_model->getHighRoleInfoByRoleId($id);
        $where=array(
            'dele_status'=>NO_DELETE_STATUS,
            'role_id'=>$ret['id']
        );
        return $this->admin_user_model->select('id,name,role_id',$where);
    }

    /**
     * 获取用户绑定的下游审批人id
     * @param $id
     * @return mixed
     */
    private function getNextId($id){
        $ret=$this->bind_next_model->get_one('next_id',array('current_id'=>$id));
        return $ret['next_id'];
    }
    /**
     * ajax 处理管理员新建
     *
     */
    public function createManager(){
        $id = $this->input->get_post('id')?$this->input->get_post('id'):0; //会员id
        $role_id= $this->input->get_post('role_id');
        $name= $this->input->get_post('name');
        $mobile= $this->input->get_post('mobile');
        $service_code= $this->input->get_post('service_code');
        $password= $this->input->get_post('password');
        $repassword= $this->input->get_post('repassword');
        //判断角色id
        if($role_id==0 && $id<>1){ //id=1 是超级管理员
            $data=array(
                'status'=>0,
                'msg'=>'请选择角色！'
            );
            exit(json_encode($data));
        }
        //用户名
        if(!$name){
            $data=array(
                'status'=>0,
                'msg'=>'请填写用户名！'
            );
            exit(json_encode($data));
        }
        //手机号不能为空
        if(!$mobile){
            $data=array(
                'status'=>0,
                'msg'=>'请填写手机号码！'
            );
            exit(json_encode($data));
        }
        //判断手机号是否唯一
        $where_m = "mobile ='".$mobile."' and id<>".$id;
        if($this->admin_user_model->get_one('*',$where_m)){
            $data=array(
                'status'=>0,
                'msg'=>'手机号码已存在！'
            );
            exit(json_encode($data));
        }
        //手机号码的合法性
        if(!preg_match("/^13[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|15[0-9]{9}$|18{1}[0-9]{9}$/",$mobile)){
            $data = array(
                'status' => 0,
                'msg' => '手机号不合法！',
            );
            exit(json_encode($data));
        }
        //判断密码是否一致
        if(!empty($password) && !empty($repassword)){
            if(strlen(trim($password))<6){
                $data=array(
                    'status'=>0,
                    'msg'=>'密码长度至少6位！'
                );
                exit(json_encode($data));

            }elseif( trim($password) !== trim($repassword)){
                $data=array(
                    'status'=>0,
                    'msg'=>'密码不一致！'
                );
                exit(json_encode($data));
            }
            //密码加密存放
            $encrypt = randomstr();
            $post_data['password'] = encryptMd5(trim($password),$encrypt);
            $post_data['encrypt'] = $encrypt;
        }else{ //新增
            if(!$id){
                    $data=array(
                        'status'=>0,
                        'msg'=>'密码不能为空！'
                    );
                    exit(json_encode($data));
            }
        }

        $post_data['role_id']=$role_id;
        $post_data['name']=$name;
        $post_data['mobile']=$mobile;
        $post_data['service_code']=$service_code;
        $post_data['login_role_id']=PARTNER_ADMIN;
        $post_data['status']=VER_HAD_AUDIT;

        //echo '<pre>';
        //print_r($post_data);exit;
        //执行插入
        if(!$id){
                $r = $this->admin_user_model->insert($post_data,true);
                $msg = '创建成功！';
        }else{
                $r = $this->admin_user_model->update($post_data,'id ='.$id);
                if($r){
                    $r = $id; 
                }
                $msg = '修改成功！';
        }
        //echo $r;exit;
        if($r) {
            $data = array(
                'status' => 1,
                'data' => $r,
                'msg' => $msg, 
            );
                if(!$id){//add
                //绑定合伙人列表和我的客户列表权限(insert) 开始
                    $menu_info_id = array();
                    $menu_info_code = array();
        
                    $param_auth_id = array(1,3,10); //合伙人列表id  我的客户列表id

                    foreach($param_auth_id as $k=>$v){
                        $menu_info_id[] = $v;
                        $menu_info_code[] = $this->mem_menu_list[$v]['code'];
                    }
       
                    $data_info['org_id'] = $this->mid;
                    $data_info['member_id'] = $r;
                    $data_info['menu_id'] = implode(',',$menu_info_id);
                    $data_info['menu_code'] = implode(',',$menu_info_code);
                    $data_info['status'] = CLOUD_MENU_MEMBER_LOOK_STATUS_OPEN;//开启
                    $data_info['created_time'] = date('Y-m-d H:i:s',time());
                    $data_info['created_by'] = $this->mid;
                    //echo '<pre>';
                    //print_r($data_info);exit;

                    $privilege_id = $this->Member_org_model->insert($data_info,true);
               //绑定合伙人列表和我的客户列表权限 结束
               }
            exit(json_encode($data));
        }
    }
    public function bindNext(){
        $current_id=$this->input->get_post('id');
        $next_id=$this->input->get_post('role_id');
        if(!$current_id){
            $data=array(
                'status'=>0,
                'msg'=>'管理员不存在！'
            );
            exit(json_encode($data));
        }
        $up_date=array(
            'current_id'=>$current_id,
            'next_id'=>$next_id
        );
        $where['current_id']=$current_id;
        $is=$this->bind_next_model->getCount($where);

        if($is){
            $id=$this->bind_next_model->update($up_date,$where);
        }else{
            $id=$this->bind_next_model->insert($up_date);
        }

        if($id){
            $data=array(
                'status'=>1,
                'msg'=>'绑定下游审核成功！'
            );
            exit(json_encode($data));
        }
    }
}

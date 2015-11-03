<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2015/5/22
 * Time: 14:31
 */

class Audit extends MY_Controller {
    private $where; //where条件
    private $perpage=10; //每页条数

    public function __construct(){
        parent::__construct();
        $this->load->model('admin_user_model','partner');
        $this->page=$this->input->get('page')>=1?$this->input->get('page'):0;

        $this->where.= 'login_role_id in ('.PARTNER_ORG.','.PARTNER_PERSONAL.')';  // 合伙人
		$this->load->model('Member_org_model');
		//var_dump($this->member_info);exit;
    }


    /**
     * 获取未审核的合伙人列表
     */
    public function index(){

        $this->where= 'dele_status = '.NO_DELETE_STATUS;    //未冻结
        $this->where.=' and status ='.VER_IN_AUDIT; //未审核

        $data=$this->partner->list_info('*',$this->where,$this->page,$this->perpage);
        foreach($data as $k=>$v){
            $name=$this->partner->getIntro($v['intro_id']);
            $data[$k]['intro_name']=$name['name'];
        }
        $data['data']=$data;
        $data['pages']=pages($this->partner->getCount($this->where),$this->page,$this->perpage);

        $this->rendering_admin_template($data,'copartner','audit_list');
    }

    /**
     * 获取所有的合伙人列表
     */
    public function allMember(){

		RunAction($this->member_info);
		echo "非法用户，请联系管理员";
		exit;
        $status=$this->input->get('status');
        $dele_status=$this->input->get('dele_status');

        if(strlen($status)>0){
            $status=intval($status);
            $this->where.=" and status =".$status; //审核
        }else{
            $status="";
        }
        if(strlen($dele_status)>0){
            $dele_status= intval($dele_status);
            $this->where.=" and dele_status =".$dele_status; //冻结
        }else{
            $dele_status="";
        }

        $data=$this->partner->list_info('*',$this->where,$this->page,$this->perpage);

        foreach($data as $k=>$v){
            $name=$this->partner->getIntro($v['intro_id']);
            $data[$k]['intro_name']=$name['name'];
        }

        $data['data']=$data;
        $data['status']=$status;
        $data['dele_status']=$dele_status;

        $data['pages']=pages($this->partner->getCount($this->where),$this->page,$this->perpage);

        $this->rendering_admin_template($data,'copartner','audit_all_member');
    }

    /**
     * 修改用户信息页面
     */
    public function edit(){
        $id=$this->uri->segment(3);
        $result=$this->partner->get_one('*','id = '.$id);
        $name=$this->partner->getIntro($result['intro_id']);//获取介绍人姓名
        $result['intro_name']=$name['name'];
        $data['data']=$result;
        $this->rendering_admin_template($data,'copartner','audit_member_edit');

    }

    /**
     * 执行修改用户信息
     */
    public function editMemberInfo(){
        $member_info=$this->input->get_post('member_info')?$this->input->get_post('member_info'):array();
        $where['id']=$member_info['id'];
        $bool=$this->partner->update($member_info,$where);
        if($bool){
            $data=array(
                'status'=>1,
                'msg'=>'修改成功！'
            );
        }else{
            $data=array(
                'status'=>0,
                'msg'=>'修改失败！'
            );
        }
        exit(json_encode($data));
    }
    /**
     * ajax处理审核和冻结
     * @param type
     * @param ids 处理的id,多个用 ","分割
     * @return json
     */
    public function ajaxManage(){
        $ids=$this->input->get_post('id');
        $type=$this->input->get_post('type');
        $up=$this->input->get_post('up');
        $tip=$this->input->get_post('tip');
        $data=array(
            'status'=>0,
            'msg'=>'操作失败！',
            'data' => '',
        );
        if($ids && $type) {
            $where='id in ('.$ids.')';
            $up_data=$this->getUpWhere($up,$type,$tip);
            $bool=$this->partner->update($up_data,$where);
            if($bool){
                $data=array(
                    'status'=>1,
                    'msg'=>'操作成功！',
                    'data' => $ids,
                );

                //绑定合伙人列表和我的客户列表权限(insert) 开始
                    $menu_info_id = array();
                    $menu_info_code = array();
        
                    $param_auth_id = array(1,3,10); //合伙人列表id  我的客户列表id

                    foreach($param_auth_id as $k=>$v){
                        $menu_info_id[] = $v;
                        $menu_info_code[] = $this->mem_menu_list[$v]['code'];
                    }
       
                    $data_info['org_id'] = $this->mid;
                    $data_info['member_id'] = $ids;
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
        exit(json_encode($data));
    }

    /**
     * 获取要更新的字段和值
     * @param $up
     * @param $type
     * @param $tip
     * @return mixed
     */
    private function getUpWhere($up,$type,$tip){
        switch($up){
            case 'dele_status':
                if ($type == 1) {
                    $up_data['dele_status']=0;
                }
                if ($type == 2) {
                    $up_data['dele_status']=1;
                }
                break;
            case 'status':
                if ($type == 1) {
                    $up_data['status']=2;
                }
                if ($type == 2) {
                    $up_data['status']=0;
                    $up_data['reject_desc']=$tip;
                }
                break;
        }
        return $up_data;
    }
}

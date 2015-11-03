<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2015/5/27
 * Time: 10:35
 */
class Ent_audit_log_Model extends MY_Model{

    public function __construct() {
        parent::__construct();
        $this->_table = "yt_ent_audit_log";	// 表名
        $this->initDB();
    }

    /**
     * 获取投资经理名
     * @param $ent_id
     * @param $step
     * @return mixed
     */
    public function getManager($ent_id){
//        $where=array(
//            'step_status'=>STEP_TWO,
//            'status'=>VER_HAD_AUDIT,
//            'ent_id'=>$ent_id,
//            'role_id'=>ROLE_FIVE,
//        );
//        $manage_id=$this->get_one('manage_id',$where);
//
        //获取经理名
        $this->load->model('admin_user_model');
        $this->load->model('user_model');
//
//        if(empty($manage_id)){
            $where1=array(
                'id'=>$ent_id,
//                'obj_status'=>1,
            );
            $manage_id=$this->user_model->get_one('manage_id',$where1);
//        }
//
//        if(empty($manage_id)){
//            return  "";
//        }

        $where2=array(
            'id'=>$manage_id['manage_id']
        );
        $manage_name=$this->admin_user_model->get_one('name',$where2);
        return $manage_name;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2015/6/9
 * Time: 16:19
 */

class Super_get_model extends MY_Model{
    private $industry=array();//行业
    private $type=array();  //上市方式

    public function __construct() {
        parent::__construct();
        $this->_table ;
        $this->initDB();
        $this->industry=array(
            array('id'=>1,'name'=>'旅游'),
            array('id'=>2,'name'=>'软件'),
            array('id'=>3,'name'=>'餐饮'),
            array('id'=>4,'name'=>'金融'),
        );
        $this->type=array(
            array('id'=>1,'name'=>'大型企业'),
            array('id'=>2,'name'=>'中型企业'),
            array('id'=>3,'name'=>'小型企业'),
            array('id'=>4,'name'=>'国有企业'),
        );

    }

    /**
     * 获取行业列表
     * @return array
     */
    public function getAllIndustry(){
        $this->_table='yt_ent_sort';
        $where=array(
            'dele_status'=>NO_DELETE_STATUS
        );
        $ret=$this->select('*',$where);
        return $ret;
    }

    /**
     * 根据id获取行业名称
     * @param $id
     * @return mixed
     */
    public function getIndustryById($id){
        $this->_table='yt_ent_sort';
        $where=array(
            'dele_status'=>NO_DELETE_STATUS,
            'id'=>$id
        );
        $ret=$this->get_one('*',$where);
        return $ret;
    }
    /**
     * 获取企业上市方式列表
     * @return array
     */
    public function getAllType(){
        return  $this->type;
    }

    /**
     * 根据id获取企业上市类型
     * @param $id
     * @return mixed
     */
    public function getTypeById($id){
        foreach($this->type as $v){
            if($id==$v['id']){
                return $v;
                break;
            }
        }
    }

    /**
     * 获取项目状态
     * @param $ent_id 项目id
     * @return string
     */
    public function getObjStatus($ent_id){
        $this->_table="yt_user";

        $where=array(
            'id'=>$ent_id,
        );
        $res=$this->get_one('*',$where);

        switch($res['obj_status']){
            case OBJ_NONE:
                $status="未立项";
                break;
            case OBJ_ONE:
                $status="尽调进行中";
                break;
            case OBJ_TWO:
                $status="待投资总监审核";
                break;
            case OBJ_THREE:
                $status="待投资总经理审核";
                break;
            case OBJ_FOUR:
                $status="已上会讨论";
                break;
        }

        if($res['status']==VER_NOT_AUDIT){
            $status= "已否决";
        }elseif($res['status']==VER_HAD_AUDIT){
            $status= "终审通过";
        }

        return $status;
    }

    /**
     * 获取上一步审核人附言
     * @param $ent_id   企业id
     * @param $mid      管理员id
     * @param $type     0为前台，1为后台
     * @param int $status   审核通过状态
     * @param int $obj_status   资料审核、项目审核阶段
     * @return mixed
     */
    public function getPreSuggest($ent_id ,$mid,$type=1,$status=VER_HAD_AUDIT,$obj_status=STEP_ONE){
        $select = 'u.id AS id,e.manage_id,u.org_name,e.sur_mark,e.pro_mark,e.status,a.name,u.obj_status,u.status AS u_status,e.step_status';    //字段

        //根据前后台获取查询条件
        if($type){
            $where = 'e.dele_status = '.NO_DELETE_STATUS.' AND e.ent_id = '.$ent_id.' AND e.step_status ='.$obj_status;   //条件
            $where .= ' AND e.status = '.$status.' AND u.next_id = '. $mid;
        }else{
            $where = 'e.dele_status = '.NO_DELETE_STATUS.' AND e.ent_id = '.$ent_id.' AND e.step_status ='.$obj_status;   //条件
        }

        $this->db->select($select);
        $this->db->from('yt_ent_audit_log AS e');
        $this->db->join('yt_user AS u', 'u.id = e.ent_id');
        $this->db->join('yt_admin_user AS a', 'e.manage_id = a.id');
        $this->db->where($where);
        $this->db->order_by('e.id', 'desc');
        $this->db->limit(1);
        $data = $this->db->get()->result_array();

        return $data[0];
    }

    /**
     * 获取企业分类
     * @param int $level
     * @param int $pid
     * @return mixed
     */
    public function getIndustry($level=1,$pid=0){
        $this->_table='yt_cate';
        $where=array(
            'level'=>$level,
            'parentid'=>$pid,
        );
        $industry=$this->select('*',$where,1000,'id asc');
        return $industry;
    }

    /**
     * 获取评测结果
     * @param $id
     * @return array
     */
    public function getResult($id){
        $this->_table='yt_result';
        return $this->get_one('*',array('id'=>$id));
    }

    /**
     * 获取分数对应的结果
     * @param $score
     * @return array
     */
    public function getResId($score){
        $this->_table='yt_score';
        return $this->get_one('result',array('score'=>$score));
    }
}
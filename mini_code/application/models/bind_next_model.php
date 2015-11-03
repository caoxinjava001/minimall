<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2015/5/27
 * Time: 10:35
 */
class Bind_next_Model extends MY_Model{

    public function __construct() {
        parent::__construct();
        $this->_table = "yt_bind_next";	// 表名
        $this->initDB();
    }
}
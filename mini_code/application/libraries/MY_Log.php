<?php

/**
 * 记录日志类
 * @author jackcao@159jh.com
 * $level = array('ERROR', 'DEBUG',  'INFO', 'ALL')
 */
class MY_Log extends CI_Log {
	private $logDir = 'public_log';//公共日志目录
	private $logPath;//项目绝对地址 /application/logs
	public function __construct(){
		parent::__construct();
		$this->logPath = $_SERVER['DOCUMENT_ROOT'].'/application/logs/';
	}


	/**
	 * 记录日志
	 * @param string $msg 日志信息
	 * @param string $level 日志类别
	 * @param string $destination 指定目录
	 */
	public function write_log_user($level = 'ERROR',$msg,$dir=''){
		if(empty($dir)){
			$logSaveDir = $this->logPath.$this->logDir;
		}else{
			$logSaveDir = $this->logPath.$dir.'_log';
		}
		$this->makeDir($logSaveDir);
		$logName = date('Ymd').'.log'; //日志名
		$date = date('Y-m-d H:i:s',time());
		error_log("{$level} -- {$date} --> {$msg}\r\n", 3,$logSaveDir.'/'.$logName);
		return true;
	}

	/**
	 * 生成目录
	 * @param string $dir
	 */
	private function makeDir($dir){
		if(!is_dir($dir)){
			mkdir($dir , 0777,true);
		}
	}
}

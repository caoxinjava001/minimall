<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2012 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource	
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Memcached Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */

class CI_Cache_memcached extends CI_Driver {

	private $_memcached;	// Holds the memcached object

	protected $_memcache_conf 	= array(
					'default' => array(
						'default_host'		=> '127.0.0.1',
						'default_port'		=> 31301,
						'default_weight'	=> 1
					)
				);

	// ------------------------------------------------------------------------	

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 * @desc update function get  order to support memcachedb
	 * @update author :  jackcao
	 $ @update desc : memcachedb   没有过期时间 ，应用层中增加 过期时间 ，方便 定时操作
	 $ @update $is_valid :  true  为  放什么存什么 没有过期时间，false 为 有过期时间 
	 */	
	public function get($id,$is_valid=true) {	
		if ($is_valid) {
			$ret = $this->getMemcaheBb($id); 
			return empty($ret) ? FALSE : $ret;
		} 
		$ret = $this->getMemcaheBbExpire($id); 
		return empty($ret) ? FALSE : $ret;
		//return $ret;
		// by jackcao delete 
		//$data = $this->_memcached->get($id);
		//
		//return (is_array($data)) ? $data[0] : FALSE;
	}


	/**
	 * getAll 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 * @desc  get  memcached data in memcachedb  没有过期时间
	 * @author  by jackcao
	 * @update author :  jackcao
	 $ @update desc : memcachedb   没有过期时间
	 */
	public function getMemcaheBb($id) {	
		$data = $this->_memcached->get($id);
		$data = json_decode($data,true);
		return $data;
	}


	/**
	 * getAll 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 * @desc  get  memcached data in memcachedb 增加过期 处理数据 
	 * @author  by jackcao
	 $ @update desc : memcachedb   设置有过期时间
	 */
	public function getMemcaheBbExpire($id) {	
		$data = $this->_memcached->get($id);
		$data = json_decode($data,true);
		if ( !is_array($data) ) {
			return FALSE;
		}
		$add_time = !empty($data[1]) ? intval($data[1]) : 0;
		if (!$add_time) {
			return FALSE;
		}
		$limit_time = !empty($data[2]) ? intval($data[2]) : 0;
		if ( !$limit_time ) {
			return FALSE;
		}
		$is_valid = $this->isValidData($add_time, $limit_time);
		
		// 如果时间过期  删除memcahedb数据  
		if ( !$is_valid ) {
			$this->delete($id);
			return FALSE;
		}
		return $data[0];
	}
	
	/**
	 * isValidData
	 *
	 * @access private
	 * @return void
	 * @desc  this function is valid  data for memcachdb time
	 * @author  by jackcao
	 */
	private function isValidData($add_time, $limit_time) {
		$now_time = time();
		$now_limit_time = $now_time - $add_time;
		return $now_limit_time >= $limit_time ? FALSE : TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Save
	 *
	 * @param 	string		unique identifier
	 * @param 	mixed		data being cached
	 * @param 	int			time to live
	 * @return 	boolean 	true on success, false on failure
	 * @desc  get  memcached data in memcachedb 增加过期 处理数据 
	 * @author  by jackcao
	 $ @update desc : memcachedb   设置过期时间
	 */
	public function saveExpire($id, $data, $ttl = 60)
	{
		if (get_class($this->_memcached) == 'Memcached')
		{
			return $this->_memcached->set($id, json_encode(array($data, time(), $ttl)), $ttl);
		}
		else if (get_class($this->_memcached) == 'Memcache')
		{
			return $this->_memcached->set($id, json_encode(array($data, time(), $ttl)), 0, $ttl);
		}
		
		return FALSE;
	}



	/**
	 * save 
	 * 
	 * @param mixed $id 
	 * @param mixed $data 
	 * @param int $ttl 
	 * @access public
	 * @return void
	 * @author  jackcao
	 * @desc    重构此方法  跟php python   java数据一致  放什么 就存什么 不做处理
	 */
	public function save($id, $data, $ttl = 60)
	{
		if (get_class($this->_memcached) == 'Memcached')
		{
			return $this->_memcached->set($id, json_encode($data), $ttl);
		}
		else if (get_class($this->_memcached) == 'Memcache')
		{
			return $this->_memcached->set($id, json_encode($data), 0, $ttl);
		}
		return FALSE;
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		key to be deleted.
	 * @return 	boolean 	true on success, false on failure
	 */
	public function delete($id)
	{
		return $this->_memcached->delete($id);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */
	public function clean()
	{
		return $this->_memcached->flush();
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param 	null		type not supported in memcached
	 * @return 	mixed 		array on success, false on failure
	 */
	public function cache_info($type = NULL)
	{
		return $this->_memcached->getStats();
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		FALSE on failure, array on success.
	 */
	public function get_metadata($id)
	{
		$stored = $this->_memcached->get($id);

		if (count($stored) !== 3)
		{
			return FALSE;
		}

		list($data, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Setup memcached.
	 */
	private function _setup_memcached()
	{
		// Try to load memcached server info from the config file.
		$CI =& get_instance();
		if ($CI->config->load('memcached', TRUE, TRUE))
		{
			if (is_array($CI->config->config['memcached']))
			{
				$this->_memcache_conf = NULL;

				foreach ($CI->config->config['memcached'] as $name => $conf)
				{
					$this->_memcache_conf[$name] = $conf;
				}				
			}			
		}
		
// 		$this->_memcached = new Memcached();
		if (extension_loaded('memcached')){
			$this->_memcached = new Memcached();
		}else{
			$this->_memcached = new Memcache();
		}
		
		foreach ($this->_memcache_conf as $name => $cache_server)
		{
			if ( ! array_key_exists('hostname', $cache_server))
			{
				// $cache_server['hostname'] = $this->_default_options['default_host']; //坑爹的原代码,没有_default_options属性
				$cache_server['hostname'] = $this->_memcache_conf['default']['default_host'];
			}
	
			if ( ! array_key_exists('port', $cache_server))
			{
				// $cache_server['port'] = $this->_default_options['default_port'];//坑爹的原代码,没有_default_options属性
				$cache_server['port'] = $this->_memcache_conf['default']['default_port'];
			}
	
			if ( ! array_key_exists('weight', $cache_server))
			{
				//  $cache_server['weight'] = $this->_default_options['default_weight'];//坑爹的原代码,没有_default_options属性
				$cache_server['weight'] = $this->_memcache_conf['default']['default_weight'];
			}
			if (extension_loaded('memcached')){
				$this->_memcached->addServer($cache_server['hostname'], $cache_server['port'], $cache_server['weight']);
			}else{
				$this->_memcached->addServer($cache_server['hostname'],$cache_server['port'],TRUE, $cache_server['weight']);
			}
// 			$this->_memcached->addServer(
// 					$cache_server['hostname'], $cache_server['port'], $cache_server['weight']
// 			);
		}
		
	}

	// ------------------------------------------------------------------------


	/**
	 * Is supported
	 *
	 * Returns FALSE if memcached is not supported on the system.
	 * If it is, we setup the memcached object & return TRUE
	 */
	public function is_supported()
	{
		if ( ! extension_loaded('memcached') && !extension_loaded('memcache'))
		{
			log_message('error', 'The Memcached Extension must be loaded to use Memcached Cache.');
			
			return FALSE;
		}
		
		$this->_setup_memcached();
		return TRUE;
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_memcached.php */
/* Location: ./system/libraries/Cache/drivers/Cache_memcached.php */

<?php
/***********************************
 * @date  2013/11/29
 * 缓存公共方法存放处
 * 为了方便别人查找和防止写多个一样作用的方法
 * 请大家在这里添加的方法都在头部写一下
 * 方便他人就是方便自己
 * 此helper下的所有方法：
 *
 * cacheSave() 保存缓存
 * cacheGet()  获取缓存
 * cacheDel()  删除某一个缓存
 *
 * cacheFileSave() 保存文件缓存
 * cacheFileGet()  获取文件缓存
 * cacheFileDel()  删除某一个文件缓存
 ****************************************/

/**
 * 保存缓存
 * @param string $key 键
 * @param string $value 键
 * @param string $expired 过期时间
 * 失败返回false
 * @author jackcao@159jh.com
 */
function cacheSave($key,$value,$expired=0){
	try {
		$CI =& get_instance();
		if($expired){
			$result = $CI->cache->save($key,$value,$expired,False);
		}else{
			$result = $CI->cache->save($key,$value);
		}
		if(!$result){
			log_message_user('ERROR', 'Memcache->key:'.$key.' save缓存失败','memcachedb');
		}else{
			log_message_user('INFO', 'Memcache->key:'.$key.' save缓存成功  Time:'.$expired,'memcachedb');
		}
		return $result;
	}catch (Exception $e) {
		log_message_user('ERROR', $e->getMessage(),'memcachedb');
	}
}
/**
 * 得到缓存数据
 * @param string $key 键
 * @param 布尔 $always 默认key过期 当$always=true时key是不过期的
 * 成功返回数据 失败返回false
 * @author jackcao@159jh.com
 */
function cacheGet($key,$always=False){
	try {
		$CI =& get_instance();
		$result = $CI->cache->get($key,$always);
		if(!$result){
			log_message_user('ERROR', 'Memcache->key:'.$key.' get缓存失败','memcachedb');
		}else{
			log_message_user('INFO', 'Memcache->key:'.$key.' get缓存成功  ','memcachedb');
		}
		return $result;
	}catch (Exception $e) {
		log_message_user('ERROR', $e->getMessage(),'memcachedb');
	}

}


/**
 * 得到缓存数据
 * @param string $key 键
 * 成功返回数据 失败返回false
 * @author jackcao@159jh.com
 */
function cacheDel($key){
	try {
		$CI =& get_instance();
		return $CI->cache->delete($key);
	}catch (Exception $e) {
		log_message_user('ERROR', $e->getMessage(),'memcachedb');
	}

}

/**
 * 文件缓存 /application/cache
 * 保存缓存
 * @param string $key 键
 * @param string $value 键
 * @param string $expired 过期时间
 * 失败返回false
 * @author jackcao
 */
function cacheFileSave($key,$value,$expired=0){
	try {
        checkFilePath($key);
		$CI =& get_instance();
		if($expired){
			$result = $CI->cache->file->save($key,$value,$expired);
		}else{
			$result = $CI->cache->file->save($key,$value);
		}
		if(!$result){
			log_message_user('ERROR', 'File->key:'.$key.' save缓存失败','memcachedb');
		}else{
			log_message_user('INFO', 'File->key:'.$key.' save缓存成功  Time:'.$expired,'memcachedb');
		}
		return $result;
	}catch (Exception $e) {
		log_message_user('ERROR', $e->getMessage(),'memcachedb');
	}
}
/**
 * 文件缓存 /application/cache
 * 得到缓存数据
 * @param string $key 键
 * @param 布尔 $always 默认key过期 当$always=true时key是不过期的
 * 成功返回数据 失败返回false
 * @author jackcao
 */
function cacheFileGet($key){
	try {
        checkFilePath($key);
		$CI =& get_instance();
		$result = $CI->cache->file->get($key);
		if(!$result){
			log_message_user('ERROR', 'File->key:'.$key.' get缓存失败','memcachedb');
		}else{
			log_message_user('INFO', 'File->key:'.$key.' get缓存成功  ','memcachedb');
		}
		return $result;
	}catch (Exception $e) {
		log_message_user('ERROR', $e->getMessage(),'memcachedb');
	}

}


/**
 * 文件缓存 /application/cache
 * 得到缓存数据
 * @param string $key 键
 * 成功返回数据 失败返回false
 * @author jackcao
 */
function cacheFileDel($key){
	try {
        checkFilePath($key);
		$CI =& get_instance();
		return $CI->cache->file->delete($key);
	}catch (Exception $e) {
		log_message_user('ERROR', $e->getMessage(),'memcachedb');
	}

}

/**
 * 检测文件缓存的目录是否存在
 * 不存在则创建
 * @param string $path
 */
function checkFilePath($path){
	$file_path = explode('/',$path);
	$n = count($file_path);
	if($n!=1){
		$path = $_SERVER['DOCUMENT_ROOT'].'/application/cache';
		$str = '';
		for($i=0;$i<$n-1;$i++){
			if($file_path[$i]){
				$str .= '/'.$file_path[$i];
			}
		}
		if(!is_dir($path.$str)){
			mkdir($path.$str , 0777,true);
		}
	}
}

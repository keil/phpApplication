<?php

/**************************************************
 * APPLICATION
 **************************************************/

/**************************************************
 * @package application
 * @version 2.01 $Revision: 899 $
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: Application.class.php 899 2010-06-01 18:35:30Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/matthias-keil.de/Release%20(1.0)/httpdocs/_app/Application.class.php $
 * $Date: 2010-06-01 20:35:30 +0200 (Di, 01 Jun 2010) $
 * $Author: webadmin $
 * $Revision: 899 $
 **************************************************/

Application::$classpath = $_SERVER['DOCUMENT_ROOT'].'/_app';
Application::$cache = $_SERVER['DOCUMENT_ROOT'].'/_cache/packages.cache.php';

Application::initialize();

class Application {

	public static $classpath = '';
	public static $cache = '';

	private static $include = array();

	static function initialize() {
		if(file_exists(Application::$cache)) {
			Application::$include = Application::read();
		} else {
			Application::$include = Application::scan(Application::$classpath);
			Application::write(Application::$include);
		}
	}

	/**
	 * @param unknown_type $_path
	 * @param unknown_type $_prefix
	 * @return Ambigous <string, multitype:>
	 */
	private static function scan($_path, $_prefix = '') {
		$delimiter = '/';
		$separator = '.';
		$wildcard = '*';
		$result = array();
		$files = array();

		foreach(scandir($_path) as $resource) {
			if ($resource == "." || $resource == ".." || $resource == ".svn" || $resource == ".htaccess" || $resource == ".htpasswd") {
				continue;
			} else if(is_dir($_path.$delimiter.$resource)) {
				$result = array_merge($result, Application::scan($_path.$delimiter.$resource, $_prefix.$resource.$separator));
				continue;
			}else if(is_file($_path.$delimiter.$resource)) {
				$files[] = $_path.$delimiter.$resource;
				$result[$_prefix.substr($resource, 0, strpos($resource, '.'))] = $_path.$delimiter.$resource;
				continue;
			}
		}

		$result[$_prefix.$wildcard] = $files;
		return $result;
	}

	/**
	 * @param string $_include
	 */
	static function import($_include) {
		if(!array_key_exists($_include, Application::$include)) {
			throw new RuntimeException('undefinded package: '.$_include);
		} else if(is_array(Application::$include[$_include])) {
			foreach (Application::$include[$_include] as $file) {
				require_once $file;
			}
		} else {
			require_once Application::$include[$_include];
		}
	}

	/**
	 * @return mixed
	 */
	static function read() {
		return unserialize(file_get_contents(Application::$cache));
	}

	/**
	 * @param array $_value
	 */
	static function write(array $_value) {
		file_put_contents(Application::$cache, serialize($_value));
	}
}
?>
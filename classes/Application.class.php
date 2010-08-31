<?php

/**************************************************
 * APPLICATION
 **************************************************/

/**************************************************
 * @package application
 * @version 1.10 $Revision: 803 $
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: Application.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/matthias-keil.de/Release%20(1.0)/httpdocs/_app/Application.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::initialize($_SERVER["DOCUMENT_ROOT"].'/_app');

class Application {

	private static $include = array();

	static function initialize($_root) {
		Application::$include = Application::scan($_root);
	}

	private static function scan($_path, $_prefix = '') {
		$delimiter = '/';
		$separator = '.';
		$wildcard = '*';
		$result = array();
		$files = array();

		$handle = opendir($_path);
		while(false !== ($resource = readdir($handle))) {
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
		closedir($handle);
		return $result;
	}

	static function import($_include) {
		if(!array_key_exists($_include, Application::$include)) {
			throw new RuntimeException('undefinded package: '.$package);
		} else if(is_array(Application::$include[$_include])) {
			foreach (Application::$include[$_include] as $file) {
				require_once $file;
			}
		} else {
			require_once Application::$include[$_include];
		}
	}
}
?>
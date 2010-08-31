<?php

/**************************************************
 * APPLICATION
 * php application fileloader
 **************************************************/

/**************************************************
 * Author: Roman Matthias Keil
 * Copyright: Roman Matthias Keil
 * Publisher: rm-keil.de
 **************************************************/

/**************************************************
 * $Date: 2010-05-19 13:45:33 +0200 (Mi, 19 Mai 2010) $
 * $Revision: 800 $
 **************************************************/

Application::initialize($_SERVER["DOCUMENT_ROOT"].'/_app');

class Application {

	private static $include = array();

	static function initialize($_root) {
		Application::$include = Application::scan($_root);
	}

	private static function scan($_path) {
		$delimiter = "/";
		$result = array();

		$handle = opendir($_path);
		while(false !== ($resource = readdir($handle))) {
			if ($resource == "." || $resource == ".." || $resource == ".svn" || $resource == ".htaccess" || $resource == ".htpasswd") continue;

			if(is_dir($_path.$delimiter.$resource)) {
				$result[$resource] = Application::scan($_path.$delimiter.$resource);
			}
			else if(is_file($_path.$delimiter.$resource)) {
				$result[substr($resource, 0, strpos($resource, '.'))] = $_path.$delimiter.$resource;
			}
		}
		closedir($handle);
		return $result;
	}

	static function import($_include) {

		$packages = explode('.', $_include);
		$file = array_pop($packages);

		$includes = Application::$include;

		foreach($packages as $package)
			if(isset($includes[$package]))
				$includes = $includes[$package];
			else
				throw new RuntimeException('undefinded package: '.$package);

		if($file != '*') {
			if(is_file($includes[$file])) require_once $includes[$file];
		}
		else {
			foreach($includes as $include) {
				if(is_file($include)) require_once $include;}
		}
	}
}
?>
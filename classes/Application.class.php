<?php
/**	Application
 *	php application file loader

 *	Author:	Roman Matthias Keil
 *	Copyright: Roman Matthias Keil
 *	Publisher: RRC-Bötzingen (rrc-boetzingen.de)
 *	Version 1.00
 
 *	$Id: Application.class.php 243 2009-11-25 20:34:04Z webadmin $
 *	$HeadURL: http://svn.rm-keil.de/rm-keil/projects/phpApplication/workspace/1.00/Application.class.php $
 *	$Date: 2009-11-25 21:34:04 +0100 (Mi, 25 Nov 2009) $
 *	$Author: webadmin $
 *	$Revision: 243 $

 *	TODO:
 */

Application::initialize($_SERVER["DOCUMENT_ROOT"]);

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
			if ($resource == "." || $resource == ".." || $resource == ".svn") continue;

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
				throw new RuntimeException('undefindes package: '.$package);

		if($file != '*')
			require_once $includes[$file];
		else
			foreach($includes as $include)
				require_once $include;
	}
}
?>
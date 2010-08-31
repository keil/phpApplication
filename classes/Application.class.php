<?php
/**	Application
 *	php application file loader

 *	Author:	Roman Matthias Keil
 *	Copyright: Roman Matthias Keil
 *	Publisher: RRC-Bötzingen (rrc-boetzingen.de)
 *	Version 1.00
 
 *	$Id: Application.class.php 798 2010-05-19 11:36:01Z webadmin $
 *	$HeadURL: http://svn.rm-keil.de/rm-keil/projects/phpApplication/workspace/1.01/Application.class.php $
 *	$Date: 2010-05-19 13:36:01 +0200 (Mi, 19 Mai 2010) $
 *	$Author: webadmin $
 *	$Revision: 798 $

 *	TODO:
 */

Application::initialize($_SERVER["DOCUMENT_ROOT"]);

class Application {

	private static $include = array();

	static function initialize($_root) {
		Application::$include = ayrray_merge(Application::$includem Application::scan($_root));
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
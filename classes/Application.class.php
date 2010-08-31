<?php

// todo
// Application::initialize('/var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/');
Application::initialize($_SERVER["DOCUMENT_ROOT"].'/ddi/classes');

/*
Array
(
    [logger] => Array
        (
            [core] => Array
                (
                    [Level.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/core/Level.class.php
                    [Logger.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/core/Logger.class.php
                    [LoggerConnection.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/core/LoggerConnection.class.php
                    [LoggerFactory.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/core/LoggerFactory.class.php
                )
            [view] => Array
                (
                    [Level.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/view/Level.class.php
                    [Logger.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/view/Logger.class.php
                    [LoggerConnection.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/view/LoggerConnection.class.php
                    [LoggerFactory.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/view/LoggerFactory.class.php
                )
            [Level.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/Level.class.php
            [Logger.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/Logger.class.php
            [LoggerConnection.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/LoggerConnection.class.php
            [LoggerFactory.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/logger/LoggerFactory.class.php
        )
    [model] => Array
        (
            [Database.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/model/Database.class.php
            [Row.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/model/Row.class.php
            [Tabel.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/model/Tabel.class.php
        )
    [controller] => Array
        (
            [Credential.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/controller/Credential.class.php
            [Host.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/controller/Host.class.php
            [Connection.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/controller/Connection.class.php
        )
    [exception] => Array
        (
            [UndefinedDatabaseException.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/UndefinedDatabaseException.class.php
            [UndefinedRowException.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/UndefinedRowException.class.php
            [UndefinedTabelException.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/UndefinedTabelException.class.php
            [SQLStatementException.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/SQLStatementException.php
            [UnknownDatabaseException.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/UnknownDatabaseException.php
            [UndefinedException.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/UndefinedException.class.php
            [DatabaseException.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/DatabaseException.class.php
            [UndefinedFieldException.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/exception/UndefinedFieldException.class.php
        )
    [adapter] => Array
        (
            [DatabaseAdapter.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/adapter/DatabaseAdapter.class.php
            [RowAdapter.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/adapter/RowAdapter.class.php
            [TabelAdapter.class.php] => /var/www/vhosts/rm-keil.de/subdomains/development/httpdocs/ddi/classes/adapter/TabelAdapter.class.php
        )
)



*/


class Application {

	private static $include = array();
	
	static function initialize($_root) {
		Application::$include = Application::scan($_root);
	}
	
	private static function scan($_path) {
		$delimiter = "/";
		$result = array();

echo "#".$_path."<br>";

		$handle = opendir($_path); 
		while(false !== ($resource = readdir($handle))) { 
			if ($resource == "." || $resource == ".." || $resource == ".svn") continue;

			if(is_dir($_path.$delimiter.$resource)) {
				$result[$resource] = Application::scan($_path.$delimiter.$resource); 
			}
			else if(is_file($_path.$delimiter.$resource)) {
				$result[$resource] = $_path.$delimiter.$resource;
			}
        } 
        closedir($handle);
//        sort($result);

		return $result;
	}

	
	static function import($_include) {
		print_r(Application::$include);
		echo "<br>";
		echo "package: ".$_include;
		
		
		$packages = explode('.', $_include);
		$file = array_pop($packages);
		
		$includes = AppendIterator::$include;
		
		foreach($packages as $package)
			if(array_key_exists($package))
				$includes = $includes[$package]."<br>";
			else
				throw new RuntimeException('undefindes package'); 
		
		if($file != '*')
			echo "require_once: ".$includes[$file]; // require_once $includes[$file];
		else
			foreach($includes as $include)
				if(!is_array($include)) echo "require_once: ".$includes[$include]."<br>"; //include $includes[$include];

	}
}
?>

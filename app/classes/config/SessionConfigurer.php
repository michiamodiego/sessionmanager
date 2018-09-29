<?php

	namespace config;
	
	use com\dsweb\session\SessionManager as SessionManager;
	use com\dsweb\session\MysqlSessionRepository as MysqlSessionRepository;
	
	class SessionConfigurer {
		
		public static function init() {
			
			SessionManager::init(
				function($sessionConfig) {
					
					$mysqlConfig["hostname"] = "127.0.0.1";
					$mysqlConfig["port"] = "3306";
					$mysqlConfig["database"] = "your_database";
					$mysqlConfig["username"] = "your_username";
					$mysqlConfig["password"] = "your_password";
					
					$sessionConfig->setTimeoutInterval(60);
					$sessionConfig->setCookieName("YOUR_SESSION_VARIABLE_NAME"); // like PHP_SESSION_ID
					$sessionConfig->setRepository(new MysqlSessionRepository($mysqlConfig));
					
				}
			);	
			
		}
		
	}

?>
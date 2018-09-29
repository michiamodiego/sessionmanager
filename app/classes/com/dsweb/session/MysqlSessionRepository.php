<?php

	namespace com\dsweb\session;

	class MysqlSessionRepository implements SessionRepository {
		
		private $config;
		
		public function __construct($config) {
			
			$this->config = $config;
			
		}
		
		private static function getConnection($config) {
			
			$dbh = mysql_connect($config["hostname"], $config["username"], $config["password"]);
			
			mysql_select_db($config["database"], $dbh);
			
			return $dbh;
			
		}
		
		private static function mapRowToSession($row) {
			
			return new Session(
				$row["id"], 
				$row["creation_time"], 
				$row["timeout_interval"], 
				$row["last_update_time"], 
				unserialize($row["data"])
			);
			
		}
		
		public function getById($id) {
			
			$dbh = self::getConnection($this->config);
			
			$result = mysql_query("select id, creation_time, timeout_interval, last_update_time, data from session where id = '${id}'", $dbh);
			
			$session = null;
			
			if(mysql_num_rows($result) > 0) {
				
				$session = self::mapRowToSession(mysql_fetch_assoc($result));
				
			}
			
			mysql_close($dbh);
			
			return $session;
			
		}
		
		public function update($session) {
			
			$dbh = self::getConnection($this->config);
			
			$creationTime = $session->getCreationTime();
			$timeoutInterval = $session->getTimeoutInterval();
			$lastUpdateTime = $session->getLastUpdateTime();
			$data = serialize($session->getDataList());
			$id = $session->getId();
			
			mysql_query("update session set creation_time = '${creationTime}', timeout_interval = '${timeoutInterval}', last_update_time = '${lastUpdateTime}', data='${data}' where id = '${id}'", $dbh);
			
			mysql_close($dbh);
			
		}
		
		public function save($session) {
			
			$dbh = self::getConnection($this->config);
			
			$id = $session->getId();
			$creationTime = $session->getCreationTime();
			$timeoutInterval = $session->getTimeoutInterval();
			$lastUpdateTime = $session->getLastUpdateTime();
			$data = serialize($session->getDataList());
			
			mysql_query("insert into session (id, creation_time, timeout_interval, last_update_time, data) values ('${id}', '${creationTime}', '${timeoutInterval}', '${lastUpdateTime}', '${data}')", $dbh);
			
			mysql_close($dbh);
			
		}
		
		public function delete($session) {
			
			$dbh = self::getConnection($this->config);
			
			$id = $session->getId();
			
			mysql_query("delete from session where id = '${id}'", $dbh);
			
			mysql_close($dbh);
			
		}
		
	}

?>
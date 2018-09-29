<?php

	namespace com\dsweb\session;

	class SessionConfig {
		
		private $timeoutInterval;
		private $cookieName;
		private $repository;
		
		public function setTimeoutInterval($timeoutInterval) {
			
			$this->timeoutInterval = $timeoutInterval;
			
		}
		
		public function getTimeoutInterval() {
			
			return $this->timeoutInterval;
			
		}
		
		public function setCookieName($cookieName) {
			
			$this->cookieName = $cookieName;
			
		}
		
		public function getCookieName() {
			
			return $this->cookieName;
			
		}
		
		public function setRepository($repository) {
			
			$this->repository = $repository;
			
		}
		
		public function getRepository() {
			
			return $this->repository;
			
		}
		
	}

?>
<?php

	namespace com\dsweb\session;

	class Session {

		private $id;
		private $creationTime;
		private $lastUpdateTime;
		private $timeoutInterval;
		private $data;

		public function __construct($id, $creationTime, $timeoutInterval, $lastUpdateTime = null, $data = null) {

			$this->id = $id;
			$this->creationTime = $creationTime;
			$this->timeoutInterval = $timeoutInterval;
			$this->lastUpdateTime = $lastUpdateTime == null ? $creationTime : $lastUpdateTime;
			$this->data = $data == null ? array() : $data;

		}

		public function getId() {

			return $this->id;

		}

		public function getCreationTime() {

			return $this->creationTime;

		}

		public function getLastUpdateTime() {

			return $this->lastUpdateTime;

		}
		
		public function getTimeoutInterval() {
			
			return $this->timeoutInterval;
			
		}

		public function getExpirationTime() {

			return $this->lastUpdateTime + $this->timeoutInterval;

		}

		public function isExpired() {

			return $this->getExpirationTime() <= time();

		}

		public function setData($key, $value) {
			
			if($this->isExpired()) {
				
				throw new SessionException("the session is expired");
				
			}

			$this->data[$key] = $value;

		}

		public function getData($key) {

			return in_array($key, $this->data) ? $this->data[$key] : null;

		}

		public function getKeys() {

			return array_keys($this->data);

		}
		
		public function setDataList($dataList) {
			
			if($this->isExpired()) {
				
				throw new SessionException("the session is expired");
				
			}
			
			$this->data = $dataList;
			
		}

		public function getDataList() {

			return $this->data;

		}
		
		public function getUpdatedSession() {
			
			if($this->isExpired()) {
				
				throw new SessionException("the session is expired");
				
			}
			
			return new Session(
				$this->id, 
				$this->creationTime, 
				$this->timeoutInterval, 
				time(), 
				$this->data			
			);
			
		}

	}

?>
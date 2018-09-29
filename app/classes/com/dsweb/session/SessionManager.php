<?php

	namespace com\dsweb\session;

	class SessionManager {

		private static $currentSession = null;
		private static $config = null;

		private static function setCurrentSession($newSession) {

			self::$currentSession = $newSession;

		}

		private static function getCurrentSession() {

			return self::$currentSession;

		}
		
		public static function init($callback) {
			
			if($callback == null) {
				
				throw new InvalidArgumentException("init argument cannot be null");
				
			}
			
			self::$config = new SessionConfig(); 
			
			$callback(self::$config);
			
			if(self::$config->getCookieName() == null) {
				
				throw new SessionManagerException("cookie name is null");
				
			}
			
			if(self::$config->getTimeoutInterval() == null) {
				
				throw new SessionManagerException("timeout interval is null");
				
			}
			
			if(self::$config->getRepository() == null) {
				
				throw new SessionManagerException("repository is null");
				
			}
			
		}
		
		private static function getTimeoutInterval() {
			
			return self::$config->getTimeoutInterval();
			
		}
		
		private static function getCookieName() {
			
			return self::$config->getCookieName();
			
		}
		
		private static function getRepository() {
			
			return self::$config->getRepository();
			
		}
		
		private static function getCookieVariable() {
			
			global $_COOKIE;
			
			if(!isset($_COOKIE)) {
				
				throw new SessionManagerException("cookie variable does not exist");
				
			}
			
			return $_COOKIE;
			
		}
		
		private static function getSessionId() {
			
			return isset(self::getCookieVariable()[self::getCookieName()]) ? 
					self::getCookieVariable()[self::getCookieName()] :
					null;
			
		}
		
		private static function createBrandNewSession() {
			
			$session = new Session(uniqid(), time(), self::getTimeoutInterval());

			self::getRepository()->save($session);
			
			self::refreshCookie($session);
			
			return $session;
			
		}
		
		private static function refreshSession($session) {

			$newSession = $session->getUpdatedSession();

			self::getRepository()->update($newSession);
				
			self::refreshCookie($newSession);

			return $session;
			
		}

		public static function createSession() {
			
			// The session exists but it's not been loaded
			if(self::getCurrentSession() == null && self::getSessionId() != null) {

				$session = self::getRepository()->getById(self::getSessionId());

				if($session == null) {

					throw new SessionManagerException("session does not exist");

				}
				
				if($session->isExpired()) {
					
					self::setCurrentSession(self::createBrandNewSession());
					
					return self::getCurrentSession();
					
				} else {
					
					self::setCurrentSession(self::refreshSession($session));
					
					return self::getCurrentSession();
					
				}

			} 
			
			// The session does not exists
			if(self::getCurrentSession() == null && self::getSessionId() == null) {

				self::setCurrentSession(self::createBrandNewSession());
				
				return self::getCurrentSession();

			}
			
			return self::getCurrentSession();
			
		}

		public static function saveSession() {

			if(self::getCurrentSession() == null) {

				throw new SessionManagerException("session does not exist");

			}
			
			self::setCurrentSession(self::refreshSession(self::getCurrentSession()));

			return self::getCurrentSession();

		}

		public static function destroySession() {

			if(self::getCurrentSession() == null) {

				throw new ServiceManagerException("session does not exist");

			}

			self::getRepository()->delete(self::getCurrentSession());

			self::setCurrentSession(null);
			
			self::destroyCookie();

		}

		private static function refreshCookie($session) {

			setcookie(
				self::getCookieName(), 
				$session->getId(), 
				$session->getExpirationTime()
			);

		}

		private static function destroyCookie() {

			setcookie(
				self::getCookieName(), 
				null, 
				time() - self::getTimeoutInterval()
			);

		}

	}

?>
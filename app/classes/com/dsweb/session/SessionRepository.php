<?php

	namespace com\dsweb\session;

	interface SessionRepository {
		
		function getById($id);
		function update($session);
		function save($session);
		function delete($session);
		
	}

?>
<?php

	spl_autoload_register(
		function($class) {
			
			$class = str_replace("\\", DIRECTORY_SEPARATOR, $class);

			include_once("./app/classes/" . $class . ".php");
			
		}
	);

?>
<?php

	/** NOTE **
		This script just shows you how to use the SessionManager. 
		It also teaches you the reason why developers need Frameworks like 
		Laravel, Slim or CodeIgniter :). 
		Mixing PHP and HTML code together makes the code unreadable and unintelligible!!!
	**/

	include("./autoloader.php");

	use \config\SessionConfigurer as SessionConfigurer;
	use \com\dsweb\session\SessionManager as SessionManager;
	
	SessionConfigurer::init();
	
	$session = SessionManager::createSession();
	
	if(!($session->getData("logged") != null && $session->getData("logged"))) {
		// The user is not logged in
	
		if($_SERVER["REQUEST_METHOD"] == "GET") {
			
?>
	<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
		<?php if(isset($_GET["e"])) { ?><strong>Bad username or password.</strong><br><?php } ?>
		Username <input type="text" name="username">
		<br>
		Password <input type="password" name="password">
		<br>
		<input type="reset" value="Reset">
		<input type="submit" value="Login">
	</form>
<?php
			
		} else if($_SERVER["REQUEST_METHOD"] == "POST") {
			
			if(isset($_POST["username"]) && isset($_POST["password"])) {
				
				if($_POST["username"] == "username" && $_POST["password"] == "password") {
					
					$session->setData("logged", true);
					
					SessionManager::saveSession();
					
					header("Location: " . $_SERVER["PHP_SELF"]);
					
				} else {
					
					header("Location: " . $_SERVER["PHP_SELF"] . "?e=1");
					
				}
				
			} else {
				
				header("Location: " . $_SERVER["PHP_SELF"] . "?e=1");
				
			}
			
		}

	} else {
		// The user is logged in
	
		if(isset($_GET["a"]) && $_GET["a"] == "logout") {
			
			SessionManager::destroySession();
			
			header("Location: " . $_SERVER["PHP_SELF"]);
			
		} else {
			
?>
	You are logged in!
	<br>
	<a href="<?php $_SERVER["PHP_SELF"]; ?>?a=logout">Logout</a>
<?php
			
		}
	
	}

?>
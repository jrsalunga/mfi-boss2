<?php
include_once('../lib/initialize.php');





	
	log_action('logout', User::row($session->user_id, 0));
	
	$session->logout();
	
	
    redirect_to("login");


?>
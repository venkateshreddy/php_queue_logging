<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once("../classes/logpublisher.php");
$rabbit_credentials	= array(
							"host" 		=> 	"localhost",
							"username" 	=> 	"guest",
							"password" 	=> 	"guest",
							"port" 		=> 	"15672"
							);
echo date("Y-m-d H:i:s")."<br />";
for($i=1; $i<=100; $i++){
	$message_publisher = LogPublisher::getInstance($rabbit_credentials);
	$message_publisher->publishMessage("Hai this is log message ".$i);
}
echo date("Y-m-d H:i:s");
exit;
?>
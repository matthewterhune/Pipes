<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if( isset($_POST['scores']) ) {
	file_put_contents("./scores/perfect", $_POST['scores']);
}
?>
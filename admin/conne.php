<?php
	$dsn  ='mysql:host=localhost;dbname=shop';
	$user ='root';
	$pass ='';
	try {
		$db=new PDO($dsn,$user,$pass);
		$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		echo "faild connect".$e->getmessage();
	}
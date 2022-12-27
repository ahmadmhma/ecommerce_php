<?php 
session_start();
$pageTitle='';
if (isset($_SESSION['username'])) {
	include 'ini.php';
	$do= isset($_GET['do']) ? $_GET['do']:'mange';
	if ($do == 'mange') { 

    }elseif ($do=='add') {

	}elseif ($do=='insert') {

	}elseif ($do == 'Edit') { 

	}elseif ($do == 'update') {

	}elseif ($do == 'Delete') {

	}elseif ($do == 'activate') {

	}
	include $tpl . 'footer.php';
	}else{
	header('location:index.php');
	exit();
} ?>
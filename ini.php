<?php
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);
	include 'admin/conne.php';
	$suser = '';
	if (isset($_SESSION['user'])) {
		$suser = $_SESSION['user'];
	}
	$tpl='include/template/';
	$lang='include/languages/';
	$func='include/functions/';
	include $lang . 'en.php';
	include $func . 'functions.php';
	include $tpl . 'header.php';
	
		

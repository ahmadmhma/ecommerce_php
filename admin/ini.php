<?php
	include 'conne.php';
	$tpl='include/template/';
	$lang='include/languages/';
	$func='include/functions/';
	include $lang . 'en.php';
	include $func . 'functions.php';
	include $tpl . 'header.php';
	if(!isset($no_nav)){ include $tpl . 'navbar.php'; }
		

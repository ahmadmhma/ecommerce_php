<?php
	$do = '';
	if (isset($_GET['do'])) {
		$do=$_GET['do'];
	}else{
		$do = 'mange';
	}
	if ($do == 'mange') {
		echo "this is mange";
	}elseif ($do == 'add' ) {
		echo "this is add";
	}
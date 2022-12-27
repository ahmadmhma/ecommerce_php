<?php
	function lang($phr){
		static $lang = array('MESSAGE' => 'welcome' ,
		'ADMIN'        => 'Home',
		'Categories'   => 'Categories',
		'Items'        => 'Items',
		'Comments'      => 'Comments',
		'Statics'      => 'Statics',
		'Logs'         => 'Logs',
		'Members'      => 'Members',
		 );
		return $lang[$phr];
	}
?>
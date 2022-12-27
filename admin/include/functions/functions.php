<?php

	function getall($select ,$from ,$where=NULL ,$and=NULL ,$orderby ,$ordering='DESC' ){
		global $db;
		$stmt = $db->prepare("SELECT $select FROM $from $where $and ORDER BY $orderby $ordering ");
		$stmt->execute();
		$gets = $stmt->fetchall();
		return $gets;
	}



	function getTitle(){
		GLOBAL $pageTitle;
		if (isset($pageTitle)) {		
			echo $pageTitle;
		}else{
			echo "default";
		}
	}
	// func redir v2
	function redirectHome($msg , $url=null , $second=3){
		echo  $msg ;
		echo "<div class='alert alert-info'>".$second. ' second </div>';
		if ($url === null) {
			$url = 'index.php';
		}else{
			if (isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] !== '')) {
				$url = $_SERVER['HTTP_REFERER'];
			}else{
				$url = 'index.php';
			}
		}
		header("refresh: $second ; url= $url ");
		exit();
	}
	//function database 
	function checkItem($select , $from , $value){
		global $db;
		$state = $db->prepare("SELECT $select FROM $from WHERE $select =?");
		$state->execute(array($value));
		$count = $state->rowcount();
		return $count;
	}
	//func to count items
	function countItem($item , $table){
		global $db;
		$stmt = $db->prepare("SELECT COUNT($item) FROM $table");
		$stmt->execute();
		return $stmt->fetchcolumn();
	}
	//func to latest item from database
	function getlatest($select , $table , $order , $limit){
		global $db;
		$stmt = $db->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$stmt->execute();
		$row = $stmt->fetchAll();
		return $row;
	}
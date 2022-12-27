<?php
	session_start();
	$pageTitle='index';
	if (isset($_SESSION['username'])) {																				
		header('location:dash.php');
	}
	$no_nav='';
	include 'ini.php';
	include $tpl . 'header.php';
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$user=$_POST['user'];
		$pas=$_POST['pass'];
		$hashedpass=sha1($pas);
		$stmt=$db->prepare("SELECT userID ,username ,password FROM users WHERE username=? AND password=? AND groupID=1 LIMIT 1");
		$stmt->execute(array($user ,$hashedpass ));
		$row = $stmt->fetch();
		$count=$stmt->rowCount();
		if ($count>0) {
			$_SESSION['username']=$user;
			$_SESSION['ID']=$row['userID'];
			header('location:dash.php');
			exit();	
		}
	}
	?>
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<h2 class="text-center">login form</h2>
			<input class="form-control" type="text" name="user" placeholder="username" autocomplete="off" />
			<input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password" />
			<input class=" btn btn-primary btn-block" type="submit" value="login"/>
		</form>





	<?php include $tpl . 'footer.php'; ?>
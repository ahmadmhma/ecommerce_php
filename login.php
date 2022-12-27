<?php 
session_start();
$pageTitle='Login';
if (isset($_SESSION['user'])) {
	header('location:index.php');
}
include 'ini.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	if(isset($_POST['login'])){
	$user    = $_POST['username'];
	$pass    = $_POST['password'];
	$shapass = sha1($pass);
	$stmt    = $db->prepare("SELECT userID , username , password FROM users
							 WHERE username = ? AND password = ?");
	$stmt->execute(array($user , $shapass));
	$get = $stmt->fetch();
	$count   = $stmt->rowcount();
	if ($count > 0) {
	 	$_SESSION['user'] = $user;
	 	$_SESSION['uid']  = $get['userID'];
	 	header('location:index.php');
	 	exit();
	 } 
	}else{
		$formError = array();

		$user  		= $_POST['username'];
		$pass  		= $_POST['password'];
		$email 		= $_POST['email'];
		$avname 	= $_FILES['avatar']['name'];
		$avsize 	= $_FILES['avatar']['size'];
		$avtmp  	= $_FILES['avatar']['tmp_name'];
		$avtype 	= $_FILES['avatar']['type'];
		$avallext  	= array("jpeg" , "jpg" , "png" ,"gif");
		$expava		= explode('.', $avname);
		$avext 		= strtolower(end($expava));


		if (isset($_POST['username'])) {
			$filteruser = filter_var($_POST['username'] , FILTER_SANITIZE_STRING);
			if (strlen($filteruser)<4) {
				$formError[] = 'username must be larger than <strong>4</strong> character';
			}
		}
		if (isset($_POST['password']) && isset($_POST['password2'])) {
			if (empty($_POST['password'])) {
				$formError[] = 'password cant be empty';
			}
			$pass1 = sha1($_POST['password']);
			$pass2 = sha1($_POST['password2']);
			if ($pass1 !== $pass2) {
				$formError[] = 'password is not <strong>match</strong> ';
			}
		}
		if (isset($_POST['email'])) {
			$filtermail = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL);
			if (filter_var($filtermail , FILTER_VALIDATE_EMAIL) != true) { 
				$formError[] = 'email is not valid';
			}
		}
		if (! empty($avname) && ! in_array($avext, $avallext)) {
			$formerrore[] = "<div class='alert alert-danger'>extention can not <strong>allow</strong></div>";
		}
		if ($avsize > 4194304) {
			$formError[] = "<div class='alert alert-danger'>image can not be larger than 
							 <strong>4MB</strong></div>";
		}
		if(empty($formError)){
			$avatar = rand(0 , 1000000000) . '_' . $avname;
			move_uploaded_file($avtmp, 'admin/upload/avatar/'. $avatar);
			$count = checkItem('username' , 'users' , $user);
			if ($count > 0) {
				$formError[] = "This user is exist";
			}else{
				$stmt = $db->prepare("INSERT INTO users(username , email ,password ,regstatus ,avatar ,`date`)
									  VALUES (:zuser ,:zemail ,:zpass ,0 ,:zavatar ,now())");
				$stmt->execute(array('zuser'    => $user,
									 'zemail'   => $email,
									 'zpass'    => sha1($pass),
									 'zavatar'	=> $avatar  ));
				$successmsg = "<div class='alert alert-success'>Congratulations. You have successfully 				   registered. Wait for the final activation message</div>";
			}
		}
	}
}
 ?>
<div class="container sign">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
	</h1>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"
		  enctype="multipart/form-data"  >
		<div class="abs">
			<input type="text" name="username" class="form-control" placeholder="Enter your username" 
				   required="required" />
	    </div>
	    <div class="abs">
			<input type="password" name="password" class="form-control"  placeholder="Enter ypur password" 		   required="required" autocomplete="new-password"/>
	    </div>
		    <input type="submit" value="Login" name="login" class="btn btn-primary btn-block" />
	</form>
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" 
		  enctype="multipart/form-data" >
		<div class="abs">
			<input type="text" name="username" class="form-control" pattern=".{4,}" 
			title="username must be 4 char" placeholder="Enter your username" required="required" />
	    </div>
	    <div class="abs">
			<input type="email" name="email" class="form-control" placeholder="Enter your email" 
				   required="required" />
	    </div>
	    <div class="abs">
			<input type="password" name="password" class="form-control" placeholder="Enter ypur password"
				   required="required" autocomplete="new-password" minlength="5"/>
	    </div>
	    <div class="abs">
			<input type="password" name="password2" class="form-control" placeholder="Enter ypur password"
				   required="required" autocomplete="new-password" minlength="5"/>
	    </div>
	    <div class="abs">
			<input type="file" name="avatar" class="form-control"  />
	    </div>
		    <input type="submit" value="Signup" name="signup" class="btn btn-success btn-block" />
	</form>
</div>
<div class="text-center">
	<?php
		if (!empty($formError)) {
			foreach ($formError as $form) {
				echo "<p>" . $form . "</p>";
			}
		}if (isset($successmsg)) {
			echo $successmsg;
		}
	?>
</div>

   
<?php include $tpl . 'footer.php'; ?>
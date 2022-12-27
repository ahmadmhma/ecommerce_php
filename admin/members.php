<?php 
session_start();
$pageTitle='Member';
if (isset($_SESSION['username'])) {
	include 'ini.php';
	$do= isset($_GET['do']) ? $_GET['do']:'mange';
	if ($do == 'mange') {  
		$quer = '';
		if (isset($_GET['page']) && $_GET['page'] == 'pending') {
			$quer = 'AND regstatus = 0';
		}
			$stmt = $db->prepare("SELECT * FROM users WHERE groupID!=1 $quer");
			$stmt->execute();
			$rows = $stmt->fetchAll();
		?>
		<h1 class="text-center"> Manage Member </h1>
		<div class="container">
			<div class="table-responsive">
					<table class="main-table text-center mta table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Image</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registed Date</td>
							<td>Control</td>
						</tr>
						<?php
						foreach ($rows as $row ) {
							echo "<tr>";
								echo "<td>" . $row['userID']   . "</td>";
								echo "<td>";
								if(empty($row['avatar'])){
									echo "<img src='upload/avatar/ava.jpg' alt=''>";
								}else{
								echo "<img src='upload/avatar/" . $row['avatar'] ."' alt=''>"; }
								echo "</td>";
								echo "<td>" . $row['username'] . "</td>";
								echo "<td>" . $row['email']    . "</td>";
								echo "<td>" . $row['fullname'] . "</td>";
								echo "<td>" . $row['date']     . "</td>";
								echo "<td>" . '<a href="members.php?do=Edit&userid='.$row['userID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
											   <a href="members.php?do=Delete&userid='.$row['userID'].'" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
								   if ($row['regstatus'] == 0) {
								    echo ' <a href="members.php?do=activate&userid='.$row['userID'].'" class="btn btn-primary confirm"><i class="fa fa-exclamation-triangle"></i> activate</a>';
								   }
								   echo "</td>";
							echo "</tr>";
						}
						?>
					</table>
			</div>
			<a href='members.php?do=add' class="btn btn-primary"><i class="fa fa-plus" > New Member</i></a>
		</div>
		
	<?php } elseif ($do=='add') { ?>
			<h1 class="text-center"> Add Member </h1>
			<div class="container">
			<form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data" />
				<div class="form-group">
					<label class="col-sm-2 control-label">username</label>
					<div class="col-sm-6">
						<input type="text" name="user"  class="form-control" autocomplete="off" placeholder="please enter your username" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">password</label>
					<div class="col-sm-6">
						<input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="enter your password" required="required" />
						<i class="eye-form fa fa-eye fa-2x"></i>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-6">
						<input type="email" name="email" class="form-control" required="required" placeholder="enter your valid email" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">FullName</label>
					<div class="col-sm-6">
						<input type="text" name="full" class="form-control" placeholder="enter your full name" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">User image</label>
					<div class="col-sm-6">
						<input type="file" name="avatar" class="form-control" required="required">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-6">
						<input type="submit" value="Add member" class="btn btn-primary">
					</div>
				</div>
			</form>
		</div>
		<?php
	}elseif ($do=='insert') {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		echo "<h1 class='text-center'>insert Member</h1>";
		echo "<div class='container'>";
		//upload variable
		$avname 	= $_FILES['avatar']['name'];
		$avsize 	= $_FILES['avatar']['size'];
		$avtmp  	= $_FILES['avatar']['tmp_name'];
		$avtype 	= $_FILES['avatar']['type'];
		$avallext  	= array("jpeg" , "jpg" , "png" ,"gif");
		$expava		= explode('.', $avname);
		$avext 		= strtolower(end($expava));

		//get variable from the form
		$user       = $_POST['user'];
		$pass       = $_POST['password'];
		$email      = $_POST['email'];
		$full       = $_POST['full'];
		$hashpass   = sha1($_POST['password']);
		$formerrore = array();
		if (strlen($user) < 4) {
			$formerrore[] = "<div class='alert alert-danger'>user can not be less than <strong>4 char</strong></div>";
		}
		if (empty($user)) {
			$formerrore[] = "<div class='alert alert-danger'>user can not be <strong>empty</strong></div>";
		}
		if (empty($pass)) {
			$formerrore[] = "<div class='alert alert-danger'>pass can not be <strong>empty</strong></div>";
		}
		if (empty($email)) {
			$formerrore[] = "<div class='alert alert-danger'>email can not be <strong>empty</strong></div>";
		}
		if (empty($full)) {
			$formerrore[] = "<div class='alert alert-danger'>name can not be <strong>empty</strong></div>";
		}
		if (empty($avname)) {
			$formerrore[] = "<div class='alert alert-danger'>image can not be <strong>empty</strong></div>";
		}
		if (! empty($avname) && ! in_array($avext, $avallext)) {
			$formerrore[] = "<div class='alert alert-danger'>extention can not <strong>allow</strong></div>";
		}
		if ($avsize > 4194304) {
			$formerrore[] = "<div class='alert alert-danger'>image can not be larger than 
							 <strong>4MB</strong></div>";
		}
		foreach ($formerrore as $error ) {
			echo $error;
		}
		if (empty($formerrore)) {
			$avatar = rand(0 , 1000000000) . '_' . $avname;
			move_uploaded_file($avtmp, 'upload/avatar/'. $avatar);
			$check = checkItem('username' , 'users' ,$user);
			if ($check ==1) {
				echo "the name is exist";
			}else{
				//insert the database with this info
				$stmt = $db->prepare('INSERT INTO users(username, password ,email ,fullname ,avatar ,							 	     		regstatus , date)
									  VALUES (:auser ,:apass ,:aemail ,:afull ,:avatar , 1 ,now())');
				$stmt->execute( array('auser' 	=> $user,
									  'apass' 	=> $hashpass,
									  'aemail'	=> $email,
									  'afull' 	=> $full,
									  'avatar' 	=> $avatar
									));
				echo "<div class='alert alert-success'>" . $stmt->rowcount() . " sucsess update</div>";
			}
	}
	}else{
		$errorMsg = "can not allow";
		redirectHome($errorMsg , 5);
	}
	echo "</div>";
	}elseif ($do == 'Edit') { 
		$userid=isset($_GET['userid'] )&& is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
      	$stmt= $db->prepare("SELECT  *  FROM users WHERE userID=? LIMIT 1");
      	$stmt->execute(array($userid ));
      	$row=$stmt->fetch();
      	$count=$stmt->rowCount();
      	if ($stmt->rowCount()>0) {?>
			<h1 class="text-center"> Edit Member </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST"/>
					<input type="hidden" name="userid" value="<?php echo($userid) ?>">
					<div class="form-group">
						<label class="col-sm-2 control-label">username</label>
						<div class="col-sm-6">
							<input type="text" name="user"  class="form-control" autocomplete="off" value="<?php echo $row['username'] ?>" required="required">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">password</label>
						<div class="col-sm-6">
							<input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" >
							<input type="password" name="newpassword" class="form-control" autocomplete="new-password" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-6">
							<input type="email" name="email" class="form-control" value="<?php echo $row['email'] ?>"   required="required" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">FullName</label>
						<div class="col-sm-6">
							<input type="text" name="full" class="form-control" value="<?php echo $row['fullname'] ?>" required="required">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<input type="submit" value="update" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
	<?php 	
      	}else {echo "no";
}}elseif ($_GET['do'] == 'update') {
	echo "<h1 class='text-center'>Update Member</h1>";
	echo "<div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//get variable from the form
		$id    = $_POST['userid'];
		$user  = $_POST['user'];
		$email = $_POST['email'];
		$full  = $_POST['full']; 
		// passwrd trick
		$pass  = '';
		if (empty($_POST['newpassword'])) {
			$pass = $_POST['oldpassword'];
		}else{
			$pass = sha1($_POST['newpassword']);
		}
		$formerrore = array();
		if (strlen($user) < 4) {
			$formerrore[] = "<div class='alert alert-danger'>can not be less than <strong>4 char</strong></div>";
		}
		if (empty($user)) {
			$formerrore[] = "<div class='alert alert-danger'>can not bew <strong>empty</strong></div>";
		}
		if (empty($email)) {
			$formerrore[] = "<div class='alert alert-danger'>can not bee <strong>empty</strong></div>";
		}
		if (empty($full)) {
			$formerrore[] = "<div class='alert alert-danger'>can not beq <strong>empty</strong></div>";
		}
		foreach ($formerrore as $error ) {
			echo $error;
		}
		if (empty($formerrore)) {
			$stmt2 = $db->prepare("SELECT * FROM users WHERE username=? AND userID!=?");
			$stmt2->execute(array($user , $id));
			$count = $stmt2->rowcount();
			if($count>0){
				echo "لا تستطيع التعديل";
			}else{
			//update the database with this info
			$stmt = $db->prepare('UPDATE users SET username=? ,email=?,fullname=? ,password=? 
								  WHERE userID=?');
			$stmt->execute(array($user , $email ,$full ,$pass , $id));
			//echo sucsess message
			$msge = "<div class='alert alert-success'> " . $stmt->rowcount() . " sucsess update</div>";
			redirectHome($msge , 'back' , 6);}
		}
	}else{
		echo "can not allow";
	}
	echo "</div>";
}elseif ($do == 'Delete') {
	echo "<h1 class='text-center'>Delete Member</h1>";
	echo "<div class='container'>";
		$userid = isset($_GET['userid'])&&is_numeric($_GET['userid'])?intval($_GET['userid']):0;
		$check  = checkItem('userID' , 'users' , $userid);
		if ($check > 0) {
			$stmt = $db->prepare('DELETE FROM users WHERE userID = :zuser');
			$stmt->bindparam(':zuser' , $userid);
			$stmt->execute();
			echo "<div class='alert alert-success'>" . $check . " success Delete</div>";
		}else{
			$msg = "not found ";
			redirectHome($msg , 'b');
		} 
	echo "</div>";
}elseif ($do == 'activate') {
	echo "<h1 class= 'text-center'>Activate</h1>";
	echo "<div class='container'>";
		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):0;
		$check = checkItem('userid' , 'users' , $userid);
		if ($check > 0) {
			$stmt = $db->prepare("UPDATE users SET regstatus =1 WHERE userid = ?");
			$stmt ->execute(array($userid));
			$ms = "<div class='alert alert-success'>" . $check . " success Delete</div>";
			redirectHome($ms , 'b');
		}else{
			$msg = "not found ";
			redirectHome($msg , 'b');
		}
	echo "</div>";
}
	include $tpl . 'footer.php';
}else{
	header('location:index.php');
	exit();
}
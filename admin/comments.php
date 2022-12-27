<?php 
session_start();
$pageTitle='Comments';
if (isset($_SESSION['username'])) {
	include 'ini.php';
	$do= isset($_GET['do']) ? $_GET['do']:'mange';
	if ($do == 'mange') { 
			$stmt = $db->prepare("SELECT comments.* ,items.name AS itemName ,users.username
								 FROM comments INNER JOIN items ON items.itemID = comments.item_id
								 INNER JOIN users ON users.userID = comments.user_id");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			if(!empty($rows)){
		?>
		<h1 class="text-center"> Manage Member </h1>
		<div class="container">
			<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Comment</td>
							<td>Item name</td>
							<td>User Name</td>
							<td>added Date</td>
							<td>Control</td>
						</tr>
						<?php
						foreach ($rows as $row ) {
							echo "<tr>";
								echo "<td>" . $row['coid']   . "</td>";
								echo "<td>" . $row['comment'] . "</td>";
								echo "<td>" . $row['itemName']    . "</td>";
								echo "<td>" . $row['username'] . "</td>";
								echo "<td>" . $row['codate']     . "</td>";
								echo "<td>" . '<a href="comments.php?do=Edit&comid='.$row['coid'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
											   <a href="comments.php?do=Delete&comid='.$row['coid'].'" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
								   if ($row['status'] == 0) {
								    echo ' <a href="comments.php?do=approve&comid='.$row['coid'].'" class="btn btn-primary confirm"><i class="fa fa-exclamation-triangle"></i> approve</a>';
								   }
								   echo "</td>";
							echo "</tr>";
						}
						?>
					</table>
			</div>
			<a href='members.php?do=add' class="btn btn-primary"><i class="fa fa-plus" > New Member</i></a>
		</div>
		<?php
	}else{
		echo '<div class="container">';
			echo '<div class="nof">there are not found</div>';
		echo "</div>";
	}
	  
	}elseif ($do == 'Edit') { 
		$comid=isset($_GET['comid'] )&& is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
      	$stmt= $db->prepare("SELECT  *  FROM comments WHERE coid=?");
      	$stmt->execute(array($comid ));
      	$row=$stmt->fetch();
      	$count=$stmt->rowCount();
      	if ($stmt->rowCount()>0) {?>
			<h1 class="text-center"> Edit comment </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST"/>
					<input type="hidden" name="comid" value="<?php echo($comid) ?>">
					<div class="form-group">
						<label class="col-sm-2 control-label">Comment</label>
						<div class="col-sm-6">
							<textarea class="form-control" name="comment">
								<?php echo $row['comment'];?>
							</textarea>
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
      	}else {echo "no";} 
    }elseif ($_GET['do'] == 'update') {
		echo "<h1 class='text-center'>Update comment</h1>";
		echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//get variable from the form
			$comid    = $_POST['comid'];
			$comment  = $_POST['comment'];
			//update the database with this info
			$stmt = $db->prepare('UPDATE comments SET comment=? WHERE coid=?');
			$stmt->execute(array($comment , $comid));
			//echo sucsess message
			$msge = "<div class='alert alert-success'> " . $stmt->rowcount() . " sucsess update</div>";
			redirectHome($msge , 'back' , 6);
		}
		else{
			echo "can not allow";
		}
		echo "</div>";
}elseif ($do == 'Delete') {
	echo "<h1 class='text-center'>Delete Comment</h1>";
	echo "<div class='container'>";
		$comid = isset($_GET['comid'])&&is_numeric($_GET['comid'])?intval($_GET['comid']):0;
		$check  = checkItem('coid' , 'comments' , $comid);
		if ($check > 0) {
			$stmt = $db->prepare('DELETE FROM comments WHERE coid = :zid');
			$stmt->bindparam(':zid' , $comid);
			$stmt->execute();
			echo "<div class='alert alert-success'>" . $check . " success Delete</div>";
		}else{
			$msg = "not found ";
			redirectHome($msg , 'b');
		} 
	echo "</div>";
}elseif ($do == 'approve') {
	echo "<h1 class= 'text-center'>approve</h1>";
	echo "<div class='container'>";
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):0;
		$check = checkItem('coid' , 'comments' , $comid);
		if ($check > 0) {
			$stmt = $db->prepare("UPDATE comments SET status =1 WHERE coid = ?");
			$stmt ->execute(array($comid));
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
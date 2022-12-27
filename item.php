<?php 
	session_start();
	$pageTitle='item';
	include 'ini.php';

	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
	$stmt = $db->prepare("SELECT items.* , categories.name AS cat_name , users.username
						  FROM items INNER JOIN categories ON categories.ID = items.cat_id 
						  INNER JOIN users ON users.userID = items.member_id
						  WHERE itemID = ? AND approve = 1");
	$stmt->execute(array($itemid));
	if($stmt->rowcount()>0){

	$item = $stmt->fetch();

	?>
<h1 class="text-center"><?php echo $item['name']; ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php if (empty($item['itemimg'])) {
				echo '<img class="img-responsive img-thumbnail center-block img" src="ava.jpg" alt="" />';
			}else { echo '<img class="img-responsive img-thumbnail center-block img" 
						   	   src="admin/upload/avatar/'.$item['itemimg'].'" alt="" />'; }
			 ?>	
		</div>
		<div class="col-md-9 item">
			<h3><?php echo $item['name']; ?></h3>
			<p><?php echo $item['description']; ?></p>
			<ul class="list-unstyled">
				<li><i class="fa fa-calendar"></i>
					<span> Added date</span> : <?php echo $item['date']; ?>
				</li>
				<li><i class="fa fa-money"></i>
					<span> Price</span> : <?php echo $item['price']; ?>
				</li>
				<li><i class="fa fa-suitcase"></i>
					<span> Made in</span> : <?php echo $item['country_made']; ?>
				</li>
				<li><i class="fa fa-tags"></i>
					<span> Category</span> : <a href="categories.php?pageid=<?php echo $item['cat_id']?>"><?php echo $item['cat_name']; ?></a>
				</li>
				<li><i class="fa fa-user"></i>
					<span> Added by</span> : <a href="#"><?php echo $item['username']; ?></a>
				</li>
				<li><i class="fa fa-tag"></i>
					<span> Tags</span> : 
						<?php $alltags = explode(',', $item['tags']);
							foreach ($alltags as $tag) {
								$tag = str_replace(' ', '', $tag);
								$ltag = strtolower($tag);
								echo '<a href="tags.php?name='.$ltag.'">' . $tag . '</a>  ' ;
							}
						 ?>
				</li>
			</ul>
		</div>
	</div>
	<hr class="hr-c">
	<?php if (isset($_SESSION['user'])) { ?>
	<div class="row">
		<div class="col-md-offset-3">
			<div class="comment">
				<h3>Add your comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['itemID'] ?>" 
					  method="POST">
					<textarea name="comment" required="required"></textarea>
					<input type="submit" name="submit" class="btn btn-primary" value="Add comment">
				</form>
				<?php
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$comment = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING);
						$itemid  = $item['itemID'];
						$user    = $_SESSION['uid'];
						if (!empty($comment)) {
							$stmt = $db->prepare("INSERT INTO comments(comment , status ,codate ,item_id ,user_id) VALUES (:zcomment ,0 ,now() ,:zitem ,:zuser)");
							$stmt->execute(array("zcomment" => $comment,
												 "zitem"    => $itemid,
												 "zuser"    => $user
												));
							if ($stmt) {
								echo "<div class='alert alert-success'>Comment added</div>";
							}
						}else{
							echo "Comment must be not empty";
						}
					}
				?>
			</div>
		</div>
	</div>  
<?php }else{
			echo "you are must be <a href='login.php'>Login</a> or <a href='login.php'>register</a>";
}?>
	<hr class="hr-c">
	<?php 
		$stmt = $db->prepare("SELECT comments.* ,users.username ,users.avatar FROM comments INNER JOIN users 
				       		  ON users.userID = comments.user_id WHERE item_id=? AND status = 1");
		$stmt->execute(array($itemid));
		$comments = $stmt->fetchAll();
		foreach ($comments as $comment) { ?>
			<div class="comm">
				<div class='row'>
					<div class='col-md-2'>
					<?php
						if(empty($comment['avatar'])){
							echo '<img class="img-responsive img-thumbnail img-circle center-block" 
								   src="ava.jpg" alt="" />';
						}else{
							echo '<img class="img-responsive img-thumbnail img-circle center-block" 
								   src="admin/upload/avatar/' . $comment["avatar"] .'" alt="">'; } 
					?>
						<h3 class="text-center"><?php echo $comment['username'] ?> </h3>
					</div>
					<div class='col-md-10'>
						<p><?php echo  $comment['comment'] ?></p>
					</div>
				</div>
				<hr class="hr-c">
			<?php  }  ?>
			</div>

<?php }else{
	echo "<div class='container'><div class='alert alert-danger'>there is no such id</div></div>";
}
	include $tpl . 'footer.php';
?>
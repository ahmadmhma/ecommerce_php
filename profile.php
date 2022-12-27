<?php
	session_start();
	$pageTitle='Profile';
	include 'ini.php'; 
	if (isset($_SESSION['user'])) {
		$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->execute(array($suser));
		$info = $stmt->fetch();
	?>
<h1 class="text-center">My Profile</h1>
<div class="information">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My info</div>
			<div class="panel-body">
				<div class="container">
					<div class="row">
						<div class="col-md-3">
							<img class="imgw" src="admin\upload\avatar\
							<?php if(empty($info['avatar'])){ echo "ava.jpg"; }
							      else{ echo $info['avatar']; }  ?>" 
							alt=""/>
						</div>
						<div class="col-sm-8">
							<ul class="list-unstyled">
							<li>
								<i class="fa fa-unlock-alt"></i>
								<span>Name</span>: <?php echo $info['username']; ?>
							</li>
							<li>
								<i class="fa fa-envelope"></i>
								<span>Email</span>: <?php echo $info['email']; ?>
							</li>
							<li>
								<i class="fa fa-user"></i>
								<span>FullName</span>: <?php echo $info['fullname']; ?>
							</li>
							<li>
								<i class="fa fa-calendar"></i>
								<span>Reg Data</span>: <?php echo $info['date']; ?>
							</li>
						</ul>
						<a href="#" class="btn btn-primary">Edit</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 
<div class="information">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Ads</div>
			<div class="panel-body">
				<?php 
					if(!empty(getitems('member_id' , $info['userID']))){
						echo "<div class='row'>";
						foreach (getitems('member_id' , $info['userID'] , 1) as $item) {
							echo '<div class="col-sm-6 col-md-3">';
								echo '<div class="thumbnail item-box">';
								if($item['approve'] == 0){echo "<div class='approve'>Waiting approval</div>";}
									echo '<span class="price">$'. $item['price'] ."</span>";
									echo '<img class="img-responsive" src="ava.jpg" alt="" />';
									echo '<div class="caption text-center">';
										echo '<h3><a href="item.php?itemid='.$item['itemID'] .'">'  . $item['name'] .'</a></h3>';
										echo '<p>'. $item['description'] .'</p>';
										echo '<p>'. $item['date'] .'</p>';
									echo "</div>";
								echo "</div>";
							echo "</div>";}
						echo "</div>";
					}else{
						echo "there is no ads | Create <a href='newad.php'>Ads</a>";
					}

			    ?>
			</div>
		</div>
	</div>
</div>
<div class="information">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
				<?php 
					$stmt = $db->prepare("SELECT comments.* ,items.name FROM comments 
						INNER JOIN items ON items.itemID = comments.item_id WHERE user_id = ?");
					$stmt->execute(array($info['userID']));
					$comments = $stmt->fetchAll();
					if(!empty($comments)){
					foreach ($comments as $comment) {
						echo "<div class='container'>";
							echo "<div class='row'>";
								echo "<div class='col-md-6'>";
									echo '<p>'. $comment['comment'] . '</p>';
								echo "</div>";
								echo "<div class='col-md-3'>";
									echo '<h4><a href="item.php?itemid='. $comment['item_id'] .'">'  
									. $comment['name'] . '</a></h4>';
								echo "</div>";
							echo "</div>";
						echo "</div>";
					}
					}else{echo "There is no comment now";}
				?>
			</div>
		</div>
	</div>
</div>

<?php }else{
	header('location:login.php');
	exit();
}   
	include $tpl . 'footer.php';
?>
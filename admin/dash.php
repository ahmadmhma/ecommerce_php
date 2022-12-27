<?php
session_start();
$pageTitle='dash';
if (isset($_SESSION['username'])) {
	include 'ini.php';
	$l = 5;
	$latest = getlatest('username' , 'users' , 'username' , $l);
	$itemslatest = getlatest('*' ,'items', 'name' ,$l);
	?>
	<div class="container home-stat text-center">
		<h1>Dashborder</h1>
		<div class="row">
			<div class="col-md-3">
				<div class="stat">
					Total members
					<span><a href="members.php"> <?php echo countItem('userID' , 'users');?></a> </span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat">
					Pending members
					<span><a href="members.php?do=mange&page=pending"> <?php echo checkItem('regstatus' , 'users' , 0); ?>
					</a></span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat">
					Total items
					<span><a href="items.php"> <?php echo countItem('itemID' , 'items');?></a> </span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat">
					Total comments
					<span><a href="comments.php"> <?php echo countItem('coid' , 'comments');?></a></span>
				</div>
			</div>
		</div>
	</div>
	<div class="container Latest">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading"><i class="fa fa-users"></i> Latest <?php echo $l ?>
					   registered users
					   <span class="pull-right pl-mi"><i class="fa fa-plus "></i></span>
					</div>
				<div class="panel-body">
				    <?php foreach ($latest as $late ) {
				    	echo $late['username'] .'<br>';
				    } ?>
				  </div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
				  	<div class="panel-heading"><i class="fa fa-tags"></i> 
				  		Latest items
				  		<span class="pull-right pl-mi"><i class="fa fa-plus "></i></span>
					</div>
				  <div class="panel-body">
				  	<ul class="list-unstyled">
				    <?php foreach ($itemslatest as $item ) {
				    	echo "<li>";
				    	echo $item['name'] .'<br>';
				    	echo '</li>';
				    } ?>
				    </ul>
				  </div>
				</div>
			</div>
		</div>
	</div>
	<?php
	include $tpl . 'footer.php';
}else{
	header('location:index.php');
	exit();
}
<!DOCTYPE html> 
<html>
<head>
	<meta charset="utf-8" />
	<title><?php getTitle() ?></title>
	<link rel="stylesheet" href="layout/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="layout/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="layout/css/front.css"/>
</head>
<body>
	<div class="upperbar">
		<div class="container">
			<?php  if (isset($_SESSION['user'])) {  
					$user = $_SESSION['user'];
					$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
					$stmt->execute(array($user));
					$rows = $stmt->fetch();
					
					if(empty($rows['avatar'])){
						echo '<img class="img-responsive img-thumbnail img-circle" src="ava.jpg" 
						   alt="" />';
					}else{
					echo '<img class="img-responsive img-thumbnail img-circle" src="admin/upload/avatar/' 
					. $rows["avatar"] .'" alt="">'; } 
				?>
					   <div class="btn-group">
					   		<span class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					   			<?php echo $_SESSION['user']; ?>
					   			<span class="caret"></span>
					   		</span>
					   		<ul class="dropdown-menu">
					   			<li><a href="profile.php">Profile</a></li>
					   			<li><a href="newad.php">Create Items</a></li>
					   			<li><a href="logout.php">Logout</a></li>
					   		</ul>
					   </div>
					  <?php 
				  }else{ 
		    ?> 
			<a href="login.php">
				<span class="pull-right">Login/Signup</span>
			</a>	
		<?php } ?>
		</div>
	</div>
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#appnav" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php"><?php echo lang('ADMIN')?></a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="appnav">
	      <ul class="nav navbar-nav navbar-right">
	        <?php
		        $stmt = $db->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ID ASC");
				$stmt->execute();
				$cats = $stmt->fetchAll();
	        		foreach ( $cats as $cat) {
	        			echo "<li><a href='categories.php?pageid=". $cat['ID'] ."'>". $cat['name'] .
	        			     "</a></li>";
	        		}
	        ?>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
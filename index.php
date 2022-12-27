<?php
	session_start();
	$pageTitle='Home page';
	include 'ini.php';
?>

<div class="container cate">
	<h1 class="text-center">Category</h1>
	<div class="row"> 
		<?php 
		$stmt = $db->prepare("SELECT * FROM items WHERE approve = 1 ORDER BY itemID DESC");
		$stmt->execute();
		$items = $stmt->fetchAll();
		 foreach ($items as $item) {
			echo '<div class="col-sm-6 col-md-3">';
				echo '<div class="thumbnail item-box">';
					echo '<span class="price">'. $item['price'] ."</span>";
					if (empty($item['itemimg'])) {
						echo '<img class="img-responsive img-thumbnail " src="ava.jpg"
							   alt="" />';
					}else { echo '<img class="img-responsive img-thumbnail  " 
						   	   src="admin/upload/avatar/'.$item['itemimg'].'" alt="" />'; }
					
					echo '<div class="caption text-center">';
						echo '<h3><a href="item.php?itemid='.$item['itemID'] .'">'. $item['name'] .'</a></h3>';
						echo '<p>'. $item['description'] .'</p>';
						echo '<p>'. $item['date'] .'</p>';
					echo "</div>";
				echo "</div>";
			echo "</div>";
		} ?> 
	</div>
</div>

<?php
    include $tpl . 'footer.php';
?>
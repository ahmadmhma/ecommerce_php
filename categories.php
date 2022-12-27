<?php
 session_start();
 $pageTitle='Category';
 include 'ini.php'; ?>
<div class="container">
	<h1 class="text-center">Category</h1>
	<div class="row">
		<?php
		if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
		 foreach (getitems('cat_id',$_GET['pageid']) as $item) {
			echo '<div class="col-sm-6 col-md-3">';
				echo '<div class="thumbnail item-box">';
					echo '<span class="price">'. $item['price'] ."</span>";
					if (empty($item['itemimg'])) {
						echo '<img class="img-responsive img-thumbnail center-block img" src="ava.jpg" 
						alt="" />';
					}else { echo '<img class="img-responsive img-thumbnail center-block img" 
						   	   src="admin/upload/avatar/'.$item['itemimg'].'" alt="" />'; }
						echo '<div class="caption text-center">';
						echo '<h3><a href="item.php?itemid='.$item['itemID'] .'">'. $item['name'] .'</a></h3>';
						echo '<p>'. $item['description'] .'</p>';
						echo '<p>'. $item['date'] .'</p>';
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		}else{
			echo "error";
		} ?> 
	</div>
</div>
<?php include $tpl . 'footer.php'; ?>
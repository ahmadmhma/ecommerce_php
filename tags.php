<?php include 'ini.php'; ?>
<div class="container">
	<div class="row">
		<?php
		if (isset($_GET['name'])) {
			$tags = $_GET['name'];
			echo "<h1 class='text-center'>". $tags ."</h1>";
		$getitems =	getall('*' ,'items' ,"where tags like '%$tags%' " ,'and approve=1' ,'itemID'  );
		 foreach ($getitems as $item) {
			echo '<div class="col-sm-6 col-md-3">';
				echo '<div class="thumbnail item-box">';
					echo '<span class="price">'. $item['price'] ."</span>";
					echo '<img class="img-responsive" src="ava.jpg" alt="" />';
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
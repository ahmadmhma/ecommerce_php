<?php
session_start();
$pageTitle='items';
if (isset($_SESSION['username'])) {
	include 'ini.php';
	$do= isset($_GET['do']) ? $_GET['do']:'mange';
	if ($do == 'mange') {
			$stmt = $db->prepare("SELECT items.* ,categories.name AS catname, users.username FROM items
								  INNER JOIN categories ON categories.ID = items.cat_id
								  INNER JOIN users ON users.userID = items.member_id ORDER BY itemID ASC");
			$stmt->execute();
			$items = $stmt->fetchAll();
		?>
		<h1 class="text-center"> Manage Items </h1>
		<div class="container">
			<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Image</td>
							<td>Name</td>
							<td>Description</td>
							<td>Price</td>
							<td>Adding Date</td>
							<td>Category</td>
							<td>Username</td>
							<td>Control</td>
						</tr>
						<?php
						foreach ($items as $item ) {
							echo "<tr>";
								echo "<td>" . $item['itemID']   . "</td>";
								echo "<td>";
								if(empty($item['itemimg'])){
									echo "<img src='upload/avatar/ava.jpg' alt=''>" ;
								}else{echo "<img src='upload/avatar/". $item['itemimg'] ."' alt=''>";}  
								echo "</td>";
								echo "<td>" . $item['name'] . "</td>";
								echo "<td>" . $item['description']    . "</td>";
								echo "<td>" . $item['price'] . "</td>";
								echo "<td>" . $item['date']     . "</td>";
								echo "<td>" . $item['catname']     . "</td>";
								echo "<td>" . $item['username']     . "</td>";
								echo "<td>" . '<a href="items.php?do=Edit&itemid='.$item['itemID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
											   <a href="items.php?do=Delete&itemid='.$item['itemID'].'" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
											   if($item['approve'] == 0){
											   echo	'<a href="items.php?do=approve&itemid='. $item['itemID'].'"class="btn btn-primary confirm"><i class=" fa fa-exclamation-triangle"></i>approve</a>';
											   }
								   echo "</td>";
							echo "</tr>";
						}
						?>
					</table>
			</div>
			<a href='items.php?do=add' class="btn btn-primary"><i class="fa fa-plus" > New Item</i></a>
		</div>
    <?php }elseif ($do=='add') { ?>
	    	<h1 class="text-center"> Add new items </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=insert" method="POST"
					  enctype="multipart/form-data" />
					<!-- start name faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-6">
							<input type="text" name="name" class="form-control" placeholder="Name of item"       required="required">
						</div>
					</div>
					<!-- end name faild -->
					<!-- start Description faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-6">
							<input type="text" name="description" class="description form-control"  
							       placeholder="Write the description" required="required" />
						</div>
					</div>
					<!-- end Description faild -->
					<!-- start price faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-6">
							<input type="text" name="price" class="form-control"  
							       placeholder="Write the price" required="required"/>
						</div>
					</div>
					<!-- end price faild -->
					<!-- start country faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-6">
							<input type="text" name="country" class="form-control"  
							       placeholder="Write the country made" required="required"/>
						</div>
					</div>
					<!-- end country faild -->
					<!-- start status faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-6">
							<select class="form-control" name="status">
								<option value="0">...</option>
								<option value="1">New</option>
								<option value="2">Like new</option>
								<option value="3">used</option>
								<option value="4">Old</option>
								<option value="5">very old</option>
							</select>
						</div>
					</div>
					<!-- end status faild -->
					<!-- start member faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-6">
							<select class="form-control" name="member">
								<option value="0">...</option>
								<?php
									$stmt = $db->prepare("SELECT * FROM users ");
									$stmt->execute();
									$users = $stmt->fetchAll();
									foreach ($users as $user) {
										echo "<option value=".$user['userID'].">" . $user['username'] .
											 "</option> ";
									}
								?>
							</select>
						</div>
					</div>
					<!-- end member faild -->
					<!-- start category faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-6">
							<select class="form-control" name="category">
								<option value="0">...</option>
								<?php
									$stmt2 = $db->prepare("SELECT * FROM categories WHERE parent = 0");
									$stmt2->execute();
									$cats = $stmt2->fetchAll();
									foreach ($cats as $cat) {
										echo "<option value=". $cat['ID'].">" . $cat['name']. "</option>";
									$child = getall("*" ,"categories" ,"where parent={$cat['ID']}" ,"" ,"ID" ,'DESC' );
									foreach ($child as $c) {
										echo "<option value=". $c['ID'].">--" . $c['name']. "</option>";
									}
									}
								?>
							</select>
						</div>
					</div>
					<!-- end category faild -->
					<!-- start tags faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Tags</label>
						<div class="col-sm-6">
							<input type="text" name="tags" class="form-control"  
							       placeholder="Separate tags whith comma (,)" />
						</div>
					</div>
					<!-- end tags faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Item image</label>
						<div class="col-sm-6">
							<input type="file" name="itemimg" class="form-control" required="required">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<input type="submit" value="Add item" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
	<?php
	}elseif ($do=='insert') {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo "<h1 class='text-center'>Insert item</h1>";
			echo "<div class='container'>";
			//get variable from the form
			$name       = $_POST['name'];
			$desc       = $_POST['description'];
			$price      = $_POST['price'];
			$country    = $_POST['country'];
			$status 	= $_POST['status'];
			$member 	= $_POST['member'];
			$cat 	    = $_POST['category'];
			$tags 	    = $_POST['tags'];

			$imgname 	= $_FILES['itemimg']['name'];
			$imgtmp		= $_FILES['itemimg']['tmp_name'];
			$imgsize    = $_FILES['itemimg']['size'];
			$imgtype	= $_FILES['itemimg']['type'];
			$imgallexp	= array('jpeg' , 'jpg' ,'png');
			$imgexplode = explode('.', $imgname);
			$imgexp 	= strtolower(end($imgexplode));

			$formerrore = array();
			if (empty($name)) {
				$formerrore[] = "<div class='alert alert-danger'>Name can/'t be <strong>empty</strong></div>";
			}
			if (empty($desc)) {
				$formerrore[] = "<div class='alert alert-danger'>Description can/'t be <strong>								empty</strong></div>";
			}
			if (empty($price)) {
				$formerrore[] = "<div class='alert alert-danger'>Price can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($country)) {
				$formerrore[] = "<div class='alert alert-danger'>Country can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($status)) {
				$formerrore[] = "<div class='alert alert-danger'>Status can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($member)) {
				$formerrore[] = "<div class='alert alert-danger'>Member can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($cat)) {
				$formerrore[] = "<div class='alert alert-danger'>Category can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($imgname)) {
				$formerrore[] = "<div class='alert alert-danger'>Image can/'t be 
									<strong>empty</strong></div>";
			}
			if (!empty($imgname) && !in_array($imgexp, $imgallexp)) {
				$formerrore[] = "<div class='alert alert-danger'>Extention can/'t  
									<strong>Allow</strong></div>";
			}
			if ($imgsize > 4194304) {
				$formerrore[] = "<div class='alert alert-danger'>Image can/'t be larger than  
									<strong>4MB</strong></div>";
			}
			foreach ($formerrore as $error ) {
				echo $error;
			}
			if (empty($formerrore)) {
				$itemimg = rand(0 , 1000000000) . '_' . $imgname;
				move_uploaded_file($imgtmp, 'upload/avatar/'.$itemimg);
				//insert the database with this info
				$stmt = $db->prepare("INSERT INTO items(name ,description ,price ,country_made ,status , 
					  member_id ,cat_id, tags ,itemimg ,`date`)
									  VALUES (:aname ,:adesc ,:aprice ,:acountry ,:astatus ,:amember ,:acat ,:atags ,:aitemimg ,now())");
				$stmt->execute( array('aname'    => $name,
									  'adesc'    => $desc,
									  'aprice'   => $price,
									  'acountry' => $country,
									  'astatus'  => $status,
									  'amember'  => $member,
									  'acat'	 => $cat,
									  'atags'	 => $tags,
									  'aitemimg'	 => $itemimg
									));
				echo "<div class='alert alert-success'>" . $stmt->rowcount() . " sucsess update</div>";
				
		}
		}else{
			$errorMsg = "can not allow";
			redirectHome($errorMsg , 5);
		}
		echo "</div>";
	}elseif ($do == 'Edit') { 
		$itemid=isset($_GET['itemid'] )&& is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
      	$stmt= $db->prepare("SELECT  *  FROM items WHERE itemID=? ");
      	$stmt->execute(array($itemid ));
      	$item=$stmt->fetch();
      	$count=$stmt->rowCount();
      	if ($stmt->rowCount()>0) {?>
			<h1 class="text-center"> Add new items </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST"/>
					<input type="hidden" name="itemid" value="<?php echo($itemid); ?>">
					<!-- start name faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-6">
							<input type="text" name="name" class="form-control" placeholder="Name of item"       required="required" value="<?php echo $item['name']?>">
						</div>
					</div>
					<!-- end name faild -->
					<!-- start Description faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-6">
							<input type="text" name="description" class="description form-control"  
							       placeholder="Write the description" required="required"
							       value="<?php echo $item['description']?>" />
						</div>
					</div>
					<!-- end Description faild -->
					<!-- start price faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-6">
							<input type="text" name="price" class="form-control"  
							       placeholder="Write the price" required="required"
							       value="<?php echo $item['price']?>" />
						</div>
					</div>
					<!-- end price faild -->
					<!-- start country faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-6">
							<input type="text" name="country" class="form-control"  
							       placeholder="Write the country made" required="required" 
							       value="<?php echo $item['country_made']?>" />
						</div>
					</div>
					<!-- end country faild -->
					<!-- start status faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-6">
							<select class="form-control" name="status">
								<option value="1" <?php if($item['status']==1){echo "selected";} ?>>
								New</option>
								<option value="2" <?php if($item['status']==2){echo "selected";} ?>>
								Like new</option>
								<option value="3" <?php if($item['status']==3){echo "selected";} ?>>
								used</option>
								<option value="4" <?php if($item['status']==4){echo "selected";} ?>>
								Old</option>
								<option value="5" <?php if($item['status']==5){echo "selected";} ?>>
								very old</option>
							</select>
						</div>
					</div>
					<!-- end status faild -->
					<!-- start member faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-6">
							<select class="form-control" name="member">
								<?php
									$stmt = $db->prepare("SELECT * FROM users ");
									$stmt->execute();
									$users = $stmt->fetchAll();
									foreach ($users as $user) {
										echo "<option value='".$user['userID']."'";
										if ($item['member_id']==$user['userID']) {echo "selected";}
										echo ">" . $user['username'] .
											 "</option> ";
									}
								?>
							</select>
						</div>
					</div>
					<!-- end member faild -->
					<!-- start category faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-6">
							<select class="form-control" name="category">
								<option value="0">...</option>
								<?php
									$stmt2 = $db->prepare("SELECT * FROM categories");
									$stmt2->execute();
									$cats = $stmt2->fetchAll();
									foreach ($cats as $cat) {
										echo "<option value='". $cat['ID']."'";
										if($item['cat_id']==$cat['ID']){echo "selected";}
										echo ">" . $cat['name']. "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- end category faild -->
					<!-- start tags faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Tags</label>
						<div class="col-sm-6">
							<input type="text" name="tags" class="form-control"  
							       placeholder="Separate tags whith comma (,)"
							       value="<?php echo $item['tags']?>" />
						</div>
					</div>
					<!-- end tags faild -->
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<input type="submit" value="save item" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
	<?php
      	}else {echo "no";}
	}elseif ($do == 'update') {
		echo "<h1 class='text-center'>Update Item</h1>";
		echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//get variable from the form
			$id    	  = $_POST['itemid'];
			$name 	  = $_POST['name'];
			$desc     = $_POST['description'];
			$price    = $_POST['price']; 
			$country  = $_POST['country'];
			$status   = $_POST['status'];
			$member   = $_POST['member'];
			$cat      = $_POST['category'];
			$tags     = $_POST['tags'];
			$formerrore = array();
			if (empty($name)) {
				$formerrore[] = "<div class='alert alert-danger'>Name can/'t be <strong>empty</strong></div>";
			}
			if (empty($desc)) {
				$formerrore[] = "<div class='alert alert-danger'>Description can/'t be <strong>								empty</strong></div>";
			}
			if (empty($price)) {
				$formerrore[] = "<div class='alert alert-danger'>Price can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($country)) {
				$formerrore[] = "<div class='alert alert-danger'>Country can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($status)) {
				$formerrore[] = "<div class='alert alert-danger'>Status can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($member)) {
				$formerrore[] = "<div class='alert alert-danger'>Member can/'t be 
									<strong>empty</strong></div>";
			}
			if (empty($cat)) {
				$formerrore[] = "<div class='alert alert-danger'>Category can/'t be 
									<strong>empty</strong></div>";
			}
			foreach ($formerrore as $error ) {
				echo $error;
			}
			
			if (empty($formerrore)) {
				
			//update the database with this info
			$stmt = $db->prepare('UPDATE items SET name=? ,description=?,price=? ,country_made=?,
			status=?, cat_id=?, member_id=? WHERE itemID=?');
			$stmt->execute(array($name , $desc ,$price ,$country, $status, $cat, $member , $id));
			//echo sucsess message
			$msge = "<div class='alert alert-success'> " . $stmt->rowcount() . " sucsess update</div>";
			redirectHome($msge , 'back' , 6);
		}
		}else{
			echo "can not allow";
		}
		echo "</div>";

	}elseif ($do == 'Delete') {
		echo "<h1 class='text-center'>Delete item</h1>";
		echo "<div class='container'>";
			$itemid = isset($_GET['itemid'])&&is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
			$check  = checkItem('itemID' , 'items' , $itemid);
			if ($check > 0) {
				$stmt = $db->prepare('DELETE FROM items WHERE itemID = :zitemid');
				$stmt->bindparam(':zitemid' , $itemid);
				$stmt->execute();
				echo "<div class='alert alert-success'>" . $check . " success Delete</div>";
			}else{
				$msg = "not found ";
				redirectHome($msg , 'b');
			} 
		echo "</div>";
	}elseif ($do == 'approve') {
		echo "<h1 class= 'text-center'>Approve</h1>";
		echo "<div class='container'>";
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']):0;
			$check = checkItem('itemID' , 'items' , $itemid);
			if ($check > 0) {
				$stmt = $db->prepare("UPDATE items SET approve =1 WHERE itemID = ?");
				$stmt ->execute(array($itemid));
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
} ?>
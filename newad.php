<?php 
	session_start();
	$pageTitle='Create New Ads';
	include 'ini.php'; 
	if (isset($_SESSION['user'])) {
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$formError = array();
			$user     = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
			$desc     = filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
			$price    = filter_var($_POST['price'] , FILTER_SANITIZE_NUMBER_INT); 
			$country  = filter_var($_POST['country'] , FILTER_SANITIZE_STRING);
			$status   = filter_var($_POST['status'] , FILTER_SANITIZE_NUMBER_INT);
			$cat      = filter_var($_POST['category'] , FILTER_SANITIZE_NUMBER_INT);
			$tags     = filter_var($_POST['tags'] , FILTER_SANITIZE_STRING);
			// uploar variable
			$imgname 	= $_FILES['itemimg']['name'];
			$imgtmp	 	= $_FILES['itemimg']['tmp_name'];
			$imgsize 	= $_FILES['itemimg']['size'];
			$imgtype 	= $_FILES['itemimg']['type'];
			$imgallexp	= array('jpeg' , 'jpg' , 'png');
			$imgexpl 	= explode('.', $imgname);
			$imgexp		= strtolower(end($imgexpl));

			if(strlen($user) < 4){
				$formError[] = 'item name must be at least 4 char';
			}
			if (strlen($desc) < 10) {
				$formError[] = 'Description must be at least 10 char';
			}
			if (strlen($country) < 2) {
				$formError[] = 'country must be at least 2 char';
			}
			if (empty($price))  {
				$formError[] = 'price must be not empty';
			} 
			if (empty($status)) {
				$formError[] = 'status must be not empty';}
			if (empty($cat))  {
			$formError[] = 'category must be not empty';
			}
			if (empty($imgname))  {
			$formError[] = 'Image must be not empty';
			}
			if (!empty($cat) && ! in_array($imgexp, $imgallexp))  {
			$formError[] = 'extention can not allow';
			}
			if ($imgsize > 4194304)  {
			$formError[] = 'image can not be larger than <strong>4MB</strong>';
			}

			if (empty($formError)) {
				$itemimg = rand(0 , 1000000000).'_'.$imgname;
				move_uploaded_file($imgtmp, 'admin/upload/avatar/'.$itemimg);

			 	//insert the database with this info
				$stmt = $db->prepare("INSERT INTO items(name ,description ,price ,country_made ,status , 
					  member_id ,cat_id ,tags ,itemimg ,`date`)
									  VALUES (:aname ,:adesc ,:aprice ,:acountry ,:astatus ,:amember ,:acat ,:atags ,:aitemimg ,now())");
				$stmt->execute( array('aname'    => $user,
									  'adesc'    => $desc,
									  'aprice'   => $price,
									  'acountry' => $country,
									  'astatus'  => $status,
									  'amember'  => $_SESSION['uid'],
									  'acat'	 => $cat,
									  'atags'	 => $tags,
									  'aitemimg' => $itemimg
									));
				if ($stmt) {
					$successMsg = "the item is added";
				}
			}
		}
	?>
<h1 class="text-center">Create New Ads</h1>
<div class="information abb">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">New ads</div>
			<div class="panel-body">
				<div class="row">
				<div class="col-sm-8">
					<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>"
						  method="POST" enctype="multipart/form-data"/>
					<!-- start name faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-9">
							<input type="text" name="name" class="form-control read" pattern=".{4,}" 
							       title="must be 4 char" placeholder="Name of item" required="required" 
							       data-class=".rname" >
						</div>
					</div>
					<!-- end name faild -->
					<!-- start Description faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-9">
							<input type="text" name="description" class="description form-control read"  
							       placeholder="Write the description" required="required" data-class=".rdesc"/>
						</div>
					</div>
					<!-- end Description faild -->
					<!-- start price faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-9">
							<input type="text" name="price" class="form-control read"  
							       placeholder="Write the price" required="required" data-class=".rprice"/>
						</div>
					</div>
					<!-- end price faild -->
					<!-- start country faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-9">
							<input type="text" name="country" class="form-control"  
							       placeholder="Write the country made" required="required"/>
						</div>
					</div>
					<!-- end country faild -->
					<!-- start status faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-9">
							<select class="form-control" name="status" required="required">
								<option value="">...</option>
								<option value="1">New</option>
								<option value="2">Like new</option>
								<option value="3">used</option>
								<option value="4">Old</option>
								<option value="5">very old</option>
							</select>
						</div>
					</div>
					<!-- end status faild -->
					<!-- start category faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-9">
							<select class="form-control" name="category" required="required">
								<option value="0">...</option>
								<?php
									$stmt2 = $db->prepare("SELECT * FROM categories");
									$stmt2->execute();
									$cats = $stmt2->fetchAll();
									foreach ($cats as $cat) {
										echo "<option value=". $cat['ID'].">" . $cat['name']. "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- end category faild -->
					<!-- start tags faild -->
					<div class="form-group">
						<label class="col-sm-2 control-label">Tags</label>
						<div class="col-sm-9">
							<input type="text" name="tags" class="form-control"  
							       placeholder="Separate tags whith comma (,)" />
						</div>
					</div>
					<!-- end tags faild -->
					<div class="form-group">
					<label class="col-sm-2 control-label">User image</label>
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
				<div class="col-sm-4 ">
					<div class="thumbnail item-box">
						<span class="price rprice">Price</span>
						<img class="img-responsive" src="ava.jpg" alt="" />
					<div class="caption text-center">
						<h3 class="rname">Title</h3>
						<p class="rdesc">Description</p>
					</div>
				</div>
			</div>
			
		</div>
		<?php
				if(!empty($formError)){
					foreach ($formError as $error) {
						echo "<div class='alert alert-danger'>". $error . " </div>";
					}
				}
				if (isset($successMsg)) {
					echo'<div class="alert alert-success">' . $successMsg . '</div>';
				}
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
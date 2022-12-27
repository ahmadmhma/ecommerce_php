<?php
session_start();
$pageTitle='categories';
if (isset($_SESSION['username'])) {
	include 'ini.php';
	$do= isset($_GET['do']) ? $_GET['do']:'mange';
	if ($do == 'mange') {  
		$sort   = 'ASC';
		$bysort = array('ASC' , 'DESC');
		if (isset($_GET['sort']) && in_array($_GET['sort'], $bysort)) {
		 	$sort = $_GET['sort'];
		 } 
		$stmt = $db->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ordering $sort");
		$stmt-> execute();
		$cats = $stmt->fetchAll();
		?>
			<h1 class="text-center">Mange category</h1>
			<div class="container category">
				<div class="panel panel-default">
					<div class="panel-heading">Manage category
						<div class="ord pull-right">Ordering
							<a class="<?php if($sort == 'ASC') echo 'active' ?>" href="?sort=ASC">Asc</a>|
							<a class="<?php if($sort == 'DESC') echo 'active' ?>" href="?sort=DESC">Desc</a>
						</div>
					</div>
					<div class="panel-body">
						<?php
							foreach ($cats as $cat) {
								echo '<div class="cat">';
								echo '<div class="hid-but"><a href="?do=Edit&catid='. $cat['ID'].'"class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
								<a href="?do=Delete&catid='. $cat['ID'] .'"class=" btn btn-danger btn-xs confirm"><i class="fa fa-close"></i> Delete</a></div>';
								echo "<h3>" . $cat['name'] . "</h3>";
								echo "<div class='mini'>";
									echo "<p>"; if ($cat['description'] =='') {echo 'this category has no description';}else{echo $cat['description'];} echo "</p>";
									if ($cat['visibility'] ==1) { echo "<span class='vis'>Hidden</span>";}
									if ($cat['allowcomment'] ==1) {echo "<span class='alc'>Disable</span>";} 
									if($cat['allowads'] ==1){echo "<span class='alad'>Disable</span>";} 
								echo "</div>";
							$gets =	getall("*" ,"categories" ,"where parent= {$cat['ID']}" ," " ,"ID","ASC");
								if(! empty($gets)){
								echo "<h4 class='chead'>Child category</h4>";
								echo "<ul class='list-unstyled cul'>";
								foreach ($gets as $get) {
									echo "<li class='cli'>- <a href='?do=Edit&catid=". $get['ID']."'>"
										 . $get['name'] . "</a>
										 <a href='?do=Delete&catid=". $get['ID'] ."'class='showde confirm'> Delete</a>
										 </li>";
								}
								echo "</ul>";
							   }
								echo "</div>";
								echo "<hr class='chr'>";
							}
						?>
					</div>
				</div>
				<a href="?do=add" class="btn btn-primary">Add new category</a>
			</div>
		<?php
   }elseif ($do=='add') { ?>
   		<h1 class="text-center"> Add new categories </h1>
		<div class="container">
			<form class="form-horizontal" action="?do=insert" method="POST"/>
				<div class="form-group">
					<label class="col-sm-2 control-label">Name</label>
					<div class="col-sm-6">
						<input type="text" name="name"  class="form-control" autocomplete="off" placeholder="Name of category" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-6">
						<input type="text" name="description" class="description form-control"  placeholder="Write the description"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Ordering</label>
					<div class="col-sm-6">
						<input type="text" name="order" class="form-control" 
							   placeholder="Arrange categories" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Parent?</label>
					<div class="col-sm-6">
						<select name="parent" class="form-control">
							<option value="0">None</option>
							<?php
								$stmt = $db->prepare("SELECT * FROM categories WHERE parent = 0 
													  ORDER BY ID ASC");
								$stmt->execute();
								$cats = $stmt->fetchall();
								foreach ($cats as $cat) {
									echo '<option value="'. $cat['ID'] .'">'. $cat['name'] . '</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Visible</label>
					<div class="col-sm-6">
						<div>
							<input type="radio" id="yes" name="visible" value="0" checked />
							<label for="yes">Yes</label>
						</div>
						<div>
							<input type="radio" name="visible" id="no" value="1" />
							<label for="no">No</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Allow comment</label>
					<div class="col-sm-6">
						<div>
							<input type="radio" id="yes" name="allowc" value="0" checked />
							<label for="yes">Yes</label>
						</div>
						<div>
							<input type="radio" name="allowc" id="no" value="1" />
							<label for="no">No</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Allow ads</label>
					<div class="col-sm-6">
						<div>
							<input type="radio" id="yes" name="allowad" value="0" checked />
							<label for="yes">Yes</label>
						</div>
						<div>
							<input type="radio" name="allowad" id="no" value="1" />
							<label for="no">No</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-6">
						<input type="submit" value="Add category" class="btn btn-primary">
					</div>
				</div>
			</form>
		</div>
	<?php
   }elseif ($do=='insert') {
	   	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo "<h1 class='text-center'>Update Member</h1>";
			echo "<div class='container'>";
			//get variable from the form
			$name          = $_POST['name'];
			$desc          = $_POST['description'];
			$order         = $_POST['order'];
			$parent        = $_POST['parent'];
			$visible       = $_POST['visible'];
			$allowc        = $_POST['allowc'];
			$allowad	   = $_POST['allowad'];
			$check = checkItem('name' , 'categories' ,$name);
			if ($check ==1) {
				$msg = "the name is exist";
				redirectHome($msg , ' ' );
			}else{
				//insert the database with this info
				$stmt = $db->prepare("INSERT INTO categories(name, description ,ordering ,parent ,visibility 							   ,allowcomment, allowads )
							VALUES (:aname ,:adesc ,:aorder ,:aparent ,:avisible , :aallowc ,:aallowad )");
				$stmt->execute( array('aname' 		=> $name,
									  'adesc' 		=> $desc,
									  'aorder'		=> $order,
									  'aparent'		=> $parent,
									  'avisible'    => $visible,
									  'aallowc' 	=> $allowc,
									  'aallowad'	=> $allowad,
										));
				echo "<div class='alert alert-success'>" . $stmt->rowcount() . " sucsess update</div>";
			}
		}else{
			$errorMsg = "can not allow";
			redirectHome($errorMsg , 5);
		}
		echo "</div>";
   }elseif ($do == 'Edit') {
   	$catid = isset($_GET['catid'])&&is_numeric($_GET['catid'])?intval($_GET['catid']):0;
   	$stmt = $db->prepare("SELECT * FROM categories WHERE ID=? ");
   	$stmt->execute(array($catid));
   	$cats  = $stmt->fetch();
   	$count = $stmt->rowcount();
   	if ($count > 0) {?>
  		<h1 class="text-center">Edit category</h1>
  		<div class="container">
  			<form class="form-horizontal" action="?do=update" method="POST">
  				<input type="hidden" name="catid" value="<?php echo($catid) ?>">
  				<div class="form-group">
  					<label class="col-sm-2 control-label">Name</label>
  					<div class="col-sm-6">
  						<input type="text" name="name" required class="form-control" value="<?php echo($cats['name']) ?>">
  					</div>
  				</div>
  				<div class="form-group">
  					<label class="col-sm-2 control-label">Description</label>
  					<div class="col-sm-6">
  						<input type="text" name="description" class="form-control" value="<?php echo($cats['description']) ?>">
  					</div>
  				</div>
  				<div class="form-group">
  					<label class="col-sm-2 control-label">Ordering</label>
  					<div class="col-sm-6">
  						<input type="text" name="order" class="form-control" value="<?php echo($cats['ordering']) ?>">
  					</div>
  				</div>
  				<div class="form-group">
					<label class="col-sm-2 control-label">Parent?</label>
					<div class="col-sm-6">
						<select name="parent" class="form-control">
							<option value="0">None</option>
							<?php
								$stmt = $db->prepare("SELECT * FROM categories WHERE parent = 0 
													  ORDER BY ID ASC");
								$stmt->execute();
								$cat = $stmt->fetchall();
								foreach ($cat as $c) {
									echo '<option value="'. $c['ID'] .'"';
									if ($cats['parent'] == $c['ID']) {
										echo " selected";
									}
									echo '>'. $c['name'] . '</option>';
								}
							?>
						</select>
					</div>
				</div>
  				<div class="form-group">
  					<label class="col-sm-2 control-label">Visible</label>
  					<div class="col-sm-6">
  						<div>
	  						<input type="radio" name="visible" id="yes" value="0" <?php if ($cats['visibility']==0) {echo "checked";} ?> />
	  						<label for="yes">Yes</label>
  						</div>
  						<div>
  							<input type="radio" name="visible" id="no" value="1" <?php if ($cats['visibility']==1) {echo "checked";} ?>>
  							<label for="no">NO</label>
  						</div>
  					</div>
  				</div>
  				<div class="form-group">
  					<label class="col-sm-2 control-label">Allow comment</label>
  					<div class="col-sm-6">
  						<div>
  							<input type="radio" name="allowc" id="yes" value="0" <?php if ($cats['allowcomment']==0) {echo "checked";} ?>>
  							<label for="yes">Yes</label>
  						</div>
  						<div>
  							<input type="radio" name="allowc" id="no" value="1" <?php if ($cats['allowcomment']==1) {echo "checked";} ?>>
  							<label for="no">NO</label>
  						</div>
  					</div>
  				</div>
  				<div class="form-group">
  					<label class="col-sm-2 control-label">Allow Ads</label>
  					<div class="col-sm-6">
  						<div>
  							<input type="radio" name="allowad" id="yes" value="0" <?php if ($cats['allowads']==0) {echo "checked";} ?>>
  							<label for="yes">Yes</label>
  						</div>
  						<div>
  							<input type="radio" name="allowad" id="no" value="1" <?php if ($cats['allowads']==1) {echo "checked";} ?>>
  							<label for="no">NO</label>
  						</div>
  					</div>
  				</div>
  				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-6">
						<input type="submit" value="Save" class="btn btn-primary">
					</div>
				</div>
  			</form>
  		</div>
   	<?php }else {echo "no";}
 	 }elseif ($_GET['do'] == 'update') {
		echo "<h1 class='text-center'>Update Category</h1>";
		echo "<div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//get variable from the form
			$id 		   = $_POST['catid'];
			$name          = $_POST['name'];
			$desc          = $_POST['description'];
			$order         = $_POST['order'];
			$parent        = $_POST['parent'];
			$visible       = $_POST['visible'];
			$allowc        = $_POST['allowc'];
			$allowad	   = $_POST['allowad']; 
			//update the database with this info
			$stmt = $db->prepare('UPDATE categories SET name=? ,description=?,ordering=? ,parent=? 
				                  ,visibility=? , allowcomment=? ,allowads=? WHERE ID=?');
			$stmt->execute(array( $name ,$desc ,$order ,$parent , $visible ,$allowc , $allowad ,$id));
			//echo sucsess message
			$msge = "<div class='alert alert-success'> " . $stmt->rowcount() . " sucsess update</div>";
			redirectHome($msge , 'back' , 6);
		}
		else{
		echo "can not allow";
	}
	echo "</div>";
   }elseif ($do == 'Delete') {
   		echo "<h1 class='text-center'>Delete category</h1>";
		echo "<div class='container'>";
		$catid = isset($_GET['catid'])&&is_numeric($_GET['catid'])?intval($_GET['catid']):0;
		$check  = checkItem('ID' , 'categories' , $catid);
		if ($check > 0) {
			$stmt = $db->prepare('DELETE FROM categories WHERE ID = :zid');
			$stmt->bindparam(':zid' , $catid);
			$stmt->execute();
			echo "<div class='alert alert-success'>" . $check . " success Delete</div>";
		}else{
			$msg = "not found ";
			redirectHome($msg , 'b');
		} 
	echo "</div>";}
   include $tpl . 'footer.php';
 } else{
	header('location:index.php');
	exit();
}
?>
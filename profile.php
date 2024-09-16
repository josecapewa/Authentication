<?php
  require_once('load.php');
?>
  <?php
  $user_id = (int)$_GET['id'];
  if(empty($user_id)):
    header("Location: home.php");
    exit();
  else:
    global $db;
    $sql = $db->query("SELECT * FROM user WHERE id='$user_id' LIMIT 1");
    if($result = $db->fetch_assoc($sql))
        $user_profile = $result;
   else
        $user_profile = null;
  endif;
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-4">
       <div class="panel profile">
         <div class="jumbotron text-center bg-red">
            <img class="img-circle img-size-2" src="uploads/<?php echo $user['image'] ?>" alt="">
           <h3><?php echo $user_profile['name']; ?></h3>
         </div>
        <?php if( $user_profile['id'] === $user['id']):?>
         <ul class="nav nav-pills nav-stacked">
          <li><a href="edit_profile.php"> <i class="glyphicon glyphicon-edit"></i> Edit profile</a></li>
         </ul>
       <?php endif;?>
       </div>
   </div>
</div>
<?php include_once('layouts/footer.php'); ?>

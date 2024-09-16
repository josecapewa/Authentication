<?php  
  global $db;
  $id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : '';
  if(!empty($id)){
    $sql = $db->query("SELECT * FROM user WHERE id='$id' LIMIT 1");
    if($result = $db->fetch_assoc($sql))
      $user = $result;
    else
      $user = null;
  }


?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title> Authorization </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left"> Testing Authorization </div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("F j, Y, g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">
          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
              <img src="uploads/<?php echo $user['image'] ?>" alt="user-image" class="img-circle img-inline">
              <span><?php echo $user['name']; ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                      <i class="glyphicon glyphicon-user"></i>
                      Perfil
                  </a>
              </li>
             <li>
                 <a href="edit_profile.php" title="edit account">
                     <i class="glyphicon glyphicon-cog"></i>
                     Definições
                 </a>
             </li>
             <li class="last">
                 <a href="logout.php">
                     <i class="glyphicon glyphicon-off"></i>
                     Sair
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar"> 
        <?php include_once('menu.php');?>
    </div>
    
<?php endif;?>

<div class="page">
  <div class="container-fluid">

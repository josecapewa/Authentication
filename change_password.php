<?php 
    require_once("load.php");
    page_require_level(3);

    global $db;
    $id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : '';
    $sql = $db->query("SELECT * FROM user WHERE id='$id' LIMIT 1");
    if($result = $db->fetch_assoc($sql))
        $user = $result;
    else
        $user = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new = isset($_POST['new-password']) ? $_POST['new-password'] : '';
    
        if (!empty($new)) {
            $sql = "UPDATE user SET password = '$new' where id = '$id'";
            $result = $db->query($sql);
            if($result && $db->affected_rows() === 1){
                $session->logout();
                $session->msg('s', 'Password atualizada com sucesso. Inicie sess«ªo para continuar.');
                header("Location: index.php");
            } else{
                $session->msg('d', 'Password n«ªo atualizada');
                header("Location: change_password.php");
            }
        } else{
            header("Location: change_password.php");
        }
    }
?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Mude a sua password</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="change_password.php" class="clearfix">
        <div class="form-group">
              <label for="oldPassword" class="control-label">Password antiga</label>
              <input type="password" class="form-control" name="old-password" placeholder="Old password">
        </div>
        <div class="form-group">
              <label for="newPassword" class="control-label">Nova password</label>
              <input type="password" class="form-control" name="new-password" placeholder="Nova password">
        </div>
        <div class="form-group clearfix">
               <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
                <button type="submit" name="update" class="btn btn-info">Alterar</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>

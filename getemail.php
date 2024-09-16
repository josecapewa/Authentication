<?php
session_start();
    require_once('load.php');
    include("./Templates/email_recover.php");
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        if(!empty($email)){
            $sql = "SELECT * from user where email = '$email'";
            $result = $db->query($sql);
            if($db->num_rows($result) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $user = $db->fetch_assoc($result);
                $auth = new Auth($user['recuperation_email'], $htmlcontent, "recover");
                if ($auth->mail->send()) {
                    $_SESSION['user'] = $user;
                    header("Location: recuperation.php");
                    exit();
                } else {
                    $session->msg("d",'Erro ao enviar o e-mail: ' . $auth->mail->ErrorInfo);
                }
            } else {
                $session->msg('d', 'Conta n«ªo encontrada');
                header("Location: getemail.php");
            }
        } else{
            $session->msg('d', 'Insira um email');
        }
    }

?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h2>Recuperar conta</h2>
       <p>Insira o email da sua conta</p>
     </div>
     <?php echo display_msg($msg)?>
      <form method="post" class="clearfix">
        <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input type="text" class="form-control" name="email" placeholder="email">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Login</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>

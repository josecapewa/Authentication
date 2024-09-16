<?php
require_once('load.php');
session_start();
if(!isset($_SESSION['user'])){ 
    $session->msg("d", "Tente efectuar o login primeiro!");
    header('Location: index.php');
}

        
    $given_code = isset($_POST['code']) ? $_POST['code'] : '';


    $code = isset($_SESSION['code']) ? $_SESSION['code'] : '';
    $expiration_time = isset($_SESSION['expiration_time']) ? $_SESSION['expiration_time'] : 0;
    
    if(!empty($given_code) && !empty($code) && !empty($expiration_time)){
        if (time() <= $expiration_time) {
            if ($given_code == $code) {
                $session->msg("s", "Verificado com sucesso");
                unset($_SESSION['code']);
                unset($_SESSION['expiration_time']);
                header('Location: new_password.php');
                exit();
            } else {
                $session->msg("d", "Código incorreto");
                unset($_SESSION['user']);
            }
        } else {
            $session->msg("d", "O código expirou");
            unset($_SESSION['code']);
            unset($_SESSION['expiration_time']);
            unset($_SESSION['user']);
        }
    }
    
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : '';

    if (isset($user)) {
        $session->msg("s", "Usuário ". $user_id . " encontrado.");
    } else {
        $session->msg("d", "Usuário ". $user_id . " não encontrado.");
    }

?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h2>Código de recuperação</h2>
       <p>Foi enviado um código de recuperação para o email <?php echo $user['recuperation_email']; ?></p>
       <?php echo display_msg($msg)?>
     </div>
      <form method="post" class="clearfix">
        <div class="form-group">
              <label for="code" class="control-label">Código de verificação</label>
              <input type="text" class="form-control" name="code" placeholder="codigo de recuperação">
        </div>
        <div class="form-group">
            Não recebeu o código? <a href="recuperation.php"> Reenviar código </a>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Recuperar conta</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
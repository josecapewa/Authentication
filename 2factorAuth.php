<?php
require_once('load.php');
if(!isset($_SESSION['user'])){ 
    $session->msg("d", "Tente efectuar o login primeiro!");
    header('Location: index.php');
}

        
        $given_code = isset($_POST['code']) ? $_POST['code'] : '';
        $code = isset($_SESSION['code']) ? $_SESSION['code'] : '';
        $expiration_time = isset($_SESSION['expiration_time']) ? $_SESSION['expiration_time'] : 0;

        if(!empty($given_code) && !empty($code)){
            if (time() <= $expiration_time) {
            if ($given_code == $code) {
                $session->msg("s","Verificado com sucesso" . $_SESSION['user']['id']);
                unset($_SESSION['code']);
                unset($_SESSION['expiration_time']);
                $session->login($_SESSION['user']['id']);
                unset($_SESSION['user']);
                header('Location: home.php');
            } else {
                $session->msg("d","Código incorreto");
                unset($_SESSION['user']);
            }
        } else {
            $session->msg("d","O código expirou");
            unset($_SESSION['code']);
            unset($_SESSION['expiration_time']);
            unset($_SESSION['user']);
        }
    } else{
            $session->msg("d","Campo vazio, por favor insira o código");
    }
    
?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h2>Verificar Identidade</h2>
       <p>Foi enviado um código de verificação para o email <?php echo $_SESSION['user']['email']?></p>
     </div>
       <?php echo display_msg($msg)?>
      <form method="post" class="clearfix">
        <div class="form-group">
              <label for="code" class="control-label">Código de verificação</label>
              <input type="text" class="form-control" name="code" placeholder="codigo de verificação">
        </div>
        <div class="form-group">
            Não recebeu o código? <a href="2factorAuth.php"> Reenviar código </a>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Verificar identidade</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
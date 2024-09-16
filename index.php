<?php
    ob_start();
    require_once "load.php";
    if($session->isUserLoggedIn(true)) { header("Location: home.php"); };
    include("./Templates/email_auth.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        unset($_SESSION['user']);

        if (!empty($email) && !empty($password)) {
            $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
            $return = $db->query($sql);
            if($db->num_rows($return)){
                $user = $db->fetch_assoc($return);
                if($user['auth']==1){
                    $auth = new Auth($email, $htmlcontent, "auth");
                    if ($auth->mail->send()) {
                        $_SESSION['user'] = $user;
                        header("Location: 2factorAuth.php");
                        exit();
                    } else {
                        $session->msg("d",'Erro ao enviar o e-mail: ' . $auth->mail->ErrorInfo);
                    }
                } else{
                    $session->login($user['id']);
                    header("Location: home.php");
                }
            } else{
                $session->msg("d", "Email/Password incorreto");
                header("Location: index.php");
            }
        } else {
            $session->msg("d", "Campos vazios!");
            header("Location: index.php");
        }
    }
?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h1>Boas Vindas</h1>
       <p>Faça login para inicar sessão</p>
     </div>
     <?php echo display_msg($msg)?>
      <form method="post" class="clearfix">
        <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input type="text" class="form-control" name="email" placeholder="email">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="password">
        </div>
        <div class="form-group">
            Esqueceu a senha? <a href="getemail.php">Recuperar conta </a>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Login</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>

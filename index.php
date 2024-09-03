<?php
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (!empty($username) && !empty($password)) {
            $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
            $return = $db->query($sql);
            if($db->num_rows($return)){
                $user = $db->fetch_assoc($return);
                if($password === $user['password']){
                    $session->login($user['id']);
                    header("Location: home.php");
                    exit();
                }else{
                    header("Location: index.php");
                }
            } 
        } else {
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
      <form method="post" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="password">
        </div>
        <div class="form-group">
            <span>
                Não tem uma conta? <a href="signin.php">Cadastrar</a>
            </span>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Login</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>

<?php
  session_start();
  require_once("db.php");
  require_once('src/PHPMailer.php');
  require_once('src/SMTP.php');
  require_once('src/Exception.php');
  
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;
  $mail = new PHPMailer(true);
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = isset($_POST['name']) ? $_POST['name']: '';
    $email = isset($_POST['email']) ? $_POST['email']: '';
    $password = isset($_POST['password']) ? $_POST['password']: '';

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

      $sel = "SELECT * from user where email = '$email'";
      $a = $db->query($sel);
      if($db->num_rows($a)){
        $res = $db->fetch_assoc($a);
      } else{
        $res = null;
      }
      $_SESSION['name'] = $res['name'];
        try{
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pascoaltondo.code@gmail.com';
            $mail->Password = 'mpeqoqlfxlxfkyjz';
            $mail->Port = 587;
    
            $mail->setFrom('pascoaltondo.code@gmail.com');
            $mail->addAddress("$email");
    
            $min = 1234;
            $max = 9578;
            $code = mt_rand($min, $max);
            $_SESSION['code'] = $code;
    
            $mail->isHTML(true);
            $mail->Subject = "Codigo de autenticar";
            $mail->Body = "Ola caríssimo o seu código de autenticação é: <strong>$code</strong> introduza este código para fazeres o login. Equipa Pascoal Nzola Tondo.";
            $mail->AltBody = "Ola caríssimo o eu código de verificação é: $code introduza este código para fazeres o login. Equipa Pascoal Nzola Tondo.";
            if($mail->send()){
                echo 'email enviado';
                
            }
            else{
                echo 'email não enviado';
            }
        }
        catch (Exception $ex){
            echo "<script>alert('Erro ao enviar o código')</script>";
        }
    } else {
        echo "<script>
                alert('Email Inválido!');
            </script>";
    }

    } else{
      echo "E-mail inválido";
    }

?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h1>Boas Vindas</h1>
       <p>Insira os seus dados para se cadastrar</p>
     </div>
      <form method="post" class="clearfix">
        <div class="form-group">
            <label for="Name" class="control-label">name</label>
            <input type="text" name="name" class="form-control" placeholder="name">
        </div>
        <div class="form-group">
            <label for="Email" class="control-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="email">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="password">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Cadastrar</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>

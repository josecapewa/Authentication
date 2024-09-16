<?php
require_once("load.php");
page_require_level(1);

function find_all() {
    global $db;
    $sql = "SELECT * FROM user_level";
    $result = $db->query($sql);
    return $db->while_loop($result);
}

$levels = find_all();

 
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
  
    $identification = isset($_POST['identification']) ? $_POST['identification'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $recuperation_email = isset($_POST['recuperation_email']) ? $_POST['recuperation_email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $level = isset($_POST['level']) ? (int)$_POST['level'] : 0;
    $state = isset($_POST['state']) ? (int)$_POST['state'] : '';
    
    if($recuperation_email == ''){
        $recuperation_email = $email;
    }

    if(!empty($identification) && !empty($name) && !empty($email) && !empty($password)){
      if(validate_identification($identification)){
        $sql = "INSERT INTO user (email, password, user_level, name, recuperation_email, auth, identification)
            VALUES ('$email', '$password', $level, '$name', '$recuperation_email', '$state', '$identification')";

      if ($db->query($sql)) {
        $session->msg('s', $name . ' adicionado/a com sucesso');
        header("Location: users.php");
      } else {
        $session->msg('d', 'Conta não criada');
        header("Location: add_user.php");
      }
      } else{
        $session->msg("d", "Bilhete de Identidade inválido");
        header("Location: add_user.php");
      }
    } else{
      $session->msg('d', 'Preencha todos os campos');
      header("Location: add_user.php");
    }
  }

include_once('layouts/header.php');
?>

<div class="row">
    <?php echo display_msg($msg); ?>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Adicionar novo usuário</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <form method="post" action="add_user.php">
                    <div class="form-group">
                        <label for="identification">Número do Bilhete de Identidade</label>
                        <input type="text" class="form-control" name="identification" placeholder="Bilhete de identidade"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome Completo" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="email" required>
                    </div>
                    <div class="form-group">
                        <label for="recuperation_email">Email de Recuperação (opcional)</label>
                        <input type="email" class="form-control" name="recuperation_email" placeholder="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="level">Função</label>
                        <select class="form-control" name="level" required>
                            <?php foreach ($levels as $level): ?>
                            <option value="<?php echo htmlspecialchars($level['number']); ?>">
                                <?php echo htmlspecialchars($level['level_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">Verificação de dois fatores</label>
                        <select class="form-control" name="state">
                            <option value="1">Ativar</option>
                            <option value="0">Desativar</option>
                        </select>
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" name="add_user" class="btn btn-primary">Adicionar usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
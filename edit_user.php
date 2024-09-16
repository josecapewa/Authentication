<?php
require_once('load.php');
page_require_level(1);

function find($table, $id = null){
    global $db;
    if($table == 'user'){
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = $db->query($sql);
        return $result->fetch_assoc();
    } else {
        $sql = "SELECT * FROM $table";
        $result = $db->query($sql);
        $set_results = $db->while_loop($result);
        return $set_results;
    }
}

$e_user = find('user', $_GET['id']);
$levels = find('user_level');

if (!$e_user) {
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $id = (int)$e_user['id'];
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $identification = isset($_POST['identification']) ? $_POST['identification'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $recuperation_email = isset($_POST['recuperation_email']) ? $_POST['recuperation_email'] : $email;
        $level = isset($_POST['level']) ? $_POST['level'] : '';
        $state = isset($_POST['state']) ? $_POST['state'] : '';

        if (!empty($email) && !empty($name) && !empty($recuperation_email) && !empty($identification)) {
            if(validate_identification($identification)){
                $sql = "UPDATE user SET name ='$name', email ='$email', user_level = '$level', recuperation_email = '$recuperation_email', auth= $state, identification = '$identification' WHERE id=$id";
                $result = $db->query($sql);

                if ($result && $db->affected_rows() === 1) {
                    $session->msg("s", "$name atualizado com sucesso!");
                    header("Location: users.php");
                    exit;
                } else {
                    header("Location: edit_user.php?id=" . (int)$e_user['id']);
                    exit;
                }
            } else {
                $session->msg('d', 'Bilhete de identidade inválido');
                header('Location: edit_user.php?id='  . (int)$e_user['id']);
            }
        } else {
            $session->msg("d", "Há campos que não podem estar vazios!");
            header("Location: edit_user.php?id=" . (int)$e_user['id']);
            exit;
        }
    }


    if (isset($_POST['password'])) {
        $id = (int)$e_user['id'];
        $password = trim($_POST['password']); 

        if (empty($password)) {
            $session->msg("d", "A senha não pode estar em branco.");
            header("Location: edit_user.php?id=" . $id);
            exit;
        }

        $sql = "UPDATE user SET password = '$password' WHERE id = $id";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->msg("s", "Senha atualizada com sucesso!");
        } else {
            $session->msg("d", "Não foi possível atualizar a senha.");
        }

        header("Location: edit_user.php?id=" . $id);
        exit;
    }


    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $id = (int)$e_user['id'];
        $photo = new Media();
        
        try {
            $photo->upload($_FILES['profile_image']);
            if ($photo->process_user($id)) {
                $session->msg("d", "Falha ao atualizar a imagem de perfil.");
            } else {
                $session->msg("s", "Imagem de perfil atualizada com sucesso!");
            }
        } catch (RuntimeException $e) {
            $session->msg("d", "Erro: " . $e->getMessage());
        }

        header("Location: edit_user.php?id=" . $id);
        exit;
    }
}
?>
<?php include_once('layouts/header.php'); ?>
         <?php echo display_msg($msg);?>
 <div class="row">
  <div class="col-md-6">
     <div class="panel panel-default">
       <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Atualizar a conta de <?php echo $e_user['name']; ?> 
        </strong>
       </div>
       <div class="panel-body">
          <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" class="clearfix">
            <div class="form-group">
                  <label for="identification" class="control-label">Identificação</label>
                  <input type="name" class="form-control" name="identification" value="<?php echo $e_user['identification']; ?>">
            </div>
            <div class="form-group">
                  <label for="name" class="control-label">Nome</label>
                  <input type="name" class="form-control" name="name" value="<?php echo $e_user['name']; ?>">
            </div>
            <div class="form-group">
                  <label for="email" class="control-label">Email</label>
                  <input type="text" class="form-control" name="email" value="<?php echo $e_user['email']; ?>">
            </div>
            <div class="form-group">
                  <label for="recuperation_email" class="control-label">Email de Recuperação</label>
                  <input type="text" class="form-control" name="recuperation_email" value="<?php echo $e_user['recuperation_email']; ?>">
            </div>
            <div class="form-group">
              <label for="level">Função</label>
              <select class="form-control" name="level">
                    <?php 
                        $options = [
                            1 => 'Administrador',
                            2 => 'Nível 2',
                            3 => 'Nível 3'
                        ];
                        foreach ($options as $value => $text):
                        $selected = ($e_user['user_level'] == $value) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>>
                        <?php echo $text; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="level">Verificação de dois fatores</label>
                <select class="form-control" name="state">
                    <?php 
                        $options = [
                            1 => 'Ativar',
                            0 => 'Desativar'
                        ];
                        foreach ($options as $value => $text):
                        $selected = ($e_user['auth'] == $value) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>>
                        <?php echo $text; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group clearfix">
                    <button type="submit" name="update" class="btn btn-info">Update</button>
            </div>
        </form>
       </div>
     </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Alterar a password de <?php echo $e_user['name']; ?> 
        </strong>
      </div>
      <div class="panel-body">
        <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post" class="clearfix">
          <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Escreva a sua nova password">
          </div>
          <div class="form-group clearfix">
                  <button type="submit" name="update-pass" class="btn btn-danger pull-right">Alterar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-picture"></span>
                Alterar imagem de <?php echo htmlspecialchars($e_user['name']); ?> 
            </strong>
        </div>
        <div class="panel-body"> 
        <div class="col-md-4">
            <img class="img-circle img-size-2" src="./uploads/<?php echo $e_user['image'] ?>" alt="">
        </div>
        <div class="col-md-8">
            <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post" enctype="multipart/form-data" class="clearfix">
                <div class="form-group">
                    <label for="profile_image" class="control-label">Imagem de Perfil</label>
                    <input type="file" class="form-control" name="profile_image">
                </div>
                <div class="form-group clearfix">
                    <button type="submit" name="update-image" class="btn btn-primary">Atualizar Imagem</button>
                </div>
            </form>
        </div>
    </div>
</div>


 </div>
<?php include_once('layouts/footer.php'); ?>

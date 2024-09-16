<?php
require_once('load.php');
page_require_level(3);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $photo = new Media();
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $user_id = (int)$_POST['user_id'];
        try {
            if ($photo->upload($_FILES['file_upload']) && $photo->process_user($user_id)) {
                $session->msg("d", "Falha ao atualizar a foto.");
            } else {
                $session->msg("s", "Foto atualizada com sucesso!");
            }
        } catch (RuntimeException $e) {
            $session->msg("d", "Erro: " . $e->getMessage());
        }
    } else {
        $session->msg("d", "Erro ao enviar o arquivo. Verifique o tamanho do arquivo e tente novamente.");
    }
    header("Location: edit_profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = (int)$_SESSION['user_id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $recuperation_email = isset($_POST['recuperation_email']) ? $_POST['recuperation_email'] : '';

    if (!empty($email) && !empty($name) && !empty($recuperation_email)) {
        $sql = "UPDATE user SET name ='$name', email ='$email', recuperation_email = '$recuperation_email' WHERE id='$id'";
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg("s", "Conta atualizada com sucesso!");
        } else {
            $session->msg("d", "Ocorreu um erro ao atualizar a conta.");
        }
    } else {
        $session->msg("d", "Todos os campos são obrigatórios.");
    }
    header("Location: edit_profile.php");
    exit();
}

include_once('layouts/header.php');
?>

<div class="row">
    <?php echo display_msg($msg); ?>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-camera"></span>
                    <span>Mudar a foto</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <img class="img-circle img-size-2" src="./uploads/<?php echo $user['image'] ?>" alt="">
                    </div>
                    <div class="col-md-8">
                        <form class="form" action="edit_profile.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="file" name="file_upload" class="btn btn-default btn-file"/>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <button type="submit" name="submit" class="btn btn-warning">Alterar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-edit"></span>
                    <span>Editar minha conta</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_profile.php" class="clearfix">
                    <div class="form-group">
                        <label for="identification" class="control-label">Identificação</label>
                        <input type="text" class="form-control" name="identification" value="<?php echo htmlspecialchars($user['identification']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label">Nome</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="recuperation_email" class="control-label">Email de Recuperação</label>
                        <input type="email" class="form-control" name="recuperation_email" value="<?php echo htmlspecialchars($user['recuperation_email']); ?>">
                    </div>
                    <div class="form-group clearfix">
                        <a href="change_password.php" title="change password" class="btn btn-danger pull-right">Mudar password</a>
                        <button type="submit" name="update" class="btn btn-info">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

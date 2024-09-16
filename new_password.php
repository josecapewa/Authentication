<?php 
    require_once("load.php");
    session_start(); // Inicie a sessão

    global $db;
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $id = isset($user['id']) ? (int)$user['id'] : 0;

    if ($id > 0) {
        $sql = $db->query("SELECT * FROM user WHERE id='$id' LIMIT 1");
        if ($result = $db->fetch_assoc($sql)) {
            $user = $result;
        } else {
            $user = null;
        }
    } else {
        $user = null;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new = isset($_POST['new-password']) ? $_POST['new-password'] : '';
        $confirm = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

        if ($new == $confirm) {
            if (!empty($new)) {
                $sql = "UPDATE user SET password = '$new' WHERE id = $id";
                $result = $db->query($sql);

                if ($result && $db->affected_rows() === 1) {
                    $session->logout();
                    $session->msg('s','Senha atualizada com sucesso. Faça login para continuar.');
                    header("Location: index.php");
                    exit();
                } else {
                    $session->msg('d', 'Senha não foi atualizada.');
                }
            } else {
                $session->msg('d', 'A nova senha não pode estar vazia.');
            }
        } else {
            $session->msg('d', 'As senhas não coincidem.');
        }
        
        header("Location: new_password.php");
        exit();
    }
?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Mude a sua senha</h3>
    </div>
    <?php echo display_msg($msg)?>
    <form method="post" action="new_password.php" class="clearfix">
        <div class="form-group">
            <label for="newPassword" class="control-label">Nova Senha</label>
            <input type="password" class="form-control" name="new-password" placeholder="Nova senha" required>
        </div>
        <div class="form-group">
            <label for="confirmPassword" class="control-label">Confirmar Senha</label>
            <input type="password" class="form-control" name="confirm-password" placeholder="Confirmar senha" required>
        </div>
        <div class="form-group clearfix">
            <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
            <button type="submit" name="update" class="btn btn-info">Alterar</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>

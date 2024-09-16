<?php 
    require_once('load.php');
    page_require_level(1);
?>

<?php 
    function delete($id){
        global $db;
        if($id != 1 && $id!= $_SESSION['user_id']){
            $sql = "DELETE FROM user where id = $id";
            $db->query($sql);
            return ($db->affected_rows() === 1);
        } else {
            return null;
        }
    }
    $delete_id = delete($_GET['id']);
    if($delete_id){
        $session->msg("s", "Usuário deletado com sucesso");
        header("Location: users.php");
    } else{
        $session->msg("d", "Não é possível deletar este usuário");
        header("Location: users.php");
    }
?>
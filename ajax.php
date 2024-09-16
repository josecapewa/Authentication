<?php
require_once('load.php');

$html = '';
global $users;
if (isset($_POST['title'])) {
    $search_result = $_POST['title'];
    if($search_result == ''){
        $users = find_all_user();
    } else{
        $users = search($search_result);
    }
    
    if ($users) {
        foreach ($users as $user) {
            $html .= "<tr>";
            $html .= "<td class=\"text-center\">{$user['id']}</td>";
            $html .= "<td>{$user['identification']}</td>";
            $html .= "<td><img src=\"./uploads/{$user['image']}\" width=\"50px\" height=\"50px\"></td>";
            $html .= "<td>{$user['name']}</td>";
            $html .= "<td>{$user['email']}</td>";
            $html .= "<td>{$user['recuperation_email']}</td>";
            $html .= "<td>{$user['password']}</td>";
            $html .= "<td class=\"text-center\">{$user['level_name']}</td>";
            $html .= "<td class=\"text-center\">";
            $html .= $user['auth'] == 0 ? "Desativada" : "Activada";
            $html .= "</td>";
            $html .= "<td class=\"text-center\">";
            $html .= "<div class=\"btn-group\">";
            $html .= "<a href=\"edit_user.php?id={$user['id']}\" class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" title=\"Editar\">";
            $html .= "<i class=\"glyphicon glyphicon-pencil\"></i></a>";
            $html .= "<a href=\"delete_user.php?id={$user['id']}\" class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Remover\">";
            $html .= "<i class=\"glyphicon glyphicon-remove\"></i></a>";
            $html .= "</div></td></tr>";
        } 
    } else {
        $html .= '<tr><td colspan="9" class="text-center">Usuário não encontrado</td></tr>';
    }

    echo json_encode($html);
}
?>

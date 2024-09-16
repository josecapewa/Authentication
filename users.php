<?php
  require_once('load.php');
  page_require_level(1);
?>
<?php

$sql = "SELECT u.id,u.image,u.name,u.email,u.recuperation_email,u.password,u.user_level, u.auth,l.level_name FROM user u LEFT JOIN user_level l ON l.number=u.user_level ORDER BY u.name ASC";
        $result = $db->query($sql);
        $set_result = $db->while_loop($result);
    
    $all_users = $set_result;

    if(isset($_POST['search-button'])){
      $search = isset($_POST['search']) ? $_POST['search'] : '';
      if(!empty($search)){
        $all_users = search($search);
      }
    }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <?php echo display_msg($msg);?>
    <div class="col-md-6">
        <form method="post" action="ajax.php" autocomplete="off" id="sug-form" onsubmit="return false;">
            <div class="form-group">
                <label for="title">Pesquisar</label>
                <input type="text" id="sug_input" class="form-control" name="title" placeholder="Pesquisar...">
            </div>
        </form>
    </div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Usuários</span>
                </strong>
                <div class="btn-group pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            Exportar <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="download_model.php">Exportar modelo</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" id="export-link">Exportar dados</a>
                            </li>
                        </ul>
                    </div>
                    <a href="add_user.php" class="btn btn-default btn-info" data-toggle="tooltip" title="Adicionar">
                        Adicionar usuário
                    </a>

                    <script>
                    document.getElementById('sug_input').addEventListener('change', function() {
                        var searchQuery = encodeURIComponent(this.value);
                        document.getElementById('export-link').href = 'download_data.php?search=' + searchQuery;
                    });

                    document.addEventListener('DOMContentLoaded', function() {
                        var searchQuery = encodeURIComponent(document.getElementById('sug_input').value);
                        document.getElementById('export-link').href = 'download_data.php?search=' + searchQuery;
                    });
                    </script>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Identificação </th>
                            <th>Foto </th>
                            <th>Nome </th>
                            <th>Email</th>
                            <th>Email de Recuperação</th>
                            <th>Password</th>
                            <th class="text-center">Nível de Usuário</th>
                            <th class="text-center">Verificação de dois factores</th>
                            <th>BI (Frente) </th>
                            <th>BI (Verso) </th>
                            <th class="text-center" style="width: 100px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="result">
                        <?php foreach($all_users as $a_user): ?>
                        <tr>
                            <td class="text-center"><?php echo $a_user['id'];?></td>
                            <td class="text-center"><?php echo $a_user['identification'];?></td>
                            <td>
                                <img src="./uploads/<?php echo $a_user['image']?>" width="50px" height="50px">
                            </td>
                            <td><?php echo $a_user['name'];?></td>
                            <td><?php echo $a_user['email'];?></td>
                            <td><?php echo $a_user['recuperation_email'];?></td>
                            <td><?php echo $a_user['password'];?></td>
                            <td class="text-center"><?php echo $a_user['level_name']?></td>
                            <td class="text-center">
                                <?php 
                                    if($a_user['auth'] == 0)
                                        echo "Desativada";
                                    else
                                        echo "Activado";
                                ?>
                            </td>
                            <td>
                                <img src="./uploads/<?php echo $a_user['bi_front']?>" width="90px" height="60px">
                            </td>
                            <td>
                                <img src="./uploads/<?php echo $a_user['bi_back']?>" width="90px" height="60px">
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>"
                                        class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>"
                                        class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remover">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
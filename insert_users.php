<?php
// function sanitizeFilePath($filePath)
// {
//   // Substitui barras invertidas (\) por barras normais (/)
//   $filePath = str_replace("\\", "/", $filePath);

//   // Remove aspas duplas se houver
//   $filePath = trim($filePath, '"');

//   return $filePath;
// }
require_once("vendor/autoload.php");
require_once("load.php");


$nameColumn = "A";
$emailColumn = "B";
$passwordColumn = "C";
$recuperation_email = "D";
$user_level = "E";

$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_FILES['users'])) {
    $file = $_FILES['users'];
    $path = "/mnt/HC_Volume_101269490/socartao/oauth.capewa.socartao.com/";
    if ($file['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
      move_uploaded_file($file["tmp_name"], "insert_users.xlsx");

      $fileName = "insert_users.xlsx";

      $filePath = $path . $fileName;

      $reader = \Ark4ne\XlReader\Factory::createReader($filePath);

      $reader->load();

      $header = $reader->read()->current();

      $all_users = [];
      $rowCount = 0;

      foreach ($reader->read() as $row => $value) {
        $rowCount++;
        if ($row > 1) {
          $all_users[] = $value;

          $size = sizeof($value);
          if ($size == 5) {
            $profilePicture = "uploads/no_image.jpg";
            if (filter_var($value[$emailColumn], FILTER_VALIDATE_EMAIL) || filter_var($value[$recuperation_email], FILTER_VALIDATE_EMAIL)) {
              if ($value[$user_level] == "admin" || $value[$user_level] == "nivel 1" || $value[$user_level] == "nivel 2") {
                $userRole = $value[$user_level] == "admin" ? 1 : ($value[$user_level] == "level2" ? 2 : ($value[$user_level] == "level3" ? 3 : ""));


                $checkEmailQuery = "SELECT id FROM user WHERE email = '$value[$emailColumn]'";
                $result = $db->query($checkEmailQuery);


                /* if ($db->num_rows($result)) {
                  $session->msg("d","Já existe um usuário com o mesmo email!" . $value);
                  header("Location: users.php");
                } else {
                    $new_password = $passwordColumn;
                    $insertQuery = "INSERT INTO user (name, email, recuperation_email, password, user_level) values('$value[$nameColumn]', '$value[$emailColumn]', '$value[$recuperation_email]', '$value[$passwordColumn]', '$userRole')";

                    if ($result = $db->query($insertQuery)) {
                        $session->msg("s","Usuários carregados com sucesso");
                  header("Location: users.php");
                    } else {
                      $session->msg("d","Erro ao criar usuário. Tente novamente.");
                    }
                  } 
                } */

              }
            }
          }
        }
      }
    }
    unlink($filePath);
    if ($rowCount == 0) {
      $session->msg("d", "O ficheiro Excel está vazio!");
      header("Location: users.php");
    }
  }
}
?>

<?php
include('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Usuários a serem cadastrados</span>
        </strong>
        <div class="btn-group pull-right">
          <form action="insert_users.php" method="POST" enctype="multipart/form-data" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <label for="profile_picture">Ficheiro excel (xlsx)</label>
                <div class="input-group">
                  <input type="file" class="form-control" id="users" name="users" accept=".xlsx, .xls">
                  <span class="input-group-btn">
                    <button type="submit" name="preview" class="btn btn-info">Carregar Ficheiro</button>
                  </span>
                </div>
          </form>
        </div>
      </div>
      </div>
      <div class="panel-body">
        <?php

        echo "<table class=\"table table-bordered table-striped\">
  <thead>
      <tr>
          <th>Nome </th>
          <th>Email</th>
          <th>Email de Recuperação</th>
          <th>Password</th>
          <th class=\"text-center\">Nível de Usuário</th>
      </tr>
  </thead>
  <tbody id=\"result\">";
        foreach ($all_users as $a_user):
          echo "    <tr>
          <td> " . $a_user['A'] . "</td>
          <td> " . $a_user['B'] . " </td>
          <td> " . $a_user['C'] . "</td>
          <td> " . $a_user['D'] . "</td>
          <td class=\"text-center\"> " . $a_user['E'] . "</td>
          
      </tr>";
        endforeach;
        echo " </tbody>
</table>"; ?>

      </div>
      </div>
        <?php include('layouts/footer.php'); ?>
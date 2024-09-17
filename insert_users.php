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
    $path = "/mnt/HC_Volume_101269490/socartao/oauth.capewa.socartao.com/files/";
    if ($file['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
      move_uploaded_file($file["tmp_name"], "/files/insert_users.xlsx");

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
      echo "<table class=\"table table-bordered table-striped\">
                  <thead>
                      <tr>
                          <th class=\"text-center\" style=\"width: 50px;\">#</th>
                          <th>Identificação </th>
                          <th>Foto </th>
                          <th>Nome </th>
                          <th>Email</th>
                          <th>Email de Recuperação</th>
                          <th>Password</th>
                          <th class=\"text-center\">Nível de Usuário</th>
                          <th class=\"text-center\">Verificação de dois factores</th>
                          <th>BI (Frente) </th>
                          <th>BI (Verso) </th>
                          <th class=\"text-center\" style=\"width: 100px;\">Ações</th>
                      </tr>
                  </thead>
                  <tbody id=\"result\">";
    foreach ($all_users as $a_user):
      echo "    <tr>
                          <td class=\"text-center\"> " . $a_user['id'] . "</td>
                          <td> " . $a_user['name'] . "</td>
                          <td> " . $a_user['email'] . " </td>
                          <td> " . $a_user['recuperation_email'] . "</td>
                          <td> " . $a_user['password'] . "</td>
                          <td class=\"text-center\"> " . $a_user['level_name'] . "</td>
                          
                      </tr>";
    endforeach;
    echo " </tbody>
              </table>";

}
unlink($filePath);
if ($rowCount == 0) {
  $session->msg("d", "O ficheiro Excel está vazio!");
  header("Location: users.php");
}
  }}
header("Location: users.php");

?>

<?php
include('layouts/header.php');
?>

<main class="main-content">
  <section class="col-md-4">
    <h2>Informações dos Usuários</h2>
    <form action="insert_users.php" method="POST" enctype="multipart/form-data" class="clearfix">
      <p style="margin: 10px 0px;"><strong>Atenção: </strong>Se algum usuário não tiver todos os campos preenchidos, não
        será cadastrado!</p>
      <div class="form-group">
        <div class="input-group">
          <label for="profile_picture">Ficheiro excel (xlsx ou csv)</label>
          <div class="input-group">
            <input type="file" class="form-control" id="users" name="users" accept=".xlsx, .xls">
            <span class="input-group-btn">
              <button type="submit" name="preview" class="btn btn-info">Carregar Ficheiro</button>
            </span>
          </div>
    </form>
  </section>
</main>
<?php include('layouts/footer.php'); ?>
<?php
  $page_title = 'Home Page';
  require_once('load.php');
  if (!$session->isUserLoggedIn(true)) { header('Location: index.php');}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
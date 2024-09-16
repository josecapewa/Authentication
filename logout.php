<?php
  require_once('load.php');
  if(!$session->logout()) { header("Location: index.php");}
?>

<?php
    $htmlcontent = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f2f2f2;">
  <table style="width: 600px; margin: 0 auto; background-color: #fff; border-radius: 5px;">
    <tr>
      <td style="padding: 20px;">
        <h1 style="color: #333; text-align: center;">Recuperação de Senha</h1>
        <p style="color: #666; text-align: center;">Digite o código abaixo para redefinir sua senha:</p>
        <div style="background-color: #e8e8e8; padding: 20px; text-align: center;">
          <h2 style="color: #333;">{{code}}</h2>
        </div>
        <p style="color: #666; text-align: center;">Este link expirará em 10 minutos.</p>
      </td>
    </tr>
  </table>
</body>
</html>';
?>
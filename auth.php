<?php
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Auth
{
    public $mail;

    public function __construct($email, $htmlcontent, $type = '')
    {
        $this->mail = new PHPMailer(true);

        try {
            $this->mail->isSMTP();
            $this->mail->Host = 'cloud5.angoweb.biz';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'no-reply.capewa@socartao.com';
            $this->mail->Password = '^4vDS)5NBX20';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => false
                )
            );
            $this->mail->setFrom('no-reply.capewa@socartao.com', 'Equipa OAuth');
            $this->mail->addAddress($email);

            $code = mt_rand(100000, 999999);
            $_SESSION['code'] = $code;
            
            
            if($type == 'auth'){
                $_SESSION['expiration_time'] = time() + 5 * 60;
            } else if($type == 'recover'){
                $_SESSION['expiration_time'] = time() + 10 * 60;
            }

            $text = str_replace('{{code}}', $code, $htmlcontent);

            $this->mail->isHTML(true);
            $this->mail->Charset = 'UTF-8';
            $this->mail->Subject = "Código de autenticação";
            $this->mail->Body = $text;

        } catch (Exception $ex) {
            echo "Erro ao enviar o e-mail. Mensagem de erro: {$this->mail->ErrorInfo}";
        }
    }
}
?>

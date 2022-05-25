<?php
function isValidEmail($email)
{
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return true;
  }

  return false;
}

// Getting data from form
if (isset($_POST["submit"])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $fullName = $firstName . ' ' . $lastName;
  $email = $_POST['email'];
  $message = $_POST['message'];

  if (isValidEmail($email) === false) {
    header("location: ../index.php?error=invalidemail");
  }
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Dotenv\Dotenv;

//Load Composer's autoloader
require '../vendor/autoload.php';

// PHPMailer instance
$mail = new PHPMailer(true);
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');
$password = $_SERVER['EMAIL_PASSWORD'] ?? '';

try {
  //Server settings
  $mail->isSMTP(); //Send using SMTP
  $mail->Host       = 'smtp.gmail.com'; // SMTP Server
  $mail->SMTPAuth   = true; //Enable SMTP authentication
  $mail->Username   = 'info.saintsforus@gmail.com'; // SMTP username
  $mail->Password   = $password; //SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
  $mail->Port       = 465; //TCP port to connect to

  //Recipients
  $mail->setFrom('aidantomcy@gmail.com', 'Tresorita Website');
  $mail->addAddress('aidantomcy@gmail.com', 'Tresorita Website');
  $mail->addAddress('aidantomcy@gmail.com');
  $mail->addReplyTo('aidantomcy@gmail.com', 'Tresorita Website');

  //Content
  $mail->isHTML(true); //Set email format to HTML
  $mail->Subject = 'New Form Submission in Tresorita Website'; // Mail Subject

  // Mail Body
  $mail->Body    = '<h1>New Form Submission</h1>';
  $mail->Body   .= '<p>There is a new form submission in the website, here are the details:';
  $mail->Body   .= ' <br>Name: ' . $fullName . '<br>Email: ' . $email;
  $mail->Body   .= '<br>Message: ' . $message . '</p>';

  // Alternate Mail Body for non-HTML mail clients
  $mail->AltBody  = 'New Form Submission\n\nThere is a new form submission in the website,';
  $mail->AltBody .= 'here are the details:\nName: ' . $fullName . '\nEmail: ' . $email;
  $mail->AltBody .= '\nMessage: ' . $message;

  $mail->send();
  echo "Message Sent Successfully! Redirecting...";
  echo '<script>location.replace("../index.php?error=none");</script>';
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

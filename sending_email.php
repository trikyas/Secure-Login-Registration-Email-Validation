<?php
    include('vendor/autoload.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.gmail.com";
    $mail->Port = "465";
    $mail->SMTPSecure = "ssl";

    $mail->Username = "trikyas@gmail.com";
    $mail->Password = "5371";

    $mail->setFrom("trikyas@gmail.com");
    $mail->addReplyTo("no-reply@chad.com");

    // recipient
    $mail->addAddress("trikyas@icloud.com");
    $mail->isHTML();
    $mail->Subject = "Sending from localhost";
    $mail->Body = "<div style='color: blue;font-size: 20px;background-color:grey;'>Thank you buddy!!!</div>";

    if($mail->send()) {
        echo "Email sent";
    } else {
        echo "Sorry somethings wrong";
    }
?>

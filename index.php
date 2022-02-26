<?php

//require_once 'vendor/autoload.php';
// https://github.com/symfony/mailer
//use Symfony\Component\Mailer\Transport;
//use Symfony\Component\Mailer\Mailer;
//use Symfony\Component\Mime\Email;
//
//try {
//    $transport = Transport::fromDsn('mailjet+smtp://' . urlencode('API_KEY') . ':' . urlencode('SECRET_KEY') . '@default');
//    $mailer = new Mailer($transport);
//
//    $email = (new Email())
//        ->from('SENDER_NAME <SENDER_EMAIL_ADDRESS>')
//        ->to('RECIPIENT_EMAIL_ADDRESS')
//        ->subject('Email Subject')
//        ->html('<b>Email Body</b>');
//
//    $mailer->send($email);
//
//    echo "Email sent successfully.";
//} catch (Exception $e) {
//    echo $e->getMessage();
//}
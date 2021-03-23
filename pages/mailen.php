<?php
  use PHPMailer\PHPMailer\PHPMailer;
  require 'PHPMailer-master/src/PHPMailer.php';
  require 'PHPMailer-master/src/SMTP.php';
  require 'PHPMailer-master/src/Exception.php';
// deze function stuurt e-mails via Gmail.
function mailen($ontvangerAdres, $ontvangerNaam, $onderwerp, $bericht){

    $mail = new PHPMailer();
    
    // verbinden met Gmail
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = "ssl"; 
    $mail->Host = "smtp.gmail.com"; 
    $mail->Port = 465; 
    
    // identificeer jezelf bij Gmail
    $mail->Username = "Receptie2020Vives@gmail.com";
    $mail->Password = "receptie2020.";

    //  mail opstellen    
    $mail->isHTML(true);
    $mail->SetFrom("Receptie2020Vives@gmail.com", "Growth.Inc");
    $mail->Subject = $onderwerp;
    $mail->CharSet = 'UTF-8';
    $bericht = "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:14px; color:#000;\">". $bericht . "</body></html>";
    $mail->AddAddress($ontvangerAdres, $ontvangerNaam);
    $mail->Body = $bericht;

    // stuur mail
    if($mail->Send()){
        echo "<script>alert('Mail is verstuurd' );</script>";
    }else{
        echo "<script>alert('Kon geen mail versturen');</script>";
    }
}

?>


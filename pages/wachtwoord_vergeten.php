<!DOCTYPE html> 
<html lang="nl"> 
<head>
  <title>wachtwoord vergeten</title>
</head>
<body> 
  <div class="content">
    <form name="wachtwoord vergeten" method="POST"  action="">
      <p id="page_titel">Nieuwe wachtwoord aanvragen</p>
      <input type="email" required name="email" placeholder="email" >
      <div class="icon_container">
        <input type="submit" class="icon" id="submit"  name="submit" value="Stuur mail" />
      </div>
      <input type="submit" onclick="location.href='index.php'" value="Inloggen">
    </form>
  </div> 

  <?php
  if(isset($_POST["submit"])) {

    $melding = "";
    $email = htmlspecialchars($_POST['email']);

    // deze function genereert een token 64 tekens lang.
    $token = bin2hex(random_bytes(32));
    $timestamp = new DateTime("now");
    $timestamp = $timestamp->getTimestamp();

    // hier wordt het path naar wachtwoord_wijzigen.php gegenereerd
    $url = sprintf("%s://%s",isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 
            'https' :
            'http',$_SERVER['HTTP_HOST']. 
            dirname($_SERVER['PHP_SELF'])."/wachtwoord_resetten.php" );
    $url = $url."?token=".$token."&timestamp=".$timestamp;
    // stuur url naar het email adres van de klant
    $onderwerp = "Wachtwoord resetten";
    $bericht = "<p>Als je je wachtwoord wilt resetten klik <a href=".$url.">hier</a></p>";
    try{
      mailen($email, $userid, $onderwerp, $bericht);
      $melding = 'Open je mail om verder te gaan.';
    } catch(Exception $e){
      $melding = 'Kon geen mail sturen ';
    }      
    echo "<div id='melding'>".$melding."</div>"; 
  }
  ?>


<?php

// Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();
    if(isset($_SESSION["membre"]))
        header("Location: admin/compte.php");

    // Load Composer's autoloader
    require 'phpmailer/vendor/autoload.php';
    require "admin/database.php";
    require "admin/check.php";

    $erreur = $resetPassword = $resetErreur = "";
    
    if(!empty($_POST['submit']) && $_POST['submit'] == 'login'){

        $identifiant = checkInput($_POST["identifiant"]);
        $success = true;

        if(empty($identifiant) || empty($_POST["password"])){
            $erreur = "Les identifiants sont erronés";
            $success = false;
        };
        
        if($success){

            $db = Database::connect();
            // On recupere Prenom, role et mot de passe qui vont nous servir pour creer des sessions 
            // We get firstname, role and password to connect and also to create the sessions
            $statement = $db->prepare("SELECT Nom,Prenom,Role,Password FROM listeemploye WHERE Identifiant = ?");
            $statement->execute(array($identifiant)); 
            $item = $statement->fetch();

            if(password_verify($_POST["password"],$item["Password"])) {
                $_SESSION["membre"] = $identifiant;
                $_SESSION["role"] = $item["Role"];
                $_SESSION["nom"] = $item["Nom"];
                $_SESSION["prenom"] = $item["Prenom"];
                header("Location: admin/compte.php");
            } else{
                $erreur = "Les identifiants sont erronés";
            };
            Database::disconnect();
        };
    } else if(!empty($_POST['submit']) && $_POST['submit'] == 'reset'){
        $identifiant = checkInput($_POST["identifiant"]);
        $email = checkInput($_POST["email"]);
        $success = true;

        $db = Database::connect();
        // On recupere l'email qui a pour identifiant celui rentre dans l input
        
        $statement = $db->prepare("SELECT Email FROM listeemploye WHERE Identifiant = ?");
        $statement->execute(array($identifiant));
        $item = $statement->fetch();

        // si il retourne une ligne c;est que l identifiant est bon
        if($statement->rowCount() > 0){
            // Alors on verifie l'email de l'input avec celui recupere dans la base de donnee.
            if($item['Email'] == $email){
                $passgen = passgen(8); // Appel de la fonction pour generer un MDP avec 8 Char
                $hash = password_hash($passgen, PASSWORD_DEFAULT); // On hash le MDP
                // On update le MDP
                $sqlUpdate = $db->prepare("UPDATE listeemploye SET Password=? WHERE Identifiant=?");
                $sqlUpdate->execute(array($hash,$identifiant));
            
                // On envoie un mail a l utilisateur pour qu il recoive ses nouveaux identifiants
                $mail = new PHPMailer(true);
                try {
                    // On recupere le mail et PWD de l'admin pour envoyer un mail 
                    $sqlContact = $db->query("SELECT * FROM mailcontact");
                    $sqlContact ->execute(array());
                    $contact= $sqlContact->fetch();

                    //Server settings
                    $mail->SMTPDebug = 0;                      // Enable verbose debug output
                    $mail->isSMTP(true);                       // Send using SMTP
                    $mail->CharSet = "UTF-8";                             
                    $mail->Host       = 'smtp.gmail.com';              // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                         // Enable SMTP authentication
                    $mail->Username   = $contact['emailcontact'];       // SMTP username
                    $mail->Password   = $contact['pwd'];               // SMTP password
                    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;           // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom($contact['emailcontact'], 'CapCoree');
                    $mail->addAddress($email);
                    
                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Identifiant Compte CapCoree';
                    $mail->Body    = "Bonjour, <br>Vous venez de faire une demande de nouveaux identifiants, vous les trouverez ci-dessous :<br><br> 
                                       Identifiant : " . $identifiant ."<br>
                                       Mot De Passe : " . $passgen . "<br><br> 
                                       Pour des raisons évidentes de sécurité il vous est recommendé de modifier ceux-ci au plus vite dans votre espace membre.<br><br> 
                                       A bientot sur <a href=https://www.capcoree.fr>CapCorée</a>";
                    
                    $mail->send();
                    $resetPassword = 'Mot De Passe réinitialisé, veuillez vérifier vos emails';
                } catch (Exception $e) {
                    $resetErreur = 'Erreur, veuillez verifier vos informations';
                };
            }else{
                $resetErreur = 'Erreur, veuillez verifier vos informations';
            };
        };
    };
    
    // fonctions pour generer un MDP et le melanger avec un parametre pour un nombre de char voulu 

    function passgen($nbChar){
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCEFGHIJKLMNOPQRSTUVWXYZ0123456789'),1, $nbChar); 
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Login Page</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="images/cap.ico"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" >
  </head>
  <body class="bgLogin">

    <?php 
        include "admin/navigation.php";
    ?>

    <div class="container">
        <div class="row" >
            <section class="col-12 " >
                <form method="POST" action="" class="text-center w-auto">
                    <br><span style="font-family: Helvetica; font-weight: bolder; font-size:xx-large; color:#0be881;"><?php echo $resetPassword; ?></span><br>
                    <span style="font-family: Helvetica; font-weight: bolder; font-size:xx-large; color:#ff3f34;"><?php echo $resetErreur; ?></span><br>
                    <h1 style="color:#fff;" >Veuillez saisir vos identifiants</h1>
                    <div>
                    <span style="font-weight: bold; font-size:xx-large; color:red;"><?php echo $erreur; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="text" name="identifiant" placeholder="Identifiant *" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Mot de passe *" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <button id="connect" type="submit" name="submit" value="login" class="form-control btn btn-lg btn-dark">ME CONNECTER</button>
                    </div>
                    
                </form>
                <button id="button_modal" class="btn btn-lg btn-dark" onclick="openModal()">J'ai oublié mon mot de passe</button>
            </section>
        </div>

        <div id="modal"> <!-- Creation du modal pour reinitialiser le MDP -->
        <button id="close" class="btn btn-sm btn-dark " onclick="closeModal()">X</button>
            <h2>Mot De Passe Oublié ?</h2>
            <h6>Veuillez saisir les champs pour le réinitialiser</h6>
            
            <form method="POST" class="text-center w-auto">
                <div div class="form-group">
                    <input required type="text" name="identifiant" placeholder="Identifiant *" value="" class="form-control-sm">
                </div>
                <div class="form-group">
                    <input required type="email" name="email" value="" placeholder="E-mail *" class="form-control-sm">
                </div>
                <div class="form-group">
                    <button id="resetButton" style="display:inline" type="submit" name="submit" value="reset" class="form-control-sm btn btn-sm btn-dark">Réinitialiser</button>
                    
                </div>
                <div id="waiting" style="display:none; font-weight:bold">
                    <span >veuillez patienter...</span>
                </div>
            </form>
 
        </div>
    </div>
      
    <!-- Optional JavaScript -->
    <script src="javascript/modalLogin.js" type="text/javascript"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
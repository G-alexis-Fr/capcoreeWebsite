<?php   
    
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require '../phpmailer/vendor/autoload.php';
    require "database.php";
    require "check.php";

    session_start();
     
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] != "Admin")
        header("Location: ../login.php");

    $role = $name = $surname = $sexe = $identifiant = $email = $telephone = $kakaotalk = $adresse = $aliencard = $banque = $numerocompte = $roleError = $nameError = $surnameError = $sexeError = $emailError = $telephoneError = $kakaoError = $adresseError = $aliencardError = $banqueError = $numeroCompteError = $imageError = "";

    if(!empty($_POST)){
        $role = checkInput($_POST['role']) ;
        $name = checkInput($_POST['name']);
        $surname = checkInput($_POST['surname']);
        $sexe = checkInput($_POST['sexe']);
        // l'identifiant sera le nom et surnom de la personne
        // the Id user would be name and surname 
        $identifiant = $_POST['name'] . "." . $_POST['surname'];
        $email = checkInput($_POST['email']);
        $telephone = checkInput($_POST['telephone']);
        $kakaotalk = checkInput($_POST['kakaotalk']);
        $adresse = checkInput($_POST['adresse']);
        $aliencard = checkInput($_POST['aliencard']);
        $banque = checkInput($_POST['banque']);
        $numerocompte = checkInput($_POST['numerocompte']);
        $image = checkInput($_FILES["image"]["name"]);
        $imagePath = '../images/employes/' . basename($image);
        $imageExtension = pathinfo($imagePath,PATHINFO_EXTENSION);

        $isSuccess = true;
        $isUploadSuccess = false;
      

        if(empty($role)){
            $roleError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($name)){
            $nameError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($surname)){
            $surnameError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($email)){
            $emailError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($image)){ // Si l'utilisateur ne renseigne pas de photo, alors en fonction de son Sexe on lui attribue une Image par Default Genree 
           if($sexe == "Femme"){$image = "female.png";}
           else{$image = "male.png";};
           $isUploadSuccess = true;
        }
        else // Sinon on verifie les extensions, la taille ainsi que le nom du fichier
        {
            $isUploadSuccess = true; // Permet de verifier si on a bien envoye le fichier sur le serveur
            $newImagePath = ""; // Permet de savoir si on a un fichier avec le meme nom et donc pour redefinir le chemin

            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" ) {
                $imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
                $isUploadSuccess = false;
            };
            if($_FILES["image"]["size"] > 500000) {
                $imageError = "Le fichier ne doit pas depasser les 500KB";
                $isUploadSuccess = false;
            };

            if($isUploadSuccess && file_exists($imagePath)) {
                $image = date("dmYHis") . basename($image); // On rajoute la date et l'heure au nom de l'image pour etre sur que celle-ci n'existe pas
                $newImagePath = '../images/employes/' . $image; // On cree le nouveau chemin de sauvegarde
            };

            if($newImagePath != "") {  // Si different de vide alors on utilise le nouveau chemin
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $newImagePath)){
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                };
            }
            else{
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)){ // Sinon on utilise l'ancien.
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                };
            };
 
        };

        // Si tout ce passe bien alors on INSERT
        if($isSuccess && $isUploadSuccess){
            $db = Database::connect();
            $hash = password_hash("12345",PASSWORD_DEFAULT); // creation d un mot de passe pour l utilisateur avec par default avec la securite la plus performante du moment

            try{
                $statement = $db->prepare("INSERT INTO listeemploye (Role,Nom,Prenom,Sexe,Identifiant,Password,Email,Telephone,KakaoTalk,Adresse,AlienCard,Banque,NumeroCompte,Image) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $statement->execute(array($role,$name,$surname,$sexe,$identifiant,$hash,$email,$telephone,$kakaotalk,$adresse,$aliencard,$banque,$numerocompte,$image));

                // Envoie d un mail si on reussi l'ajout de l employe
                // Instantiation and passing `true` enables exceptions
                $mail = new PHPMailer(true);
                try {
                    
                    $sqlContact = $db->query("SELECT * FROM mailcontact");
                    $sqlContact ->execute(array());
                    $item= $sqlContact->fetch();


                    //Server settings
                    $mail->SMTPDebug = 2;                      // Enable verbose debug output
                    $mail->isSMTP(true);                       // Send using SMTP
                    $mail->CharSet = "UTF-8";                             
                    $mail->Host       = 'smtp.gmail.com';              // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                         // Enable SMTP authentication
                    $mail->Username   = $item['emailcontact'];       // SMTP username
                    $mail->Password   = $item['pwd'];               // SMTP password
                    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;           // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom($item['emailcontact'], 'CapCoree');
                    $mail->addAddress($email);
                    
                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Identifiant Compte CapCoree';
                    $mail->Body    = "Bonjour, <br>Nous sommes heureux de vous compter parmi l'équipe CapCorée. Pour nous rejoindre sur le panneau d'administration voici donc vos identifiants :<br><br> 
                                       Identifiant :" . $identifiant ."<br>Mot De Passe : 12345 <br> <br> 
                                       Pour des raisons évidentes de sécurité il vous est recommendé de modifier celui-ci au plus vite dans votre espace membre.<br><br> 
                                       A bientot sur <a href=https://www.capcoree.fr>CapCorée</a>";
                    

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                header("Location: employes.php"); // Apres l'envoie on redirigé a employé
            }
            catch(Exception $e){ 
            }
            
            Database::disconnect();            
        };
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Nouvel Employé</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/cap.ico"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet" >
  </head>
  <body>

    <?php
        include "navigation.php";
    ?>

    <div class="container-fluid">
        <div class="row">
            <section class="col-12 text-center">
                <br>
                <h1>Ajouter Un Employé</h1>
                <br><br>
            </section>

            <section class="col-6">

                <form action="employesinsert.php" method="POST" enctype="multipart/form-data" class="text-center w-auto">
                    <div class="form-group">
                        <label class="font-weight-bold" for="role">Role : </label>
                        <select id="search" name="role" id="role" >
                            <option value="Employe">Employé</option>;
                            <option value="Finance">Finance</option>;
                            <option value="Guide">Guide</option>;
                            <option value="Stagiaire">Stagiaire</option>;
                            <option value="Admin">Admin</option>;
                        </select><br>
                        <span class="erreur"><?php echo $roleError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="name">Nom : </label> 
                        <input id="search" type="text"  id="name" name="name" placeholder="Nom *" value="<?php echo $name ?>"><br>
                        <span class="erreur"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="surname">Prenom : </label> 
                        <input id="search" type="text"  id="surname" name="surname" placeholder="Prenom *" value="<?php echo $surname ?>"><br>
                        <span class="erreur"><?php echo $surnameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="sexe">Sexe : </label>
                        <select id="search" name="sexe" id="sexe">
                            <option value="Femme">Femme</option>;
                            <option value="Homme">Homme</option>;
                        </select><br>
                        <span class="erreur"><?php echo $sexeError; ?></span>
                    </div>                
                    <div class="form-group">
                        <label class="font-weight-bold" for="email">Email : </label> 
                        <input id="search" type="email"  id="email" name="email" placeholder="Email *" value="<?php echo $email ?>"><br>
                        <span class="erreur"><?php echo $emailError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="telephone">Telephone : </label> 
                        <input id="search" type="text"  id="telephone" name="telephone" placeholder="Telephone" value="<?php echo $telephone ?>"><br>
                        <span class="erreur"><?php echo $telephoneError; ?></span>
                    </div>
                </section>
                <section class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="kakaotalk">KakaoTalk : </label> 
                        <input id="search" type="text"  id="kakaotalk" name="kakaotalk" placeholder="KakaoTalk" value="<?php echo $kakaotalk ?>"><br>
                        <span class="erreur"><?php echo $kakaoError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="adress">Adresse : </label> 
                        <input id="search" type="text"  id="adresse" name="adresse" placeholder="Adresse" value="<?php echo $adresse ?>"><br>
                        <span class="erreur"><?php echo $adresseError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="aliencard">AlienCard : </label> 
                        <input id="search" type="text" id="aliencard" name="aliencard" placeholder="AlienCard" value="<?php echo $aliencard ?>"><br>
                        <span class="erreur"><?php echo $aliencardError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="banque">Banque : </label> 
                        <input id="search" type="text"  id="banque" name="banque" placeholder="Banque" value="<?php echo $banque ?>"><br>
                        <span class="erreur"><?php echo $banqueError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="numerocompte">Numero De Compte : </label> 
                        <input id="search" type="text"  id="numerocompte" name="numerocompte" placeholder="Numero De Compte" value="<?php echo $numerocompte ?>"><br>
                        <span class="erreur"><?php echo $numeroCompteError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="image">Sélectionner une image:</label>
                        <input id="search" type="file" id="image" name="image"><br>
                        <span class="erreur"><?php echo $imageError;?></span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-lg" type="submit">AJOUTER</button>
                        <a class="btn btn-primary btn-lg" href="employes.php"> RETOUR</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
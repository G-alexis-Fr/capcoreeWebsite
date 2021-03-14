<?php   
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] != "Admin")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    $role = $name = $surname = $sexe = $identifiant = $email = $telephone = $kakaotalk = $adresse = $alienCard = $banque = $numerocompte = $image = $roleError = $nameError = $surnameError = $sexeError = $identifiantError = $emailError = $telephoneError = $kakaoError = $adresseError = $aliencardError = $banqueError = $numeroCompteError = $imageError = "";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']);

    if(!empty($_POST)){
        $role = checkInput($_POST['role']);
        $name = checkInput($_POST['name']);
        $surname = checkInput($_POST['surname']);
        $sexe = checkInput($_POST['sexe']);
        $identifiant = checkInput($_POST['identifiant']);
        $email = checkInput($_POST['email']);
        $telephone = checkInput($_POST['telephone']);
        $kakaotalk = checkInput($_POST['kakaotalk']);
        $adresse = checkInput($_POST['adresse']);
        $aliencard = checkInput($_POST['aliencard']);
        $banque = checkInput($_POST['banque']);
        $numerocompte = checkInput($_POST['numerocompte']);
        $image = checkInput($_FILES['image']['name']);
        $imagePath = '../images/employes/'. basename($image);
        $imageExtension = pathinfo($imagePath,PATHINFO_EXTENSION);
        
        $isSuccess = true; // Si aucunes erreurs pour les inputs de bases

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
        if(empty($identifiant)){
            $identifiantError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($email)){
            $emailError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };


        if(empty($image)){ // Si l'utilisateur ne renseigne pas de photo, alors en fonction de son Sexe on lui attribue une Image par Default Genree 
            $isImageUpdated = false;
        } else {// Sinon on verifie les extensions, la taille ainsi que le nom du fichier

            $isUploadSuccess = true; // Permet de verifier si on a bien envoye le fichier sur le serveur
            $newImagePath = ""; // Permet de savoir si on a un fichier avec le meme nom et donc pour redefinir le chemin
            $isImageUpdated = true;
 
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

        if(($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated )){
            $db = Database::connect();
            
            if($isImageUpdated){
                // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
                $statement = $db->prepare("UPDATE listeemploye SET Role=?,Nom=?,Prenom=?,Sexe=?,Identifiant=?,Email=?,Telephone=?,KakaoTalk=?,Adresse=?,AlienCard=?,Banque=?,NumeroCompte=?,Image=? WHERE ID=?");
                $statement->execute(array($role,$name,$surname,$sexe,$identifiant,$email,$telephone,$kakaotalk,$adresse,$aliencard,$banque,$numerocompte,$image,$id));
            } else {
                $statement = $db->prepare("UPDATE listeemploye SET Role=?,Nom=?,Prenom=?,Sexe=?,Identifiant=?,Email=?,Telephone=?,KakaoTalk=?,Adresse=?,AlienCard=?,Banque=?,NumeroCompte=? WHERE ID=?");
                $statement->execute(array($role,$name,$surname,$sexe,$identifiant,$email,$telephone,$kakaotalk,$adresse,$aliencard,$banque,$numerocompte,$id));
            };
            Database::disconnect();
            header("Location: employes.php"); // Apres l'envoie on redirige a employe

        } else if ($isImageUpdated && !$isUploadSuccess){ // Si Il y a update mais erreur lors du transfert, on remet l'ancienne image
            $db = Database::connect();
            $statement = $db->prepare("SELECT Image FROM listeemploye WHERE ID = ?");
            $statement->execute(array($id));
            $item = $statement->fetch();

            $image = $item["Image"];
            Database::disconnect();
        };

    } else {

        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM listeemploye WHERE ID = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        $role = $item["Role"];
        $name = $item["Nom"];
        $surname = $item["Prenom"];
        $sexe = $item["Sexe"];
        $identifiant = $item["Identifiant"];
        $email = $item["Email"];
        $telephone = $item["Telephone"];
        $kakaotalk = $item["KakaoTalk"];
        $adresse = $item["Adresse"];
        $aliencard = $item["AlienCard"];
        $banque = $item["Banque"];
        $numerocompte = $item["NumeroCompte"];
        $image = $item["Image"];

        Database::disconnect();
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Maj Employé</title>
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

    <div class="container-fluid text-center">
    <h1>Modifier Employé</h1>
        <div class="row text-center">

            <section class="col-6">
                <form action="<?php echo 'employesupdate.php?id=' . $id ?>" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label class="font-weight-bold" for="role">Role : </label>
                <?php 
                    if($_SESSION["role"] == "Admin"){ // Seul les ADMINS peuvent modifier le role de n importe qui
                ?>
                        <select id="search" name="role" id="role" >
                            <option value="<?php echo $role ?>"><?php echo $role ?></option>;
                            <option value="Employe">Employé</option>;
                            <option value="Finance">Finance</option>;
                            <option value="Guide">Guide</option>;
                            <option value="Stagiaire">Stagiaire</option>;
                            <option value="Admin">Admin</option>;
                        </select><br>
                <?php
                    }else{ 
                ?>
                        <select id="search" name="role" id="role" >
                            <option value="<?php echo $role ?>"><?php echo $role ?></option>;
                        </select><br>
                <?php
                    };
                ?>
                        <span class="erreur"><?php echo $roleError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="name">Nom : </label> 
                        <input id="search" type="text"  id="name" name="name" placeholder="Nom *" value="<?php echo $name ?>"><br>
                        <span class="erreur"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="surname">Prenom : </label> 
                        <input id="search" type="text" id="surname" name="surname" placeholder="Prenom *" value="<?php echo $surname ?>"><br>
                        <span class="erreur"><?php echo $surnameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="sexe">Sexe : </label>
                        <select id="search" name="sexe" id="sexe">
                            <option value="<?php echo $sexe?>"><?php echo $sexe ?></option>;
                            <option value="Femme">Femme</option>;
                            <option value="Homme">Homme</option>;
                        </select><br>
                        <span class="erreur"><?php echo $sexeError; ?></span>
                    </div>
                <?php 
                    if($_SESSION["role"] == "Admin"){ // Seul les ADMINS peuvent modifier le role de n importe qui
                ?>
                    <div class="form-group">
                        <label class="font-weight-bold" for="identifiant">Identifiant : </label> 
                        <input id="search" type="text" id="identifiant" name="identifiant" placeholder="Identifiant *" value="<?php echo $identifiant ?>"><br>
                        <span class="erreur"><?php echo $identifiantError; ?></span>
                    </div>
                <?php
                    }else{ 
                ?>
                    <div class="form-group">
                        <label class="font-weight-bold" for="identifiant">Identifiant : </label> 
                        <input readonly id="search" type="text" id="identifiant" name="identifiant" placeholder="Identifiant *" value="<?php echo $identifiant ?>"><br>
                        <span class="erreur"><?php echo $identifiantError; ?></span>
                    </div>
                <?php
                    };
                ?>
                    <div class="form-group">
                        <label class="font-weight-bold" for="email">Email : </label> 
                        <input id="search" type="email" id="email" name="email" placeholder="Email *" value="<?php echo $email ?>"><br>
                        <span class="erreur"><?php echo $emailError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="telephone">Telephone : </label> 
                        <input id="search" type="text" id="telephone" name="telephone" placeholder="Telephone" value="<?php echo $telephone ?>"><br>
                        <span class="erreur"><?php echo $telephoneError; ?></span>
                    </div>
            </section>
            <section class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="kakaotalk">KakaoTalk : </label> 
                        <input id="search" type="text" id="kakaotalk" name="kakaotalk" placeholder="KakaoTalk" value="<?php echo $kakaotalk ?>"><br>
                        <span class="erreur"><?php echo $kakaoError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="adress">Adresse : </label> 
                        <input id="search" type="text" id="adresse" name="adresse" placeholder="Adresse" value="<?php echo $adresse ?>"><br>
                        <span class="erreur"><?php echo $adresseError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="aliencard">AlienCard : </label> 
                        <input id="search" type="text" id="aliencard" name="aliencard" placeholder="AlienCard" value="<?php echo $aliencard ?>"><br>
                        <span class="erreur"><?php echo $aliencardError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="banque">Banque : </label> 
                        <input id="search" type="text" id="banque" name="banque" placeholder="Banque" value="<?php echo $banque ?>"><br>
                        <span class="erreur"><?php echo $banqueError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="numerocompte">Numero De Compte : </label> 
                        <input id="search" type="text" id="numerocompte" name="numerocompte" placeholder="Numero De Compte" value="<?php echo $numerocompte ?>"><br>
                        <span class="erreur"><?php echo $numeroCompteError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Image:</label>
                        <p ><?php echo $image; ?></p>
                        <label class="font-weight-bold" for="image">Sélectionner une nouvelle image:</label>
                        <input type="file" id="image" name="image"><br> 
                        <span class="erreur help-inline"><?php echo $imageError;?></span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-lg" type="submit">MODIFIER</button>
                        <a class="btn btn-primary btn-lg" href="employes.php">RETOUR</a>
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
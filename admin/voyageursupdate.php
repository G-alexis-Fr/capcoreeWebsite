<?php   
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");
        
    require "database.php";
    require "check.php";

    $name = $surname = $sexe = $age = $email = $whatsapp = $telephone = $adresse = $commentaire = $passeport = $banque = $numerocompte =  $nameError = $surnameError = $sexeError = $ageError = $emailError = $whatsappError = $telephoneError = $adresseError = $commentaireError = $passeportError = $banqueError = $numeroCompteError = "";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']);

    if(!empty($_POST)){

        $name = checkInput($_POST['name']);
        $surname = checkInput($_POST['surname']);
        $sexe = checkInput($_POST['sexe']);
        $age = checkInput($_POST['age']);
        $email = checkInput($_POST['email']);
        $whatsapp = checkInput($_POST['whatsapp']);
        $telephone = checkInput($_POST['telephone']);
        $adresse = checkInput($_POST['adresse']);
        $commentaire = checkInput($_POST['commentaire']);
        $passeport = checkInput($_POST['passeport']);
        $banque = checkInput($_POST['banque']);
        $numerocompte = checkInput($_POST['numerocompte']);
        
        $isSuccess = true;
        $isUploadSuccess = false;

        if(empty($age)){
            $ageError = "Ce champs ne peut etre vide";
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
    
        if($isSuccess){
            $db = Database::connect();
            // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
            $statement = $db->prepare("UPDATE voyageurs SET Nom=?,Prenom=?,Sexe=?,Age=?,Email=?,WhatsApp=?,Telephone=?,Adresse=?,Commentaire=?,Passeport=?,Banque=?,NumeroCompte=? WHERE ID=?");
            $statement->execute(array($name,$surname,$sexe,$age,$email,$whatsapp,$telephone,$adresse,$commentaire,$passeport,$banque,$numerocompte,$id));
            Database::disconnect();
            header("Location: voyageurs.php"); // Apres l'envoie on redirige a employe
        };
    } else {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM voyageurs WHERE ID = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        $name = $item['Nom'];
        $surname = $item['Prenom'];
        $sexe = $item['Sexe'];
        $age = $item['Age'];
        $email = $item['Email'];
        $whatsapp = $item['WhatsApp'];
        $telephone = $item['Telephone'];
        $adresse = $item['Adresse'];
        $commentaire = $item['Commentaire'];
        $passeport = $item['Passeport'];
        $banque = $item['Banque'];
        $numerocompte = $item['NumeroCompte'];

        Database::disconnect();
    };
?>

<!doctype html>
<html lang="en">

<head>
    <title>Maj Voyageur</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/cap.ico"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet" >
</head>

<body>

    <?php 
        include "header.php";
    ?>

    <div class="container-fluid text-center">
        <h1>Modifier Voyageur</h1>
        <div class="row text-center">

            <section class="col-12">
                <form action="<?php echo 'voyageursupdate.php?id=' . $id ?>" method="POST">
                    <div class="form-group">
                        <label class="font-weight-bold" for="name">Nom : </label>
                        <input id="search" type="text" id="name" name="name" placeholder="Nom *"
                            value="<?php echo $name ?>"><br>
                        <span class="erreur"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="surname">Prenom : </label>
                        <input id="search" type="text" id="surname" name="surname" placeholder="Prenom *"
                            value="<?php echo $surname ?>"><br>
                        <span class="erreur"><?php echo $surnameError; ?></span>
                    </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="sexe">Sexe : </label>
                            <select id="search" name="sexe" id="sexe">
                                <option value="<?php echo $item["Sexe"]?>"><?php echo $item["Sexe"]?></option>
                                <option value="Femme">Femme</option>;
                                <option value="Homme">Homme</option>;
                            </select><br>
                            <span class="erreur"><?php echo $sexeError; ?></span>
                    </div>
                        
                    <div class="form-group">
                        <label class="font-weight-bold" for="age">Age : </label>
                        <input id="search" type="text" id="age" name="age" placeholder="Age *"
                            value="<?php echo $age ?>"><br>
                        <span class="erreur"><?php echo $ageError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="email">Email : </label>
                        <input id="search" type="text" id="email" name="email" placeholder="Email *"
                            value="<?php echo $email ?>"><br>
                        <span class="erreur"><?php echo $emailError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="whatsapp">Whats'App : </label>
                        <input id="search" type="text" id="whatsapp" name="whatsapp" placeholder="Whats'app"
                            value="<?php echo $whatsapp ?>"><br>
                        <span class="erreur"><?php echo $whatsappError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="telephone">Telephone : </label>
                        <input id="search" type="text" id="telephone" name="telephone" placeholder="Telephone"
                            value="<?php echo $telephone ?>"><br>
                        <span class="erreur"><?php echo $telephoneError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="adress">Adresse : </label>
                        <input id="search" type="text" id="adresse" name="adresse" placeholder="Adresse"
                            value="<?php echo $adresse ?>"><br>
                        <span class="erreur"><?php echo $adresseError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="commentaire">Commentaire : </label>
                        <input id="search" class="textarea" type="textarea" id="commentaire" name="commentaire"
                            placeholder="Commentaire" value="<?php echo $commentaire ?>"><br>
                        <span class="erreur"><?php echo $commentaireError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="passeport">Passeport : </label>
                        <input id="search" type="text" id="passeport" name="passeport" placeholder="Passeport"
                            value="<?php echo $passeport ?>"><br>
                        <span class="erreur"><?php echo $passeportError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="banque">Banque : </label>
                        <input id="search" type="text" id="banque" name="banque" placeholder="Banque"
                            value="<?php echo $banque ?>"><br>
                        <span class="erreur"><?php echo $banqueError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="numerocompte">Numero De Compte : </label>
                        <input id="search" type="text" id="numerocompte" name="numerocompte"
                            placeholder="Numero De Compte" value="<?php echo $numerocompte ?>"><br>
                        <span class="erreur"><?php echo $numeroCompteError; ?></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-lg" type="submit">MODIFIER</button>
                        <a class="btn btn-lg btn-primary" href="voyageurs.php">RETOUR</a>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
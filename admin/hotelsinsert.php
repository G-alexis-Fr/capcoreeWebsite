<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    $anglaisError = $receptionError = $shuttleError = $wifiError = $parkingError = $restaurantError = $piscineError = $gymError = $commentaireError = $numeroCompteError = $banqueError = $etoileError = $telephoneError = $kakaoError = $adresseError = $villeError = $emailError = $nameError = $name = $email = $ville = $adresse = $kakaotalk = $telephone = $etoile = $banque = $numerocompte = $commentaire = $gym = $piscine = $restaurant = $parking = $wifi = $shuttle = $reception = $anglais = "";

    if(!empty($_POST)){
        $name = checkInput($_POST['name']);
        $email = checkInput($_POST['email']);
        $ville = checkInput($_POST['ville']);
        $adresse = checkInput($_POST['adresse']);
        $kakaotalk = checkInput($_POST['kakaotalk']);
        $telephone = checkInput($_POST['telephone']);
        $etoile = checkInput($_POST['etoile']);
        $banque = checkInput($_POST['banque']);
        $numerocompte = checkInput($_POST['numerocompte']);
        $commentaire = checkInput($_POST['commentaire']);
        $gym = checkInput($_POST['gym']);
        $piscine = checkInput($_POST['piscine']);
        $restaurant = checkInput($_POST['restaurant']);
        $parking = checkInput($_POST['parking']);
        $wifi = checkInput($_POST['wifi']);
        $shuttle = checkInput($_POST['shuttle']);
        $reception = checkInput($_POST['reception']);
        $anglais = checkInput($_POST['anglais']);
        
        $isSuccess = true;
        $isUploadSuccess = false;

        if(empty($name)){
            $nameError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };  
        if(empty($ville)){
            $villeError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($email)){
            $emailError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
    
        if($isSuccess){
            $db = Database::connect();
            // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
            $statement = $db->prepare("INSERT INTO hotel (Nom,Email,Ville,Adresse,KakaoTalk,Telephone,Etoile,Banque,NumeroCompte,Commentaire,Gym,Piscine, Restaurant, Parking, Wifi, Shuttle, Reception, Anglais) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $statement->execute(array($name,$email,$ville,$adresse,$kakaotalk,$telephone,$etoile,$banque,$numerocompte,$commentaire,$gym,$piscine,$restaurant,$parking,$wifi,$shuttle,$reception,$anglais));
            Database::disconnect();
            header("Location: hotels.php"); // Apres l'envoie on redirige a hotels.php
        };
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Nouvel Hôtel</title>
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
        <h1>Nouvel Hôtel</h1>
        <br><br>
        <div class="row text-center">
            
            <section class="col-6">
            <form action="hotelsinsert.php" method="POST">
            <div class="form-group">
                <label class="font-weight-bold" for="name">Nom : </label> 
                <input id="search" type="text"  id="name" name="name" placeholder="Nom *" value="<?php echo $name ?>"><br>
                <span class="erreur"><?php echo $nameError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="email">Email : </label> 
                <input id="search" type="text" id="email" name="email" placeholder="Email *" value="<?php echo $email ?>"><br>
                <span class="erreur"><?php echo $emailError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="ville">Ville : </label>
                <select id="search" name="ville" id="ville" >
                    <option value="Seoul">Seoul</option>;
                    <option value="Busan">Busan</option>;
                    <option value="Gyeongju">Gyeongju</option>;
                    <option value="Jeonju">Jeonju</option>;
                    <option value="Pohang">Pohang</option>;
                </select><br>
                <span class="erreur"><?php echo $villeError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="adress">Adresse : </label> 
                <input id="search" type="text" id="adresse" name="adresse" placeholder="Adresse" value="<?php echo $adresse ?>"><br>
                <span class="erreur"><?php echo $adresseError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="kakaotalk">KakaoTalk : </label> 
                <input id="search" type="text" id="kakaotalk" name="kakaotalk" placeholder="KakaoTalk" value="<?php echo $kakaotalk ?>"><br>
                <span class="erreur"><?php echo $kakaoError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="telephone">Telephone : </label> 
                <input id="search" type="text" id="telephone" name="telephone" placeholder="Telephone" value="<?php echo $telephone ?>"><br>
                <span class="erreur"><?php echo $telephoneError; ?></span>
            </div>
            
            <div class="form-group">
                <label class="font-weight-bold" for="etoile">Etoile : </label> 
                <input id="search" type="text" id="etoile" name="etoile" placeholder="Etoile" value="<?php echo $etoile ?>"><br>
                <span class="erreur"><?php echo $etoileError; ?></span>
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
            </section>
            <section class="col-6">
            <div class="form-group">
                <label class="font-weight-bold" for="commentaire">Commentaire : </label>
                <input id="search" class="textarea" type="textarea" id="commentaire" name="commentaire" placeholder="Commentaire" value="<?php echo $commentaire ?>"><br> 
                <span class="erreur"><?php echo $commentaireError; ?></span>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="gym">Gym : </label>
                <select id="search" name="gym" id="gym">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $gymError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="piscine">Piscine : </label>
                <select id="search" name="piscine" id="piscine">
                    <option value="v">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $piscineError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="restaurant">Restaurant : </label>
                <select id="search" name="restaurant" id="restaurant">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $restaurantError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="parking">Parking : </label>
                <select id="search" name="parking" id="parking">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $parkingError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="wifi">Wifi : </label>
                <select id="search" name="wifi" id="wifi">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $wifiError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="shuttle">Shuttle : </label>
                <select id="search" name="shuttle" id="shuttle">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $shuttleError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="reception">Reception : </label>
                <select id="search" name="reception" id="reception">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $receptionError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="anglais">Anglais : </label>
                <select id="search" name="anglais" id="anglais">
                    <option value="Oui">Oui</option>;
                    <option value="Non">Non</option>;
                </select><br>
                <span class="erreur"><?php echo $anglaisError; ?></span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">AJOUTER</button>
                <a class="btn btn-lg btn-primary" href="hotels.php">RETOUR</a>
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
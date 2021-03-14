<?php   
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    $name = $ville = $adresse = $heureouv = $heureferm = $duree = $prix = $nameError = $villeError = $adresseError = $heureouvError = $heurefermError = $dureeError = $prixError = "";

    if(!empty($_POST)){
        $name = checkInput($_POST['name']);
        $ville = checkInput($_POST['ville']);
        $adresse = checkInput($_POST['adresse']);
        $heureouv = checkInput($_POST['heureouv']);
        $heureferm = checkInput($_POST['heureferm']);
        $duree = checkInput($_POST['duree']);
        $prix = checkInput($_POST['prix']);
        
        $isSuccess = true;

        if(empty($name)){
            $nameError = "Verifier ce champs";
            $isSuccess = false;
        };
        if(empty($ville)){
            $villeError = "Verifier ce champs";
            $isSuccess = false;
        };
        if(empty($adresse)){
            $adresseError = "Verifier ce champs";
            $isSuccess = false;
        };
        
        if($isSuccess){
            try{
                $db = Database::connect();

                $statement = $db->prepare("INSERT INTO activites (Nom,Ville,Adresse,HeureOuv,HeureFerm,Duree,Prix) VALUES (?,?,?,?,?,?,?)");
                $statement->execute(array($name,$ville,$adresse,$heureouv,$heureferm,$duree,$prix));
                Database::disconnect();
                header("Location: activites.php"); // Apres l'envoie on redirige a activites
            }catch(Exception $e){
                
            }; 
        };
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Nouvelle Activité</title>
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
        <h1>Nouvelle Activité</h1>
        <br><br>
        <div class="row text-center">
            
            <section class="col-12">
                <form action="activitesinsert.php" method="POST">       
                    <div class="form-group">
                        <label class="font-weight-bold" for="name">Nom : </label> 
                        <input id="search" type="text"  id="name" name="name" placeholder="Nom *" value="<?php echo $name ?>"><br>
                        <span class="erreur"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="ville">Ville : </label> 
                        <input id="search" type="text" id="ville" name="ville" placeholder="Ville *" value="<?php echo $ville ?>"><br>
                        <span class="erreur"><?php echo $villeError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="adresse">Adresse : </label> 
                        <input id="search" type="text" id="adresse" name="adresse" placeholder="Adresse *" value="<?php echo $adresse ?>"><br>
                        <span class="erreur"><?php echo $adresseError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="heureouv">Heure Ouverture : </label> 
                        <input id="search" type="text" id="heureouv" name="heureouv" placeholder="Heure Ouverture" value="<?php echo $heureouv ?>"><br>
                        <span class="erreur"><?php echo $heureouvError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="heureferm">Heure Fermeture : </label> 
                        <input id="search" type="text" id="heureferm" name="heureferm" placeholder="Heure Fermeture" value="<?php echo $heureferm ?>"><br>
                        <span class="erreur"><?php echo $heurefermError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="duree">Durée : </label> 
                        <input id="search" type="text" id="duree" name="duree" placeholder="Duree" value="<?php echo $duree ?>"><br>
                        <span class="erreur"><?php echo $dureeError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="prix">Prix : </label> 
                        <input id="search" type="text" id="prix" name="prix" placeholder="Prix" value="<?php echo $prix ?>"><br>
                        <span class="erreur"><?php echo $prixError; ?></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-lg" type="submit">AJOUTER</button>
                        <a class="btn btn-lg btn-primary" href="activites.php">RETOUR</a>
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
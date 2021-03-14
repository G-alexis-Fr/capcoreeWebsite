<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);

        $db = Database::connect();
        $statement = $db->prepare("SELECT Nom FROM hotel WHERE ID=?");
        $statement->execute(array($id));
        $hotel = $statement->fetch();
        $db = Database::disconnect();

        $hotel = $hotel["Nom"];
    };

    $typeError = $nbreLitError = $prixError = $type = $nbreLit = $prix = "";

    if(!empty($_POST)){
        $id = checkInput($_POST['id']);
        $hotel = checkInput($_POST['hotel']);
        $type = checkInput($_POST['type']);
        $nbreLit = checkInput($_POST['nbreLit']);
        $prix = checkInput($_POST['prix']);
        
        $isSuccess = true;
        $isUploadSuccess = false;

        
        if(empty($type)){
            $typeError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        
        if(empty($nbreLit)){
            $nbreLitError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($prix)){
            $prixError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
    
        if($isSuccess){
            $db = Database::connect();
            // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
            $statement = $db->prepare("INSERT INTO chambre (idHotel,TypeChambre,NbreLit,Prix) VALUES(?,?,?,?)");
            $statement->execute(array($id,$type,$nbreLit,$prix));
            Database::disconnect();
            header("Location: hotelsview.php?id=$id"); // Apres l'envoie on redirige a hotels.php
        };
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Nouvelle Chambre</title>
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
<h1>Ajouter Une Nouvelle Chambre <span  style="color:#0a3d62;"><?php echo $hotel ?></span></h1>
    <br><br>
    <div class="row text-center">
        
        <section class="col-12">
        
        <form action="chambresinsert.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id;?>"/>
        <input type="hidden" name="hotel" value="<?php echo $hotel;?>"/>
            <div class="form-group">
                <label class="font-weight-bold" for="type">Type De Chambre : </label>
                <select id="search" name="type" id="type" >
                    <option value="Single">Chambre Simple</option>;
                    <option value="Double">Chambre Double</option>;
                    <option value="Suite">Suite</option>;
                    <option value="Deluxe">Deluxe</option>;
                </select><br>
                <span class="erreur"><?php echo $typeError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="nbreLit">Nombre De Lit : </label>
                <select id="search" name="nbreLit" id="nbreLit">
                    <option value="1">1</option>;
                    <option value="2">2</option>;
                    <option value="3">3</option>;
                    <option value="4">4</option>;
                    <option value="5">5</option>;
                    <option value="6">6</option>;
                    <option value="7">7</option>;
                    <option value="8">8</option>;
                </select><br>
                <span class="erreur"><?php echo $nbreLitError; ?></span>
            </div>

            <div class="form-group">
                <label class="font-weight-bold" for="prix">Prix : </label> 
                <input id="search" type="text" id="prix" name="prix" placeholder="Prix *" value="<?php echo $prix ?>"><br>
                <span class="erreur"><?php echo $prixError; ?></span>
            </div>

            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">AJOUTER</button>
                <a class="btn btn-primary btn-lg" href="hotelsview.php?id=<?php echo $id ?>">RETOUR</a>
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
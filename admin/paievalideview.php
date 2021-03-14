<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");
        
    require "database.php";
    require "check.php";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']); 
    
    if(!empty($_POST)){
        $id = $_POST['id'];

        $success = false;
        echo "nope doesn't pass by on top";
        $guide = checkInput($_POST['guide']);
        $amilocal = checkInput($_POST['amilocal']);
        $extension = checkInput($_POST['extension']);
        $sbn = checkInput($_POST['sbn']);
        $checkin = checkInput($_POST['checkin']);
        $paieht = checkInput($_POST['paieht']);
        $paiettc = checkInput($_POST['paiettc']);
        $extra = checkInput($_POST['extra']);
        $depense = checkInput($_POST['depense']);
        $commentaire = checkInput($_POST['commentaire']);
        $mois = checkInput($_POST['mois']);

        try{
            $db = Database::connect();
            // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
            echo "nope doesn't pass by";
            $statement = $db->prepare("INSERT INTO historique_paie (Guide, AmiLocal, Extension, SBN, CheckIn, PaieHT, PaieTTC, Extra, Depense, Commentaire, Date) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $statement->execute(array($guide,$amilocal,$extension,$sbn,$checkin,$paieht,$paiettc,$extra,$depense,$commentaire,$mois));

            $success = true;
            var_dump($success);
            if($success){
                $statement = $db->prepare("DELETE FROM paievalide WHERE ID = ?");
                $statement->execute(array($id));
            };
        }catch(Exception $e){
            echo "nope didn't work";
        };

        Database::disconnect();
        header("Location: alerte.php"); // Apres l'envoie on redirige a alerte
    }else{
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM paievalide WHERE ID = ?");
        $statement->execute(array($id));

        $item = $statement->fetch();

        $guide = $item['Guide'];
        $amilocal = $item['AmiLocal'];
        $extension = $item['Extension'];
        $sbn = $item['SBN'];
        $checkin = $item['CheckIn'];
        $paieht = $item['PaieHT'];
        $paiettc = $item['PaieTTC'];
        $extra = $item['Extra'];
        $depense = $item['Depense'];
        $commentaire = $item['Commentaire'];
        $mois = $item['Date'];

        Database::disconnect();
    };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Voir Paie</title>
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

                <h1>Valider Paie</h1>
                <br><br>

                <form action="paievalideview.php" method="POST">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $id;?>"/>
                    <label class="font-weight-bold" for="guide" >Guide :</label>
                    <input type="text" id="guide" name="guide" value="<?php echo $guide ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="amilocal" >Ami Local : </label>
                    <input type="text" id="amilocal" name="amilocal" value="<?php echo $amilocal ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="extension" >Extension : </label>
                    <input type="text" id="extension" name="extension" value="<?php echo $extension ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="sbn" >SBN : </label>
                    <input type="text" id="sbn" name="sbn" value="<?php echo $sbn ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="checkin" >Check-In : </label>
                    <input type="text" id="checkin" name="checkin" value="<?php echo $checkin ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="paieht" >PaieHT : </label>
                    <input type="text" id="paieht" name="paieht" value="<?php echo $paieht ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="paiettc" >PaieTTC : </label>
                    <input type="text" id="paiettc" name="paiettc" value="<?php echo $paiettc ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="extra" >Extra : </label>
                    <input type="text" id="extra" name="extra" value="<?php echo $extra ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="depense" >Depense : </label>
                    <input type="text" id="depense" name="depense" value="<?php echo $depense ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="commentaire" >Commentaire : </label>
                    <input type="text" id="commentaire" name="commentaire" value="<?php echo $commentaire ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <label class="font-weight-bold" for="mois" >Date : </label>
                    <input type="text" id="mois" name="mois" value="<?php echo $mois ?>" readonly><br>
                    </div>
                    <div class="form-group">
                    <button class="btn btn-primary btn-lg" type="submit">VALIDER</button>
                    <a class="btn btn-primary btn-lg" href="alerte.php">RETOUR</a>
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
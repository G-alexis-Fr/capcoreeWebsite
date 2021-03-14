<?php 
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    $nouvelleData = false;

    $conducteur = $allergie = $marcheDiffi = $santeSoucis = $evenement = $comment = $nomUrgent = $paysUrgent = $telUrgent = 
    $assuNom = $assuNumero = $assuTel = $allerAero = $allerNumVol = $allerHeureVol = $allerDateVol = $retourAero = $NumVolRetour = 
    $retourHeureVol = $retourDateVol = $conducteurError = $allergieError = $marcheDiffiError = $santeSoucisError = $evenementError = 
    $commentError = $nomUrgentError = $paysUrgentError = $telUrgentError = $assuNomError = $assuNumeroError = $assuTelError = 
    $allerAeroError = $allerNumVolError = $allerHeureVolError = $allerDateVolError = $retourAeroError = $NumVolRetourError = $retourHeureVolError = $retourDateVolError = "";

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']); // id de voyageurs

        $db = Database::connect();

        $statement = $db->prepare("SELECT * FROM reservation WHERE IdReservation = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        Database::disconnect();
    };

    if(!empty($_POST)){

        if ($statement->rowCount() < 1){ // Permet de savoir si il y a une donnee a ce voyageur, SI oui, on UPDATE, Si non, On INSERT
            $nouvelleData = true;
        };

        $conducteur = checkInput($_POST['conducteur']);
        $allergie = checkInput($_POST['allergie']);
        $marcheDiffi = checkInput($_POST['marcheDiffi']);
        $santeSoucis = checkInput($_POST['santeSoucis']);
        $evenement = checkInput($_POST['evenement']);
        $comment = checkInput($_POST['comment']);
        $nomUrgent = checkInput($_POST['nomUrgent']);
        $paysUrgent = checkInput($_POST['paysUrgent']);
        $telUrgent = checkInput($_POST['telUrgent']);
        $assuNom = checkInput($_POST['assuNom']);
        $assuNumero = checkInput($_POST['assuNumero']);
        $assuTel = checkInput($_POST['assuTel']);
        $allerAero = checkInput($_POST['allerAero']);
        $allerNumVol = checkInput($_POST['allerNumVol']);
        $allerHeureVol = checkInput($_POST['allerHeureVol']);
        $allerDateVol = checkInput($_POST['allerDateVol']);
        $retourAero = checkInput($_POST['retourAero']);
        $NumVolRetour = checkInput($_POST['NumVolRetour']);
        $retourHeureVol = checkInput($_POST['retourHeureVol']);
        $retourDateVol = checkInput($_POST['retourDateVol']);


        $isSuccess = true; // NEED TO ADD ALL THE ERROR POSSIBILITIES
        $db = Database::connect();

        if($isSuccess && $nouvelleData == false){

            try{
                $statement = $db->prepare("UPDATE reservation SET Conducteur=?,Allergie=?,Difficulte=?,Maladie=?,Evenement=?,Commentaire=?,NomCUrgent=?,PaysCUrgent=?,TelCUrgent=?,AeroAller=?,NumVolAller=?, HeureVolAller=?, DateVolAller=?, AeroRetour=?, NumVolRetour=?, HeureVolRetour=?,DateVolRetour=?, NomAssurance=?, ContratAssurance=?,TelAssurance=?  WHERE IdVoyageurs=?");
                $statement->execute(array($conducteur,$allergie,$marcheDiffi,$santeSoucis,$evenement,$comment,$nomUrgent,$paysUrgent,$telUrgent,$allerAero,$allerNumVol,$allerHeureVol,$allerDateVol,$retourAero,$NumVolRetour,$retourHeureVol,$retourDateVol,$assuNom,$assuNumero,$assuTel,$id));
                
            }catch(Exception $e){
                echo($e);
            };          
        };
        if($isSuccess && $nouvelleData){

            try{
                $statement = $db->prepare("INSERT INTO reservation (IdVoyageurs,Conducteur,Allergie,Difficulte,Maladie,Evenement,Commentaire,NomCUrgent,PaysCUrgent,TelCUrgent,AeroAller,NumVolAller, HeureVolAller, DateVolAller, AeroRetour, NumVolRetour,HeureVolRetour,DateVolRetour,NomAssurance,ContratAssurance,TelAssurance) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $statement->execute(array($id,$conducteur,$allergie,$marcheDiffi,$santeSoucis,$evenement,$comment,$nomUrgent,$paysUrgent,$telUrgent,$allerAero,$allerNumVol,$allerHeureVol,$allerDateVol,$retourAero,$NumVolRetour,$retourHeureVol,$retourDateVol,$assuNom,$assuNumero,$assuTel));
 
            }catch(Exception $e){
                echo($e);
            };           
        };

        $statement = $db->prepare("SELECT * FROM reservation WHERE IdVoyageurs = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        Database::disconnect();
    };

    // Ceci nous permet de recuperer les infos pour les mettres comme valeurs directement dans les inputs

    $conducteur = $item['Conducteur'];
    $allergie = $item['Allergie'];
    $marcheDiffi = $item['Difficulte'];
    $santeSoucis = $item['Maladie'];
    $evenement = $item['Evenement'];
    $comment = $item['Commentaire'];
    $nomUrgent = $item['NomCUrgent'];
    $paysUrgent = $item['PaysCUrgent'];
    $telUrgent = $item['TelCUrgent'];
    $assuNom =  $item['NomAssurance'];
    $assuNumero = $item['ContratAssurance'];
    $assuTel = $item['TelAssurance'];
    $allerAero = $item['AeroAller'];
    $allerNumVol = $item['NumVolAller'];
    $allerHeureVol = $item['HeureVolAller'];
    $allerDateVol = $item['DateVolAller'];
    $retourAero = $item['AeroRetour'];
    $NumVolRetour = $item['NumVolRetour'];
    $retourHeureVol = $item['HeureVolRetour'];
    $retourDateVol = $item['DateVolRetour'];
?>

<!doctype html>
<html lang="en">

<head>
    <title>Réservation Voyage</title>
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
        include "navigation.php";
    ?>

    <form id="formReser" method="POST"> <!-- On ajoute un id au form qui nous servira pour le bouton submit -->
        <div class="container-fluid">
            <div class="row text-center center-block">
                <section class="col-12">
                    <h1>Réservation De Voyage</h1>
                </section>
                <section class="col-sm-12 col-md-6">
                    <br><br>

                    <label class="font-weight-bold" for="conducteur">Nom du ou des conducteurs : </label><br>
                    <label class="labelA"><?php echo " " . $item['Conducteur']; ?></label><input type="text" style="display:none" id="conducteur" name="conducteur" value="<?php echo $conducteur?>"><br>
                    <span class="erreur"><?php echo $conducteurError; ?></span>

                    <label class="font-weight-bold" for="allergie">Avez-vous des allergies alimentaires/un régime particulier? *:</label><br>
                    <label class="labelA"><?php echo " " . $item['Allergie']; ?></label><input type="text" style="display:none" id="allergie" name="allergie" value="<?php echo $allergie?>"><br>
                    <span class="erreur"><?php echo $allergieError; ?></span>

                    <label class="font-weight-bold" for="marcheDiffi">Avez-vous des difficultés pour marcher? * </label><br>
                    <label class="labelA"><?php echo " " . $item['Difficulte']; ?></label><input type="text" style="display:none" id="marcheDiffi" name="marcheDiffi" value="<?php echo $marcheDiffi?>"><br>
                    <span class="erreur"><?php echo $marcheDiffiError; ?></span>

                    <label class="font-weight-bold" for="santeSoucis">Avez-vous un soucis de santé particulier dont vous voulez nous faire part?</label><br>
                    <label class="labelA"><?php echo " " . $item['Maladie']; ?></label><input type="text" style="display:none" id="santeSoucis" name="santeSoucis" value="<?php echo $santeSoucis?>"><br>
                    <span class="erreur"><?php echo $santeSoucisError; ?></span>

                    <label class="font-weight-bold" for="evenement">Y a t'il un événement particulier pendant votre voyage ?</label><br>
                    <label class="labelA"><?php echo " " . $item['Evenement']; ?></label><input type="text" style="display:none" id="evenement" name="evenement" value="<?php echo $evenement?>"><br>
                    <span class="erreur"><?php echo $evenementError; ?></span>

                    <label class="font-weight-bold" for="comment">Commentaire : </label><br>
                    <label class="labelA"><?php echo " " . $item['Commentaire']; ?></label><input type="text" style="display:none" id="comment" name="comment" value="<?php echo $comment?>"><br>
                    <span class="erreur"><?php echo $commentError; ?></span>
                </section>
            
                
                <section class="col-sm-12 col-md-3 bg-light">
                    <br><br>

                    <h3>Contact Urgence</h3> 

                    <label class="font-weight-bold" for="nomUrgent">Nom : </label>
                    <label class="labelA"><?php echo " " . $item['NomCUrgent']; ?></label><input type="text" style="display:none" id="nomUrgent" name="nomUrgent" value="<?php echo $nomUrgent?>"><br>
                    <span class="erreur"><?php echo $nomUrgentError; ?></span>

                    <label class="font-weight-bold" for="paysUrgent">Pays : </label>
                    <label class="labelA"><?php echo " " . $item['PaysCUrgent']; ?></label><input type="text" style="display:none" id="paysUrgent" name="paysUrgent" value="<?php echo $paysUrgent?>"><br>
                    <span class="erreur"><?php echo $paysUrgentError; ?></span>
                    <label class="font-weight-bold" for="telUrgent">Num Tel : </label>
                    <label class="labelA"><?php echo " " . $item['TelCUrgent']; ?></label><input type="text" style="display:none" id="telUrgent" name="telUrgent" value="<?php echo $telUrgent?>"><br>
                    <span class="erreur"><?php echo $telUrgentError; ?></span><br>

                    <h3>Infos Vol Aller</h3>  

                    <label class="font-weight-bold" for="allerAero">Aeroport : </label>
                    <label class="labelA"><?php echo " " . $item['AeroAller']; ?></label><input type="text" style="display:none" id="allerAero" name="allerAero" value="<?php echo $allerAero?>"><br>
                    <span class="erreur"><?php echo $allerAeroError; ?></span>

                    <label class="font-weight-bold" for="allerNumVol">Num Vol : </label>
                    <label class="labelA"><?php echo " " . $item['NumVolAller']; ?></label><input type="text" style="display:none" id="allerNumVol" name="allerNumVol" value="<?php echo $allerNumVol?>"><br>
                    <span class="erreur"><?php echo $allerNumVolError; ?></span>

                    <label class="font-weight-bold" for="allerHeureVol">Heure : </label>
                    <label class="labelA"><?php echo " " . $item['HeureVolAller']; ?></label><input type="text" style="display:none" id="allerHeureVol" name="allerHeureVol" value="<?php echo $allerHeureVol?>"><br>
                    <span class="erreur"><?php echo $allerHeureVolError; ?></span>

                    <label class="font-weight-bold" for="allerDateVol">Date : </label>
                    <label class="labelA"><?php echo " " . $item['DateVolAller']; ?></label><input type="text" style="display:none" id="allerDateVol" name="allerDateVol" value="<?php echo $allerDateVol?>"><br>
                    <span class="erreur"><?php echo $allerDateVolError; ?></span>

                </section>

                <section class="col-sm-12 col-md-3 bg-light">
                <br><br>
                <h3>Assurance</h3>

                    <label class="font-weight-bold" for="assuNom">Nom : </label>
                    <label class="labelA"><?php echo " " . $item['NomAssurance']; ?></label><input type="text" style="display:none" id="assuNom" name="assuNom" value="<?php echo $assuNom?>"><br>
                    <span class="erreur"><?php echo $assuNomError; ?></span>

                    <label class="font-weight-bold" for="assuNumero">Num Assurance :</label>
                    <label class="labelA"><?php echo " " . $item['ContratAssurance']; ?></label ><input type="text" style="display:none" id="assuNumero" name="assuNumero" value="<?php echo $assuNumero?>"><br>
                    <span class="erreur"><?php echo $assuNumeroError; ?></span>

                    <label class="font-weight-bold" for="assuTel">Num Tel : </label>
                    <label class="labelA"><?php echo " " . $item['TelAssurance']; ?></label><input type="text" style="display:none" id="assuTel" name="assuTel" value="<?php echo $assuTel?>"><br>
                    <span class="erreur"><?php echo $assuTelError; ?></span><br>

                    <h3>Infos Vol Retour</h3>

                    <label class="font-weight-bold" for="retourAero">Aeroport : </label>
                    <label class="labelA"><?php echo " " . $item['AeroRetour']; ?></label><input type="text" style="display:none" id="retourAero" name="retourAero" value="<?php echo $retourAero?>"><br>
                    <span class="erreur"><?php echo $retourAeroError; ?></span>

                    <label class="font-weight-bold" for="NumVolRetour">Num Vol : </label>
                    <label class="labelA"><?php echo " " . $item['NumVolRetour']; ?></label><input type="text" style="display:none" id="NumVolRetour" name="NumVolRetour" value="<?php echo $NumVolRetour?>"><br>
                    <span class="erreur"><?php echo $NumVolRetourError; ?></span>

                    <label class="font-weight-bold" for="retourHeureVol">Heure : </label>
                    <label class="labelA"><?php echo " " . $item['HeureVolRetour']; ?></label><input type="text" style="display:none" id="retourHeureVol" name="retourHeureVol" value="<?php echo $retourHeureVol?>"><br>
                    <span class="erreur"><?php echo $retourHeureVolError; ?></span>

                    <label class="font-weight-bold" for="retourDateVol">Date : </label>
                    <label class="labelA"><?php echo " " . $item['DateVolRetour']; ?></label><input type="text" style="display:none" id="retourDateVol" name="retourDateVol" value="<?php echo $retourDateVol?>"><br>
                    <span class="erreur"><?php echo $retourDateVolError; ?></span>
                
                </section>
                    
                <br>
            </div>
        </div>
    </form>

    <div class="row text-center">
        <div class="col-12">
            <button id="btnMod" class="btn btn-primary btn-lg">MODIFIER</button>
            <button type="submit" form="formReser" style="display:none" id="btnValid" class="btn btn-primary btn-lg">VALIDER</button><!-- on rajoute form="" car le bouton submit est en dehors des balises form -->
            <a href="voyageursview.php?id=<?php echo $id ?>" class="btn btn-primary btn-lg">RETOUR</a>
        </div>
    </div>

    <?php
        // On verifie si une row exist dans la base de donnee, et si elle n'existe pas alors on lance un script pour afficher les inputs directement et donc faire un INSERT
            if ($statement->rowCount() < 1){ 
                $nouvelleData = true;
            
    ?>
        <script type="text/javascript"> 

            var button = document.getElementById("btnMod"); // le bouton Modifier
                var val = document.getElementById("btnValid"); // le bouton Valider en position cache au debut du script
                var lesInputs = document.getElementsByTagName('input'); // On selectionne les inputs
                var lesLabels = document.getElementsByClassName('labelA'); // On selectionne les labels
                
                for (var a = 0; a < lesLabels.length; a++) {
                    var etat = lesLabels[a].style.display = "none";
                };

                for (var i = 0; i < lesInputs.length; i++) {
                    etat = lesInputs[i].style.display;
                    if (etat == "none"){ 
                        lesInputs[i].style.display = "inline"; 
                    } else { 
                        lesInputs[i].style.display = "none"; 
                    };
                };
                button.style.display = "none";
                val.style.display = "inline";
        </script>

    <?php
    };
    ?>


    <!-- Optional JavaScript -->
    <script src="../javascript/script.js"></script> 
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
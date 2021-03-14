<?php 
    session_start();
    if(!isset($_SESSION["membre"]))
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    $guide = $amilocal = $extension = $sbn = $checkin = $extra = $depense = $commentaire = $mois =  $guideError = $amilocalError = $extensionError = $sbnError = $checkinError = $extraError = $depenseError = $commentaireError = $moisError = "";

    if(!empty($_POST)){
        $guide = checkInput($_POST['guide']);
        $amilocal = checkInput($_POST['amilocal']);
        $extension = checkInput($_POST['extension']);
        $sbn = checkInput($_POST['sbn']);
        $checkin = checkInput($_POST['checkin']);
        $extra = checkInput($_POST['extra']);
        $depense = checkInput($_POST['depense']);
        $commentaire = checkInput($_POST['commentaire']);
        $mois = checkInput($_POST['mois']);

        $isSuccess = true; // Verifiy if there is no error if false then doesn't upload

        if(empty($guide)){
            $guideError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if((filter_var($extra, FILTER_VALIDATE_INT) != true)){
            $extraError = "Veuillez ne saisir que des chiffres";
            $isSuccess = false;
        };
        if(empty($extra)){
            $extraError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if((filter_var($depense, FILTER_VALIDATE_INT) != true)){
            $depenseError = "Veuillez ne saisir que des chiffres";
            $isSuccess = false;
        };
        if(empty($depense)){
            $depenseError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($mois)){
            $moisError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };

        if($isSuccess){
            $db = Database::connect();
            // On recupere les prix des prestations QUI on etes PARAMETRES dans la page PARAMETRES.PHP par un ADMIN
            $sqlPrix = $db->query("SELECT * FROM prixprestation");
            $sqlPrix ->execute(array());
            $prix = $sqlPrix->fetch();

            // On recupere les valeurs du formulaire et on effectue la comtpa HT et ensuite TTC
            // We get the data from the form + we calculate with the data from the DATABASE which as been filled up by the ADMIN
            $paieht = ((int)$amilocal * $prix['amilocal']) + ((int)$extension * $prix['extension']) + ((int)$sbn * $prix['sbn']) + ((int)$checkin * $prix['checkin']);
            $paiettc = $paieht * 0.967; // 3.3 % for the tax
            
            // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
            $statement = $db->prepare("INSERT INTO newpaie (Guide, AmiLocal, Extension, SBN, CheckIn, PaieHT, PaieTTC, Extra, Depense, Commentaire, Date) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $statement->execute(array($guide,$amilocal,$extension,$sbn,$checkin,$paieht,$paiettc,$extra,$depense,$commentaire,$mois));

            Database::disconnect();
            header("Location: employes.php"); // Apres l'envoie on redirige a employe
        };
    };
?>

<!doctype html>
<html lang="en">

<head>
    <title>Compta Guide</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/cap.ico" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>

    <?php
        include "navigation.php";
    ?>

    <div class="container">
        <div class="row">
            <section class="col-12 text-center">
                <br>
                <h1>Comptabilité</h1>
                <br><br>
            </section>
            <div class="col-12">
                <form action="compta.php" method="POST" class="text-center w-auto">

                    <div class="form-group">
                        <label class="font-weight-bold" for="guide">Guide* : </label>
                        <select class="curseur" id="search" name="guide" id="guide">
                            <?php
                                $db = Database::connect();
                                $membre = $_SESSION["membre"];
                                
                                if($_SESSION["role"] == "Guide"){ // Le guide qui se connecte ne verra que son nom
                                    $nomMembre = $db->prepare('SELECT Nom,Prenom FROM listeemploye WHERE  Identifiant=?');
                                    $nomMembre->execute(array($membre));

                                    $item = $nomMembre->fetch();

                                    echo '<option value="'. $item['Nom'] . ' ' . $item['Prenom'] .'">'. $item['Nom'] . ' ' . $item['Prenom'] . '</option>';
                                }
                                else{ // Les employes, admins etc eux verront la liste de tous les guides pour eventuellement faire la compta de l'un d'eux
                                    foreach ($db->query('SELECT Nom,Prenom,Role FROM listeemploye WHERE Role="Guide"') as $row) { 
                                    echo '<option value="'. $row['Nom'] . ' ' . $row['Prenom'] .'">'. $row['Nom'] . ' ' . $row['Prenom'] . '</option>';
                                    };
                                };
                                
                                Database::disconnect();
                            ?>
                        </select>
                        <span class="erreur"><?php echo $guideError; ?></span>
                    </div>

                    <div class="form-group">
                        <label data-toggle="tooltip"
                            title="Veuillez renseigner le nombre de journee Ami Local que vous avez effectue"
                            class="font-weight-bold" for="amilocal">Journée Ami Local* : </label>
                        <select data-toggle="tooltip"
                            title="Veuillez renseigner le nombre de journee Ami Local que vous avez effectue"
                            id="search" type="text" id="amilocal" name="amilocal">
                            <option value="0">0</option>;
                            <option value="1">1</option>;
                            <option value="2">2</option>;
                            <option value="3">3</option>;
                            <option value="4">4</option>;
                            <option value="5">5</option>;
                            <option value="6">6</option>;
                            <option value="7">7</option>;
                            <option value="8">8</option>;
                            <option value="9">9</option>;
                            <option value="10">10</option>;
                            <option value="11">11</option>;
                            <option value="12">12</option>;
                            <option value="13">13</option>;
                            <option value="14">14</option>;
                            <option value="15">15</option>;
                        </select><br>
                        <span class="erreur"><?php echo $amilocalError; ?></span>
                    </div>
                    <div class="form-group">
                        <label data-toggle="tooltip"
                            title="Veuillez renseigner le nombre d'extension que vous avez effectue"
                            class="font-weight-bold" for="extension">Extension* : </label>
                        <select data-toggle="tooltip"
                            title="Veuillez renseigner le nombre d'extension que vous avez effectue" id="search"
                            type="text" id="extension" name="extension">
                            <option value="0">0</option>;
                            <option value="1">1</option>;
                            <option value="2">2</option>;
                            <option value="3">3</option>;
                            <option value="4">4</option>;
                            <option value="5">5</option>;
                            <option value="6">6</option>;
                            <option value="7">7</option>;
                            <option value="8">8</option>;
                            <option value="9">9</option>;
                            <option value="10">10</option>;
                            <option value="11">11</option>;
                            <option value="12">12</option>;
                            <option value="13">13</option>;
                            <option value="14">14</option>;
                            <option value="15">15</option>;
                        </select><br>
                        <span class="erreur"><?php echo $extensionError; ?></span>
                    </div>

                    <div class="form-group">
                        <label data-toggle="tooltip"
                            title="Veuillez renseigner le nombre d'extension Seoul By Night que vous avez effectue"
                            class="font-weight-bold" for="sbn">Séoul By Night* : </label>
                        <select data-toggle="tooltip"
                            title="Veuillez renseigner le nombre d'extension Seoul By Night que vous avez effectue"
                            id="search" type="text" id="sbn" name="sbn">
                            <option value="0">0</option>;
                            <option value="1">1</option>;
                            <option value="2">2</option>;
                            <option value="3">3</option>;
                            <option value="4">4</option>;
                            <option value="5">5</option>;
                            <option value="6">6</option>;
                            <option value="7">7</option>;
                            <option value="8">8</option>;
                            <option value="9">9</option>;
                            <option value="10">10</option>;
                            <option value="11">11</option>;
                            <option value="12">12</option>;
                            <option value="13">13</option>;
                            <option value="14">14</option>;
                            <option value="15">15</option>;
                        </select><br>
                        <span class="erreur"><?php echo $sbnError; ?></span>
                    </div>
                    <div class="form-group">
                        <label data-toggle="tooltip"
                            title="Veuillez renseigner le nombre de Check-In que vous avez effectue"
                            class="font-weight-bold" for="checkin">Check-In* : </label>
                        <select data-toggle="tooltip"
                            title="Veuillez renseigner le nombre de Check-In que vous avez effectue" id="search"
                            type="text" id="checkin" name="checkin">
                            <option value="0">0</option>;
                            <option value="1">1</option>;
                            <option value="2">2</option>;
                            <option value="3">3</option>;
                            <option value="4">4</option>;
                            <option value="5">5</option>;
                            <option value="6">6</option>;
                            <option value="7">7</option>;
                            <option value="8">8</option>;
                            <option value="9">9</option>;
                            <option value="10">10</option>;
                            <option value="11">11</option>;
                            <option value="12">12</option>;
                            <option value="13">13</option>;
                            <option value="14">14</option>;
                            <option value="15">15</option>;
                        </select><br>
                        <span class="erreur"><?php echo $checkinError; ?></span>
                    </div>

                    <div class="form-group">
                        <label data-toggle="tooltip"
                            title="Veuillez renseigner le montant en KRW des extras que vous avez effectue"
                            class="font-weight-bold" for="extra">Extra* : </label>
                        <input data-toggle="tooltip"
                            title="Veuillez renseigner le montant en KRW des extras que vous avez effectue" id="search"
                            type="text" id="extra" name="extra" placeholder="Extra *" value="<?php echo $extra ?>"><br>
                        <span class="erreur"><?php echo $extraError; ?></span>
                    </div>
                    <div class="form-group">
                        <label data-toggle="tooltip"
                            title="Veuillez renseigner le montant en KRW des depenses supplementaires (cafe,bus) que vous avez effectue avec votre carte"
                            class="font-weight-bold" for="depense">Depense* : </label>
                        <input data-toggle="tooltip"
                            title="Veuillez renseigner le montant en KRW des depenses supplementaires (cafe,bus) que vous avez effectue avec votre carte"
                            id="search" type="text" id="depense" name="depense" placeholder="Depense *"
                            value="<?php echo $depense ?>"><br>
                        <span class="erreur"><?php echo $depenseError; ?></span>
                    </div>

                    <div class="form-group">
                        <label data-toggle="tooltip" title="Ajouter un commentaire" class="font-weight-bold" for="commentaire">Commentaire : </label>
                        <input data-toggle="tooltip" title="Ajouter un commentaire" id="search" class="textarea" type="textarea" id="commentaire" name="commentaire" placeholder="Commentaire" value="<?php echo $commentaire ?>"><br>
                        <span class="erreur"><?php echo $commentaireError; ?></span>
                    </div>
                    <div class="form-group">
                        <label data-toggle="tooltip" title="Veuillez selectionner le mois travaille" class="font-weight-bold" for="mois">Quel mois* :</label>
                        <input data-toggle="tooltip" title="Veuillez selectionner le mois travaille" id="search" type="month" id="mois" name="mois" value="<?php echo $mois ?>"><br>
                        <span class="erreur"><?php echo $moisError; ?></span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-lg" type="submit">ENVOYER</button>
                        <a class="btn btn-primary btn-lg" href="employes.php">RETOUR</a>
                    </div>

                </form>
            </div>
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
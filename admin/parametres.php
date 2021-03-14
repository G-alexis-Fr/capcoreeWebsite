<?php
  session_start();
  if(!isset($_SESSION["membre"]) || $_SESSION["role"] != "Admin")
      header("Location: ../login.php");

  require "database.php";
  require "check.php";

  $amilocal = $extension = $sbn = $checkin = $email = $password = $amilocalError = $extensionError = $sbnError = $checkinError = $emailError = $passwordError = "";

  if(!empty($_POST)){ // Si un formulaire est soumit alors on verifie lequel et on execute
    $db = Database::connect();

    if(!empty($_POST['amilocal'])){ // SI c;est celui des prestations
      $ami = checkInput($_POST['amilocal']);
      $ext = checkInput($_POST['extension']);
      $seoulbn = checkInput($_POST['sbn']);
      $check = checkInput($_POST['checkin']);

      $isSuccess = true;
      // on verifie que ce ne soit pas vide sinon on update pas
      if((filter_var($ami, FILTER_VALIDATE_INT) != true)){
            $amilocalError = "Veuillez ne saisir que des chiffres";
            $isSuccess = false;
        };
      if(empty($ami)){
          $amilocalError = "Ce champs ne peut etre vide";
          $isSuccess = false;
      };
      if((filter_var($ext, FILTER_VALIDATE_INT) != true)){
            $extensionError = "Veuillez ne saisir que des chiffres";
            $isSuccess = false;
        };
      if(empty($ext)){
          $extensionError = "Ce champs ne peut etre vide";
          $isSuccess = false;
      };
      if((filter_var($seoulbn, FILTER_VALIDATE_INT) != true)){
            $sbnError = "Veuillez ne saisir que des chiffres";
            $isSuccess = false;
        };
      if(empty($seoulbn)){
          $sbnError = "Ce champs ne peut etre vide";
          $isSuccess = false;
      };
      if((filter_var($check, FILTER_VALIDATE_INT) != true)){
            $checkinError = "Veuillez ne saisir que des chiffres";
            $isSuccess = false;
        };
      if(empty($check)){
          $checkinError = "Ce champs ne peut etre vide";
          $isSuccess = false;
      };

      if($isSuccess){
        // On fait une requete pour voir si il y a deja des infos dans la BDD 
        $sqlPrix = $db->query("SELECT COUNT(*) FROM prixprestation");
        $sqlPrix ->execute(array());

        $taillePrix = $sqlPrix->fetchColumn();

        if($taillePrix > 0){ // Si info alors on fait un UPDATE
          $statement = $db->prepare("UPDATE prixprestation SET amilocal=?,extension=?,sbn=?,checkin=? WHERE ID=1");
          $statement->execute(array($ami,$ext,$seoulbn,$check));

        }else{ // Sinon INSERT
          $statement = $db->prepare("INSERT INTO prixprestation (amilocal,extension,sbn,checkin) VALUES(?,?,?,?)");
          $statement->execute(array($ami,$ext,$seoulbn,$check));
        };
      };
    };

    if(!empty($_POST['email'])){ // Si c est celui des informations d email
      
      $mail = checkInput($_POST['email']);
      $pwd = checkInput($_POST['password']);

      $isSuccess = true;
      // on verifie que ce ne soit pas vide sinon on update pas
      if(empty($mail)){
          $emailError = "Ce champs ne peut etre vide";
          $isSuccess = false;
      };
      if(empty($pwd)){
          $passwordError = "Ce champs ne peut etre vide";
          $isSuccess = false;
      };

      if($isSuccess){
        // On fait une requete pour voir si il y a deja des infos dans la BDD 
        $sqlContact = $db->query("SELECT COUNT(*) FROM mailcontact");
        $sqlContact ->execute(array());

        $tailleContact = $sqlContact->fetchColumn();

        if($tailleContact > 0){ // Si info alors on fait un UPDATE
          $statement = $db->prepare("UPDATE mailcontact SET emailcontact=?,pwd=? WHERE ID=1");
          $statement->execute(array($mail,$pwd));

        }else{ // Sinon INSERT
          $statement = $db->prepare("INSERT INTO mailcontact (emailcontact,pwd) VALUES(?,?)");
          $statement->execute(array($mail,$pwd));
        };
      };
    };

    $db = Database::connect();
    $statement = $db->query("SELECT * FROM prixprestation"); // On recupere les infos des prestations
    $statement->execute(array());
    $item = $statement->fetch();

    $adminInfo = $db->query("SELECT * FROM mailcontact");
    $adminInfo->execute(array());
    $itemadminInfo = $adminInfo->fetch();

    Database::disconnect();
    
  } else{ // Sinon on affiche les donnees de la BDD

    $db = Database::connect();
    $statement = $db->query("SELECT * FROM prixprestation"); // On recupere les infos des prestations
    $statement->execute(array());
    $item = $statement->fetch();

    $adminInfo = $db->query("SELECT * FROM mailcontact");
    $adminInfo->execute(array());
    $itemadminInfo = $adminInfo->fetch();

    Database::disconnect();
  };

  // On recupere ces variables pour les inserer dans les inputs quand on les affichera
  $amilocal = $item['amilocal'];
  $extension = $item['extension'];
  $sbn = $item['sbn'];
  $checkin = $item['checkin'];

  $email = $itemadminInfo['emailcontact'];
  $password = $itemadminInfo['pwd'];

  // Permet de remplacer le MDP par des etoiles pour ne pas l afficher a l ecran
  // Replace the char by * to do not display the password 
  $hidePassword = "";
  for($i=1; $i<= strlen($password); $i++){
    $hidePassword .= "*";
  };
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Paramètres</title>
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
          <div class="col-sm-12 text-center">
              <h1>Paramètres</h1>
              <br>
          </div>
      </div>
      <div class="row text-center">
        <div class="col-sm-12 col-md-4">
          <h2 id="">Prix Des Prestations</h2>
          <br>
          <form method="POST" id="priceForm">
            <label class="font-weight-bold" for="amilocal">Prix AmiLocal* :</label>
            <label class="labelPrix"><?php echo " " . $item['amilocal']; ?></label><input type="text" class="inputPrix" style="display:none" id="amilocal" name="amilocal" value="<?php echo $amilocal?>"><br>
            <span class="erreur"><?php echo $amilocalError; ?></span>

            <label class="font-weight-bold" for="extension">Prix Extension* :</label>
            <label class="labelPrix"><?php echo " " . $item['extension']; ?></label><input type="text" class="inputPrix" style="display:none" id="extension" name="extension" value="<?php echo $extension?>"><br>
            <span class="erreur"><?php echo $extensionError; ?></span>

            <label class="font-weight-bold" for="sbn">Prix Seoul By Night* :</label>
            <label class="labelPrix"><?php echo " " . $item['sbn']; ?></label><input type="text" class="inputPrix" style="display:none" id="sbn" name="sbn" value="<?php echo $sbn ?>"><br>
            <span class="erreur"><?php echo $sbnError; ?></span>

            <label class="font-weight-bold" for="checkin">Prix Check-In* :</label>
            <label class="labelPrix"><?php echo " " . $item['checkin']; ?></label><input type="text" class="inputPrix" style="display:none" id="checkin" name="checkin" value="<?php echo $checkin?>"><br>
            <span class="erreur"><?php echo $checkinError; ?></span>
            <br>
            
          </form>
        </div>

        <div class="col-sm-12 col-md-4 ">
          <h2 id="">Email Admin Info</h2>
          <br>
          <form method="POST" id="emailForm">
            <label class="font-weight-bold" for="email">Adresse E-mail* :</label>
            <label class="labelEmail"><?php echo " " . $itemadminInfo['emailcontact']; ?></label><input type="text" class="inputEmail" style="display:none" id="email" name="email" value="<?php echo $email?>"><br>
            <span class="erreur"><?php echo $emailError; ?></span>

            <label class="font-weight-bold" for="password">Mot de passe* :</label>
            <label type="password" class="labelEmail"><?php echo " " . $hidePassword; ?></label><input type="password" class="inputEmail" style="display:none" id="password" name="password" value="<?php echo $password?>"><br>
            <span class="erreur"><?php echo $passwordError; ?></span>
            <br>
          </form>
        </div>
      </div>
  
      <div class="row text-center">
        <div class="col-sm-12 col-md-4">
          <button id="btnModPrix" class="btn btn-primary btn-lg">MODIFIER</button>
          <button type="submit" style="display:none" form="priceForm" name="price"  id="btnValidPrix" class="btn btn-primary btn-lg">VALIDER</button><!-- on rajoute form="" car ce bouton correspond a l'id du form -->
        </div>
        <div class="col-sm-12 col-md-4">
          <button id="btnModEmail" class="btn btn-primary btn-lg">MODIFIER</button>
          <button type="submit" style="display:none" form="emailForm" name="emailll" id="btnValidEmail" class="btn btn-primary btn-lg">VALIDER</button><!-- on rajoute form="" car ce bouton correspond a l'id du form -->
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="../javascript/parametres.js"></script> 
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
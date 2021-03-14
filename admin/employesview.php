<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']);
        
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM listeemploye WHERE ID = ?");
    $statement->execute(array($id));
    $item = $statement->fetch();

    Database::disconnect();
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Voir Employé</title>
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
        <div class="row text-center">
            <section class="col-12">
            <h1>Profil de l'employé</h1>
            </section>

            <section class="col-sm-12 col-md-4">
                <br><br>
                <img src="<?php echo '../images/employes/'.$item['Image'];?>" alt="Profil Picture Employe" class="img-thumbnail" style="width:300px"> 
            </section>

            <section class="col-sm-12 col-md-3">
            
                <br><br>

                <label class="font-weight-bold" for="">Role : </label><?php echo " " . $item['Role']; ?><br>
                <label class="font-weight-bold" for="">Nom : </label><?php echo " " . $item['Nom']; ?>   <br>
                <label class="font-weight-bold" for="">Prenom : </label><?php echo " " . $item['Prenom']; ?><br>
                <label class="font-weight-bold" for="">Sexe : </label><?php echo " " . $item['Sexe']; ?><br>
                <label class="font-weight-bold" for="">Identifiant : </label><?php echo " " . $item['Identifiant']; ?><br>
                <label class="font-weight-bold" for="">Email : </label><?php echo " " . $item['Email']; ?><br>
            </section>
            <section class="col-sm-12 col-md-3">

                <br><br>
                
                <label class="font-weight-bold" for="">Telephone : </label><?php echo " " . $item['Telephone']; ?><br>
                <label class="font-weight-bold" for="">KakaoTalk : </label><?php echo " " . $item['KakaoTalk']; ?><br>
                <label class="font-weight-bold" for="">Adresse : </label><?php echo " " . $item['Adresse']; ?><br>
                <label class="font-weight-bold" for="">AlienCard : </label><?php echo " " . $item['AlienCard']; ?><br>
                <label class="font-weight-bold" for="">Banque : </label><?php echo " " . $item['Banque']; ?><br>
                <label class="font-weight-bold" for="">NumeroCompte : </label><?php echo " " . $item['NumeroCompte']; ?><br>

                <br><br>

            </section>
        </div>
        <div class="row text-center">
            <div class="col-12">

            <!-- Seulement l'admin peut avoir acces aux boutons modifier/supprimer -->
            <!-- Only the Admin can have access to the 2 buttons to update and delete the employe -->
        <?php
            if($_SESSION["role"] == "Admin"){ 
        ?>
                <a class="btn btn-info btn-lg" href="employesupdate.php?id=<?php echo $id ?>" ></span> MODIFIER</a>
                <a class="btn btn-danger btn-lg" href="employesdelete.php?id=<?php echo $id ?>" ></span> SUPPRIMER</a>
        <?php
            };
        ?> 
                <a href="employes.php" class="btn btn-primary btn-lg">RETOUR</a>
            </div>
        </div>
    </div>

      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
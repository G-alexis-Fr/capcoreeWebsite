<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    };

    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM activites WHERE ID = ?");
    $statement->execute(array($id));

    $item = $statement->fetch();

    Database::disconnect();

?>


<!doctype html>
<html lang="en">

<head>
    <title>Affichage Activité</title>
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
            <section class="col-sm-12 text-center">
                <h1>Information De L'Activité</h1>

                <br><br>

                <label class="font-weight-bold" for="">Nom : </label><?php echo " " . $item['Nom']; ?> <br>
                <label class="font-weight-bold" for="">Ville : </label><?php echo " " . $item['Ville']; ?><br>
                <label class="font-weight-bold" for="">Adresse : </label><?php echo " " . $item['Adresse']; ?><br>
                <label class="font-weight-bold" for="">Heure Ouverture :
                </label><?php echo " " . $item['HeureOuv']; ?><br>
                <label class="font-weight-bold" for="">Heure Fermeture :
                </label><?php echo " " . $item['HeureFerm']; ?><br>
                <label class="font-weight-bold" for="">Durée : </label><?php echo " " . $item['Duree']; ?><br>
                <label class="font-weight-bold" for="">Prix : </label><?php echo " " . $item['Prix']; ?><br>

                <br><br>

            </section>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <a class="btn btn-info btn-lg" href="activitesupdate.php?id=<?php echo $id ?>" ></span> MODIFIER</a>
                <a class="btn btn-danger btn-lg" href="activitesdelete.php?id=<?php echo $id ?>" ></span> SUPPRIMER</a>
                <a href="activites.php" class="btn btn-primary btn-lg">RETOUR</a>
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
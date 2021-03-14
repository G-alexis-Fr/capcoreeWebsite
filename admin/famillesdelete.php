<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']); // On verifie l'id du MEMBRE de la famille

        $db = Database::connect();
        $statement = $db->prepare("SELECT ID FROM voyageursfamille WHERE IDFAMILLE = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        $membre = $item["ID"];
    };    

    $db = Database::connect();
    $statement = $db->prepare("SELECT Nom FROM voyageursfamille WHERE IDFamille = ?");
    $statement->execute(array($id));

    $item = $statement->fetch();

    Database::disconnect();

    if(!empty($_POST["id"])){ // Si l'utilisateur a clique sur OUI alors on supprime a partir de l'id
        
        $id = checkInput($_POST['id']);
        $membre = checkInput($_POST['membre']);

        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM voyageursfamille WHERE IDFamille = ?");
        $statement->execute(array($id));

        Database::disconnect();
        header("Location: voyageursview.php?id=$membre"); // Apres l'envoie on redirige a employes
    };
?>

<!doctype html>
<html lang="en">

<head>
    <title>Supprimer Famille</title>
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

    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Supprimer Membre Famille</h1>
                <form action="famillesdelete.php" role="form" method="post">
                    <input type="hidden" name="id" value="<?php echo $id;?>" />
                    <input type="hidden" name="membre" value="<?php echo $membre;?>" />
                    <p>Etes vous sur de vouloir supprimer le membre de la famille
                        <strong><?php echo " " . $item["Nom"]?>?</strong></p>
                    <div>
                        <button class="btn btn-primary btn-lg" type="submit">SUPPRIMER</button>
                        <a class="btn btn-primary btn-lg" href="voyageursview.php?id=<?php echo $membre ?>">
                            RETOUR</a> </div>
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
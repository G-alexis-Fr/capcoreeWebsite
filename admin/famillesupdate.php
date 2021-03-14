<?php   
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");
        
    require "database.php";
    require "check.php";

    $name = $surname = $sexe = $age = $passeport =  $nameError = $surnameError = $sexeError = $ageError = $passeportError = "";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']); // id du membre de la famille

    if(!empty($_POST)){

        $id = $_POST['id'];
        $idvoyageur = $_POST['membre'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $age = $_POST['age'];
        $sexe = $_POST['sexe'];
        $passeport = $_POST['passeport'];
        
        $isSuccess = true;
        $isUploadSuccess = false;

        if(empty($name)){
            $nameError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($surname)){
            $surnameError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($age)){
            $ageError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($sexe)){
            $emailError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
        if(empty($passeport)){
            $emailError = "Ce champs ne peut etre vide";
            $isSuccess = false;
        };
       
        if($isSuccess){
            $db = Database::connect();
            // On fait un prepare avec des valeur ? qui vont nous etre envoyes par le formulaire
            $statement = $db->prepare("UPDATE voyageursfamille SET Nom=?,Prenom=?,Age=?,Sexe=?,Passeport=? WHERE IDFamille=?");
            $statement->execute(array($name,$surname,$age,$sexe,$passeport,$id));
            Database::disconnect();
            header("Location: voyageursview.php?id=$idvoyageur"); // Apres l'envoie on redirige a employe
        };
    } else {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM voyageursfamille WHERE IDFamille = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        $idvoyageur = $item['ID'];
        $name = $item['Nom'];
        $surname = $item['Prenom'];
        $age = $item['Age'];
        $sexe = $item['Sexe'];
        $passeport = $item['Passeport'];
        
        Database::disconnect();
    };
?>

<!doctype html>
<html lang="en">

<head>
    <title>Maj Famille</title>
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

    <div class="container-fluid text-center">
        <h1>Modifier Membre</h1>
        <div class="row text-center">

            <section class="col-12">
                <form action="<?php echo 'famillesupdate.php?id=' . $id ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id;?>" /> <!-- On aura besoin de l'ID quand on soumettra le formulaire, alors on le met en cache pour pouvoir le sauvegarde, pareil pour l'id du voyageur-->
                    <input type="hidden" name="membre" value="<?php echo $idvoyageur;?>" />
                    <div class="form-group">
                        <label class="font-weight-bold" for="name">Nom : </label>
                        <input id="search" type="text" id="name" name="name" placeholder="Nom *"
                            value="<?php echo $name ?>"><br>
                        <span class="erreur"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="surname">Prenom : </label>
                        <input id="search" type="text" id="surname" name="surname" placeholder="Prenom *"
                            value="<?php echo $surname ?>"><br>
                        <span class="erreur"><?php echo $surnameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="sexe">Sexe : </label>
                        <select id="search" name="sexe" id="sexe">
                            <option value="<?php echo $item["Sexe"]?>"><?php echo $item["Sexe"]?></option>
                            <option value="Femme">Femme</option>;
                            <option value="Homme">Homme</option>;
                        </select><br>
                        <span class="erreur"><?php echo $sexeError; ?></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="age">Age : </label>
                        <input id="search" type="text" id="age" name="age" placeholder="Age *"
                            value="<?php echo $age ?>"><br>
                        <span class="erreur"><?php echo $ageError; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="passeport">Passeport : </label>
                        <input id="search" type="text" id="passeport" name="passeport" placeholder="Passeport *"
                            value="<?php echo $passeport ?>"><br>
                        <span class="erreur"><?php echo $passeportError; ?></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-lg" type="submit">MODIFIER</button>
                        <a class="btn btn-lg btn-primary"
                            href="voyageursview.php?id=<?php echo $idvoyageur ?>">RETOUR</a>
                    </div>
                </form>
            </section>
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
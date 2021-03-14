<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']);
        
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM hotel WHERE ID = ?");
    $statement->execute(array($id));

    $item = $statement->fetch();

    Database::disconnect();
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Voir Hôtel</title>
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
        <div class="row text-center center-block">
            <section class="col-sm-12 col-md-6">
                <h1>Information Hôtel</h1>

                <br><br>

            <label class="font-weight-bold" for="">Nom : </label><?php echo " " . $item['Nom']; ?>   <br>
            <label class="font-weight-bold" for="">Email : </label><?php echo " " . $item['Email']; ?><br>
            <label class="font-weight-bold" for="">Ville : </label><?php echo " " . $item['Ville']; ?><br>
            <label class="font-weight-bold" for="">Adresse : </label><?php echo " " . $item['Adresse']; ?><br>
            <label class="font-weight-bold" for="">KakaoTalk : </label><?php echo " " . $item['KakaoTalk']; ?><br>
            <label class="font-weight-bold" for="">Telephone : </label><?php echo " " . $item['Telephone']; ?><br>
            <label class="font-weight-bold" for="">Etoile : </label><?php echo " " . $item['Etoile']; ?><br>
            <label class="font-weight-bold" for="">Banque : </label><?php echo " " . $item['Banque']; ?><br>
            <label class="font-weight-bold" for="">Numero Compte : </label><?php echo " " . $item['NumeroCompte']; ?><br>
            <label class="font-weight-bold" for="">Commentaire : </label><?php echo " " . $item['Commentaire']; ?><br>
            <label class="font-weight-bold" for="">Gym : </label><?php echo " " . $item['Gym']; ?><br>
            <label class="font-weight-bold" for="">Piscine : </label><?php echo " " . $item['Piscine']; ?><br>
            <label class="font-weight-bold" for="">Restaurant : </label><?php echo " " . $item['Restaurant']; ?><br>
            <label class="font-weight-bold" for="">Parking : </label><?php echo " " . $item['Parking']; ?><br>
            <label class="font-weight-bold" for="">Wifi : </label><?php echo " " . $item['Wifi']; ?><br>
            <label class="font-weight-bold" for="">Shuttle : </label><?php echo " " . $item['Shuttle']; ?><br>
            <label class="font-weight-bold" for="">Reception : </label><?php echo " " . $item['Reception']; ?><br>
            <label class="font-weight-bold" for="">Anglais : </label><?php echo " " . $item['Anglais']; ?><br>

                <br><br>

            </section>

            <section class="col-sm-12 col-md-6">
                <br><br>
                <h1><strong>Liste des Chambres</strong><br><a class="btn btn-primary btn-lg"
                        href="chambresinsert.php?id=<?php echo $item['ID']?>">+ Ajouter</a></h1>
                <table class="table text-center table-striped table-bordered table-hover table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th>Type de Chambre</th>
                        <th>Nbre De Lit</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        
                        $db = Database::connect();
                        
                        $statement = $db->prepare("SELECT * FROM chambre WHERE idHotel=? ");
                        $statement->execute(array($id));

                        while($chambre = $statement->fetch())
                        {
                        echo "<tr>";
                        echo "<td>" . $chambre['TypeChambre'] . "</td>";
                        echo "<td>" . $chambre['NbreLit'] . "</td>";
                        echo "<td>" . $chambre['Prix'] . "</td>";
                        
                        echo '<td width="300">';
                        echo '<a class="btn btn-info btn-sm" href="chambresupdate.php?id=' . $chambre['ID'] .'" ></span> MODIFIER</a>';
                        echo ' / ';
                        echo '<a class="btn btn-danger btn-sm" href="chambresdelete.php?id=' . $chambre['ID'] .'" ></span> SUPPRIMER</a>';
                        echo "</td>";
                        echo "</tr>";
                        };
                        Database::disconnect();
                    ?>
                </tbody>
            </table>
            </section>
        </div>
        <div class="row text-center">
            <div class="col-12">

                <a class="btn btn-info btn-lg" href="hotelsupdate.php?id=<?php echo $id ?>" ></span> MODIFIER</a>
                <a class="btn btn-danger btn-lg" href="hotelsdelete.php?id=<?php echo $id ?>" ></span> SUPPRIMER</a>
                <a href="hotels.php" class="btn btn-primary btn-lg">RETOUR</a>
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
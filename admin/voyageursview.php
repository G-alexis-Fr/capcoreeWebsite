<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");
        
    require "database.php";
    require "check.php";

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']); // id de voyageurs
        
    // On recupere les infos du voyageurs
    $db = Database::connect();

    $statement = $db->prepare("SELECT * FROM voyageurs WHERE ID = ?");
    $statement->execute(array($id));
    $item = $statement->fetch();
    
    Database::disconnect();
?>

<!doctype html>
<html lang="en">

<head>
    <title>Voir Voyageur</title>
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

    <div class="container-fluid">
        <div class="row text-center center-block">
            <section class="col-sm-12 col-md-6">
                <br>
                <h1>Information Voyageur</h1>

                <br><br>

                <label class="font-weight-bold" for="">Nom : </label><?php echo " " . $item['Nom']; ?> <br>
                <label class="font-weight-bold" for="">Prenom : </label><?php echo " " . $item['Prenom']; ?><br>
                <label class="font-weight-bold" for="">Sexe : </label><?php echo " " . $item['Sexe']; ?><br>
                <label class="font-weight-bold" for="">Age : </label><?php echo " " . $item['Age']; ?><br>
                <label class="font-weight-bold" for="">Email : </label><?php echo " " . $item['Email']; ?><br>
                <label class="font-weight-bold" for="">WhatsApp : </label><?php echo " " . $item['WhatsApp']; ?><br>
                <label class="font-weight-bold" for="">Telephone : </label><?php echo " " . $item['Telephone']; ?><br>
                <label class="font-weight-bold" for="">Passeport : </label><?php echo " " . $item['Passeport']; ?><br>
                <label class="font-weight-bold" for="">Banque : </label><?php echo " " . $item['Banque']; ?><br>
                <label class="font-weight-bold" for="">Numero Compte :
                </label><?php echo " " . $item['NumeroCompte']; ?><br>
                <label class="font-weight-bold" for="">Commentaire :
                </label><?php echo " " . $item['Commentaire']; ?><br>


                <br><br>

            </section>

            <section class="col-sm-12 col-md-6">
                <br>
                <a class="btn btn-success btn-lg" href="construction.php?id=<?php echo $id ?>">
                    <h1 id="btnConst">Construction</h1>
                </a>
                <a  class="btn btn-success btn-lg" href="reservation.php?id=<?php echo $id ?>">
                    <h1 id="btnReser">Réservation</h1>
                </a><br>
                    <?php
                        if(!empty($_GET['id']))
                        {
                            $id = checkInput($_GET['id']); // id de voyageurs
                        }
                            // On recupere les infos du voyageurs
                        $db = Database::connect();
                    
                            // On verifie si il a deja une fiche construction 
                        $sqlConst = $db->prepare("SELECT COUNT(*) FROM construction WHERE IdVoyageurs = ? ");
                        $sqlConst ->execute(array($id));

                        $tailleConst = $sqlConst->fetchColumn();

                        if($tailleConst > 0){ // SI OUI alors on modifie le bouton
                    ?>
                            <script type="text/javascript">
                                document.getElementById("btnConst").innerHTML = "Voir Construction";
                            </script>
                    <?php
                        }
                        else{ // SI NON alors on modifie le bouton
                    ?>
                            <script type="text/javascript">
                                document.getElementById("btnConst").innerHTML = "Creer Construction";
                            
                            </script>
                    <?php
                        };

                        $sqlReser = $db->prepare("SELECT COUNT(*) FROM reservation WHERE IdVoyageurs = ? ");
                        $sqlReser ->execute(array($id));

                        $tailleReser = $sqlReser->fetchColumn();
                        
                        if($tailleReser > 0){ // SI OUI alors on modifie le bouton
                    ?>
                            <script type="text/javascript">
                                document.getElementById("btnReser").innerHTML = "Voir Réservation";
                            </script>
                    <?php
                        }
                        else{ // SI NON alors on modifie le bouton
                    ?>
                            <script type="text/javascript">
                                document.getElementById("btnReser").innerHTML = "Créer Réservation";
                            
                            </script>
                    <?php
                        };

                        Database::disconnect();
                    ?>

                
                <br><br>
                <h1><strong>Liste des Membres</strong><br><a class="btn btn-primary btn-lg"
                        href="famillesinsert.php?id=<?php echo $item['ID']?>">+ AJOUTER</a></h1>
                <table class="table text-center table-striped table-bordered table-hover table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Age</th>
                            <th>Passeport</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            
                            $db = Database::connect();
                            
                            $statement = $db->prepare("SELECT * FROM voyageursfamille WHERE ID=? ");
                            $statement->execute(array($id));

                            while($famille = $statement->fetch())
                            {
                            echo "<tr>";
                            echo "<td>" . $famille['Nom'] . "</td>";
                            echo "<td>" . $famille['Prenom'] . "</td>";
                            echo "<td>" . $famille['Age'] . "</td>";
                            echo "<td>" . $famille['Passeport'] . "</td>";
                            
                            echo '<td width="300">';
                            echo '<a class="btn btn-info btn-sm" href="famillesupdate.php?id=' . $famille['IDFamille'] .'" ></span> MODIFIER</a>';
                            echo ' / ';
                            echo '<a class="btn btn-danger btn-sm" href="famillesdelete.php?id=' . $famille['IDFamille'] .'" ></span> SUPPRIMER</a>';
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
            <a class="btn btn-info btn-lg" href="voyageursupdate.php?id=<?php echo $id ?>" ></span> MODIFIER</a>
                <a class="btn btn-danger btn-lg" href="voyageursdelete.php?id=<?php echo $id ?>" ></span> SUPPRIMER</a>
                <a href="voyageurs.php" class="btn btn-primary btn-lg">RETOUR</a>
                <br><br>
            </div>
        </div>

    </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- <script src="../script.js"></script> -->
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
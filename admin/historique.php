<?php
    session_start();
    if(!isset($_SESSION["membre"]))
        header("Location: ../login.php");
    
    $search1 = "";
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Historique Paie</title>
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
            <section class="col-12">

                <div class="form-group text-center">
                <h1><a href=""><img src="../images/refresh.png" alt="refresh"></a><strong>  Historique</strong></h1>
                </div>

                <form action="" method="POST">
                    <label for="search">Recherche</label>
                    <select class="curseur" name="search" id="search">
                <?php
                    if($_SESSION["role"] == "Guide"){ 
                ?>
                        <option value="Date">Date</option>;
                <?php
                    }else {
                ?>
                        <option value="Guide">Guide</option>;
                        <option value="Date">Date</option>;
                <?php
                    };
                ?>
                    </select>            
                    <input name="search1" id="search" type="text" value="<?php echo $search1 ?>">
                    <input class="btn-sm btn-secondary rounded" type="submit" name="submit">
                </form>

                <table class="table text-center table-striped table-bordered table-hover table-sm">
                    <thead class="thead-dark">
                        <tr>
                          <th>Guide</th>
                          <th>Ami Local</th>
                          <th>Extension</th>
                          <th>SBN</th>
                          <th>Check-In</th>
                          <th>PaieHT</th>
                          <th>PaieTTC</th>
                          <th>Extra</th>
                          <th>Depense</th>
                          <th>Comentaire</th>
                          <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require "database.php";
                            $db = Database::connect();
    
                            // SI guide alors vous ne voyez que votre historique
                            // If Guide alors you are able to see only your historic
                            if($_SESSION["role"] == "Guide"){
                                $statement = $db->prepare("SELECT * FROM historique_paie WHERE Guide=? ORDER BY Date DESC");
                                $statement->execute(array(($_SESSION["nom"] . " " .$_SESSION["prenom"])));

                            }else{
                                $statement = $db->query("SELECT * FROM historique_paie ORDER BY Date DESC");
                            };

                            if(!empty($_POST["submit"])){
                                
                                $champ = $_POST["search"]; // Ceci est la valeur de listebox selectionnee
                                $search1 = $_POST["search1"]; // Ce que l'utilisateur a saisi dans l'input

                                if($_SESSION["role"] != "Guide"){
                                    $statement = $db->prepare("SELECT * FROM historique_paie WHERE $champ LIKE '%$search1%' ORDER BY Date DESC"); // Permet de faire une recherche avec seulement une partie du mot
                                    $statement->execute();
                                }else{
                                    $statement = $db->prepare("SELECT * FROM historique_paie WHERE $champ  LIKE '%$search1%' AND Guide=? ORDER BY Date DESC"); // Permet de faire une recherche avec seulement une partie du mot
                                    $statement->execute(array(($_SESSION["nom"] . " " .$_SESSION["prenom"])));
                                };
                            };
                            

                            if($statement->rowCount() < 1){ // Si aucunes donnees n'a ete trouvees alors on renvoie le texte ( AUCUN HISTORIQUE TROUVE)
                                echo "<tr>";
                                echo "<td> Aucun Historique trouvé </td>";
                            }else{ // SINOn on renvoie les infos 
                                while($item = $statement->fetch())
                                {
                                    echo "<tr>";
                                    echo "<td>" . $item['Guide'] . "</td>";
                                    echo "<td>" . $item['AmiLocal'] . "</td>";
                                    echo "<td>" . $item['Extension'] . "</td>";
                                    echo "<td>" . $item['SBN'] . "</td>";
                                    echo "<td>" . $item['CheckIn'] . "</td>";
                                    echo "<td>" . number_format($item['PaieHT'],2) ." Krw" . "</td>";
                                    echo "<td>" . number_format($item['PaieTTC'],2) ." Krw" . "</td>";
                                    echo "<td>" . number_format($item['Extra'],2) . "</td>";
                                    echo "<td>" . number_format($item['Depense'],2) . "</td>";
                                    echo "<td>" . wordwrap($item['Commentaire'], 15, "<br />\n") . "</td>"; // Wordwrap permet de revenir a la ligne
                                    echo "<td>" . $item['Date'] . "</td>";
                                }
                            };
                            Database::disconnect();
                        ?>
                    </tbody>
                </table>
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
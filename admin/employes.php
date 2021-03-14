<?php
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

     $search1 = "";
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Liste Employé</title>
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
                <h1><a href=""><img src="../images/refresh.png" alt="refresh"></a><strong>  Liste des Employés</strong></h1>
            <?php
                if($_SESSION["role"] == "Admin"){ // Only the Admin can delete or Update an Employe
            ?>
                <a href="employesinsert.php" class="btn btn-primary btn-lg " role="button" aria-pressed="true">+ AJOUTER</a>
            <?php
                };
            ?>
                </div>

                <form action="" method="POST">
                    <label for="search">Recherche</label>
                    <select class="curseur" name="search" id="search">
                        <option value="Role">Role</option>;
                        <option value="Nom">Nom</option>;
                        <option value="Prenom">Prenom</option>;
                        <option value="Sexe">Genre</option>;
                    </select>                
                    <input name="search1" id="search" type="text" value="<?php echo $search1 ?>">
                    <input class="btn-sm btn-secondary rounded" type="submit" name="submit">
                </form>

                <table class="table text-center table-striped table-bordered table-hover table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Role</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Sexe</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require "database.php";
                            $db = Database::connect();
                            if(!empty($_POST["submit"]))
                                {
                                    $champ = $_POST["search"];
                                    $search1 = $_POST["search1"];

                                    $statement = $db->prepare("SELECT * FROM listeemploye WHERE $champ LIKE '%$search1%'");
                                    $statement->execute();
                                }
                                else
                                {
                                    $statement = $db->query("SELECT * FROM listeemploye ORDER BY ID");
                                }
                            

                            while($item = $statement->fetch())
                            {
                            echo "<tr>";
                            echo "<td>" . $item['Role'] . "</td>";
                            echo "<td>" . $item['Nom'] . "</td>";
                            echo "<td>" . $item['Prenom'] . "</td>";
                            echo "<td>" . $item['Sexe'] . "</td>";
                            
                            echo '<td width="300">';
                            echo '<a class="btn btn-success btn-sm" href="employesview.php?id=' . $item['ID'] .'" ></span> VOIR </a>';

                            if($_SESSION["role"] == "Admin"){ // Only the Admin can delete or Update an Employe
                            echo ' / ';
                            echo '<a class="btn btn-info btn-sm" href="employesupdate.php?id=' . $item['ID'] .'" ></span> MODIFIER</a>';
                            echo ' / ';
                            echo '<a class="btn btn-danger btn-sm" href="employesdelete.php?id=' . $item['ID'] .'" ></span> SUPPRIMER</a>';
                            };
                            echo "</td>";
                            echo "</tr>";
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